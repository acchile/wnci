<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="mb-6">
        <a href="<?= base_url('countries') ?>" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Countries
        </a>
    </div>
    
    <div class="card p-8 max-w-3xl">
        <h2 class="text-2xl font-bold mb-6">Edit Country: <?= esc($country->name) ?></h2>
        
        <?= form_open('countries/edit/' . $country->id) ?>
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Continent -->
                <div class="md:col-span-2">
                    <label class="block text-gray-300 mb-2 font-medium">
                        Continent <span class="text-red-400">*</span>
                    </label>
                    <select name="continent_id" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <?php foreach ($continents as $continent): ?>
                        <option value="<?= $continent->id ?>" <?= $continent->id == $country->continent_id ? 'selected' : '' ?>>
                            <?= esc($continent->name) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->getError('continent_id')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('continent_id') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Name -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Country Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="<?= old('name', $country->name) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    <?php if (isset($validation) && $validation->getError('name')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('name') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Code (ISO3) -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        ISO Code (3 letters) <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           value="<?= old('code', $country->code) ?>" 
                           maxlength="3"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase" 
                           required>
                    <?php if (isset($validation) && $validation->getError('code')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('code') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- ISO2 -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        ISO2 Code (2 letters) <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="iso2" 
                           value="<?= old('iso2', $country->iso2) ?>" 
                           maxlength="2"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase" 
                           required>
                    <?php if (isset($validation) && $validation->getError('iso2')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('iso2') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Slug -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        Slug <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="slug" 
                           value="<?= old('slug', $country->slug) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                    <?php if (isset($validation) && $validation->getError('slug')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('slug') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Phone Code -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Phone Code</label>
                    <input type="text" 
                           name="phone_code" 
                           value="<?= old('phone_code', $country->phone_code) ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Currency Code -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Currency Code</label>
                    <input type="text" 
                           name="currency_code" 
                           value="<?= old('currency_code', $country->currency_code) ?>" 
                           maxlength="3"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Status</label>
                    <select name="status" 
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" <?= $country->status == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $country->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-slate-800 rounded-lg">
                <p class="text-sm text-gray-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Created:</strong> <?= date('M d, Y', strtotime($country->created_at)) ?>
                    <span class="mx-2">|</span>
                    <strong>States:</strong> <?= $country->state_count ?? 0 ?>
                </p>
            </div>
            
            <div class="flex gap-4 mt-8">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Country
                </button>
                <a href="<?= base_url('countries') ?>" class="btn bg-slate-700 hover:bg-slate-600 text-white">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?= view('templates/footer') ?>