<?php

namespace App\Controllers;

use App\Models\Staff_model;

class Staff extends BaseController
{
    protected $staffModel;

    public function __construct()
    {
        $this->staffModel = new Staff_model();
    }

    /**
     * Display list of all staff
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $data['staff'] = $this->staffModel->getAllStaff();
        
        return view('staff/list', $data);
    }

    /**
     * Show add staff form
     */
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        return view('staff/add');
    }

    /**
     * Store new staff
     */
    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $validation = \Config\Services::validation();

        $rules = [
            'name'            => 'required|min_length[2]|max_length[255]',
            'email'           => 'required|valid_email|max_length[255]|is_unique[staff.email]',
            'phone'           => 'required|min_length[11]|max_length[20]',
            'role'            => 'required|in_list[Barber,Stylist,Admin]',
            'specialization'  => 'permit_empty|max_length[255]',
            'status'          => 'required|in_list[Active,Inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'name'            => $this->request->getPost('name'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'role'            => $this->request->getPost('role'),
            'specialization'  => $this->request->getPost('specialization'),
            'status'          => $this->request->getPost('status')
        ];

        if ($this->staffModel->insertStaff($data)) {
            session()->setFlashdata('success', 'Staff member added successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to add staff member.');
        }

        return redirect()->to(base_url('staff'));
    }

    /**
     * Show edit staff form
     */
    public function edit($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $data['staff'] = $this->staffModel->getStaffById($id);
        
        if (!$data['staff']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Staff not found');
        }

        return view('staff/edit', $data);
    }

    /**
     * Update staff
     */
    public function update($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $staff = $this->staffModel->getStaffById($id);
        
        if (!$staff) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Staff not found');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'name'            => 'required|min_length[2]|max_length[255]',
            'email'           => 'required|valid_email|max_length[255]|is_unique[staff.email,id,' . $id . ']',
            'phone'           => 'required|min_length[11]|max_length[20]',
            'role'            => 'required|in_list[Barber,Stylist,Admin]',
            'specialization'  => 'permit_empty|max_length[255]',
            'status'          => 'required|in_list[Active,Inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'name'            => $this->request->getPost('name'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'role'            => $this->request->getPost('role'),
            'specialization'  => $this->request->getPost('specialization'),
            'status'          => $this->request->getPost('status')
        ];

        if ($this->staffModel->updateStaff($id, $data)) {
            session()->setFlashdata('success', 'Staff member updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update staff member.');
        }

        return redirect()->to(base_url('staff'));
    }

    /**
     * Delete staff
     */
    public function delete($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $staff = $this->staffModel->getStaffById($id);
        
        if (!$staff) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Staff not found');
        }

        if ($this->staffModel->deleteStaff($id)) {
            session()->setFlashdata('success', 'Staff member deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete staff member.');
        }

        return redirect()->to(base_url('staff'));
    }
}
