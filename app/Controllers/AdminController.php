<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) session()->get('role'));
        if ($role !== 'admin') {
            return redirect()->to(base_url('receptionist'));
        }

        $userModel = new UserModel();

        // Get all users
        $data['users'] = $userModel->findAll();

        // Get user activity data 
        $data['user_activity'] = $this->getUserActivity();

        // Get statistics
        try {
            $data['total_users'] = $userModel->countAll();
        } catch (\Exception $e) {
            $data['total_users'] = 0;
        }

        return view('Admin Dashboard/index', $data);
    }

    /**
     * Create new user
     */
    public function createUser()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) session()->get('role'));
        if ($role !== 'admin') {
            return redirect()->to(base_url('receptionist'));
        }

        $userModel = new UserModel();

        // Get all users
        $data['users'] = $userModel->findAll();

        // Get user activity data (users with their last login info)
        $data['user_activity'] = $this->getUserActivity();

        // Get statistics
        try {
            $data['total_users'] = $userModel->countAll();
        } catch (\Exception $e) {
            $data['total_users'] = 0;
        }

        return view('Admin Dashboard/index', $data);
    }

    /**
     * Store new user
     */
    public function storeUser()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) session()->get('role'));
        if ($role !== 'admin') {
            return redirect()->to(base_url('receptionist'));
        }

        $model = new UserModel();
        
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash('chanelle', PASSWORD_DEFAULT),
            'full_name' => $this->request->getPost('full_name'),
            'role' => $this->request->getPost('role')
        ];

        // Validate the data
        if (!$model->validate($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed. Please check the form.',
                'errors' => $model->getErrors()
            ]);
        }

        if ($model->insert($data)) {
            session()->setFlashdata('success', 'User created successfully!');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User created successfully!',
                'redirect_url' => base_url('admin'),
                'user' => [
                    'id' => $model->getInsertID(),
                    'username' => $data['username'],
                    'full_name' => $data['full_name'],
                    'role' => $data['role']
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create user. Please try again.',
                'errors' => $model->errors()
            ]);
        }
    }

    /**
     * Update user
     */
    public function updateUser($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) session()->get('role'));
        if ($role !== 'admin') {
            return redirect()->to(base_url('receptionist'));
        }

        $model = new UserModel();
        
        $data = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'role' => $this->request->getPost('role')
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($model->update($id, $data)) {
            session()->setFlashdata('success', 'User updated successfully!');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User updated successfully!',
                'redirect_url' => base_url('admin')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update user. Please try again.',
                'errors' => $model->errors()
            ]);
        }
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleUserStatus($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) session()->get('role'));
        if ($role !== 'admin') {
            return redirect()->to(base_url('receptionist'));
        }

        $model = new UserModel();
        
        // Get current user data
        $user = $model->find($id);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        // Prevent editing the admin user
        if ($user['role'] === 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot modify admin user. This user is protected.'
            ]);
        }

        // Toggle status
        $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
        
        if ($model->update($id, ['status' => $newStatus])) {
            session()->setFlashdata('success', 'User status updated successfully!');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User status updated successfully!',
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update user status. Please try again.'
            ]);
        }
    }


    /**
     * Get all users
     */
    public function getAllUsers()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) session()->get('role'));
        if ($role !== 'admin') {
            return redirect()->to(base_url('receptionist'));
        }

        $model = new UserModel();
        $users = $model->findAll();

        return $this->response->setJSON([
            'success' => true,
            'users' => $users
        ]);
    }

    private function getUserActivity()
    {
        $userModel = new UserModel();
        
        $users = $userModel->findAll();
        
        foreach ($users as &$user) {
            $user['last_login'] = $user['created_at'];
        }
        
        return $users;
    }
}
