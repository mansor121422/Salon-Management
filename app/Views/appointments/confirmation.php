<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl p-12 shadow-xl text-center animate-in scale-95 duration-500">
        <div class="text-5xl mb-4 animate-bounce">✅</div>
        <h2 class="text-green-600 text-2xl font-bold mb-2">Appointment Created!</h2>
        <p class="text-gray-600 mb-8">The appointment has been successfully scheduled</p>

        <div class="bg-gray-50 rounded-lg p-8 mb-8 text-left">
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Appointment ID:</span>
                <span class="text-gray-600">#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Customer Name:</span>
                <span class="text-gray-600"><?= esc($appointment['customer_name']) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Phone Number:</span>
                <span class="text-gray-600"><?= esc($appointment['customer_phone']) ?></span>
            </div>
            <?php if ($appointment['customer_email']): ?>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Email:</span>
                <span class="text-gray-600"><?= esc($appointment['customer_email']) ?></span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Service:</span>
                <span class="text-gray-600"><?= esc($appointment['service_type']) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Date:</span>
                <span class="text-gray-600"><?= date('F d, Y', strtotime($appointment['appointment_date'])) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Time:</span>
                <span class="text-gray-600"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Status:</span>
                <span class="text-gray-600">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold <?= $appointment['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= ucfirst($appointment['status']) ?>
                    </span>
                </span>
            </div>
            <?php if ($appointment['notes']): ?>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Notes:</span>
                <span class="text-gray-600"><?= esc($appointment['notes']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="flex gap-4 mt-8">
            <a href="<?= base_url('dashboard') ?>" class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-4 px-6 rounded-lg font-semibold hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300">
                View All Appointments
            </a>
            <a href="<?= base_url('appointments/create') ?>" class="flex-1 bg-green-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300">
                Create Another
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
