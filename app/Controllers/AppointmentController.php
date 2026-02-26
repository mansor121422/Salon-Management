<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class AppointmentController extends BaseController
{
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        return view('appointments/create');
    }

    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $model = new AppointmentModel();
        
        $data = [
            'customer_name'     => $this->request->getPost('customer_name'),
            'customer_phone'    => $this->request->getPost('customer_phone'),
            'customer_email'    => $this->request->getPost('customer_email'),
            'service_type'      => $this->request->getPost('service_type'),
            'appointment_date'  => $this->request->getPost('appointment_date'),
            'appointment_time'  => $this->request->getPost('appointment_time'),
            'notes'             => $this->request->getPost('notes'),
            'status'            => 'pending'
        ];

        if ($model->insert($data)) {
            session()->setFlashdata('success', 'Appointment created successfully!');
            return redirect()->to('/appointments/confirmation/' . $model->getInsertID());
        } else {
            session()->setFlashdata('error', 'Failed to create appointment. Please check the form.');
            return redirect()->back()->withInput();
        }
    }

    public function confirmation($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
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
            return redirect()->to('/login');
        }

        $model = new AppointmentModel();
        $status = $this->request->getPost('status');
        
        if ($model->update($id, ['status' => $status])) {
            session()->setFlashdata('success', 'Appointment status updated!');
        } else {
            session()->setFlashdata('error', 'Failed to update status');
        }
        
        return redirect()->to('/dashboard');
    }
}
