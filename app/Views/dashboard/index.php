<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .dashboard-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .dashboard-header h1 {
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        color: #666;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    .appointments-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-header h2 {
        color: #333;
    }

    .btn-new {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .appointments-table thead {
        background: #f8f9fa;
    }

    .appointments-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
    }

    .appointments-table td {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .appointments-table tbody tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
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

    .status-completed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 5px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-confirm {
        background: #28a745;
        color: white;
    }

    .btn-confirm:hover {
        background: #218838;
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
    }

    .btn-cancel:hover {
        background: #c82333;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .appointments-table {
            font-size: 0.85rem;
        }

        .appointments-table th,
        .appointments-table td {
            padding: 0.5rem;
        }
    }
</style>

<div class="dashboard-header">
    <h1>👋 Welcome, <?= esc(session()->get('full_name')) ?>!</h1>
    <p>Manage your salon appointments efficiently</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-value"><?= count($appointments) ?></div>
        <div class="stat-label">Total Appointments</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">
            <?= count(array_filter($appointments, fn($a) => $a['status'] === 'pending')) ?>
        </div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value">
            <?= count(array_filter($appointments, fn($a) => $a['status'] === 'confirmed')) ?>
        </div>
        <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🎉</div>
        <div class="stat-value">
            <?= count(array_filter($appointments, fn($a) => $a['status'] === 'completed')) ?>
        </div>
        <div class="stat-label">Completed</div>
    </div>
</div>

<div class="appointments-section">
    <div class="section-header">
        <h2>📋 All Appointments</h2>
        <a href="/appointments/create" class="btn-new">+ New Appointment</a>
    </div>

    <?php if (empty($appointments)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>No appointments yet</h3>
            <p>Create your first appointment to get started</p>
        </div>
    <?php else: ?>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td>#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td><?= esc($appointment['customer_name']) ?></td>
                    <td><?= esc($appointment['customer_phone']) ?></td>
                    <td><?= esc($appointment['service_type']) ?></td>
                    <td><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></td>
                    <td><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                    <td>
                        <span class="status-badge status-<?= $appointment['status'] ?>">
                            <?= ucfirst($appointment['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <?php if ($appointment['status'] === 'pending'): ?>
                                <form action="/appointments/update-status/<?= $appointment['id'] ?>" method="post" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn-action btn-confirm">Confirm</button>
                                </form>
                                <form action="/appointments/update-status/<?= $appointment['id'] ?>" method="post" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn-action btn-cancel">Cancel</button>
                                </form>
                            <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                <form action="/appointments/update-status/<?= $appointment['id'] ?>" method="post" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn-action btn-confirm">Complete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
