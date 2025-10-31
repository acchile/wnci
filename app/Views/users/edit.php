<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="mb-6">
        <a href="<?= base_url('users') ?>" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Users
        </a>
    </div>
    
    <div class="card p-8 max-w-3xl">
        <h2 class="text-2xl font-bold mb-6">Edit User: <?= esc($user->username) ?></h2>
        
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-6">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
        <?php endif; ?>
        
        <?= form_open('users/edit/' . $user->id) ?>
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username (Read-only) -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Username</label>
                    <input type="text" 
                           value="<?= esc($user->username) ?>" 
                           class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-3 text-gray-400 cursor-not-allowed" 
                           disabled>
                    <p class="text-gray-500 text-sm mt-1">Username cannot be changed</p>
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="<?= old('email', $user->email) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    <?php if (isset($validation) && $validation->getError('email')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('email') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- First Name -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        First Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="first_name" 
                           value="<?= old('first_name', $user->first_name) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    <?php if (isset($validation) && $validation->getError('first_name')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('first_name') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Last Name -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Last Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="last_name" 
                           value="<?= old('last_name', $user->last_name) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    <?php if (isset($validation) && $validation->getError('last_name')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('last_name') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Password (Optional) -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        New Password
                    </label>
                    <input type="password" 
                           name="password" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Leave blank to keep current">
                    <p class="text-gray-500 text-sm mt-1">Only fill if you want to change password</p>
                </div>
                
                <!-- Role -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Role <span class="text-red-400">*</span>
                    </label>
                    <select name="role_id" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required>
                        <?php foreach ($roles as $role): ?>
                        <option value="<?= $role->id ?>" <?= $role->id == $user->role_id ? 'selected' : '' ?>>
                            <?= esc(ucfirst($role->name)) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->getError('role_id')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('role_id') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Language -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Language</label>
                    <select name="language" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="en" <?= $user->language == 'en' ? 'selected' : '' ?>>English</option>
                        <option value="es" <?= $user->language == 'es' ? 'selected' : '' ?>>Español</option>
                        <option value="fr" <?= $user->language == 'fr' ? 'selected' : '' ?>>Français</option>
                    </select>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Status</label>
                    <select name="status" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" <?= $user->status == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $user->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-slate-800 rounded-lg">
                <p class="text-sm text-gray-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Last Login:</strong> <?= $user->last_login ? date('M d, Y H:i', strtotime($user->last_login)) : 'Never' ?>
                    <span class="mx-2">|</span>
                    <strong>Created:</strong> <?= date('M d, Y', strtotime($user->created_at)) ?>
                </p>
            </div>
            
            <div class="flex gap-4 mt-8">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Update User
                </button>
                <a href="<?= base_url('users') ?>" class="btn bg-slate-700 hover:bg-slate-600 text-white">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?= view('templates/footer') ?>