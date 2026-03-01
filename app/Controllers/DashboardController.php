<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $appointmentModel = new AppointmentModel();
        $data['appointments'] = $appointmentModel->orderBy('appointment_date', 'ASC')
                                                  ->orderBy('appointment_time', 'ASC')
                                                  ->findAll();
        
        return view('dashboard/index', $data);
    }
}
