<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $session = session();
        $model = new UserModel();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $model->where('username', $username)->first();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Store role id and role name in session when available
                $roleId = $user['role_id'] ?? null;
                $roleName = null;
                if ($roleId) {
                    $db = \Config\Database::connect();
                    $row = $db->table('roles')->where('id', $roleId)->get()->getRow();
                    $roleName = $row->name ?? null;
                } else {
                    // fallback to legacy `role` column if present
                    $roleName = $user['role'] ?? null;
                }

                $sessionData = [
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'full_name' => $user['full_name'],
                    'role_id'   => $roleId,
                    'role'      => $roleName,
                    'logged_in' => true
                ];
                $session->set($sessionData);
                
                // Redirect based on user role
                if (strtolower($roleName) === 'receptionist') {
                    return redirect()->to(base_url('receptionist'));
                } else {
                    return redirect()->to(base_url('dashboard'));
                }
            } else {
                $session->setFlashdata('error', 'Invalid password');
                return redirect()->to(base_url('login'));
            }
        } else {
            $session->setFlashdata('error', 'Invalid username');
            return redirect()->to(base_url('login'));
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('login'));
    }
}
