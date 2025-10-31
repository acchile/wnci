<aside id="sidebar" class="sidebar w-64 min-h-screen fixed lg:relative transition-transform lg:translate-x-0 -translate-x-full z-40">
    <div class="p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Admin Panel</h2>
                <p class="text-sm text-gray-400">v1.0</p>
            </div>
        </div>
        
        <nav>
            <!-- Dashboard -->
            <a href="<?= base_url('dashboard') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg mb-2 <?= url_is('dashboard*') ? 'active' : '' ?>">
                <i class="fas fa-home w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <!-- Users with Submenu -->
            <?php if (in_array('users.view', $permissions ?? [])): ?>
            <div class="mb-2">
                <!-- Users Parent Menu -->
                <button onclick="toggleSubmenu('usersMenu')" 
                        class="sidebar-link flex items-center justify-between w-full px-4 py-3 rounded-lg <?= url_is('users*') || url_is('roles*') ? 'active' : '' ?>">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users w-5"></i>
                        <span>Users</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform" id="usersMenuIcon"></i>
                </button>
                
                <!-- Submenu -->
                <div id="usersMenu" class="ml-4 mt-2 space-y-1 <?= url_is('users*') || url_is('roles*') ? '' : 'hidden' ?>">
                    <a href="<?= base_url('users') ?>" 
                       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-gray-300 hover:text-white hover:bg-slate-700/50 transition <?= url_is('users*') ? 'bg-slate-700/50 text-white' : '' ?>">
                        <i class="fas fa-user-friends w-4"></i>
                        <span>Manage Users</span>
                    </a>
                    
                    <?php if (in_array('roles.view', $permissions ?? [])): ?>
                    <a href="<?= base_url('roles') ?>" 
                       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-gray-300 hover:text-white hover:bg-slate-700/50 transition <?= url_is('roles*') ? 'bg-slate-700/50 text-white' : '' ?>">
                        <i class="fas fa-user-shield w-4"></i>
                        <span>Roles & Permissions</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Geography with Submenu -->
                <!-- Geography Section with Nested Countries Submenu -->

                <div class="mb-2">
                    <!-- Geography Parent Menu -->
                    <button onclick="toggleSubmenu('geographyMenu')" 
                            class="sidebar-link flex items-center justify-between w-full px-4 py-3 rounded-lg <?= url_is('continents*') || url_is('countries*') || url_is('states*') || url_is('cities*') || url_is('group-types*') || url_is('country-groups*') || url_is('country-group-members*') ? 'active' : '' ?>">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-globe w-5"></i>
                            <span>Geography</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform" id="geographyMenuIcon"></i>
                    </button>
                    
                    <!-- Geography Submenu -->
                    <div id="geographyMenu" class="ml-4 mt-2 space-y-1 <?= url_is('continents*') || url_is('countries*') || url_is('states*') || url_is('cities*') || url_is('group-types*') || url_is('country-groups*') || url_is('country-group-members*') ? '' : 'hidden' ?>">
                        
                        <!-- Continents -->
                        <a href="<?= base_url('continents') ?>" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-gray-300 hover:text-white hover:bg-slate-700/50 transition <?= url_is('continents*') ? 'bg-slate-700/50 text-white' : '' ?>">
                            <i class="fas fa-earth-americas w-4"></i>
                            <span>Continents</span>
                        </a>
                        
                        <!-- Countries with Nested Submenu -->
                        <div>
                            <button onclick="toggleSubmenu('countriesSubmenu')" 
                                    class="flex items-center justify-between w-full px-4 py-2 rounded-lg text-sm text-gray-300 hover:text-white hover:bg-slate-700/50 transition <?= url_is('countries*') || url_is('group-types*') || url_is('country-groups*') || url_is('country-group-members*') ? 'bg-slate-700/50 text-white' : '' ?>">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-flag w-4"></i>
                                    <span>Countries</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform text-xs" id="countriesSubmenuIcon"></i>
                            </button>
                            
                            <!-- Countries Nested Submenu -->
                            <div id="countriesSubmenu" class="ml-6 mt-1 space-y-1 <?= url_is('countries*') || url_is('group-types*') || url_is('country-groups*') || url_is('country-group-members*') ? '' : 'hidden' ?>">
                                
                                <!-- Manage Countries Link -->
                                <a href="<?= base_url('countries') ?>" 
                                   class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-slate-700/30 transition <?= url_is('countries*') && !url_is('country-groups*') && !url_is('country-group-members*') && !url_is('group-types*') ? 'bg-slate-700/30 text-white' : '' ?>">
                                    <i class="fas fa-list w-3"></i>
                                    <span>All Countries</span>
                                </a>
                                
                                <!-- Group Types -->
                                <a href="<?= base_url('group-types') ?>" 
                                   class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-slate-700/30 transition <?= url_is('group-types*') ? 'bg-slate-700/30 text-white' : '' ?>">
                                    <i class="fas fa-tags w-3"></i>
                                    <span>Group Types</span>
                                </a>
                                
                                <!-- Organizations -->
                                <a href="<?= base_url('country-groups') ?>" 
                                   class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-slate-700/30 transition <?= url_is('country-groups*') && !url_is('country-group-members*') ? 'bg-slate-700/30 text-white' : '' ?>">
                                    <i class="fas fa-users-cog w-3"></i>
                                    <span>Organizations</span>
                                </a>
                                
                                <!-- Memberships -->
                                <a href="<?= base_url('country-group-members') ?>" 
                                   class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-slate-700/30 transition <?= url_is('country-group-members*') ? 'bg-slate-700/30 text-white' : '' ?>">
                                    <i class="fas fa-user-friends w-3"></i>
                                    <span>Memberships</span>
                                </a>
                                
                            </div>
                        </div>
                        
                        <!-- States/Provinces -->
                        <a href="<?= base_url('states') ?>" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-gray-300 hover:text-white hover:bg-slate-700/50 transition <?= url_is('states*') ? 'bg-slate-700/50 text-white' : '' ?>">
                            <i class="fas fa-map w-4"></i>
                            <span>States/Provinces</span>
                        </a>
                        
                        <!-- Cities -->
                        <a href="<?= base_url('cities') ?>" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-gray-300 hover:text-white hover:bg-slate-700/50 transition <?= url_is('cities*') ? 'bg-slate-700/50 text-white' : '' ?>">
                            <i class="fas fa-city w-4"></i>
                            <span>Cities</span>
                        </a>
                        
                    </div>
                </div>




        </nav>
    </div>
</aside>

<!-- Main Content Area -->
<main class="flex-1 p-6 lg:p-8">
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success fade-in">
        <i class="fas fa-check-circle text-xl"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger fade-in">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
    <?php endif; ?>