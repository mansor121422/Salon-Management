<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 rounded-xl p-8 mb-8 shadow-xl">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-white mb-2">Edit Staff</h1>
            <p class="text-purple-200">Update staff member information</p>
        </div>
        <a href="<?= base_url('staff') ?>" class="bg-white text-purple-900 px-6 py-3 rounded-lg font-semibold hover:bg-purple-100 transition-colors">
            ← Back to Staff List
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl p-8 shadow-xl">
    <form action="<?= base_url('staff/update/' . $staff['id']) ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="name" class="block mb-2 text-gray-700 font-medium">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-300"
                       value="<?= old('name', $staff['name']) ?>" required>
            </div>

            <div>
                <label for="email" class="block mb-2 text-gray-700 font-medium">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-300"
                       value="<?= old('email', $staff['email']) ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="phone" class="block mb-2 text-gray-700 font-medium">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-300"
                       value="<?= old('phone', $staff['phone']) ?>" placeholder="e.g., 09123456789" required>
            </div>

            <div>
                <label for="role" class="block mb-2 text-gray-700 font-medium">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role" name="role" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-300"
                        required>
                    <option value="Barber" <?= old('role', $staff['role']) === 'Barber' ? 'selected' : '' ?>>Barber</option>
                    <option value="Stylist" <?= old('role', $staff['role']) === 'Stylist' ? 'selected' : '' ?>>Stylist</option>
                    <option value="Admin" <?= old('role', $staff['role']) === 'Admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="specialization" class="block mb-2 text-gray-700 font-medium">
                    Specialization
                </label>
                <input type="text" id="specialization" name="specialization" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-300"
                       value="<?= old('specialization', $staff['specialization'] ?? '') ?>" placeholder="e.g., Hair Coloring, Cuts">
            </div>

            <div>
                <label for="status" class="block mb-2 text-gray-700 font-medium">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-300"
                        required>
                    <option value="Active" <?= old('status', $staff['status']) === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= old('status', $staff['status']) === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="<?= base_url('staff') ?>" 
               class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold text-center hover:bg-gray-700 transition-colors duration-300">
                Cancel
            </a>
            <button type="submit" 
                    class="flex-1 bg-gradient-to-r from-purple-800 to-purple-900 text-white py-3 px-6 rounded-lg font-semibold hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300">
                Update Staff Member
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
