<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\Staff_model;
use CodeIgniter\Email\Email;

class AppointmentController extends BaseController
{
    /**
     * Send appointment email notification
     */
    protected function sendAppointmentEmail($appointmentId)
    {
        $model = new AppointmentModel();
        
        // Get appointment details with staff name
        $appointment = $model->select('appointments.*, staff.name as staff_name, staff.email as staff_email')
                           ->join('staff', 'staff.id = appointments.staff_id', 'left')
                           ->find($appointmentId);
        
        if (!$appointment) {
            log_message('error', 'Appointment not found for email: ' . $appointmentId);
            return false;
        }
        
        // Get email configuration
        $emailConfig = new \Config\Email();
        
        // Get customer email - THIS IS THE KEY FIX
        $customerEmail = $appointment['customer_email'];
        
        // Also get admin email from config or use default
        $adminEmail = getenv('email.admin_email') ?: $emailConfig->fromEmail;
        
        // Send to customer AND admin
        $recipients = [];
        
        // Add customer email if provided
        if (!empty($customerEmail)) {
            $recipients[] = $customerEmail;
        }
        
        // Also send to admin
        $recipients[] = $adminEmail;
        
        // Add staff email if assigned
        if (!empty($appointment['staff_email'])) {
            $recipients[] = $appointment['staff_email'];
        }
        
        // Remove duplicates
        $recipients = array_unique($recipients);
        
        // Generate queue number if not set
        if (empty($appointment['queue_number'])) {
            try {
                $queueNumber = $this->generateQueueNumber($appointment['appointment_date']);
                $model->update($appointmentId, ['queue_number' => $queueNumber]);
                $appointment['queue_number'] = $queueNumber;
            } catch (\Exception $e) {
                log_message('error', 'Failed to generate queue number: ' . $e->getMessage());
                // Continue without queue number
            }
        }
        
        try {
            $email = \Config\Services::email();
            
            // Set email from using config values
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($recipients);
            $email->setSubject('New Appointment Booked - ' . $appointment['customer_name']);
            
            // Get email template
            $emailBody = view('emails/appointment_notification', ['appointment' => $appointment]);
            $email->setMessage($emailBody);
            $email->setMailType('html');
            
            if ($email->send()) {
                log_message('info', 'Appointment email sent successfully for ID: ' . $appointmentId . ' to: ' . implode(', ', $recipients));
                return true;
            } else {
                // Log the error
                $error = $email->printDebugger(['headers', 'body']);
                log_message('error', 'Email sending failed: ' . $error);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate queue number for an appointment
     */
    protected function generateQueueNumber($appointmentDate)
    {
        $model = new AppointmentModel();
        
        // Count appointments for the same date
        $count = $model->where('appointment_date', $appointmentDate)->countAllResults();
        
        // Queue number is the next number for that day (starting from 1)
        return $count + 1;
    }

    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $db = \Config\Database::connect();
        
        // Get service prices for the dropdown (if table exists)
        $servicePrices = [];
        try {
            $tables = $db->listTables();
            if (in_array('service_prices', $tables)) {
                $servicePrices = $db->table('service_prices')->get()->getResult();
            }
        } catch (\Exception $e) {
            $servicePrices = [];
        }
        
        // Get active staff for the dropdown (if table exists)
        $staffModel = new Staff_model();
        $staff = [];
        try {
            $staff = $staffModel->getActiveStaff();
        } catch (\Exception $e) {
            $staff = [];
        }
        
        $data['service_prices'] = $servicePrices;
        $data['staff'] = $staff;
        
        return view('appointments/create', $data);
    }

    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $model = new AppointmentModel();
        
        $staffId = $this->request->getPost('staff_id');
        
        $data = [
            'customer_name'     => $this->request->getPost('customer_name'),
            'customer_phone'    => $this->request->getPost('customer_phone'),
            'customer_email'   => $this->request->getPost('customer_email'),
            'service_type'     => $this->request->getPost('service_type'),
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'price'            => $this->request->getPost('price'),
            'notes'            => $this->request->getPost('notes'),
            'status'           => 'pending'
        ];
        
        // Add staff_id if provided
        if (!empty($staffId)) {
            $data['staff_id'] = $staffId;
        }

        if ($model->insert($data)) {
            $appointmentId = $model->getInsertID();
            
            // Send email notification (don't fail appointment creation if email fails)
            $emailSent = $this->sendAppointmentEmail($appointmentId);
            
            if ($emailSent) {
                session()->setFlashdata('success', 'Appointment created successfully! Notification email sent.');
            } else {
                session()->setFlashdata('success', 'Appointment created successfully! (Email notification could not be sent)');
            }
            
            return redirect()->to(base_url('appointments/confirmation/' . $appointmentId));
        } else {
            session()->setFlashdata('error', 'Failed to create appointment. Please check the form.');
            return redirect()->back()->withInput();
        }
    }

    public function confirmation($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $model = new AppointmentModel();
        $data['appointment'] = $model->find($id);

        if (!$data['appointment']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('appointments/confirmation', $data);
    }

    public function updateStatus($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $model = new AppointmentModel();
        $status = $this->request->getPost('status');
        
        if ($model->update($id, ['status' => $status])) {
            session()->setFlashdata('success', 'Appointment status updated!');
        } else {
            session()->setFlashdata('error', 'Failed to update status');
        }
        
        return redirect()->to(base_url('dashboard'));
    }
}
