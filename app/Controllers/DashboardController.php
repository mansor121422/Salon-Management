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
        $db = \Config\Database::connect();

        // Get all appointments (existing functionality)
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
            log_message('error', 'Dashboard revenue query error: ' . $e->getMessage());
        }

        try {
            // Total Customers Count (distinct customer_phone)
            $data['total_customers'] = $db->table('appointments')
                ->distinct()
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Dashboard customers query error: ' . $e->getMessage());
        }

        try {
            // Recent Appointments (last 5, ordered by date and time desc)
            $data['recent_appointments'] = $appointmentModel->orderBy('appointment_date', 'DESC')
                                                             ->orderBy('appointment_time', 'DESC')
                                                             ->limit(5)
                                                             ->find();
        } catch (\Exception $e) {
            log_message('error', 'Dashboard recent appointments query error: ' . $e->getMessage());
        }

        return view('dashboard/index', $data);
    }
}
