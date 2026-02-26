<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .login-container {
        max-width: 450px;
        margin: 4rem auto;
    }

    .login-card {
        background: white;
        border-radius: 15px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-header h1 {
        color: #667eea;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .login-header p {
        color: #666;
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .login-info {
        margin-top: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #666;
    }

    .login-info strong {
        color: #333;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>💇 Salon Login</h1>
            <p>Receptionist Dashboard Access</p>
        </div>

        <form action="/login" method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="login-info">
            <strong>Demo Credentials:</strong><br>
            Username: <code>receptionist</code><br>
            Password: <code>receptionist123</code>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
