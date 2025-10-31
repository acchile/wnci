<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold mb-2">Edit Group Type</h2>
            <p class="text-gray-400">Update group type information</p>
        </div>
        <a href="<?= base_url('group-types') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>
    
    <div class="card p-6">
        <form action="<?= base_url('group-types/edit/' . $groupType['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium mb-2">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control <?= session('errors.name') ? 'border-red-500' : '' ?>" 
                       value="<?= old('name', $groupType['name']) ?>" 
                       placeholder="e.g., Economic, Military, Geopolitical"
                       required>
                <?php if (session('errors.name')): ?>
                    <p class="text-red-500 text-sm mt-1"><?= session('errors.name') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          class="form-control <?= session('errors.description') ? 'border-red-500' : '' ?>" 
                          placeholder="Brief description of this group type"><?= old('description', $groupType['description']) ?></textarea>
                <?php if (session('errors.description')): ?>
                    <p class="text-red-500 text-sm mt-1"><?= session('errors.description') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Group Type
                </button>
                <a href="<?= base_url('group-types') ?>" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?= view('templates/footer') ?>