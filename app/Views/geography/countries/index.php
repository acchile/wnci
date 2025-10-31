<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Countries Management</h2>
        <a href="<?= base_url('countries/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Country
        </a>
    </div>
    
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Continent</th>
                        <th>Code</th>
                        <th>Phone</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($countries)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-8">No countries found</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($countries as $country): ?>
                        <tr>
                            <td><?= esc($country->id) ?></td>
                            <td class="font-medium"><?= esc($country->name) ?></td>
                            <td class="text-gray-400"><?= esc($country->continent_name) ?></td>
                            <td><span class="badge badge-success"><?= esc($country->code) ?></span></td>
                            <td class="text-gray-400"><?= esc($country->phone_code) ?></td>
                            <td class="text-gray-400"><?= esc($country->currency_code) ?></td>
                            <td>
                                <?php if ($country->status == 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="<?= base_url('countries/edit/' . $country->id) ?>" 
                                       class="text-blue-400 hover:text-blue-300 text-lg"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('countries/delete/' . $country->id) ?>" 
                                       onclick="return confirm('Are you sure you want to delete this country?')" 
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