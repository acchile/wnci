</main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('-translate-x-full');
}

function toggleSubmenu(menuId) {
    const menu = document.getElementById(menuId);
    const icon = document.getElementById(menuId + 'Icon');
    
    menu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

function changeLanguage(lang) {
    fetch('<?= base_url('profile/change-language') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'language=' + lang
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Auto-expand submenus based on current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    
    // Users submenu
    if (currentPath.includes('/users') || currentPath.includes('/roles')) {
        const usersMenu = document.getElementById('usersMenu');
        const usersMenuIcon = document.getElementById('usersMenuIcon');
        if (usersMenu && usersMenu.classList.contains('hidden')) {
            usersMenu.classList.remove('hidden');
            if (usersMenuIcon) {
                usersMenuIcon.classList.add('rotate-180');
            }
        }
    }
    
    // Geography submenu
        // Geography submenu - auto-expand main menu
        if (currentPath.includes('/continents') || currentPath.includes('/countries') || 
            currentPath.includes('/states') || currentPath.includes('/cities') ||
            currentPath.includes('/group-types') || currentPath.includes('/country-groups') || 
            currentPath.includes('/country-group-members')) {
            const geographyMenu = document.getElementById('geographyMenu');
            const geographyMenuIcon = document.getElementById('geographyMenuIcon');
            if (geographyMenu && geographyMenu.classList.contains('hidden')) {
                geographyMenu.classList.remove('hidden');
                if (geographyMenuIcon) {
                    geographyMenuIcon.classList.add('rotate-180');
                }
            }
        }

        // Countries submenu - auto-expand nested menu
        if (currentPath.includes('/countries') || currentPath.includes('/group-types') || 
            currentPath.includes('/country-groups') || currentPath.includes('/country-group-members')) {
            const countriesSubmenu = document.getElementById('countriesSubmenu');
            const countriesSubmenuIcon = document.getElementById('countriesSubmenuIcon');
            if (countriesSubmenu && countriesSubmenu.classList.contains('hidden')) {
                countriesSubmenu.classList.remove('hidden');
                if (countriesSubmenuIcon) {
                    countriesSubmenuIcon.classList.add('rotate-180');
                }
            }
        }
});
</script>

</body>
</html>