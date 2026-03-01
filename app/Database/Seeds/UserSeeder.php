<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // If roles table exists, resolve role ids; otherwise insert by role name
        $db = \Config\Database::connect();
        $rolesExist = in_array('roles', $db->listTables());

        if ($rolesExist) {
            $roleMap = [];
            $rows = $db->table('roles')->get()->getResult();
            foreach ($rows as $r) {
                $roleMap[$r->name] = $r->id;
            }

            $data = [
                [
                    'username'   => 'receptionist',
                    'password'   => password_hash('receptionist123', PASSWORD_DEFAULT),
                    'full_name'  => 'Receptionist User',
                    'role_id'    => $roleMap['receptionist'] ?? null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'username'   => 'admin',
                    'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                    'full_name'  => 'Admin User',
                    'role_id'    => $roleMap['admin'] ?? null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'username'   => 'customer',
                    'password'   => password_hash('customer123', PASSWORD_DEFAULT),
                    'full_name'  => 'Customer User',
                    'role_id'    => $roleMap['customer'] ?? null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ];

            $this->db->table('users')->insertBatch($data);
        } else {
            // Fallback for older schema: insert role as string into `role` column
            $data = [
                [
                    'username'   => 'receptionist',
                    'password'   => password_hash('receptionist123', PASSWORD_DEFAULT),
                    'full_name'  => 'Receptionist User',
                    'role'       => 'receptionist',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'username'   => 'admin',
                    'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                    'full_name'  => 'Admin User',
                    'role'       => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'username'   => 'customer',
                    'password'   => password_hash('customer123', PASSWORD_DEFAULT),
                    'full_name'  => 'Customer User',
                    'role'       => 'customer',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ];

            $this->db->table('users')->insertBatch($data);
        }
    }
}
