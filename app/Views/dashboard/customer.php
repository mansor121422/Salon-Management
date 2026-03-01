<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl p-8 mb-8 shadow-xl">
    <h1 class="text-white mb-2">Hello, <?= esc(session()->get('full_name')) ?>!</h1>
    <p class="text-green-200">Your appointments</p>
</div>

<div class="bg-white rounded-xl p-6 shadow-xl">
    <?php if (empty($appointments)): ?>
        <div class="text-center py-12 text-gray-600">
            <div class="text-4xl mb-4">📭</div>
            <h3 class="text-lg font-semibold">No appointments found</h3>
            <p class="mt-2 text-sm text-gray-500">You don't have any appointments yet.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Service</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Date</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Time</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-600 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-3 border-b border-gray-200"><?= esc($appointment['service_type']) ?></td>
                        <td class="p-3 border-b border-gray-200"><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></td>
                        <td class="p-3 border-b border-gray-200"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                        <td class="p-3 border-b border-gray-200">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold">
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

<?= $this->endSection() ?>
