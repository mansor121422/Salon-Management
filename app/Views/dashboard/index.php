<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="bg-gradient-to-r from-gray-900 via-indigo-900 to-indigo-700 rounded-xl p-8 mb-8 shadow-2xl border-2 border-indigo-600">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-white mb-2 text-2xl">Admin Console — <?= esc(session()->get('full_name')) ?></h1>
            <p class="text-indigo-200">Overview and management</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-indigo-300">System Status</div>
            <div class="mt-1 inline-flex items-center gap-2">
                <span class="inline-block w-3 h-3 rounded-full bg-green-400"></span>
                <span class="text-sm text-white">All services running</span>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards (Admin emphasis) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 shadow-lg text-gray-900 hover:shadow-2xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-yellow-900 text-sm font-medium mb-1">Today's Revenue</div>
                <div class="text-3xl font-bold">₱<?= number_format($today_revenue, 2) ?></div>
            </div>
            <div class="text-5xl opacity-60">💸</div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-6 shadow-lg text-white hover:shadow-2xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-indigo-100 text-sm font-medium mb-1">Weekly Revenue</div>
                <div class="text-3xl font-bold">₱<?= number_format($weekly_revenue, 2) ?></div>
            </div>
            <div class="text-5xl opacity-50">📊</div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg p-6 shadow-lg text-white hover:shadow-2xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-purple-100 text-sm font-medium mb-1">Monthly Revenue</div>
                <div class="text-3xl font-bold">₱<?= number_format($monthly_revenue, 2) ?></div>
            </div>
            <div class="text-5xl opacity-60">📈</div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
        <div class="text-5xl mb-2">📅</div>
        <div class="text-3xl font-bold text-brand-dark"><?= count($appointments) ?></div>
        <div class="text-gray-600 text-sm">Total Appointments</div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
        <div class="text-5xl mb-2">👥</div>
        <div class="text-3xl font-bold text-brand-dark"><?= $total_customers ?></div>
        <div class="text-gray-600 text-sm">Total Customers</div>
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

<!-- Recent Appointments and Top Services -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Appointments -->
    <div class="bg-white rounded-xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark text-lg font-bold">🕐 Recent Appointments</h2>
            <a href="<?= base_url('appointments/create') ?>" class="text-purple-600 hover:text-purple-800 text-sm font-medium">+ New</a>
        </div>
        
        <?php if (empty($recent_appointments)): ?>
            <div class="text-center py-8 text-gray-600">
                <div class="text-4xl mb-2">📭</div>
                <p>No recent appointments</p>
            </div>
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
                        <?php foreach ($recent_appointments as $appointment): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-3 border-b border-gray-100 text-sm"><?= esc($appointment['customer_name']) ?></td>
                            <td class="p-3 border-b border-gray-100 text-sm"><?= esc($appointment['service_type']) ?></td>
                            <td class="p-3 border-b border-gray-100 text-sm"><?= date('M d', strtotime($appointment['appointment_date'])) ?></td>
                            <td class="p-3 border-b border-gray-100">
                                <?php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusClass = $statusClasses[$appointment['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
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

    <!-- Top Services -->
    <div class="bg-white rounded-xl p-6 shadow-xl">
        <h2 class="text-brand-dark text-lg font-bold mb-6">🏆 Top Services</h2>
        
        <?php if (empty($top_services)): ?>
            <div class="text-center py-8 text-gray-600">
                <div class="text-4xl mb-2">📊</div>
                <p>No service data yet</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php 
                $maxCount = !empty($top_services) ? $top_services[0]->total_count : 1;
                foreach ($top_services as $index => $service): 
                    $percentage = ($service->total_count / $maxCount) * 100;
                    $colors = ['bg-purple-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500'];
                    $color = $colors[$index % count($colors)];
                ?>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full <?= $color ?> text-white flex items-center justify-center text-sm font-bold">
                        <?= $index + 1 ?>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-medium text-gray-800"><?= esc($service->service_type) ?></span>
                            <span class="text-sm text-gray-600"><?= $service->total_count ?> appointments</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="<?= $color ?> h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">₱<?= number_format($service->total_revenue ?? 0, 2) ?> revenue</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- All Appointments -->
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
                        <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Price</th>
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
                        <td class="p-4 border-b border-gray-200">₱<?= number_format($appointment['price'] ?? 0, 2) ?></td>
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
