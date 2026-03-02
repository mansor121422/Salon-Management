<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPriceToAppointments extends Migration
{
    public function up()
    {
        // Add price column to existing appointments table
        $fields = [
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
                'after'      => 'notes'
            ]
        ];
        
        $this->forge->addColumn('appointments', $fields);
    }

    public function down()
    {
        // Remove price column
        $this->forge->dropColumn('appointments', 'price');
    }
}