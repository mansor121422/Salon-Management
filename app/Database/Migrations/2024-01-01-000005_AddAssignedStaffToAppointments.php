<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssignedStaffToAppointments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('appointments', [
            'assigned_staff_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'after'          => 'price',
            ],
        ]);

        // Add foreign key constraint
        $this->forge->addForeignKey('assigned_staff_id', 'users', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('appointments', 'appointments_assigned_staff_id_foreign');
        $this->forge->dropColumn('appointments', 'assigned_staff_id');
    }
}