<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<script>
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

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('customer_name');
    const phoneInput = document.getElementById('customer_phone');
    const notesInput = document.getElementById('notes');
    
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
    
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }
});
</script>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl p-10 shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-purple-900 text-2xl font-bold mb-2">📅 Create New Appointment</h2>
            <p class="text-gray-600">Fill in the customer details and schedule information</p>
        </div>

        <form action="<?= base_url('appointments/store') ?>" method="post">
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
                    <option value="Haircut" <?= old('service_type') == 'Haircut' ? 'selected' : '' ?>>Haircut</option>
                    <option value="Hair Coloring" <?= old('service_type') == 'Hair Coloring' ? 'selected' : '' ?>>Hair Coloring</option>
                    <option value="Hair Styling" <?= old('service_type') == 'Hair Styling' ? 'selected' : '' ?>>Hair Styling</option>
                    <option value="Hair Treatment" <?= old('service_type') == 'Hair Treatment' ? 'selected' : '' ?>>Hair Treatment</option>
                    <option value="Manicure" <?= old('service_type') == 'Manicure' ? 'selected' : '' ?>>Manicure</option>
                    <option value="Pedicure" <?= old('service_type') == 'Pedicure' ? 'selected' : '' ?>>Pedicure</option>
                    <option value="Facial" <?= old('service_type') == 'Facial' ? 'selected' : '' ?>>Facial</option>
                    <option value="Makeup" <?= old('service_type') == 'Makeup' ? 'selected' : '' ?>>Makeup</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                        onclick="window.location.href='<?= base_url('dashboard') ?>'">
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

<?= $this->endSection() ?>
