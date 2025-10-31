<?= view('templates/header', $data ?? []) ?>
<?= view('templates/sidebar', $data ?? []) ?>

<div class="fade-in">
    <div class="mb-6">
        <a href="<?= base_url('cities') ?>" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Cities
        </a>
    </div>
    
    <div class="card p-8 max-w-3xl">
        <h2 class="text-2xl font-bold mb-6">Add New City</h2>
        
        <?= form_open('cities/create') ?>
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Country -->
                <div class="md:col-span-2">
                    <label class="block text-gray-300 mb-2 font-medium">
                        Country <span class="text-red-400">*</span>
                    </label>
                    <select name="country_id" 
                            id="country_id"
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="loadStates(this.value)"
                            required>
                        <option value="">Select Country</option>
                        <?php foreach ($countries as $country): ?>
                        <option value="<?= $country->id ?>" <?= old('country_id') == $country->id ? 'selected' : '' ?>>
                            <?= esc($country->name) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->getError('country_id')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('country_id') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- State (Cascading) -->
                <div class="md:col-span-2">
                    <label class="block text-gray-300 mb-2 font-medium">State/Province (Optional)</label>
                    <select name="state_id" 
                            id="state_id"
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select State/Province (if applicable)</option>
                    </select>
                    <p class="text-gray-500 text-sm mt-1">Only for countries with states/provinces</p>
                </div>
                
                <!-- Name -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">
                        City Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="<?= old('name') ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., New York"
                           required>
                    <?php if (isset($validation) && $validation->getError('name')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('name') ?></p>
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
                           placeholder="e.g., new-york"
                           required>
                    <?php if (isset($validation) && $validation->getError('slug')): ?>
                        <p class="text-red-400 text-sm mt-1"><?= $validation->getError('slug') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Latitude -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Latitude</label>
                    <input type="text" 
                           name="latitude" 
                           value="<?= old('latitude') ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., 40.7128">
                    <p class="text-gray-500 text-sm mt-1">Decimal format</p>
                </div>
                
                <!-- Longitude -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Longitude</label>
                    <input type="text" 
                           name="longitude" 
                           value="<?= old('longitude') ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., -74.0060">
                    <p class="text-gray-500 text-sm mt-1">Decimal format</p>
                </div>
                
                <!-- Population -->
                <div>
                    <label class="block text-gray-300 mb-2 font-medium">Population</label>
                    <input type="number" 
                           name="population" 
                           value="<?= old('population') ?>" 
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., 8336817">
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
                    <i class="fas fa-save mr-2"></i>Create City
                </button>
                <a href="<?= base_url('cities') ?>" class="btn bg-slate-700 hover:bg-slate-600 text-white">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
function loadStates(countryId) {
    const stateSelect = document.getElementById('state_id');
    stateSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (!countryId) {
        stateSelect.innerHTML = '<option value="">Select State/Province (if applicable)</option>';
        return;
    }
    
    fetch('<?= base_url('ajax/states-by-country/') ?>' + countryId)
        .then(response => response.json())
        .then(states => {
            stateSelect.innerHTML = '<option value="">Select State/Province (if applicable)</option>';
            
            if (states.length > 0) {
                states.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    stateSelect.appendChild(option);
                });
            } else {
                stateSelect.innerHTML = '<option value="">No states/provinces for this country</option>';
            }
        })
        .catch(error => {
            console.error('Error loading states:', error);
            stateSelect.innerHTML = '<option value="">Error loading states</option>';
        });
}

// Load states if country is pre-selected (validation failed)
document.addEventListener('DOMContentLoaded', function() {
    const countryId = document.getElementById('country_id').value;
    if (countryId) {
        loadStates(countryId);
    }
});
</script>

<?= view('templates/footer') ?>