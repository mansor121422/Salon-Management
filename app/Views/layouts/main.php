<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Salon Management System' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            purple: '#5A2C76',
                            dark: '#2C0A4B',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white min-h-screen flex flex-col font-sans antialiased">
    <?php if (session()->get('logged_in')): ?>
    <nav class="bg-brand-dark shadow-lg px-8 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl md:text-2xl font-bold tracking-tight uppercase text-white">Chanelle Salon</h1>
            <div class="flex items-center space-x-6">
                <?php 
                $role = strtolower((string) session()->get('role'));
                if ($role === 'admin'): ?>
                    <a href="<?= base_url('admin') ?>" class="text-white hover:text-brand-purple transition-colors">Dashboard</a>
                    <a href="<?= base_url('admin?tab=users') ?>" class="text-white hover:text-brand-purple transition-colors">Manage Users</a>
                    <a href="<?= base_url('admin?tab=activity') ?>" class="text-white hover:text-brand-purple transition-colors">User Activity</a>
                <?php elseif ($role === 'receptionist'): ?>
                    <a href="<?= base_url('receptionist') ?>" class="text-white hover:text-brand-purple transition-colors">Dashboard</a>
                    <a href="<?= base_url('receptionist?tab=appointments') ?>" class="text-white hover:text-brand-purple transition-colors">Appointments</a>
                <?php elseif ($role === 'staff'): ?>
                    <a href="<?= base_url('staff') ?>" class="text-white hover:text-brand-purple transition-colors">Dashboard</a>
                <?php elseif ($role === 'owner'): ?>
                    <a href="<?= base_url('owner') ?>" class="text-white hover:text-brand-purple transition-colors">Dashboard</a>
                    <a href="<?= base_url('owner?tab=appointments') ?>" class="text-white hover:text-brand-purple transition-colors">Appointments</a>
                    <a href="<?= base_url('owner?tab=analytics') ?>" class="text-white hover:text-brand-purple transition-colors">Analytics</a>
                    <a href="<?= base_url('owner?tab=monitoring') ?>" class="text-white hover:text-brand-purple transition-colors">Monitoring</a>
                <?php else: ?>
                    <a href="<?= base_url('dashboard') ?>" class="text-white hover:text-brand-purple transition-colors">Dashboard</a>
                <?php endif; ?>
                <button class="text-white hover:text-brand-purple transition-colors relative" onclick="toggleNotifications()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </button>
                <a href="<?= base_url('logout') ?>" class="bg-brand-purple hover:bg-brand-dark text-white px-4 py-2 rounded-lg transition-colors">Logout</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 animate-pulse">
                ✓ <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate-pulse">
                ✗ <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <footer class="bg-brand-dark py-6 text-center text-white mt-auto">
        <p> © 2024 Chanelle Salon. All rights reserved. </p>
    </footer>
</body>
</html>