<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="max-w-2xl mx-auto my-8 bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white py-8 px-8 text-center">
            <h1 class="text-3xl font-bold mb-2">Appointment Confirmed!</h1>
            <p class="text-purple-100">Your appointment has been successfully confirmed</p>
        </div>

        <!-- Content -->
        <div class="p-8">
            <div class="text-lg font-semibold text-gray-800 mb-4">Hello <?= esc($customer_name) ?>,</div>
            
            <p class="text-gray-600 mb-6 leading-relaxed">
                We're excited to confirm your appointment at Chanelle Salon. Your booking details are shown below.
            </p>

            <!-- Appointment Details -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-2xl">📋</span>
                    <h3 class="text-xl font-bold text-gray-800">Appointment Details</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-300">
                        <span class="font-semibold text-gray-700">Appointment ID</span>
                        <span class="text-gray-600">#<?= str_pad($appointment_id, 5, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-300">
                        <span class="font-semibold text-gray-700">Service</span>
                        <span class="text-gray-600"><?= esc($service_type) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-300">
                        <span class="font-semibold text-gray-700">Date</span>
                        <span class="text-gray-600"><?= date('F j, Y', strtotime($appointment_date)) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-300">
                        <span class="font-semibold text-gray-700">Time</span>
                        <span class="text-gray-600"><?= date('g:i A', strtotime($appointment_time)) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-300">
                        <span class="font-semibold text-gray-700">Status</span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Confirmed</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-300">
                        <span class="font-semibold text-gray-700">Price</span>
                        <span class="text-gray-600">₱<?= number_format($price, 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <?php if (!empty($notes)): ?>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-2xl">📝</span>
                    <h3 class="text-lg font-bold text-orange-800">Additional Notes</h3>
                </div>
                <p class="text-orange-700 leading-relaxed"><?= esc($notes) ?></p>
            </div>
            <?php endif; ?>

            <hr class="my-6 border-gray-300">

            <p class="text-gray-700 mb-4 leading-relaxed">
                <strong class="text-gray-800">Important:</strong> Please arrive 10-15 minutes before your scheduled appointment time.
                If you need to reschedule or cancel, please contact us at least 24 hours in advance.
            </p>

            <p class="text-gray-700 leading-relaxed">
                We look forward to serving you!
            </p>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 border-t border-gray-200 py-6 px-8 text-center">
            <p class="text-gray-800 font-semibold text-lg mb-1">Chanelle Salon</p>
            <p class="text-gray-600 mb-4">Professional Beauty & Hair Services</p>
            <p class="text-sm text-gray-500">
                For questions or assistance, please contact our reception desk.<br>
                Thank you for choosing Chanell Salon!
            </p>
        </div>
    </div>
</body>
</html>