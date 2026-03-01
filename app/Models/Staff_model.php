<?php

namespace App\Models;

use CodeIgniter\Model;

class Staff_model extends Model
{
    protected $table            = 'staff';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'phone', 'role', 'specialization', 'status'];

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
    protected $validationRules      = [
        'name'            => 'required|min_length[2]|max_length[255]',
        'email'           => 'required|valid_email|max_length[255]|is_unique[staff.email,id,{id}]',
        'phone'           => 'required|min_length[11]|max_length[20]',
        'role'            => 'required|in_list[Barber,Stylist,Admin]',
        'specialization'  => 'permit_empty|max_length[255]',
        'status'          => 'required|in_list[Active,Inactive]'
    ];
    protected $validationMessages   = [
        'name' => [
            'required'   => 'Name is required',
            'min_length' => 'Name must be at least 2 characters',
            'max_length' => 'Name cannot exceed 255 characters'
        ],
        'email' => [
            'required'    => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique'    => 'This email is already registered'
        ],
        'phone' => [
            'required'   => 'Phone is required',
            'min_length' => 'Phone must be at least 11 characters',
            'max_length' => 'Phone cannot exceed 20 characters'
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list'  => 'Please select a valid role'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list'  => 'Please select a valid status'
        ]
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

    /**
     * Get all staff members
     */
    public function getAllStaff()
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get staff by ID
     */
    public function getStaffById($id)
    {
        return $this->find($id);
    }

    /**
     * Get active staff members (for dropdown)
     */
    public function getActiveStaff()
    {
        return $this->where('status', 'Active')->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Insert new staff
     */
    public function insertStaff($data)
    {
        return $this->insert($data);
    }

    /**
     * Update staff
     */
    public function updateStaff($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete staff
     */
    public function deleteStaff($id)
    {
        return $this->delete($id);
    }
}
