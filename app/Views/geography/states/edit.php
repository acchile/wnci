<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="mb-6">
        <a href="<?= base_url('states') ?>" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to States/Provinces
        </a>
    </div>
    
    <div class="card p-8 max-w-2xl">
        <h2 class="text-2xl font-bold mb-6">Edit State/Province: <?= esc($state->name) ?></h2>
        
        <?= form_open('states/edit/' . $state->id) ?>
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <!-- Country -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Country <span class="text-red-400">*</span>
                    </label>
                    <select name="country_id" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <?php foreach ($countries as $country): ?>
                        <option value="<?= $country->id ?>" <?= $country->id == $state->country_id ? 'selected' : '' ?>>
                            <?= esc($country->name) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->getError('country_id')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('country_id') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Name -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        State/Province Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="<?= old('name', $state->name) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    <?php if (isset($validation) && $validation->getError('name')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('name') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Code -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Code</label>
                    <input type="text" 
                           name="code" 
                           value="<?= old('code', $state->code) ?>" 
                           maxlength="10"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                </div>
                
                <!-- Slug -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Slug <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="slug" 
                           value="<?= old('slug', $state->slug) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    <?php if (isset($validation) && $validation->getError('slug')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('slug') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Status</label>
                    <select name="status" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" <?= $state->status == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $state->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-slate-800 rounded-lg">
                <p class="text-sm text-gray-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Country:</strong> <?= esc($state->country_name) ?>
                    <span class="mx-2">|</span>
                    <strong>Created:</strong> <?= date('M d, Y', strtotime($state->created_at)) ?>
                </p>
            </div>
            
            <div class="flex gap-4 mt-8">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Update State/Province
                </button>
                <a href="<?= base_url('states') ?>" class="btn bg-slate-700 hover:bg-slate-600 text-white">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?= view('templates/footer') ?>