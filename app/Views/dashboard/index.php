<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 rounded-xl p-8 mb-8 shadow-xl">
    <h1 class="text-white mb-2">Welcome, <?= esc(session()->get('full_name')) ?>!</h1>
    <p class="text-purple-200">Manage your salon appointments efficiently</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
        <div class="text-5xl mb-2">📅</div>
        <div class="text-3xl font-bold text-brand-dark"><?= count($appointments) ?></div>
        <div class="text-gray-600 text-sm">Total Appointments</div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
        <div class="text-5xl mb-2">⏳</div>
        <div class="text-3xl font-bold text-brand-dark">
            <?= count(array_filter($appointments, fn($a) => $a['status'] === 'pending')) ?>
        </div>
        <div class="text-gray-600 text-sm">Pending</div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
        <div class="text-5xl mb-2">✅</div>
        <div class="text-3xl font-bold text-brand-dark">
            <?= count(array_filter($appointments, fn($a) => $a['status'] === 'confirmed')) ?>
        </div>
        <div class="text-gray-600 text-sm">Confirmed</div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
        <div class="text-5xl mb-2">🎉</div>
        <div class="text-3xl font-bold text-brand-dark">
            <?= count(array_filter($appointments, fn($a) => $a['status'] === 'completed')) ?>
        </div>
        <div class="text-gray-600 text-sm">Completed</div>
    </div>
</div>

<div class="bg-white rounded-xl p-8 shadow-xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-brand-dark">📋 All Appointments</h2>
        <a href="<?= base_url('appointments/create') ?>" class="bg-gradient-to-r from-brand-purple to-brand-dark text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">+ New Appointment</a>
    </div>

    <?php if (empty($appointments)): ?>
        <div class="text-center py-12 text-gray-600">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-xl font-semibold mb-2">No appointments yet</h3>
            <p>Create your first appointment to get started</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse mt-4">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">ID</th>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Customer</th>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Phone</th>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Service</th>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Date</th>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Time</th>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Status</th>
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 border-b border-gray-200">#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td class="p-4 border-b border-gray-200"><?= esc($appointment['customer_name']) ?></td>
                        <td class="p-4 border-b border-gray-200"><?= esc($appointment['customer_phone']) ?></td>
                        <td class="p-4 border-b border-gray-200"><?= esc($appointment['service_type']) ?></td>
                        <td class="p-4 border-b border-gray-200"><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></td>
                        <td class="p-4 border-b border-gray-200"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                        <td class="p-4 border-b border-gray-200">
                            <?php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-blue-100 text-blue-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusClass = $statusClasses[$appointment['status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?= $statusClass ?>">
                                <?= ucfirst($appointment['status']) ?>
                            </span>
                        </td>
                        <td class="p-4 border-b border-gray-200">
                            <div class="flex gap-2">
                                <?php if ($appointment['status'] === 'pending'): ?>
                                    <form action="<?= base_url('appointments/update-status/' . $appointment['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">Confirm</button>
                                    </form>
                                    <form action="<?= base_url('appointments/update-status/' . $appointment['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">Cancel</button>
                                    </form>
                                <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                    <form action="<?= base_url('appointments/update-status/' . $appointment['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">Complete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
