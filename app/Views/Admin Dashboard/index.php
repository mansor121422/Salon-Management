<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Dashboard Header -->
<div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 rounded-xl p-8 mb-8 shadow-xl">
    <h1 class="text-white mb-2">Welcome, <?= esc(session()->get('full_name')) ?>!</h1>
    <p class="text-purple-200">Manage your salon system efficiently</p>
</div>

<!-- Dashboard Overview Tab -->
<div id="dashboard-tab" class="tab-content active">
    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
            <div class="text-5xl mb-2">👥</div>
            <div class="text-3xl font-bold text-brand-dark"><?= $total_users ?? 0 ?></div>
            <div class="text-gray-600 text-sm">Total Users</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
            <div class="text-5xl mb-2">👤</div>
            <div class="text-3xl font-bold text-brand-dark"><?= count($user_activity ?? []) ?></div>
            <div class="text-gray-600 text-sm">Active Users</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-lg text-center hover:shadow-2xl transition-shadow">
            <div class="text-5xl mb-2">📊</div>
            <div class="text-3xl font-bold text-brand-dark"><?= count(array_filter($user_activity ?? [], function($u) { 
                $now = new DateTime();
                $lastLogin = $u['last_login'] ? new DateTime($u['last_login']) : null;
                if (!$lastLogin) return false;
                $interval = $now->diff($lastLogin);
                $minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
                return $minutes <= 60;
            })) ?></div>
            <div class="text-gray-600 text-sm">Recent Activity</div>
        </div>
    </div>
    
    <div class="text-center py-12 text-gray-600">
        <div class="text-6xl mb-4">📋</div>
        <h3 class="text-xl font-semibold mb-2">Dashboard Overview</h3>
        <p>Use the navigation above to manage users or monitor user activity</p>
    </div>
</div>

<!-- User Management Tab -->
<div id="users-tab" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark text-2xl font-bold">👥 User Management</h2>
            <button onclick="openCreateUserModal()" class="bg-gradient-to-r from-brand-purple to-brand-dark text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                + Add User
            </button>
        </div>

        <?php if (empty($users)): ?>
            <div class="text-center py-12 text-gray-600">
                <div class="text-6xl mb-4">👥</div>
                <h3 class="text-xl font-semibold mb-2">No users yet</h3>
                <p>Create your first user to get started</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">ID</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Username</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Full Name</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Role</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Created At</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 border-b border-gray-200">#<?= str_pad($user['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td class="p-4 border-b border-gray-200"><?= esc($user['username']) ?></td>
                            <td class="p-4 border-b border-gray-200"><?= esc($user['full_name']) ?></td>
                            <td class="p-4 border-b border-gray-200">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td class="p-4 border-b border-gray-200"><?= date('M d, Y H:i', strtotime($user['created_at'])) ?></td>
                            <td class="p-4 border-b border-gray-200">
                                <div class="flex gap-2">
                                    <button type="button" onclick="openEditUserModal(<?= $user['id'] ?>)" class="bg-gradient-to-r from-purple-800 to-purple-900 hover:from-purple-900 hover:to-indigo-950 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Edit
                                    </button>
                                    <button type="button" onclick="deleteUser(<?= $user['id'] ?>)" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Delete
                                    </button>
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

<!-- User Activity Tab -->
<div id="activity-tab" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-brand-dark text-2xl font-bold">👤 User Activity Monitor</h2>
            <div class="text-sm text-gray-600">
                Last updated: <?= date('M d, Y H:i') ?>
            </div>
        </div>

        <?php if (empty($user_activity ?? [])): ?>
            <div class="text-center py-12 text-gray-600">
                <div class="text-6xl mb-4">👤</div>
                <h3 class="text-xl font-semibold mb-2">No recent activity</h3>
                <p>User login activity will appear here</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Username</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Full Name</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Role</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Last Login</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Status</th>
                            <th class="p-4 text-left font-semibold text-gray-800 border-b-2 border-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_activity as $activity): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 border-b border-gray-200 font-medium"><?= esc($activity['username']) ?></td>
                            <td class="p-4 border-b border-gray-200"><?= esc($activity['full_name']) ?></td>
                            <td class="p-4 border-b border-gray-200">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                    <?= ucfirst($activity['role']) ?>
                                </span>
                            </td>
                            <td class="p-4 border-b border-gray-200">
                                <?php if ($activity['last_login']): ?>
                                    <?= date('M d, Y H:i', strtotime($activity['last_login'])) ?>
                                <?php else: ?>
                                    <span class="text-gray-500">Never logged in</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 border-b border-gray-200">
                                <?php 
                                $now = new DateTime();
                                $lastLogin = $activity['last_login'] ? new DateTime($activity['last_login']) : null;
                                $status = 'offline';
                                $statusClass = 'bg-gray-100 text-gray-800';
                                
                                if ($lastLogin) {
                                    $interval = $now->diff($lastLogin);
                                    $minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
                                    
                                    if ($minutes <= 5) {
                                        $status = 'online';
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($minutes <= 60) {
                                        $status = 'recent';
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    }
                                }
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?= $statusClass ?>">
                                    <?= ucfirst($status) ?>
                                </span>
                            </td>
                            <td class="p-4 border-b border-gray-200">
                                <div class="flex gap-2">
                                    <button type="button" onclick="viewUserDetails(<?= $activity['id'] ?>)" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        View Details
                                    </button>
                                    <?php if ($activity['role'] !== 'admin'): ?>
                                        <button type="button" onclick="resetUserPassword(<?= $activity['id'] ?>)" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                            Reset Password
                                        </button>
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

<!-- Create User Modal -->
<div id="create-user-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 shadow-xl text-center max-w-2xl w-full mx-4 animate-in scale-95 duration-300">
        <div class="text-center mb-6">
            <h2 class="text-purple-900 text-2xl font-bold mb-2">👥 Create New User</h2>
            <p class="text-gray-600">Add a new user to the system</p>
        </div>

        <form id="create-user-form" action="<?= base_url('admin/users/store') ?>" method="post" class="text-left">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="username" class="block mb-2 text-gray-700 font-medium">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="username" name="username" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                           value="<?= old('username') ?>" required oninput="validateUsername()">
                    <div id="username-error" class="mt-2 text-red-500 text-sm hidden"></div>
                </div>

                <div>
                    <label for="full_name" class="block mb-2 text-gray-700 font-medium">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                           value="<?= old('full_name') ?>" required oninput="validateFullName()">
                    <div id="fullname-error" class="mt-2 text-red-500 text-sm hidden"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="password" class="block mb-2 text-gray-700 font-medium">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                           required oninput="validatePassword()">
                    <div id="password-error" class="mt-2 text-red-500 text-sm hidden"></div>
                </div>

                <div>
                    <label for="role" class="block mb-2 text-gray-700 font-medium">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300 cursor-pointer"
                            required>
                        <option value="">-- Select Role --</option>
                        <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="receptionist" <?= old('role') == 'receptionist' ? 'selected' : '' ?>>Receptionist</option>
                        <option value="user" <?= old('role') == 'user' ? 'selected' : '' ?>>User</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="button" 
                        class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-700 transition-colors duration-300"
                        onclick="closeCreateUserModal()">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-800 to-purple-900 text-white py-3 px-6 rounded-lg font-semibold hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 shadow-xl text-center max-w-2xl w-full mx-4 animate-in scale-95 duration-300">
        <div class="text-center mb-6">
            <h2 class="text-purple-900 text-2xl font-bold mb-2">✏️ Edit User</h2>
            <p class="text-gray-600">Update user information</p>
        </div>

        <form id="edit-user-form" action="" method="post" class="text-left">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="edit_username" class="block mb-2 text-gray-700 font-medium">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_username" name="username" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                           required>
                </div>

                <div>
                    <label for="edit_full_name" class="block mb-2 text-gray-700 font-medium">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_full_name" name="full_name" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300"
                           required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="edit_password" class="block mb-2 text-gray-700 font-medium">
                        New Password (Optional)
                    </label>
                    <input type="password" id="edit_password" name="password" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300">
                </div>

                <div>
                    <label for="edit_role" class="block mb-2 text-gray-700 font-medium">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_role" name="role" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300 cursor-pointer"
                            required>
                        <option value="admin">Admin</option>
                        <option value="receptionist">Receptionist</option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>

            <div id="edit-user-error-message" class="mb-4 text-red-500 text-sm hidden"></div>

            <div class="flex gap-4">
                <button type="button" 
                        class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-700 transition-colors duration-300"
                        onclick="closeEditUserModal()">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-800 to-purple-900 text-white py-3 px-6 rounded-lg font-semibold hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 shadow-xl text-center max-w-2xl w-full mx-4 animate-in scale-95 duration-300">
        <div class="text-5xl mb-4 animate-bounce">✅</div>
        <h2 class="text-green-600 text-2xl font-bold mb-2">Success!</h2>
        <p class="text-gray-600 mb-8" id="success-message">Operation completed successfully</p>

        <div class="flex gap-4">
            <button onclick="closeSuccessModal()" class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-4 px-6 rounded-lg font-semibold hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Modal functionality
function openCreateUserModal() {
    document.getElementById('create-user-modal').classList.remove('hidden');
    document.getElementById('create-user-form').reset();
    document.getElementById('username-error').classList.add('hidden');
    document.getElementById('fullname-error').classList.add('hidden');
    document.getElementById('password-error').classList.add('hidden');
    document.querySelectorAll('input, select').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-200', 'focus:border-indigo-500');
    });
}

function closeCreateUserModal() {
    document.getElementById('create-user-modal').classList.add('hidden');
    document.getElementById('create-user-form').reset();
    document.getElementById('username-error').classList.add('hidden');
    document.getElementById('fullname-error').classList.add('hidden');
    document.getElementById('password-error').classList.add('hidden');
    document.querySelectorAll('input, select').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-200', 'focus:border-indigo-500');
    });
}

function openEditUserModal(userId) {
    fetch(`<?= base_url('admin/users/edit/') ?>${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit_username').value = data.user.username;
                document.getElementById('edit_full_name').value = data.user.full_name;
                document.getElementById('edit_role').value = data.user.role;
                
                const form = document.getElementById('edit-user-form');
                form.action = `<?= base_url('admin/users/update/') ?>${userId}`;
                
                document.getElementById('edit-user-modal').classList.remove('hidden');
                document.getElementById('edit-user-error-message').classList.add('hidden');
                document.getElementById('edit-user-error-message').textContent = '';
            } else {
                showFlashMessage(data.message || 'Failed to load user data.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('An error occurred while loading user data.', 'error');
        });
}

function closeEditUserModal() {
    document.getElementById('edit-user-modal').classList.add('hidden');
    document.getElementById('edit-user-form').reset();
    document.getElementById('edit-user-error-message').classList.add('hidden');
    document.getElementById('edit-user-error-message').textContent = '';
}

// Form validation functions
function validateUsername() {
    const usernameInput = document.getElementById('username');
    const usernameError = document.getElementById('username-error');
    const username = usernameInput.value.trim();

    const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    
    if (username.length > 0) {
        if (!usernameRegex.test(username)) {
            usernameInput.classList.add('border-red-500');
            usernameInput.classList.remove('border-gray-200', 'focus:border-indigo-500');
            usernameError.classList.remove('hidden');
            usernameError.textContent = 'Username must be 3-20 characters long and contain only letters, numbers, and underscores';
            return false;
        } else {
            usernameInput.classList.remove('border-red-500');
            usernameInput.classList.add('border-gray-200', 'focus:border-indigo-500');
            usernameError.classList.add('hidden');
            return true;
        }
    } else {
        usernameInput.classList.remove('border-red-500');
        usernameInput.classList.add('border-gray-200', 'focus:border-indigo-500');
        usernameError.classList.add('hidden');
        return true;
    }
}

function validateFullName() {
    const fullNameInput = document.getElementById('full_name');
    const fullNameError = document.getElementById('fullname-error');
    const fullName = fullNameInput.value.trim();

    const fullNameRegex = /^[a-zA-Z\s\-']{2,}$/;
    
    if (fullName.length > 0) {
        if (!fullNameRegex.test(fullName)) {
            fullNameInput.classList.add('border-red-500');
            fullNameInput.classList.remove('border-gray-200', 'focus:border-indigo-500');
            fullNameError.classList.remove('hidden');
            fullNameError.textContent = 'Full name can only contain letters, spaces, hyphens, and apostrophes (minimum 2 characters)';
            return false;
        } else {
            fullNameInput.classList.remove('border-red-500');
            fullNameInput.classList.add('border-gray-200', 'focus:border-indigo-500');
            fullNameError.classList.add('hidden');
            return true;
        }
    } else {
        fullNameInput.classList.remove('border-red-500');
        fullNameInput.classList.add('border-gray-200', 'focus:border-indigo-500');
        fullNameError.classList.add('hidden');
        return true;
    }
}

function validatePassword() {
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password-error');
    const password = passwordInput.value;

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    
    if (password.length > 0) {
        if (!passwordRegex.test(password)) {
            passwordInput.classList.add('border-red-500');
            passwordInput.classList.remove('border-gray-200', 'focus:border-indigo-500');
            passwordError.classList.remove('hidden');
            passwordError.textContent = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character';
            return false;
        } else {
            passwordInput.classList.remove('border-red-500');
            passwordInput.classList.add('border-gray-200', 'focus:border-indigo-500');
            passwordError.classList.add('hidden');
            return true;
        }
    } else {
        passwordInput.classList.remove('border-red-500');
        passwordInput.classList.add('border-gray-200', 'focus:border-indigo-500');
        passwordError.classList.add('hidden');
        return true;
    }
}

function validateUserForm() {
    const isUsernameValid = validateUsername();
    const isFullNameValid = validateFullName();
    const isPasswordValid = validatePassword();
    
    return isUsernameValid && isFullNameValid && isPasswordValid;
}

// Success modal display function
function showSuccessModal(message) {
    document.getElementById('success-message').textContent = message;
    closeCreateUserModal();
    closeEditUserModal();
    
    setTimeout(() => {
        const successModal = document.getElementById('success-modal');
        successModal.classList.remove('hidden');
    }, 100);
}

function closeSuccessModal() {
    document.getElementById('success-modal').classList.add('hidden');
    refreshPage();
}

// Refresh page function
function refreshPage() {
    window.location.href = '<?= base_url('admin') ?>';
}

// Delete user function
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch(`<?= base_url('admin/users/delete/') ?>${userId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFlashMessage('User deleted successfully!', 'success');
                refreshPage();
            } else {
                showFlashMessage(data.message || 'Failed to delete user.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('An error occurred while deleting the user.', 'error');
        });
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const usernameInput = document.getElementById('username');
    const fullNameInput = document.getElementById('full_name');
    const passwordInput = document.getElementById('password');
    
    if (usernameInput) {
        usernameInput.addEventListener('input', validateUsername);
        usernameInput.addEventListener('blur', validateUsername);
    }
    
    if (fullNameInput) {
        fullNameInput.addEventListener('input', validateFullName);
        fullNameInput.addEventListener('blur', validateFullName);
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', validatePassword);
        passwordInput.addEventListener('blur', validatePassword);
    }
    
    const createUserForm = document.getElementById('create-user-form');
    if (createUserForm) {
        createUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateUserForm()) {
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
                    showSuccessModal('User created successfully!');
                } else {
                    if (data.errors) {
                        let errorMessage = '';
                        for (const [field, error] of Object.entries(data.errors)) {
                            errorMessage += error + '\n';
                        }
                        showFlashMessage(errorMessage, 'error');
                    } else {
                        showFlashMessage(data.message || 'Failed to create user.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessage('An error occurred while creating the user.', 'error');
            });
        });
    }

    const editUserForm = document.getElementById('edit-user-form');
    if (editUserForm) {
        editUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const userId = this.action.split('/').pop();
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessModal('User updated successfully!');
                } else {
                    if (data.errors) {
                        let errorMessage = '';
                        for (const [field, error] of Object.entries(data.errors)) {
                            errorMessage += error + '\n';
                        }
                        document.getElementById('edit-user-error-message').textContent = errorMessage;
                        document.getElementById('edit-user-error-message').classList.remove('hidden');
                    } else {
                        showFlashMessage(data.message || 'Failed to update user.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessage('An error occurred while updating the user.', 'error');
            });
        });
    }
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
    }
}

// Initialize tabs based on URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    
    // Default to dashboard tab if no tab parameter
    if (tab === 'users') {
        showTab('users-tab');
    } else if (tab === 'activity') {
        showTab('activity-tab');
    } else {
        showTab('dashboard-tab');
    }
});
</script>

<?= $this->endSection() ?>