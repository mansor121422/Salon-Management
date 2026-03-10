<?php

namespace App\Controllers;

class OwnerController extends BaseController
{
    public function index()
    {
        $session = session();
        $data['user'] = [
            'username' => $session->get('username'),
            'full_name' => $session->get('full_name'),
            'role' => $session->get('role')
        ];
        return view('Owner Dashboard/index', $data);
    }
}