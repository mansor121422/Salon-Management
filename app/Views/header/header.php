<?php
$session = session();
$role = strtolower((string) $session->get('role'));
?>
<nav class="bg-brand-dark shadow-lg px-8 py-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-xl md:text-2xl font-bold tracking-tight uppercase text-white">Salon</h1>

        <div class="flex items-center space-x-6">
            <?php if ($role === 'admin'): ?>
                <a href="<?= base_url('dashboard') ?>" class="text-white hover:text-brand-purple transition-colors">Admin Dashboard</a>
                <a href="<?= base_url('appointments') ?>" class="text-white hover:text-brand-purple transition-colors">Appointments</a>
                <a href="<?= base_url('staff') ?>" class="text-white hover:text-brand-purple transition-colors">Staff</a>
                <a href="<?= base_url('reports') ?>" class="text-white hover:text-brand-purple transition-colors">Reports</a>
                <a href="<?= base_url('staff/create') ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg transition-colors text-sm">+ Add Staff</a>
            <?php elseif ($role === 'receptionist'): ?>
                <a href="<?= base_url('dashboard') ?>" class="text-white hover:text-brand-purple transition-colors">Dashboard</a>
                <a href="<?= base_url('appointments/create') ?>" class="text-white hover:text-brand-purple transition-colors">New Appointment</a>
                <a href="<?= base_url('appointments') ?>" class="text-white hover:text-brand-purple transition-colors">All Appointments</a>
                <a href="<?= base_url('customers') ?>" class="text-white hover:text-brand-purple transition-colors">Customers</a>
            <?php else: ?>
                <a href="<?= base_url('dashboard') ?>" class="text-white hover:text-brand-purple transition-colors">My Dashboard</a>
                <a href="<?= base_url('appointments/create') ?>" class="text-white hover:text-brand-purple transition-colors">Book Appointment</a>
                <a href="<?= base_url('profile') ?>" class="text-white hover:text-brand-purple transition-colors">Profile</a>
            <?php endif; ?>

            <span class="text-white text-sm">👤 <?= esc($session->get('full_name')) ?></span>
            <a href="<?= base_url('logout') ?>" class="bg-brand-purple hover:bg-brand-dark text-white px-4 py-2 rounded-lg transition-colors">Logout</a>
        </div>
    </div>
</nav>
