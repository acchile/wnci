<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Group Types</h2>
        <a href="<?= base_url('group-types/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Group Type
        </a>
    </div>
    
    <div class="card p-6">
        <?php if (empty($groupTypes)): ?>
            <div class="text-center py-12">
                <i class="fas fa-tags text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg mb-4">No group types found</p>
                <a href="<?= base_url('group-types/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Create First Group Type
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupTypes as $type): ?>
                            <tr>
                                <td><?= esc($type['id']) ?></td>
                                <td class="font-medium text-blue-400">
                                    <?= esc($type['name']) ?>
                                </td>
                                <td class="text-gray-400">
                                    <?= esc($type['description'] ?? '-') ?>
                                </td>
                                <td class="text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($type['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="<?= base_url('group-types/edit/' . $type['id']) ?>" 
                                           class="btn btn-warning btn-sm"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteGroupType(<?= $type['id'] ?>)" 
                                                class="btn btn-danger btn-sm"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
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

<?= view('templates/footer') ?>

<script>
function deleteGroupType(id) {
    if (confirm('Are you sure you want to delete this group type?')) {
        fetch('<?= base_url('group-types/delete/') ?>' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Error deleting group type');
            console.error('Error:', error);
        });
    }
}
</script>