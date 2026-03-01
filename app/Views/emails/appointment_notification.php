<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointment Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #5A2C76 0%, #2C0A4B 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .appointment-details {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eeeeee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #5A2C76;
        }
        .detail-value {
            color: #333333;
        }
        .queue-number {
            background: linear-gradient(135deg, #5A2C76 0%, #2C0A4B 100%);
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }
        .queue-number .label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .queue-number .number {
            font-size: 48px;
            font-weight: bold;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #ffc107;
            color: #333333;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 New Appointment Booked!</h1>
        </div>
        
        <div class="content">
            <p>Hello!</p>
            
            <p>A new appointment has been scheduled at <strong>Salon Management System</strong>. Here are the details:</p>
            
            <div class="appointment-details">
                <div class="detail-row">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value"><?= $appointment['customer_name'] ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Phone Number:</span>
                    <span class="detail-value"><?= $appointment['customer_phone'] ?></span>
                </div>
                
                <?php if (!empty($appointment['customer_email'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?= $appointment['customer_email'] ?></span>
                </div>
                <?php endif; ?>
                
                <div class="detail-row">
                    <span class="detail-label">Service:</span>
                    <span class="detail-value"><?= $appointment['service_type'] ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value"><?= date('F j, Y', strtotime($appointment['appointment_date'])) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge"><?= ucfirst($appointment['status']) ?></span>
                    </span>
                </div>
                
                <?php if (!empty($appointment['staff_name'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Staff Assigned:</span>
                    <span class="detail-value"><?= $appointment['staff_name'] ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($appointment['price'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Price:</span>
                    <span class="detail-value">₱<?= number_format($appointment['price'], 2) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($appointment['queue_number'])): ?>
            <div class="queue-number">
                <div class="label">Your Queue Number</div>
                <div class="number">#<?= $appointment['queue_number'] ?></div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($appointment['notes'])): ?>
            <div class="appointment-details">
                <div class="detail-row">
                    <span class="detail-label">Notes:</span>
                    <span class="detail-value"><?= $appointment['notes'] ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <p>Thank you for using our service!</p>
            
            <p>Best regards,<br>
            <strong>Salon Management System</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; <?= date('Y') ?> Salon Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
