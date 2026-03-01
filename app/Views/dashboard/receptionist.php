<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col lg:flex-row gap-6 mb-8">
    <div class="lg:w-1/3 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl p-6 shadow-lg text-white">
        <h2 class="text-xl font-semibold mb-3">Receptionist</h2>
        <p class="text-teal-100 mb-4">Quick actions and today's overview</p>

        <div class="space-y-3">
            <a href="<?= base_url('appointments/create') ?>" class="block bg-white text-teal-700 px-4 py-3 rounded-lg font-medium hover:opacity-95">+ New Appointment</a>
            <a href="<?= base_url('appointments') ?>" class="block bg-white/20 text-white px-4 py-3 rounded-lg">View All Appointments</a>
            <div class="mt-4 bg-white/10 p-4 rounded-lg">
                <div class="text-sm text-teal-100">Total Appointments</div>
                <div class="text-2xl font-bold"><?= count($appointments) ?></div>
            </div>
        </div>
    </div>

    <div class="lg:flex-1 bg-white rounded-xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark text-lg font-bold">🕐 Recent Appointments</h2>
            <a href="<?= base_url('appointments/create') ?>" class="text-teal-600 hover:text-teal-800 text-sm font-medium">+ New</a>
        </div>

        <?php if (empty($appointments)): ?>
            <div class="text-center py-8 text-gray-600">No recent appointments</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Customer</th>
                            <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Service</th>
                            <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Date</th>
                            <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-3 border-b border-gray-100 text-sm"><?= esc($appointment['customer_name']) ?></td>
                            <td class="p-3 border-b border-gray-100 text-sm"><?= esc($appointment['service_type']) ?></td>
                            <td class="p-3 border-b border-gray-100 text-sm"><?= date('M d', strtotime($appointment['appointment_date'])) ?></td>
                            <td class="p-3 border-b border-gray-100 text-sm">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold <?= $appointment['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' ?>">
                                    <?= ucfirst($appointment['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
