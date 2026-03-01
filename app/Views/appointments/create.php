<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .appointment-form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h2 {
        color: #667eea;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: #666;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
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

    .form-group label .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    select.form-control {
        cursor: pointer;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .btn-group {
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
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="appointment-form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>📅 Create New Appointment</h2>
            <p>Fill in the customer details and schedule information</p>
        </div>

        <form action="<?= base_url('appointments/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="customer_name">Customer Name <span class="required">*</span></label>
                    <input type="text" id="customer_name" name="customer_name" class="form-control" 
                           value="<?= old('customer_name') ?>" required>
                </div>

                <div class="form-group">
                    <label for="customer_phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="customer_phone" name="customer_phone" class="form-control" 
                           value="<?= old('customer_phone') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="customer_email">Email Address (Optional)</label>
                <input type="email" id="customer_email" name="customer_email" class="form-control" 
                       value="<?= old('customer_email') ?>">
            </div>

            <div class="form-group">
                <label for="service_type">Service Type <span class="required">*</span></label>
                <select id="service_type" name="service_type" class="form-control" required>
                    <option value="">-- Select Service --</option>
                    <option value="Haircut" <?= old('service_type') == 'Haircut' ? 'selected' : '' ?>>Haircut</option>
                    <option value="Hair Coloring" <?= old('service_type') == 'Hair Coloring' ? 'selected' : '' ?>>Hair Coloring</option>
                    <option value="Hair Styling" <?= old('service_type') == 'Hair Styling' ? 'selected' : '' ?>>Hair Styling</option>
                    <option value="Hair Treatment" <?= old('service_type') == 'Hair Treatment' ? 'selected' : '' ?>>Hair Treatment</option>
                    <option value="Manicure" <?= old('service_type') == 'Manicure' ? 'selected' : '' ?>>Manicure</option>
                    <option value="Pedicure" <?= old('service_type') == 'Pedicure' ? 'selected' : '' ?>>Pedicure</option>
                    <option value="Facial" <?= old('service_type') == 'Facial' ? 'selected' : '' ?>>Facial</option>
                    <option value="Makeup" <?= old('service_type') == 'Makeup' ? 'selected' : '' ?>>Makeup</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="appointment_date">Appointment Date <span class="required">*</span></label>
                    <input type="date" id="appointment_date" name="appointment_date" class="form-control" 
                           value="<?= old('appointment_date') ?>" min="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label for="appointment_time">Appointment Time <span class="required">*</span></label>
                    <input type="time" id="appointment_time" name="appointment_time" class="form-control" 
                           value="<?= old('appointment_time') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea id="notes" name="notes" class="form-control" 
                          placeholder="Any special requests or notes..."><?= old('notes') ?></textarea>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= base_url('dashboard') ?>'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Appointment</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
