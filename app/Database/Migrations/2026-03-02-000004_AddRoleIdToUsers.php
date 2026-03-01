<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleIdToUsers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // add role_id column
        $fields = [
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ];
        $forge->addColumn('users', $fields);

        // Map existing role string values (if present) to role ids
        try {
            $roles = $db->table('roles')->get()->getResult();
            foreach ($roles as $role) {
                $db->query("UPDATE `users` SET `role_id` = ? WHERE `role` = ?", [$role->id, $role->name]);
            }

            // For any users without role_id yet, set to customer if possible
            $customer = $db->table('roles')->where('name', 'customer')->get()->getRow();
            if ($customer) {
                $db->query("UPDATE `users` SET `role_id` = ? WHERE `role_id` IS NULL", [$customer->id]);
            }

            // drop old role column if exists
            $columns = $db->getFieldNames('users');
            if (in_array('role', $columns)) {
                $db->query('ALTER TABLE `users` DROP COLUMN `role`');
            }

            // make role_id NOT NULL and add foreign key
            $db->query('ALTER TABLE `users` MODIFY `role_id` INT(11) UNSIGNED NOT NULL');
            $db->query('ALTER TABLE `users` ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        } catch (\Exception $e) {
            // log and continue; leave role_id nullable
            log_message('error', 'AddRoleIdToUsers migration mapping error: ' . $e->getMessage());
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        // Add back `role` varchar
        $columns = $db->getFieldNames('users');
        if (! in_array('role', $columns)) {
            $db->query("ALTER TABLE `users` ADD `role` VARCHAR(50) NOT NULL DEFAULT 'customer'");
        }

        // Backfill role from role_id
        try {
            $db->query('UPDATE `users` u JOIN `roles` r ON u.role_id = r.id SET u.role = r.name');
        } catch (\Exception $e) {
            log_message('error', 'AddRoleIdToUsers down migration error: ' . $e->getMessage());
        }

        // drop foreign key if exists and drop role_id column
        try {
            $db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_role`');
        } catch (\Exception $e) {
            // ignore
        }
        $columns = $db->getFieldNames('users');
        if (in_array('role_id', $columns)) {
            $db->query('ALTER TABLE `users` DROP COLUMN `role_id`');
        }
    }
}
