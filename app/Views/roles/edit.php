<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="mb-6">
        <a href="<?= base_url('roles') ?>" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Roles
        </a>
    </div>
    
    <div class="card p-8 max-w-3xl">
        <h2 class="text-2xl font-bold mb-6">Edit Role: <span class="capitalize"><?= esc($role->name) ?></span></h2>
        
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-6">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
        <?php endif; ?>
        
        <?= form_open('roles/edit/' . $role->id) ?>
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <!-- Role Name (Read-only) -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Role Name</label>
                    <input type="text" 
                           value="<?= esc($role->name) ?>" 
                           class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-3 text-gray-400 cursor-not-allowed capitalize" 
                           disabled>
                    <p class="text-gray-500 text-sm mt-1">Role name cannot be changed</p>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Description</label>
                    <textarea name="description" 
                              rows="3" 
                              class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Brief description of this role's purpose"><?= old('description', $role->description) ?></textarea>
                    <?php if (isset($validation) && $validation->getError('description')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('description') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Permissions -->
                <div>
                    <label class="block text-gray-300 mb-3 font-medium">
                        Permissions <span class="text-gray-500 text-sm">(Update permissions for this role)</span>
                    </label>
                    
                    <?php
                    // Get current role permission IDs
                    $rolePermissionIds = array_column($role_permissions, 'id');
                    
                    // Group permissions by category
                    $grouped = [];
                    foreach ($all_permissions as $perm) {
                        $parts = explode('.', $perm->name);
                        $category = $parts[0];
                        if (!isset($grouped[$category])) {
                            $grouped[$category] = [];
                        }
                        $grouped[$category][] = $perm;
                    }
                    ?>
                    
                    <div class="space-y-4">
                        <?php foreach ($grouped as $category => $perms): ?>
                        <div class="bg-slate-800 rounded-lg p-4">
                            <h4 class="text-lg font-semibold mb-3 capitalize flex items-center justify-between">
                                <span>
                                    <i class="fas fa-folder text-blue-400 mr-2"></i>
                                    <?= esc($category) ?> Management
                                </span>
                                <button type="button" 
                                        onclick="toggleCategory('<?= $category ?>')"
                                        class="text-xs text-blue-400 hover:text-blue-300">
                                    <i class="fas fa-check-square mr-1"></i>Toggle All
                                </button>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php foreach ($perms as $permission): ?>
                                <label class="flex items-center p-3 rounded-lg border border-gray-700 hover:border-blue-500 cursor-pointer transition-all hover:bg-slate-700/50 <?= in_array($permission->id, $rolePermissionIds) ? 'bg-blue-500/10 border-blue-500/50' : '' ?>">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="<?= $permission->id ?>" 
                                           <?= in_array($permission->id, $rolePermissionIds) ? 'checked' : '' ?>
                                           class="permission-checkbox category-<?= $category ?> w-4 h-4 text-blue-500 rounded focus:ring-blue-500 focus:ring-2 mr-3">
                                    <div class="flex-1">
                                        <span class="text-sm font-medium"><?= esc($permission->name) ?></span>
                                        <?php if ($permission->description): ?>
                                        <p class="text-xs text-gray-400 mt-1"><?= esc($permission->description) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (empty($all_permissions)): ?>
                    <p class="text-gray-400 text-sm">No permissions available</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-slate-800 rounded-lg">
                <p class="text-sm text-gray-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Created:</strong> <?= date('M d, Y', strtotime($role->created_at)) ?>
                    <?php if ($role->updated_at != $role->created_at): ?>
                    <span class="mx-2">|</span>
                    <strong>Last Updated:</strong> <?= date('M d, Y', strtotime($role->updated_at)) ?>
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="flex gap-4 mt-8">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Role
                </button>
                <a href="<?= base_url('roles') ?>" class="btn bg-slate-700 hover:bg-slate-600 text-white">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
function toggleCategory(category) {
    const checkboxes = document.querySelectorAll('.category-' + category);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}
</script>

<?= view('templates/footer') ?>