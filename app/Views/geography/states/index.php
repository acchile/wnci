<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">States/Provinces Management</h2>
        <a href="<?= base_url('states/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add State/Province
        </a>
    </div>
    
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Continent</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($states)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-8">No states/provinces found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($states as $state): ?>
                        <tr>
                            <td><?= esc($state->id) ?></td>
                            <td class="font-medium"><?= esc($state->name) ?></td>
                            <td class="text-gray-400"><?= esc($state->country_name) ?></td>
                            <td class="text-gray-400"><?= esc($state->continent_name) ?></td>
                            <td><?= $state->code ? '<span class="badge badge-success">' . esc($state->code) . '</span>' : '<span class="text-gray-500">-</span>' ?></td>
                            <td>
                                <?php if ($state->status == 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="<?= base_url('states/edit/' . $state->id) ?>" 
                                       class="text-blue-400 hover:text-blue-300 text-lg"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('states/delete/' . $state->id) ?>" 
                                       onclick="return confirm('Are you sure you want to delete this state/province?')" 
                                       class="text-red-400 hover:text-red-300 text-lg"
                                       title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
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