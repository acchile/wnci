<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Roles Management</h2>
        <?php if (in_array('roles.create', $permissions ?? [])): ?>
        <a href="<?= base_url('roles/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Create Role
        </a>
        <?php endif; ?>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($roles)): ?>
            <div class="col-span-full text-center text-gray-400 py-12">
                <i class="fas fa-user-shield text-6xl mb-4"></i>
                <p>No roles found</p>
            </div>
        <?php else: ?>
            <?php foreach ($roles as $role): ?>
            <div class="card p-6 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-shield text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold capitalize"><?= esc($role->name) ?></h3>
                                <p class="text-sm text-gray-400"><?= $role->permission_count ?> permissions</p>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm mt-3 min-h-[40px]">
                            <?= esc($role->description ?: 'No description') ?>
                        </p>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-700">
                    <div class="flex gap-2">
                        <?php if (in_array('roles.edit', $permissions ?? [])): ?>
                        <a href="<?= base_url('roles/edit/' . $role->id) ?>" 
                           class="flex-1 btn btn-primary text-sm py-2">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <?php endif; ?>
                        
                        <?php if (in_array('roles.delete', $permissions ?? [])): ?>
                        <a href="<?= base_url('roles/delete/' . $role->id) ?>" 
                           onclick="return confirm('Are you sure you want to delete this role?\n\nNote: You cannot delete roles with assigned users.')" 
                           class="btn bg-red-600 hover:bg-red-700 text-white text-sm py-2 px-4">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mt-3 text-xs text-gray-500">
                    Created: <?= date('M d, Y', strtotime($role->created_at)) ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= view('templates/footer') ?>