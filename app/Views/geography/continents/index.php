<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Continents Management</h2>
        <a href="<?= base_url('continents/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Continent
        </a>
    </div>
    
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Slug</th>
                        <th>Countries</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($continents)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-8">No continents found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($continents as $continent): ?>
                        <tr>
                            <td><?= esc($continent->id) ?></td>
                            <td class="font-medium"><?= esc($continent->name) ?></td>
                            <td><span class="badge badge-success"><?= esc($continent->code) ?></span></td>
                            <td class="text-gray-400"><?= esc($continent->slug) ?></td>
                            <td><?= esc($continent->country_count ?? 0) ?> countries</td>
                            <td>
                                <?php if ($continent->status == 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="<?= base_url('continents/edit/' . $continent->id) ?>" 
                                       class="text-blue-400 hover:text-blue-300 text-lg"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('continents/delete/' . $continent->id) ?>" 
                                       onclick="return confirm('Are you sure you want to delete this continent?')" 
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