<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Salon Management System' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .navbar-menu {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .navbar-menu a {
            color: #333;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .navbar-menu a:hover {
            background: #667eea;
            color: white;
        }

        .navbar-user {
            color: #666;
            font-size: 0.9rem;
        }

        .container {
            flex: 1;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            width: 100%;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            background: rgba(255, 255, 255, 0.95);
            padding: 1.5rem;
            text-align: center;
            color: #666;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <?php if (session()->get('logged_in')): ?>
    <nav class="navbar">
        <div class="navbar-brand">💇 Salon Management</div>
        <div class="navbar-menu">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('appointments/create') ?>">New Appointment</a>
            <span class="navbar-user">👤 <?= esc(session()->get('full_name')) ?></span>
            <a href="<?= base_url('logout') ?>" style="background: #dc3545; color: white;">Logout</a>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                ✓ <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                ✗ <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> Local Salon Management System | Developed by Uniteam</p>
    </footer>
</body>
</html>
