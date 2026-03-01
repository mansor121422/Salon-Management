<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .confirmation-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .confirmation-card {
        background: white;
        border-radius: 15px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        text-align: center;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .success-icon {
        font-size: 5rem;
        margin-bottom: 1rem;
        animation: bounce 1s ease;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-20px);
        }
        60% {
            transform: translateY(-10px);
        }
    }

    .confirmation-card h2 {
        color: #28a745;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .confirmation-card p {
        color: #666;
        margin-bottom: 2rem;
    }

    .appointment-details {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 2rem;
        margin: 2rem 0;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #333;
    }

    .detail-value {
        color: #666;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }
</style>

<div class="confirmation-container">
    <div class="confirmation-card">
        <div class="success-icon">✅</div>
        <h2>Appointment Created!</h2>
        <p>The appointment has been successfully scheduled</p>

        <div class="appointment-details">
            <div class="detail-row">
                <span class="detail-label">Appointment ID:</span>
                <span class="detail-value">#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Customer Name:</span>
                <span class="detail-value"><?= esc($appointment['customer_name']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone Number:</span>
                <span class="detail-value"><?= esc($appointment['customer_phone']) ?></span>
            </div>
            <?php if ($appointment['customer_email']): ?>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><?= esc($appointment['customer_email']) ?></span>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <span class="detail-label">Service:</span>
                <span class="detail-value"><?= esc($appointment['service_type']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span class="detail-value"><?= date('F d, Y', strtotime($appointment['appointment_date'])) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <span class="detail-value"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-<?= $appointment['status'] ?>">
                        <?= ucfirst($appointment['status']) ?>
                    </span>
                </span>
            </div>
            <?php if ($appointment['notes']): ?>
            <div class="detail-row">
                <span class="detail-label">Notes:</span>
                <span class="detail-value"><?= esc($appointment['notes']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="action-buttons">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">View All Appointments</a>
            <a href="<?= base_url('appointments/create') ?>" class="btn btn-success">Create Another</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
