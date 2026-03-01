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
        <?= view('header/header') ?>
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
