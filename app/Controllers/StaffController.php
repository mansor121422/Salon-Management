<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class StaffController extends BaseController
{
    protected $appointmentModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
    }

    public function index()
    {
        $session = session();
        $data['user'] = [
            'username' => $session->get('username'),
            'full_name' => $session->get('full_name'),
            'role' => $session->get('role')
        ];
        
        // Get today's date for default view
        $today = date('Y-m-d');
        $data['appointments'] = $this->getAppointmentsByDate($today);
        $data['current_date'] = $today;
        
        return view('Staff Dashboard/index', $data);
    }

    public function appointments()
    {
        $session = session();
        $data['user'] = [
            'username' => $session->get('username'),
            'full_name' => $session->get('full_name'),
            'role' => $session->get('role')
        ];

        // Get date from query parameter or default to today
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $data['appointments'] = $this->getAppointmentsByDate($date);
        $data['current_date'] = $date;

        return view('Staff Dashboard/index', $data);
    }

    public function getAppointments()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $appointments = $this->getAppointmentsByDate($date);
        
        return $this->response->setJSON([
            'success' => true,
            'appointments' => $appointments,
            'date' => $date
        ]);
    }

    public function getAppointmentDetails($appointmentId)
    {
        // Validate appointment exists
        $appointment = $this->appointmentModel->find($appointmentId);
        if (!$appointment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appointment not found'
            ])->setStatusCode(404);
        }

        // Format the appointment details for the modal
        $formattedAppointment = [
            'id' => $appointment['id'],
            'date' => date('F j, Y', strtotime($appointment['appointment_date'])),
            'time' => date('h:i A', strtotime($appointment['appointment_time'])),
            'customer' => $appointment['customer_name'],
            'service' => $appointment['service_type'],
            'status' => ucfirst($appointment['status']),
            'phone' => $appointment['customer_phone'],
            'email' => $appointment['customer_email'],
            'notes' => $appointment['notes'],
            'price' => $appointment['price'],
            'created_at' => $appointment['created_at']
        ];

        return $this->response->setJSON([
            'success' => true,
            'appointment' => $formattedAppointment
        ]);
    }

    public function updateStatus($appointmentId)
    {
        $session = session();
        
        // Validate appointment exists
        $appointment = $this->appointmentModel->find($appointmentId);
        if (!$appointment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Appointment not found'
            ])->setStatusCode(404);
        }

        // Get new status from request
        $newStatus = $this->request->getPost('status');
        if (!in_array($newStatus, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status'
            ])->setStatusCode(400);
        }

        // Update appointment status
        $data = [
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->appointmentModel->update($appointmentId, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appointment status updated successfully',
                'appointment' => array_merge($appointment, $data)
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update appointment status'
            ])->setStatusCode(500);
        }
    }

    protected function getAppointmentsByDate($date)
    {
        // Validate date format
        if (!strtotime($date)) {
            return [];
        }

        // Get appointments for the specific date, ordered by time
        $appointments = $this->appointmentModel
            ->where('appointment_date', $date)
            ->orderBy('appointment_time', 'ASC')
            ->findAll();

        // Format the appointments for display
        $formattedAppointments = [];
        foreach ($appointments as $appointment) {
            $formattedAppointments[] = [
                'id' => $appointment['id'],
                'time' => date('h:i A', strtotime($appointment['appointment_time'])),
                'customer' => $appointment['customer_name'],
                'service' => $appointment['service_type'],
                'status' => ucfirst($appointment['status']),
                'phone' => $appointment['customer_phone'],
                'email' => $appointment['customer_email'],
                'notes' => $appointment['notes'],
                'price' => $appointment['price'],
                'created_at' => $appointment['created_at']
            ];
        }

        return $formattedAppointments;
    }

}
