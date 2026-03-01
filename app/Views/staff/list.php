<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 rounded-xl p-8 mb-8 shadow-xl">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-white mb-2">Staff Management</h1>
            <p class="text-purple-200">Manage your salon staff members</p>
        </div>
        <a href="<?= base_url('staff/create') ?>" class="bg-white text-purple-900 px-6 py-3 rounded-lg font-semibold hover:bg-purple-100 transition-colors">
            + Add New Staff
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl p-8 shadow-xl">
    <?php if (empty($staff)): ?>
        <div class="text-center py-12 text-gray-600">
            <div class="text-6xl mb-4">👥</div>
            <p class="text-xl">No staff members found</p>
            <a href="<?= base_url('staff/create') ?>" class="text-purple-600 hover:text-purple-800 mt-2 inline-block">
                Add your first staff member
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-purple-50">
                        <th class="p-4 text-left text-purple-900 font-semibold border-b border-purple-200">Name</th>
                        <th class="p-4 text-left text-purple-900 font-semibold border-b border-purple-200">Email</th>
                        <th class="p-4 text-left text-purple-900 font-semibold border-b border-purple-200">Phone</th>
                        <th class="p-4 text-left text-purple-900 font-semibold border-b border-purple-200">Role</th>
                        <th class="p-4 text-left text-purple-900 font-semibold border-b border-purple-200">Specialization</th>
                        <th class="p-4 text-left text-purple-900 font-semibold border-b border-purple-200">Status</th>
                        <th class="p-4 text-left text-purple-900 font-semibold border-b border-purple-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="p-4 border-b border-gray-200">
                                <div class="font-medium text-gray-900"><?= esc($member['name']) ?></div>
                            </td>
                            <td class="p-4 border-b border-gray-200 text-gray-600">
                                <?= esc($member['email']) ?>
                            </td>
                            <td class="p-4 border-b border-gray-200 text-gray-600">
                                <?= esc($member['phone']) ?>
                            </td>
                            <td class="p-4 border-b border-gray-200">
                                <?php
                                $roleClass = match($member['role']) {
                                    'Barber' => 'bg-blue-100 text-blue-800',
                                    'Stylist' => 'bg-pink-100 text-pink-800',
                                    'Admin' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?= $roleClass ?>">
                                    <?= esc($member['role']) ?>
                                </span>
                            </td>
                            <td class="p-4 border-b border-gray-200 text-gray-600">
                                <?= esc($member['specialization'] ?? 'N/A') ?>
                            </td>
                            <td class="p-4 border-b border-gray-200">
                                <?php
                                $statusClass = $member['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?= $statusClass ?>">
                                    <?= esc($member['status']) ?>
                                </span>
                            </td>
                            <td class="p-4 border-b border-gray-200">
                                <div class="flex gap-2">
                                    <a href="<?= base_url('staff/edit/' . $member['id']) ?>" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Edit
                                    </a>
                                    <form action="<?= base_url('staff/delete/' . $member['id']) ?>" method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                            Delete
                                        </button>
                                    </form>
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
