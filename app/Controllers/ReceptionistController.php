<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\Staff_model;
use CodeIgniter\Email\Email;

class ReceptionistController extends BaseController
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

    /**
     * Unified Receptionist Dashboard
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Check if user has receptionist role
        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
        }

        $appointmentModel = new AppointmentModel();
        $db = \Config\Database::connect();

        // Get all appointments for the unified dashboard
        $data['appointments'] = $appointmentModel->orderBy('appointment_date', 'ASC')
                                                  ->orderBy('appointment_time', 'ASC')
                                                  ->findAll();

        // Initialize default values
        $data['today_revenue'] = 0;
        $data['weekly_revenue'] = 0;
        $data['monthly_revenue'] = 0;
        $data['total_customers'] = 0;
        $data['recent_appointments'] = [];
        $data['top_services'] = [];

        try {
            // Check if price column exists
            $fields = $db->getFieldData('appointments');
            $hasPriceColumn = false;
            foreach ($fields as $field) {
                if ($field->name === 'price') {
                    $hasPriceColumn = true;
                    break;
                }
            }

            if ($hasPriceColumn) {
                // Today's Revenue (completed appointments with price for today)
                $today = date('Y-m-d');
                $data['today_revenue'] = $db->table('appointments')
                    ->selectSum('price')
                    ->where('status', 'completed')
                    ->where('appointment_date', $today)
                    ->get()
                    ->getRow()->price ?? 0;

                // Weekly Revenue (completed appointments in the last 7 days)
                $data['weekly_revenue'] = $db->table('appointments')
                    ->selectSum('price')
                    ->where('status', 'completed')
                    ->where('appointment_date >=', date('Y-m-d', strtotime('-7 days')))
                    ->get()
                    ->getRow()->price ?? 0;

                // Monthly Revenue (completed appointments in the last 30 days)
                $data['monthly_revenue'] = $db->table('appointments')
                    ->selectSum('price')
                    ->where('status', 'completed')
                    ->where('appointment_date >=', date('Y-m-d', strtotime('-30 days')))
                    ->get()
                    ->getRow()->price ?? 0;

                // Top Services (group by service_type, count and sum price)
                $data['top_services'] = $db->table('appointments')
                    ->select('service_type, COUNT(*) as total_count, SUM(price) as total_revenue')
                    ->where('status', 'completed')
                    ->groupBy('service_type')
                    ->orderBy('total_count', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResult();
            }
        } catch (\Exception $e) {
            // If there's an error, just use default values
            log_message('error', 'Receptionist dashboard revenue query error: ' . $e->getMessage());
        }

        try {
            // Total Customers Count (distinct customer_phone)
            $data['total_customers'] = $db->table('appointments')
                ->distinct()
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Receptionist dashboard customers query error: ' . $e->getMessage());
        }

        try {
            // Recent Appointments (last 5, ordered by date and time desc)
            $data['recent_appointments'] = $appointmentModel->orderBy('appointment_date', 'DESC')
                                                             ->orderBy('appointment_time', 'DESC')
                                                             ->limit(5)
                                                             ->find();
        } catch (\Exception $e) {
            log_message('error', 'Receptionist dashboard recent appointments query error: ' . $e->getMessage());
        }

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

        return view('Receptionist Dashboard/index', $data);
    }

    /**
     * Create new appointment (handles both form display and submission)
     */
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Check if user has receptionist role
        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
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
        
        return view('Receptionist Dashboard/index', $data);
    }

    /**
     * Store new appointment
     */
    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Check if user has receptionist role
        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
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
            
            // Return JSON response for AJAX
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appointment created successfully!',
                'redirect_url' => base_url('receptionist'),
                'appointment' => [
                    'id' => $appointmentId,
                    'customer_name' => $data['customer_name'],
                    'customer_phone' => $data['customer_phone'],
                    'customer_email' => $data['customer_email'],
                    'service_type' => $data['service_type'],
                    'appointment_date' => $data['appointment_date'],
                    'appointment_time' => $data['appointment_time'],
                    'status' => $data['status'],
                    'notes' => $data['notes']
                ]
            ]);
        } else {
            // Return JSON response for AJAX
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create appointment. Please check the form.',
                'errors' => $model->errors()
            ]);
        }
    }

    /**
     * Update appointment status
     */
    public function updateStatus($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Check if user has receptionist role
        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
        }

        $model = new AppointmentModel();
        $status = $this->request->getPost('status');
        
        if ($model->update($id, ['status' => $status])) {
            // Return JSON response for AJAX
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appointment status updated successfully!'
            ]);
        } else {
            // Return JSON response for AJAX
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update status'
            ]);
        }
    }

    /**
     * Get all appointments (for the "All Appointments" tab)
     */
    public function getAllAppointments()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Check if user has receptionist role
        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
        }

        $model = new AppointmentModel();
        $appointments = $model->orderBy('appointment_date', 'ASC')
                              ->orderBy('appointment_time', 'ASC')
                              ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'appointments' => $appointments
        ]);
    }

    /**
     * Get appointment statistics for dashboard
     */
    public function getStatistics()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Check if user has receptionist role
        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
        }

        $db = \Config\Database::connect();
        $model = new AppointmentModel();

        $data = [
            'today_revenue' => 0,
            'weekly_revenue' => 0,
            'monthly_revenue' => 0,
            'total_customers' => 0,
            'recent_appointments' => [],
            'top_services' => []
        ];

        try {
            // Check if price column exists
            $fields = $db->getFieldData('appointments');
            $hasPriceColumn = false;
            foreach ($fields as $field) {
                if ($field->name === 'price') {
                    $hasPriceColumn = true;
                    break;
                }
            }

            if ($hasPriceColumn) {
                // Today's Revenue
                $today = date('Y-m-d');
                $data['today_revenue'] = $db->table('appointments')
                    ->selectSum('price')
                    ->where('status', 'completed')
                    ->where('appointment_date', $today)
                    ->get()
                    ->getRow()->price ?? 0;

                // Weekly Revenue
                $data['weekly_revenue'] = $db->table('appointments')
                    ->selectSum('price')
                    ->where('status', 'completed')
                    ->where('appointment_date >=', date('Y-m-d', strtotime('-7 days')))
                    ->get()
                    ->getRow()->price ?? 0;

                // Monthly Revenue
                $data['monthly_revenue'] = $db->table('appointments')
                    ->selectSum('price')
                    ->where('status', 'completed')
                    ->where('appointment_date >=', date('Y-m-d', strtotime('-30 days')))
                    ->get()
                    ->getRow()->price ?? 0;

                // Top Services
                $data['top_services'] = $db->table('appointments')
                    ->select('service_type, COUNT(*) as total_count, SUM(price) as total_revenue')
                    ->where('status', 'completed')
                    ->groupBy('service_type')
                    ->orderBy('total_count', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResult();
            }
        } catch (\Exception $e) {
            log_message('error', 'Receptionist dashboard statistics query error: ' . $e->getMessage());
        }

        try {
            // Total Customers Count
            $data['total_customers'] = $db->table('appointments')
                ->distinct()
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Receptionist dashboard customers query error: ' . $e->getMessage());
        }

        try {
            // Recent Appointments
            $data['recent_appointments'] = $model->orderBy('appointment_date', 'DESC')
                                                 ->orderBy('appointment_time', 'DESC')
                                                 ->limit(5)
                                                 ->find();
        } catch (\Exception $e) {
            log_message('error', 'Receptionist dashboard recent appointments query error: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'success' => true,
            'statistics' => $data
        ]);
    }

    /**
     * Show appointment confirmation page
     */
    public function confirmation($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Check if user has receptionist role
        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
        }

        $model = new AppointmentModel();
        $data['appointment'] = $model->find($id);

        if (!$data['appointment']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('Receptionist Dashboard/index', $data);
    }
}