<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\UserModel;

class OwnerController extends BaseController
{
    protected $appointmentModel;
    protected $userModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = session();
        $data['user'] = [
            'username' => $session->get('username'),
            'full_name' => $session->get('full_name'),
            'role' => $session->get('role')
        ];

        // Get appointment statistics
        $data['total_appointments'] = $this->appointmentModel->countAll();
        $data['pending_appointments'] = $this->appointmentModel->where('status', 'pending')->countAllResults();
        $data['confirmed_appointments'] = $this->appointmentModel->where('status', 'confirmed')->countAllResults();
        $data['completed_appointments'] = $this->appointmentModel->where('status', 'completed')->countAllResults();

        // Get today's appointments with staff information
        $today = date('Y-m-d');
        $data['today_appointments'] = $this->getAppointmentsWithStaff($today, $today);

        // Get upcoming appointments (next 7 days) with staff information
        $nextWeek = date('Y-m-d', strtotime('+7 days'));
        $data['upcoming_appointments'] = $this->getAppointmentsWithStaff($today, $nextWeek, true);

        // Get all appointments for the appointments tab with staff information
        $data['all_appointments'] = $this->getAllAppointmentsWithStaff();

        // Get analytics data
        $data['monthly_revenue'] = $this->getMonthlyRevenue();
        $data['completion_rate'] = $this->getCompletionRate();
        $data['avg_daily_appointments'] = $this->getAvgDailyAppointments();
        $data['service_breakdown'] = $this->getServiceBreakdown();

        // Get active appointments (for monitoring)
        $data['active_appointments'] = $this->getActiveAppointmentsCount();

        return view('Owner Dashboard/index', $data);
    }

    public function monitoring()
    {
        $stats = [
            'active_appointments' => $this->getActiveAppointmentsCount(),
            'pending_appointments' => $this->appointmentModel->where('status', 'pending')->countAllResults()
        ];

        // Get recent updates (last 24 hours)
        $updates = $this->getRecentUpdates();

        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats,
            'updates' => $updates
        ]);
    }

    public function analytics()
    {
        $analytics = [
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'completion_rate' => $this->getCompletionRate(),
            'avg_daily_appointments' => $this->getAvgDailyAppointments()
        ];

        return $this->response->setJSON([
            'success' => true,
            'analytics' => $analytics
        ]);
    }

    private function getMonthlyRevenue()
    {
        $currentMonth = date('Y-m');
        $builder = $this->appointmentModel->builder();
        $builder->select('SUM(price) as total_revenue')
               ->where("DATE_FORMAT(appointment_date, '%Y-%m')", $currentMonth)
               ->where('status', 'completed');
        
        $result = $builder->get()->getRow();
        return $result ? (float)$result->total_revenue : 0;
    }

    private function getCompletionRate()
    {
        $totalAppointments = $this->appointmentModel->countAll();
        if ($totalAppointments == 0) return 0;

        $completedAppointments = $this->appointmentModel->where('status', 'completed')->countAllResults();
        return round(($completedAppointments / $totalAppointments) * 100, 1);
    }

    private function getAvgDailyAppointments()
    {
        $currentMonth = date('Y-m');
        $builder = $this->appointmentModel->builder();
        $builder->select('COUNT(*) as total_appointments, COUNT(DISTINCT appointment_date) as active_days')
               ->where("DATE_FORMAT(appointment_date, '%Y-%m')", $currentMonth);
        
        $result = $builder->get()->getRow();
        
        if ($result && $result->active_days > 0) {
            return round($result->total_appointments / $result->active_days, 1);
        }
        
        return 0;
    }

    private function getServiceBreakdown()
    {
        $currentMonth = date('Y-m');
        $builder = $this->appointmentModel->builder();
        $builder->select('service_type, COUNT(*) as count, SUM(price) as revenue')
               ->where("DATE_FORMAT(appointment_date, '%Y-%m')", $currentMonth)
               ->groupBy('service_type')
               ->orderBy('count', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        if (empty($results)) return [];

        // Calculate total for percentages
        $totalAppointments = array_sum(array_column($results, 'count'));
        
        return array_map(function($service) use ($totalAppointments) {
            return [
                'service_type' => $service['service_type'],
                'count' => $service['count'],
                'revenue' => (float)$service['revenue'],
                'percentage' => round(($service['count'] / $totalAppointments) * 100, 1)
            ];
        }, $results);
    }

    private function getActiveAppointmentsCount()
    {
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');
        
        $builder = $this->appointmentModel->builder();
        $builder->where('appointment_date', $today)
               ->where('appointment_time <=', $currentTime)
               ->whereIn('status', ['confirmed', 'pending']);
        
        return $builder->countAllResults();
    }

    private function getRecentUpdates()
    {
        $builder = $this->appointmentModel->builder();
        $builder->select('id, customer_name, service_type, status, created_at')
               ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
               ->orderBy('created_at', 'DESC')
               ->limit(10);
        
        $results = $builder->get()->getResultArray();
        
        $updates = [];
        foreach ($results as $result) {
            $time = date('H:i', strtotime($result['created_at']));
            $updates[] = [
                'type' => 'new',
                'message' => "New appointment: {$result['customer_name']} - {$result['service_type']}",
                'time' => $time
            ];
        }
        
        return $updates;
    }

    private function getAppointmentsWithStaff($startDate, $endDate, $excludeToday = false)
    {
        $builder = $this->appointmentModel->builder();
        $builder->select('a.*, u.full_name as staff_name')
               ->from('appointments a')
               ->join('users u', 'a.assigned_staff_id = u.id', 'left')
               ->where('a.appointment_date >=', $startDate)
               ->where('a.appointment_date <=', $endDate);
        
        if ($excludeToday) {
            $builder->where('a.appointment_date !=', $startDate);
        }
        
        $builder->orderBy('a.appointment_date', 'ASC')
                ->orderBy('a.appointment_time', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    private function getAllAppointmentsWithStaff()
    {
        $builder = $this->appointmentModel->builder();
        $builder->select('a.*, u.full_name as staff_name')
               ->from('appointments a')
               ->join('users u', 'a.assigned_staff_id = u.id', 'left')
               ->orderBy('a.appointment_date', 'DESC')
               ->orderBy('a.appointment_time', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
