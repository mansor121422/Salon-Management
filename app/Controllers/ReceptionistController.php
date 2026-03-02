<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class ReceptionistController extends BaseController
{

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) session()->get('role'));
        if ($role !== 'receptionist') {
            return redirect()->to(base_url('dashboard'));
        }

        $appointmentModel = new AppointmentModel();
        $db = \Config\Database::connect();

        $data['appointments'] = $appointmentModel->orderBy('appointment_date', 'ASC')
                                                  ->orderBy('appointment_time', 'ASC')
                                                  ->findAll();


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

        $servicePrices = [];
        try {
            $tables = $db->listTables();
            if (in_array('service_prices', $tables)) {
                $servicePrices = $db->table('service_prices')->get()->getResult();
            }
        } catch (\Exception $e) {
            $servicePrices = [];
        }
        
        $data['service_prices'] = $servicePrices;

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
        
        $data['service_prices'] = $servicePrices;
        
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
        
        // Get form data
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
        
        // Check for duplicate appointment time
        $existingAppointment = $model->where([
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time']
        ])->first();

        if ($existingAppointment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This time slot is already booked for the selected date.',
                'errors' => ['appointment_time' => 'This time slot is already booked for the selected date.']
            ]);
        }

        // Validate the data
        if (!$model->validate($data)) {
            // Return JSON response for AJAX with validation errors
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed. Please check the form.',
                'errors' => $model->getErrors()
            ]);
        }

        // If validation passes, insert the appointment
        if ($model->insert($data)) {
            $appointmentId = $model->getInsertID();
            
            session()->setFlashdata('success', 'Appointment created successfully!');
            
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
                    'status' => 'pending', 
                    'notes' => $data['notes']
                ]
            ]);
        } else {
            // Return JSON response for AJAX
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create appointment. Please try again.',
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