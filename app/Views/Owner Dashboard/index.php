<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Dashboard Header -->
<div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 rounded-xl p-8 mb-8 shadow-xl">
    <h1 class="text-white mb-2">Welcome, <?= esc(session()->get('full_name')) ?>!</h1>
    <p class="text-purple-200">Monitor your salon's appointment activity</p>
</div>

<!-- Dashboard Overview Tab -->
<div id="dashboard-tab" class="tab-content active">
    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
            <div class="text-5xl mb-2">📅</div>
            <div class="text-3xl font-bold text-brand-dark"><?= $total_appointments ?? 0 ?></div>
            <div class="text-gray-600 text-sm">Total Appointments</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
            <div class="text-5xl mb-2">⏳</div>
            <div class="text-3xl font-bold text-brand-dark"><?= $pending_appointments ?? 0 ?></div>
            <div class="text-gray-600 text-sm">Pending</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
            <div class="text-5xl mb-2">✅</div>
            <div class="text-3xl font-bold text-brand-dark"><?= $confirmed_appointments ?? 0 ?></div>
            <div class="text-gray-600 text-sm">Confirmed</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
            <div class="text-5xl mb-2">🎉</div>
            <div class="text-3xl font-bold text-brand-dark"><?= $completed_appointments ?? 0 ?></div>
            <div class="text-gray-600 text-sm">Completed</div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Today's Appointments -->
        <div class="bg-white rounded-xl p-6 shadow-lg">
            <h3 class="text-brand-dark text-xl font-bold mb-4">📅 Today's Appointments</h3>
            <?php if (empty($today_appointments ?? [])): ?>
                <div class="text-center py-8 text-gray-600">
                    <div class="text-4xl mb-2">📅</div>
                    <p>No appointments scheduled for today</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($today_appointments as $appointment): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-900"><?= esc($appointment['customer_name']) ?></h4>
                                    <p class="text-sm text-gray-600"><?= esc($appointment['service_type']) ?></p>
                                </div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <?= date('h:i A', strtotime($appointment['appointment_time'])) ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">₱<?= number_format($appointment['price'], 2) ?></span>
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    <?= ucfirst($appointment['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-xl p-6 shadow-lg">
            <h3 class="text-brand-dark text-xl font-bold mb-4">⏰ Upcoming Appointments</h3>
            <?php if (empty($upcoming_appointments ?? [])): ?>
                <div class="text-center py-8 text-gray-600">
                    <div class="text-4xl mb-2">⏰</div>
                    <p>No upcoming appointments</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($upcoming_appointments as $appointment): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-900"><?= esc($appointment['customer_name']) ?></h4>
                                    <p class="text-sm text-gray-600"><?= esc($appointment['service_type']) ?></p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-900"><?= date('M d', strtotime($appointment['appointment_date'])) ?></div>
                                    <div class="text-xs text-gray-600"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">₱<?= number_format($appointment['price'], 2) ?></span>
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <?= ucfirst($appointment['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- All Appointments Tab -->
<div id="appointments-tab" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark text-2xl font-bold">📋 All Appointments</h2>
            <div class="flex gap-4">
                <button onclick="exportAppointments()" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    📊 Export Data
                </button>
                <button onclick="refreshAppointments()" class="bg-gradient-to-r from-purple-800 to-purple-900 hover:from-purple-900 hover:to-indigo-900 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    🔄 Refresh
                </button>
            </div>
        </div>

        <?php if (empty($all_appointments ?? [])): ?>
            <div class="text-center py-12 text-gray-600">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-semibold mb-2">No appointments yet</h3>
                <p>Appointments will appear here once created</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">ID</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Customer</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Service</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Date & Time</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Price</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Status</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Assigned Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_appointments as $appointment): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 border-b border-gray-200">#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td class="p-4 border-b border-gray-200">
                                <div>
                                    <div class="font-medium"><?= esc($appointment['customer_name']) ?></div>
                                    <div class="text-sm text-gray-600"><?= esc($appointment['customer_phone']) ?></div>
                                </div>
                            </td>
                            <td class="p-4 border-b border-gray-200"><?= esc($appointment['service_type']) ?></td>
                            <td class="p-4 border-b border-gray-200">
                                <div>
                                    <div class="font-medium"><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></div>
                                    <div class="text-sm text-gray-600"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></div>
                                </div>
                            </td>
                            <td class="p-4 border-b border-gray-200 font-semibold">₱<?= number_format($appointment['price'], 2) ?></td>
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
                                <?php if (!empty($appointment['staff_name'])): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                                        <?= esc($appointment['staff_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500 text-sm italic">Not assigned</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Analytics Tab -->
<div id="analytics-tab" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark text-2xl font-bold">📊 Appointment Analytics</h2>
            <div class="text-sm text-gray-600">
                Last updated: <span id="analytics-last-updated"><?= date('M d, Y H:i') ?></span>
            </div>
        </div>

        <!-- Analytics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-green-800 font-semibold">Revenue</h3>
                        <p class="text-green-600 text-sm">This month</p>
                    </div>
                    <div class="text-3xl">💰</div>
                </div>
                <div class="text-2xl font-bold text-green-900">₱<?= number_format($monthly_revenue ?? 0, 2) ?></div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-blue-800 font-semibold">Completion Rate</h3>
                        <p class="text-blue-600 text-sm">This month</p>
                    </div>
                    <div class="text-3xl">📈</div>
                </div>
                <div class="text-2xl font-bold text-blue-900"><?= $completion_rate ?? 0 ?>%</div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-purple-800 font-semibold">Avg. Daily Appointments</h3>
                        <p class="text-purple-600 text-sm">This month</p>
                    </div>
                    <div class="text-3xl">📊</div>
                </div>
                <div class="text-2xl font-bold text-purple-900"><?= $avg_daily_appointments ?? 0 ?></div>
            </div>
        </div>

        <!-- Service Breakdown -->
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="text-brand-dark font-bold mb-4">Service Breakdown</h3>
            <?php if (empty($service_breakdown ?? [])): ?>
                <div class="text-center py-8 text-gray-600">
                    <p>No service data available</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($service_breakdown as $service): ?>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium"><?= esc($service['service_type']) ?></span>
                                <span class="text-sm text-gray-600"><?= $service['count'] ?> appointments</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: <?= $service['percentage'] ?>%"></div>
                            </div>
                            <div class="text-right text-sm text-gray-600 mt-1">₱<?= number_format($service['revenue'], 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Real-time Monitoring -->
<div id="monitoring-tab" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark text-2xl font-bold">👁️ Real-time Monitoring</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">Last updated: <span id="monitoring-last-updated"><?= date('M d, Y H:i') ?></span></span>
                <button onclick="toggleMonitoring()" id="monitoring-toggle" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    🚫 Stop Monitoring
                </button>
            </div>
        </div>

        <!-- Live Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                <h3 class="text-green-800 font-semibold mb-2">✅ Active Appointments</h3>
                <div class="text-3xl font-bold text-green-900" id="live-active-count"><?= $active_appointments ?? 0 ?></div>
                <p class="text-green-600 text-sm">Currently in progress</p>
            </div>
            <div class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-xl p-6">
                <h3 class="text-orange-800 font-semibold mb-2">⚠️ Pending Confirmations</h3>
                <div class="text-3xl font-bold text-orange-900" id="live-pending-count"><?= $pending_appointments ?? 0 ?></div>
                <p class="text-orange-600 text-sm">Awaiting confirmation</p>
            </div>
        </div>

        <!-- Live Updates -->
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="text-brand-dark font-bold mb-4">Live Updates</h3>
            <div id="live-updates" class="space-y-2">
                <!-- Live updates will be injected here -->
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching functionality
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
        tab.classList.remove('active');
    });
    
    // Show selected tab
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
        selectedTab.classList.add('active');
        
        // If switching to monitoring tab, start auto-refresh
        if (tabName === 'monitoring-tab') {
            startMonitoring();
        } else if (tabName === 'analytics-tab') {
            refreshAnalytics();
        }
    }
}

// Real-time monitoring
let monitoringInterval;
let isMonitoring = false;

function startMonitoring() {
    if (isMonitoring) return;
    
    isMonitoring = true;
    document.getElementById('monitoring-toggle').innerHTML = '🛑 Stop Monitoring';
    document.getElementById('monitoring-toggle').className = 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2 rounded-lg font-semibold transition-colors';
    
    monitoringInterval = setInterval(() => {
        refreshMonitoring();
    }, 10000); // Refresh every 10 seconds
}

function stopMonitoring() {
    if (monitoringInterval) {
        clearInterval(monitoringInterval);
        monitoringInterval = null;
        isMonitoring = false;
        document.getElementById('monitoring-toggle').innerHTML = '▶️ Start Monitoring';
        document.getElementById('monitoring-toggle').className = 'bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-semibold transition-colors';
    }
}

function toggleMonitoring() {
    if (isMonitoring) {
        stopMonitoring();
    } else {
        startMonitoring();
    }
}

function refreshMonitoring() {
    // Update last updated time
    const now = new Date();
    const timeString = now.toLocaleString('en-PH', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Manila'
    });
    
    document.getElementById('monitoring-last-updated').textContent = timeString;
    
    // Fetch updated monitoring data
    fetch('<?= base_url('owner/monitoring') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateMonitoringStats(data.stats);
                updateLiveUpdates(data.updates);
            }
        })
        .catch(error => {
            console.error('Error refreshing monitoring:', error);
        });
}

function updateMonitoringStats(stats) {
    const activeCount = document.getElementById('live-active-count');
    const pendingCount = document.getElementById('live-pending-count');
    
    if (activeCount) {
        activeCount.textContent = stats.active_appointments || 0;
    }
    if (pendingCount) {
        pendingCount.textContent = stats.pending_appointments || 0;
    }
}

function updateLiveUpdates(updates) {
    const container = document.getElementById('live-updates');
    if (!container) return;
    
    if (updates.length === 0) {
        container.innerHTML = '<div class="text-gray-600 text-sm">No recent updates</div>';
        return;
    }
    
    container.innerHTML = updates.map(update => `
        <div class="flex items-center gap-3 p-2 bg-white rounded-lg shadow-sm">
            <div class="w-2 h-2 bg-${update.type === 'new' ? 'green' : update.type === 'status' ? 'blue' : 'yellow'}-500 rounded-full"></div>
            <div class="flex-1">
                <div class="font-medium text-sm">${update.message}</div>
                <div class="text-xs text-gray-500">${update.time}</div>
            </div>
        </div>
    `).join('');
}

function refreshAppointments() {
    window.location.reload();
}

function refreshAnalytics() {
    // Update last updated time
    const now = new Date();
    const timeString = now.toLocaleString('en-PH', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Manila'
    });
    
    document.getElementById('analytics-last-updated').textContent = timeString;
    
    // Fetch updated analytics data
    fetch('<?= base_url('owner/analytics') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAnalytics(data.analytics);
            }
        })
        .catch(error => {
            console.error('Error refreshing analytics:', error);
        });
}

function updateAnalytics(analytics) {
    // Update analytics cards
    const revenueElement = document.querySelector('.bg-gradient-to-br.from-green-50.to-emerald-50 .text-2xl');
    const completionElement = document.querySelector('.bg-gradient-to-br.from-blue-50.to-cyan-50 .text-2xl');
    const avgElement = document.querySelector('.bg-gradient-to-br.from-purple-50.to-pink-50 .text-2xl');
    
    if (revenueElement) {
        revenueElement.textContent = '₱' + (analytics.monthly_revenue || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
    }
    if (completionElement) {
        completionElement.textContent = (analytics.completion_rate || 0) + '%';
    }
    if (avgElement) {
        avgElement.textContent = analytics.avg_daily_appointments || 0;
    }
}

function exportAppointments() {
    // Create a simple CSV export
    const table = document.querySelector('#appointments-tab table tbody');
    if (!table) return;
    
    let csv = 'ID,Customer,Service,Date,Time,Price,Status,Assigned Staff\n';
    
    Array.from(table.querySelectorAll('tr')).forEach(row => {
        const cells = Array.from(row.querySelectorAll('td')).map(td => {
            return td.textContent.replace(/,/g, ';').trim();
        });
        csv += cells.join(',') + '\n';
    });
    
    // Create download link
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'appointments_export_' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Initialize tabs based on URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    
    // Default to dashboard tab if no tab parameter
    if (tab === 'appointments') {
        showTab('appointments-tab');
    } else if (tab === 'analytics') {
        showTab('analytics-tab');
    } else if (tab === 'monitoring') {
        showTab('monitoring-tab');
    } else {
        showTab('dashboard-tab');
    }
    
    // Add event listener to stop monitoring when leaving monitoring tab
    document.querySelectorAll('button[onclick*="showTab"]').forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('onclick').match(/showTab\('([^']+)'\)/)[1];
            if (tabName !== 'monitoring-tab') {
                stopMonitoring();
            }
        });
    });
    
    // Start monitoring if on monitoring tab
    if (tab === 'monitoring') {
        startMonitoring();
    }
});
</script>

<?= $this->endSection() ?>