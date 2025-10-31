<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="mb-6">
        <a href="<?= base_url('continents') ?>" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Continents
        </a>
    </div>
    
    <div class="card p-8 max-w-2xl">
        <h2 class="text-2xl font-bold mb-6">Add New Continent</h2>
        
        <?= form_open('continents/create') ?>
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Continent Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="<?= old('name') ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., Europe"
                           required>
                    <?php if (isset($validation) && $validation->getError('name')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('name') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Code -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Code (2 letters) <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           value="<?= old('code') ?>" 
                           maxlength="2"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase" 
                           placeholder="e.g., EU"
                           required>
                    <?php if (isset($validation) && $validation->getError('code')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('code') ?></p>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm mt-1">Two-letter code (e.g., AF, AS, EU, NA, OC, SA)</p>
                    <?php endif; ?>
                </div>
                
                <!-- Slug -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Slug <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="slug" 
                           value="<?= old('slug') ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., europe"
                           required>
                    <?php if (isset($validation) && $validation->getError('slug')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('slug') ?></p>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm mt-1">URL-friendly version (lowercase, hyphens allowed)</p>
                    <?php endif; ?>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Status</label>
                    <select name="status" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" <?= old('status', 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="flex gap-4 mt-8">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Create Continent
                </button>
                <a href="<?= base_url('continents') ?>" class="btn bg-slate-700 hover:bg-slate-600 text-white">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?= view('templates/footer') ?>