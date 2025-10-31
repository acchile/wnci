<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Cities Management</h2>
        <a href="<?= base_url('cities/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add City
        </a>
    </div>
    
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>State/Province</th>
                        <th>Country</th>
                        <th>Population</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cities)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-8">No cities found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($cities as $city): ?>
                        <tr>
                            <td><?= esc($city->id) ?></td>
                            <td class="font-medium"><?= esc($city->name) ?></td>
                            <td class="text-gray-400"><?= $city->state_name ? esc($city->state_name) : '<span class="text-gray-500">-</span>' ?></td>
                            <td class="text-gray-400"><?= esc($city->country_name) ?></td>
                            <td class="text-gray-400"><?= $city->population ? number_format($city->population) : '-' ?></td>
                            <td>
                                <?php if ($city->status == 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="<?= base_url('cities/edit/' . $city->id) ?>" 
                                       class="text-blue-400 hover:text-blue-300 text-lg"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('cities/delete/' . $city->id) ?>" 
                                       onclick="return confirm('Are you sure you want to delete this city?')" 
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