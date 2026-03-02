<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table            = 'appointments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'service_type',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'price'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'customer_name'     => 'required|min_length[3]|max_length[255]',
        'customer_phone'    => 'required|min_length[10]|max_length[20]|is_unique[appointments.customer_phone]',
        'service_type'      => 'required',
        'appointment_date'  => 'required|valid_date',
        'appointment_time'  => 'required',
        'status'            => 'required|in_list[pending,confirmed,completed,cancelled]',
        'price'             => 'required|numeric|greater_than[0]'
    ];

    /**
     * Custom validation rules for duplicate checking
     */
    protected $customValidationRules = [
        'customer_phone' => [
            'label' => 'Phone Number',
            'rules' => 'is_unique[appointments.customer_phone]',
            'errors' => [
                'is_unique' => 'An appointment with this phone number already exists.'
            ]
        ],
        'appointment_time' => [
            'label' => 'Appointment Time',
            'rules' => 'callback_check_duplicate_appointment',
            'errors' => [
                'check_duplicate_appointment' => 'This time slot is already booked for the selected date.'
            ]
        ]
    ];

    protected $validationMessages = [
        'customer_name' => [
            'required' => 'Customer name is required',
        ],
        'customer_phone' => [
            'required' => 'Phone number is required',
            'is_unique' => 'An appointment with this phone number already exists.'
        ],
        'appointment_time' => [
            'check_duplicate_appointment' => 'This time slot is already booked for the selected date.'
        ],
    ];


    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
