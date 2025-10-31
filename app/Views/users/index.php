<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">User Management</h2>
        <?php if (in_array('users.create', $permissions ?? [])): ?>
        <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Create User
        </a>
        <?php endif; ?>
    </div>
    
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Language</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-8">No users found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= esc($user->id) ?></td>
                            <td class="font-medium"><?= esc($user->username) ?></td>
                            <td class="text-gray-400"><?= esc($user->email) ?></td>
                            <td><?= esc($user->first_name . ' ' . $user->last_name) ?></td>
                            <td><span class="badge badge-success"><?= esc($user->role_name) ?></span></td>
                            <td>
                                <?php if ($user->status == 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="uppercase"><?= esc($user->language) ?></td>
                            <td>
                                <div class="flex gap-2">
                                    <?php if (in_array('users.edit', $permissions ?? [])): ?>
                                    <a href="<?= base_url('users/edit/' . $user->id) ?>" 
                                       class="text-blue-400 hover:text-blue-300 text-lg"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array('users.delete', $permissions ?? []) && $user->id != $current_user->id): ?>
                                    <a href="<?= base_url('users/delete/' . $user->id) ?>" 
                                       onclick="return confirm('Are you sure you want to delete this user?')" 
                                       class="text-red-400 hover:text-red-300 text-lg"
                                       title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>