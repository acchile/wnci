<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <h2 class="text-2xl font-bold mb-6">My Profile</h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="card p-6">
            <div class="text-center">
                <?php if (!empty($user->profile_image)): ?>
                    <!-- User uploaded image -->
                    <div class="relative inline-block">
                        <img src="<?= base_url('uploads/profiles/' . $user->profile_image) ?>" 
                             alt="Profile" 
                             class="w-32 h-32 rounded-full mx-auto mb-4 shadow-lg object-cover border-4 border-blue-500">
                        <a href="<?= base_url('profile/delete-image') ?>" 
                           onclick="return confirm('Are you sure you want to delete your profile image?')"
                           class="absolute bottom-4 right-0 w-8 h-8 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center text-white shadow-lg transition">
                            <i class="fas fa-times text-sm"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Default avatar with initials -->
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-4xl font-bold mx-auto mb-4 shadow-lg">
                        <?= strtoupper(substr($user->first_name ?? 'U', 0, 1)) . strtoupper(substr($user->last_name ?? 'U', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                
                <h3 class="text-2xl font-bold mb-1"><?= esc($user->first_name) ?> <?= esc($user->last_name) ?></h3>
                <p class="text-gray-400 mb-2">@<?= esc($user->username) ?></p>
                <span class="badge badge-success capitalize"><?= esc($user->role_name) ?></span>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-700 space-y-4">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs">Email</p>
                        <p class="text-white font-medium"><?= esc($user->email) ?></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <i class="fas fa-language text-purple-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs">Language</p>
                        <p class="text-white font-medium uppercase"><?= esc($user->language) ?></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-calendar text-green-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs">Member Since</p>
                        <p class="text-white font-medium"><?= date('M d, Y', strtotime($user->created_at)) ?></p>
                    </div>
                </div>
                
                <?php if ($user->last_login): ?>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs">Last Login</p>
                        <p class="text-white font-medium"><?= date('M d, Y H:i', strtotime($user->last_login)) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-700">
                <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-shield-alt text-blue-400 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-blue-400 mb-1">Account Status</h4>
                            <p class="text-sm text-gray-300">Your account is active and secure</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit Profile Form -->
        <div class="lg:col-span-2 card p-8">
            <h3 class="text-xl font-bold mb-6">Update Profile Information</h3>
            
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
            <?php endif; ?>
            
            <?= form_open_multipart('profile/update') ?>
                <?= csrf_field() ?>
                
                <div class="space-y-6">
                    <!-- Profile Image Upload -->
                    <div class="bg-slate-800 rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-image text-blue-400 mr-2"></i>
                            Profile Picture
                        </h4>
                        
                        <div class="flex items-start gap-6">
                            <!-- Preview -->
                            <div class="flex-shrink-0">
                                <?php if (!empty($user->profile_image)): ?>
                                    <img id="imagePreview" 
                                         src="<?= base_url('uploads/profiles/' . $user->profile_image) ?>" 
                                         alt="Preview" 
                                         class="w-24 h-24 rounded-lg object-cover border-2 border-gray-600">
                                <?php else: ?>
                                    <div id="imagePreview" class="w-24 h-24 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-2xl font-bold border-2 border-gray-600">
                                        <?= strtoupper(substr($user->first_name ?? 'U', 0, 1)) . strtoupper(substr($user->last_name ?? 'U', 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Upload Input -->
                            <div class="flex-1">
                                <label class="block text-gray-300 mb-2 font-medium">Upload New Image</label>
                                <input type="file" 
                                       name="profile_image" 
                                       id="profileImageInput"
                                       accept="image/*"
                                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       onchange="previewImage(this)">
                                <?php if (isset($validation) && $validation->getError('profile_image')): ?>
                                    <p class="text-red-400 text-sm mt-1"><?= $validation->getError('profile_image') ?></p>
                                <?php else: ?>
                                    <p class="text-gray-500 text-sm mt-1">JPG, PNG or GIF. Max size 2MB</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Information Section -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-user text-blue-400 mr-2"></i>
                            Personal Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            
                            <!-- Language -->
                            <div>
                                <label class="block text-gray-300 mb-2 font-medium">Preferred Language</label>
                                <select name="language" 
                                        class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="en" <?= $user->language == 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
                                    <option value="es" <?= $user->language == 'es' ? 'selected' : '' ?>>🇪🇸 Español</option>
                                    <option value="fr" <?= $user->language == 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Section -->
                    <div class="pt-6 border-t border-gray-700">
                        <h4 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-lock text-blue-400 mr-2"></i>
                            Change Password
                        </h4>
                        <div class="bg-slate-800 rounded-lg p-4 mb-4">
                            <p class="text-sm text-gray-400 flex items-start gap-2">
                                <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                                <span>Leave password fields blank if you don't want to change your password</span>
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- New Password -->
                            <div>
                                <label class="block text-gray-300 mb-2 font-medium">New Password</label>
                                <input type="password" 
                                       name="password" 
                                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       placeholder="Leave blank to keep current">
                                <?php if (isset($validation) && $validation->getError('password')): ?>
                                    <p class="text-red-400 text-sm mt-1"><?= $validation->getError('password') ?></p>
                                <?php else: ?>
                                    <p class="text-gray-500 text-sm mt-1">Minimum 6 characters</p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-gray-300 mb-2 font-medium">Confirm New Password</label>
                                <input type="password" 
                                       name="password_confirm" 
                                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       placeholder="Re-enter new password">
                                <?php if (isset($validation) && $validation->getError('password_confirm')): ?>
                                    <p class="text-red-400 text-sm mt-1"><?= $validation->getError('password_confirm') ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Info (Read-only) -->
                    <div class="pt-6 border-t border-gray-700">
                        <h4 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-id-card text-blue-400 mr-2"></i>
                            Account Information
                        </h4>
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
                            
                            <!-- Role (Read-only) -->
                            <div>
                                <label class="block text-gray-300 mb-2 font-medium">Role</label>
                                <input type="text" 
                                       value="<?= esc(ucfirst($user->role_name)) ?>" 
                                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-3 text-gray-400 cursor-not-allowed capitalize" 
                                       disabled>
                                <p class="text-gray-500 text-sm mt-1">Contact admin to change role</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4 mt-8">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <button type="reset" class="btn bg-slate-700 hover:bg-slate-600 text-white">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Replace preview with actual image
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-24 h-24 rounded-lg object-cover border-2 border-blue-500">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= view('templates/footer') ?>