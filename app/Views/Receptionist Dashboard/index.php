<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Dashboard Header -->
<div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 rounded-xl p-8 mb-8 shadow-xl">
    <h1 class="text-white mb-2">Welcome, <?= esc(session()->get('full_name')) ?>!</h1>
    <p class="text-purple-200">Manage your salon appointments efficiently</p>
</div>

    <!-- Overview Tab -->
    <div id="overview-content" class="tab-content active">
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
        
    <!-- Create Appointment Modal -->
    <div id="create-appointment-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-8 shadow-xl text-center max-w-4xl w-full mx-4 animate-in scale-95 duration-300">
            <div class="text-center mb-6">
                <h2 class="text-purple-900 text-2xl font-bold mb-2">📅 Create New Appointment</h2>
                <p class="text-gray-600">Fill in the customer details and schedule information</p>
            </div>

            <form id="appointment-form" action="<?= base_url('receptionist/appointments/store') ?>" method="post" class="text-left">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="customer_name" class="block mb-2 text-gray-700 font-medium">
                            Customer Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="customer_name" name="customer_name" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                               value="<?= old('customer_name') ?>" required oninput="validateCustomerName()">
                        <div id="name-error" class="mt-2 text-red-500 text-sm hidden"></div>
                    </div>

                    <div>
                        <label for="customer_phone" class="block mb-2 text-gray-700 font-medium">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="customer_phone" name="customer_phone" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                               value="<?= old('customer_phone') ?>" required oninput="validatePhoneNumber()">
                        <div id="phone-error" class="mt-2 text-red-500 text-sm hidden"></div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="customer_email" class="block mb-2 text-gray-700 font-medium">
                        Email Address (Optional)
                    </label>
                    <input type="email" id="customer_email" name="customer_email" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                           value="<?= old('customer_email') ?>">
                </div>

                <div class="mb-6">
                    <label for="service_type" class="block mb-2 text-gray-700 font-medium">
                        Service Type <span class="text-red-500">*</span>
                    </label>
                    <select id="service_type" name="service_type" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300 cursor-pointer"
                            required>
                        <option value="">-- Select Service --</option>
                        <?php if (!empty($service_prices)): ?>
                            <?php foreach ($service_prices as $service): ?>
                                <option value="<?= esc($service->service_name) ?>" data-price="<?= esc($service->price) ?>" <?= old('service_type') == $service->service_name ? 'selected' : '' ?>>
                                    <?= esc($service->service_name) ?> - ₱<?= number_format($service->price, 2) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="Haircut" <?= old('service_type') == 'Haircut' ? 'selected' : '' ?>>Haircut</option>
                            <option value="Hair Coloring" <?= old('service_type') == 'Hair Coloring' ? 'selected' : '' ?>>Hair Coloring</option>
                            <option value="Hair Styling" <?= old('service_type') == 'Hair Styling' ? 'selected' : '' ?>>Hair Styling</option>
                            <option value="Hair Treatment" <?= old('service_type') == 'Hair Treatment' ? 'selected' : '' ?>>Hair Treatment</option>
                            <option value="Manicure" <?= old('service_type') == 'Manicure' ? 'selected' : '' ?>>Manicure</option>
                            <option value="Pedicure" <?= old('service_type') == 'Pedicure' ? 'selected' : '' ?>>Pedicure</option>
                            <option value="Facial" <?= old('service_type') == 'Facial' ? 'selected' : '' ?>>Facial</option>
                            <option value="Makeup" <?= old('service_type') == 'Makeup' ? 'selected' : '' ?>>Makeup</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Assign Staff Dropdown -->
                <div class="mb-6">
                    <label for="staff_id" class="block mb-2 text-gray-700 font-medium">
                        Assign Staff (Optional)
                    </label>
                    <select id="staff_id" name="staff_id" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300 cursor-pointer">
                        <option value="">-- Select Staff --</option>
                        <?php if (!empty($staff)): ?>
                            <?php foreach ($staff as $member): ?>
                                <option value="<?= esc($member['id']) ?>" <?= old('staff_id') == $member['id'] ? 'selected' : '' ?>>
                                    <?= esc($member['name']) ?> (<?= esc($member['role']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Leave empty if no specific staff member is assigned</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="appointment_date" class="block mb-2 text-gray-700 font-medium">
                            Appointment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="appointment_date" name="appointment_date" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                               value="<?= old('appointment_date') ?>" min="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div>
                        <label for="appointment_time" class="block mb-2 text-gray-700 font-medium">
                            Appointment Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="appointment_time" name="appointment_time" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                               value="<?= old('appointment_time') ?>" required>
                    </div>

                    <div>
                        <label for="price" class="block mb-2 text-gray-700 font-medium">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" id="price" name="price" 
                                   class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                                   value="<?= old('price') ?>" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <label for="notes" class="block mb-2 text-gray-700 font-medium">
                        Additional Notes
                    </label>
                    <textarea id="notes" name="notes" 
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300 resize-vertical min-h-[100px]"
                              placeholder="Any special requests or notes..." oninput="validateNotes()"><?= old('notes') ?></textarea>
                    <div id="notes-error" class="mt-2 text-red-500 text-sm hidden"></div>
                </div>

            <div class="flex gap-4">
                <button type="button" 
                        class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-700 transition-colors duration-300"
                        onclick="closeCreateModal()">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-800 to-purple-900 text-white py-3 px-6 rounded-lg font-semibold hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300">
                    Create Appointment
                </button>
            </div>
            </form>
        </div>
    </div>

    <!-- All Appointments Content -->
    <div id="all-content">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark">📋 All Appointments</h2>
            <button onclick="openCreateModal()" class="bg-gradient-to-r from-brand-purple to-brand-dark text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">+ New Appointment</button>
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
                                        <form action="<?= base_url('receptionist/appointments/update-status/' . $appointment['id']) ?>" method="post" class="inline update-status-form">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">Confirm</button>
                                        </form>
                                        <form action="<?= base_url('receptionist/appointments/update-status/' . $appointment['id']) ?>" method="post" class="inline update-status-form">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">Cancel</button>
                                        </form>
                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                        <form action="<?= base_url('receptionist/appointments/update-status/' . $appointment['id']) ?>" method="post" class="inline update-status-form">
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
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 shadow-xl text-center max-w-2xl w-full mx-4 animate-in scale-95 duration-300">
        <div class="text-5xl mb-4 animate-bounce">✅</div>
        <h2 class="text-green-600 text-2xl font-bold mb-2">Appointment Created!</h2>
        <p class="text-gray-600 mb-8">The appointment has been successfully scheduled</p>

        <div id="success-appointment-details" class="bg-gray-50 rounded-lg p-8 mb-8 text-left">
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Appointment ID:</span>
                <span class="text-gray-600" id="success-appointment-id">#00000</span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Customer Name:</span>
                <span class="text-gray-600" id="success-customer-name">-</span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Phone Number:</span>
                <span class="text-gray-600" id="success-customer-phone">-</span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300" id="success-email-row" style="display: none;">
                <span class="font-semibold text-gray-900">Email:</span>
                <span class="text-gray-600" id="success-customer-email">-</span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Service:</span>
                <span class="text-gray-600" id="success-service-type">-</span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Date:</span>
                <span class="text-gray-600" id="success-appointment-date">-</span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Time:</span>
                <span class="text-gray-600" id="success-appointment-time">-</span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300">
                <span class="font-semibold text-gray-900">Status:</span>
                <span class="text-gray-600">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800" id="success-status">
                        Confirmed
                    </span>
                </span>
            </div>
            <div class="flex justify-between py-3 border-b border-gray-300" id="success-notes-row" style="display: none;">
                <span class="font-semibold text-gray-900">Notes:</span>
                <span class="text-gray-600" id="success-notes">-</span>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button onclick="closeSuccessModal()" class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-4 px-6 rounded-lg font-semibold hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300">
                Close
            </button>
            <button onclick="createAnotherAppointment()" class="flex-1 bg-green-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300">
                Create Another
            </button>
        </div>
    </div>
</div>

<script>
// Modal functionality
function openCreateModal() {
    document.getElementById('create-appointment-modal').classList.remove('hidden');
    // Reset form
    document.getElementById('appointment-form').reset();
    // Clear validation errors
    document.getElementById('name-error').classList.add('hidden');
    document.getElementById('phone-error').classList.add('hidden');
    document.getElementById('notes-error').classList.add('hidden');
    // Reset border styles
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-200', 'focus:border-indigo-500');
    });
}

function closeCreateModal() {
    document.getElementById('create-appointment-modal').classList.add('hidden');
    // Reset form
    document.getElementById('appointment-form').reset();
    // Clear validation errors
    document.getElementById('name-error').classList.add('hidden');
    document.getElementById('phone-error').classList.add('hidden');
    document.getElementById('notes-error').classList.add('hidden');
    // Reset border styles
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-200', 'focus:border-indigo-500');
    });
}

// Form validation functions (same as in create.php)
function validateCustomerName() {
    const nameInput = document.getElementById('customer_name');
    const nameError = document.getElementById('name-error');
    const name = nameInput.value.trim();

    const nameRegex = /^[a-zA-Z\s\-']{2,}$/;
    
    if (name.length > 0) {
        if (!nameRegex.test(name)) {
            nameInput.classList.add('border-red-500');
            nameInput.classList.remove('border-gray-200', 'focus:border-indigo-500');
            nameError.classList.remove('hidden');
            nameError.textContent = 'Customer name can only contain letters, spaces, hyphens, and apostrophes (minimum 2 characters)';
            return false;
        } else {
            nameInput.classList.remove('border-red-500');
            nameInput.classList.add('border-gray-200', 'focus:border-indigo-500');
            nameError.classList.add('hidden');
            return true;
        }
    } else {
        nameInput.classList.remove('border-red-500');
        nameInput.classList.add('border-gray-200', 'focus:border-indigo-500');
        nameError.classList.add('hidden');
        return true;
    }
}

function validatePhoneNumber() {
    const phoneInput = document.getElementById('customer_phone');
    const phoneError = document.getElementById('phone-error');
    const phone = phoneInput.value.trim();

    const phoneRegex = /^(09\d{9}|(\+63|63)9\d{9})$/;
    
    if (phone.length > 0) {
        if (!phoneRegex.test(phone)) {
            phoneInput.classList.add('border-red-500');
            phoneInput.classList.remove('border-gray-200', 'focus:border-indigo-500');
            phoneError.classList.remove('hidden');
            phoneError.textContent = 'Please enter a valid Philippines phone number (11 digits, e.g., 09123456789 or +639123456789)';
            return false;
        } else {
            phoneInput.classList.remove('border-red-500');
            phoneInput.classList.add('border-gray-200', 'focus:border-indigo-500');
            phoneError.classList.add('hidden');
            return true;
        }
    } else {
        phoneInput.classList.remove('border-red-500');
        phoneInput.classList.add('border-gray-200', 'focus:border-indigo-500');
        phoneError.classList.add('hidden');
        return true;
    }
}

function validateNotes() {
    const notesInput = document.getElementById('notes');
    const notesError = document.getElementById('notes-error');
    const notes = notesInput.value.trim();

    const notesRegex = /^[a-zA-Z0-9\s.,!?()-]*$/;
    
    if (notes.length > 0) {
        if (!notesRegex.test(notes)) {
            notesInput.classList.add('border-red-500');
            notesInput.classList.remove('border-gray-200', 'focus:border-indigo-500');
            notesError.classList.remove('hidden');
            notesError.textContent = 'Additional notes can only contain letters, numbers, spaces, and basic punctuation (.,!?()-)';
            return false;
        } else {
            notesInput.classList.remove('border-red-500');
            notesInput.classList.add('border-gray-200', 'focus:border-indigo-500');
            notesError.classList.add('hidden');
            return true;
        }
    } else {
        notesInput.classList.remove('border-red-500');
        notesInput.classList.add('border-gray-200', 'focus:border-indigo-500');
        notesError.classList.add('hidden');
        return true;
    }
}

function validateForm() {
    const isNameValid = validateCustomerName();
    const isPhoneValid = validatePhoneNumber();
    const isNotesValid = validateNotes();
    
    return isNameValid && isPhoneValid && isNotesValid;
}

// Auto-populate price when service is selected
function updatePrice() {
    const serviceSelect = document.getElementById('service_type');
    const priceInput = document.getElementById('price');
    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
    
    if (selectedOption.value && selectedOption.dataset.price) {
        priceInput.value = selectedOption.dataset.price;
    }
}

function closeSuccessModal() {
    document.getElementById('success-modal').classList.add('hidden');
    // Reset form
    document.getElementById('appointment-form').reset();
}

function createAnotherAppointment() {
    document.getElementById('success-modal').classList.add('hidden');
    openCreateModal();
}

// Success modal display function
function showSuccessModal(appointmentData) {
    console.log('showSuccessModal called with:', appointmentData);
    
    // Close the create appointment modal
    closeCreateModal();
    
    // Populate the success modal with appointment data
    document.getElementById('success-appointment-id').textContent = '#' + appointmentData.id.toString().padStart(5, '0');
    document.getElementById('success-customer-name').textContent = appointmentData.customer_name;
    document.getElementById('success-customer-phone').textContent = appointmentData.customer_phone;
    
    if (appointmentData.customer_email) {
        document.getElementById('success-customer-email').textContent = appointmentData.customer_email;
        document.getElementById('success-email-row').style.display = 'flex';
    } else {
        document.getElementById('success-email-row').style.display = 'none';
    }
    
    document.getElementById('success-service-type').textContent = appointmentData.service_type;
    document.getElementById('success-appointment-date').textContent = new Date(appointmentData.appointment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    document.getElementById('success-appointment-time').textContent = appointmentData.appointment_time;
    document.getElementById('success-status').textContent = appointmentData.status.charAt(0).toUpperCase() + appointmentData.status.slice(1);
    
    if (appointmentData.notes) {
        document.getElementById('success-notes').textContent = appointmentData.notes;
        document.getElementById('success-notes-row').style.display = 'flex';
    } else {
        document.getElementById('success-notes-row').style.display = 'none';
    }
    
    // Show the success modal with a small delay to ensure DOM is ready
    setTimeout(() => {
        const successModal = document.getElementById('success-modal');
        successModal.classList.remove('hidden');
        console.log('Success modal should be visible now');
    }, 100);
}

// Refresh dashboard function
function refreshDashboard() {
    window.location.href = '<?= base_url('receptionist') ?>';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Check if modal should be opened on page load (only if coming from navigation, not refresh)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_modal') === 'true') {
        openCreateModal();
        // Remove the show_modal parameter from URL without reloading the page
        const newUrl = window.location.pathname + (window.location.search ? window.location.search.replace(/[?&]show_modal=true/, '').replace(/^\?&/, '?').replace(/\?$/, '') : '');
        history.replaceState({}, document.title, newUrl);
    }
    
    const nameInput = document.getElementById('customer_name');
    const phoneInput = document.getElementById('customer_phone');
    const notesInput = document.getElementById('notes');
    const serviceSelect = document.getElementById('service_type');
    
    if (nameInput) {
        nameInput.addEventListener('input', validateCustomerName);
        nameInput.addEventListener('blur', validateCustomerName);
    }
    
    if (phoneInput) {
        phoneInput.addEventListener('input', validatePhoneNumber);
        phoneInput.addEventListener('blur', validatePhoneNumber);
    }
    
    if (notesInput) {
        notesInput.addEventListener('input', validateNotes);
        notesInput.addEventListener('blur', validateNotes);
    }
    
    if (serviceSelect) {
        serviceSelect.addEventListener('change', updatePrice);
    }
    
    const form = document.getElementById('appointment-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal with appointment data
                    showSuccessModal(data.appointment);
                    
                    // Refresh the dashboard to show updated appointment list
                    if (data.redirect_url) {
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 2000); // Wait 2 seconds before refreshing
                    }
                } else {
                    // Show error message
                    showFlashMessage(data.message || 'Failed to create appointment.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessage('An error occurred while creating the appointment.', 'error');
            });
        });
    }

    // Handle status update forms with AJAX
    document.querySelectorAll('.update-status-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const status = formData.get('status');
            const appointmentId = this.action.split('/').pop();
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status in the table
                    const row = this.closest('tr');
                    const statusSpan = row.querySelector('span.inline-block');
                    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
                    
                    // Update status text and color
                    statusSpan.textContent = statusText;
                    
                    const statusClasses = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'confirmed': 'bg-green-100 text-green-800',
                        'completed': 'bg-blue-100 text-blue-800',
                        'cancelled': 'bg-red-100 text-red-800'
                    };
                    
                    // Remove all status classes and add the new one
                    statusSpan.className = 'inline-block px-3 py-1 rounded-full text-sm font-semibold ' + statusClasses[status];
                    
                    // Remove action buttons for completed/cancelled appointments
                    if (status === 'completed' || status === 'cancelled') {
                        const actionsCell = row.querySelector('td:last-child');
                        actionsCell.innerHTML = '<span class="text-gray-500 text-sm">No actions available</span>';
                    }
                    
                    // Show success message
                    showFlashMessage('Appointment status updated successfully!', 'success');
                } else {
                    showFlashMessage('Failed to update appointment status.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessage('An error occurred while updating the status.', 'error');
            });
        });
    });
});

// Flash message function
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
