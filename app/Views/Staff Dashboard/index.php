<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Dashboard Header -->
<div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 rounded-xl p-8 mb-8 shadow-xl">
    <h1 class="text-white mb-2">Staff Dashboard</h1>
    <p class="text-purple-200">Welcome, <?= esc($user['full_name']) ?> (<?= esc($user['role']) ?>)</p>
</div>

<!-- Daily Appointment View -->
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-brand-dark text-2xl font-bold">📅 Today's Appointments</h2>
    </div>
    
    <!-- Date Selector -->
    <div class="mb-6 flex items-center space-x-4">
        <label for="appointmentDate" class="text-sm font-medium text-gray-700">Select Date:</label>
        <input type="date" id="appointmentDate" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-purple">
        <button onclick="loadAppointments()" class="bg-gradient-to-r from-brand-purple to-brand-dark text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all">
            Load Appointments
        </button>
    </div>

    <!-- Appointments Table -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Time</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Service</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white" id="appointmentsBody">
                <!-- Appointments will be loaded here via JavaScript -->
                <tr>
                    <td colspan="5" class="px-3 py-4 text-center text-gray-500">Loading appointments...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Set today's date as default
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('appointmentDate');
        
        // Set default date from PHP or use today
        const defaultDate = '<?= $current_date ?? $today ?>';
        dateInput.value = defaultDate;
        
        // Load initial appointments if data is available
        <?php if (isset($appointments) && !empty($appointments)): ?>
            displayAppointments(<?= json_encode($appointments) ?>);
        <?php else: ?>
            loadAppointments();
        <?php endif; ?>
    });

    function loadAppointments() {
        const date = document.getElementById('appointmentDate').value;
        const tbody = document.getElementById('appointmentsBody');
        
        // Show loading state
        tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">Loading appointments for ' + date + '...</td></tr>';
        
        // Fetch appointments from server
        fetch('<?= base_url('staff/appointments/data') ?>?date=' + date)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAppointments(data.appointments);
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-red-500">Failed to load appointments.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-red-500">Error loading appointments.</td></tr>';
            });
    }

    function displayAppointments(appointments) {
        const tbody = document.getElementById('appointmentsBody');
        
        if (appointments.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">No appointments for this date.</td></tr>';
            return;
        }

        let html = '';
        appointments.forEach(appointment => {
            const statusClass = appointment.status === 'Completed' ? 'text-green-600' : 
                               appointment.status === 'Confirmed' ? 'text-blue-600' : 'text-yellow-600';
            const statusBgClass = appointment.status === 'Completed' ? 'bg-green-100' : 
                                appointment.status === 'Confirmed' ? 'bg-blue-100' : 'bg-yellow-100';
            
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">${appointment.time}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${appointment.customer}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${appointment.service}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm ${statusClass}">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold ${statusBgClass} border ${statusBgClass.replace('bg-', 'border-')}">
                            ${appointment.status}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        ${appointment.status !== 'Completed' ? `
                            <button onclick="updateAppointmentStatus(${appointment.id}, 'completed')" 
                                    class="bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1 rounded text-sm font-medium hover:shadow-lg hover:translate-y-[-1px] transition-all">
                                Mark Complete
                            </button>
                        ` : `
                            <span class="text-green-600 text-sm font-medium">✓ Completed</span>
                        `}
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
    }

    function updateAppointmentStatus(appointmentId, newStatus) {
        // Show loading state on button
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Updating...';
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');

        fetch('<?= base_url('staff/appointments/update-status/') ?>' + appointmentId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'status=' + newStatus
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload appointments to reflect the change
                loadAppointments();
                
                // Show success message
                showFlashMessage('Appointment status updated successfully!', 'success');
            } else {
                // Restore button state
                button.textContent = originalText;
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                
                // Show error message
                showFlashMessage(data.message || 'Failed to update appointment status.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Restore button state
            button.textContent = originalText;
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            
            // Show error message
            showFlashMessage('An error occurred while updating the appointment.', 'error');
        });
    }

    function showFlashMessage(message, type) {
        const flashContainer = document.querySelector('.max-w-7xl');
        const flashMessage = document.createElement('div');
        flashMessage.className = `bg-${type === 'success' ? 'green' : 'red'}-100 border border-${type === 'success' ? 'green' : 'red'}-400 text-${type === 'success' ? 'green' : 'red'}-700 px-4 py-3 rounded mb-4 animate-pulse`;
        flashMessage.textContent = message;
        
        flashContainer.insertBefore(flashMessage, flashContainer.firstChild);
        
        // Remove after 3 seconds
        setTimeout(() => {
            flashMessage.remove();
        }, 3000);
    }
</script>

<?= $this->endSection() ?>