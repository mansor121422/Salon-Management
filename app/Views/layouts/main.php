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
            <h1 class="text-xl md:text-2xl font-bold tracking-tight uppercase text-white">Salon</h1>
            <div class="flex items-center space-x-6">
                <a href="<?= base_url('receptionist') ?>" class="text-white hover:text-brand-purple transition-colors">Dashboard</a>
                <a href="<?= base_url('receptionist?show_modal=true') ?>" class="text-white hover:text-brand-purple transition-colors">New Appointment</a>
                <span class="text-white text-sm">👤 <?= esc(session()->get('full_name')) ?></span>
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
        <p>&copy; <?= date('Y') ?> Local Salon Management System</p>
    </footer>
</body>
</html>