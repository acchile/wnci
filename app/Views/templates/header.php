<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page_title ?? 'Admin Dashboard') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--bg-secondary) 0%, #1a2332 100%);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.4);
        }
        
        .sidebar-link {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.1);
            border-left-color: var(--accent);
            transform: translateX(4px);
        }
        
        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--bg-tertiary);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }
        
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .table {
            width: 100%;
            background: var(--bg-secondary);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead {
            background: var(--bg-tertiary);
        }
        
        .table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        .table td {
            padding: 1rem;
            border-top: 1px solid var(--bg-tertiary);
        }
        
        .table tbody tr {
            transition: background-color 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }
        
        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--success);
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--danger);
        }
        
        .topbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--bg-tertiary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--bg-tertiary);
            border-radius: 8px;
            margin-top: 0.5rem;
            min-width: 200px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            z-index: 1000;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-item {
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            display: block;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Submenu styles */
        .sidebar nav button i {
            transition: transform 0.3s ease;
        }

        .sidebar nav button i.rotate-180 {
            transform: rotate(180deg);
        }

        /* Smooth submenu transition */
        #usersMenu {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        #usersMenu.hidden {
            max-height: 0;
            opacity: 0;
        }

#usersMenu:not(.hidden) {
    max-height: 200px;
    opacity: 1;
}
    </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar sticky top-0 z-50">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="lg:hidden text-2xl">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-xl font-bold"><?= esc($page_title ?? 'Dashboard') ?></h1>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Language Selector -->
            <select id="languageSelector" class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white">
                <option value="en" <?= session()->get('language') == 'en' ? 'selected' : '' ?>>English</option>
                <option value="es" <?= session()->get('language') == 'es' ? 'selected' : '' ?>>Español</option>
            </select>
            
            <!-- User Dropdown -->
            <div class="dropdown">
    <button class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-700/50 transition">
        <?php if (!empty($current_user->profile_image)): ?>
            <img src="<?= base_url('uploads/profiles/' . $current_user->profile_image) ?>" 
                 alt="Profile" 
                 class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
        <?php else: ?>
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold">
                <?= strtoupper(substr($current_user->first_name ?? 'U', 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div class="text-left hidden md:block">
            <div class="font-medium"><?= esc($current_user->first_name ?? '') ?> <?= esc($current_user->last_name ?? '') ?></div>
            <div class="text-sm text-gray-400"><?= esc($current_user->role_name ?? '') ?></div>
        </div>
        <i class="fas fa-chevron-down text-sm"></i>
    </button>
                
                <div class="dropdown-content">
                    <a href="<?= base_url('profile') ?>" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                    <a href="<?= base_url('profile') ?>" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <hr class="border-gray-700 my-2">
                    <a href="<?= base_url('logout') ?>" class="dropdown-item text-red-400">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex">