<style>
    /* Teal to Orange Gradient Theme for Dashboard */
    :root {
        --gradient-start: #14b8a6; /* Teal */
        --gradient-mid: #f59e0b; /* Amber/Orange */
        --gradient-end: #f97316; /* Orange */
        --primary-button: #991b1b; /* Dark red/maroon */
        --primary-button-hover: #7f1d1d;
    }

    /* Dashboard background gradient */
    .fi-body {
        background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 25%, #fef3c7 50%, #fed7aa 75%, #fdba74 100%) !important;
        min-height: 100vh !important;
    }

    .dark .fi-body {
        background: linear-gradient(135deg, #042f2e 0%, #0f766e 25%, #78350f 50%, #9a3412 75%, #c2410c 100%) !important;
    }

    /* Primary buttons - dark red/maroon */
    .fi-btn-primary,
    button[type="submit"].fi-btn,
    .fi-btn[type="submit"] {
        background: var(--primary-button) !important;
        border-color: var(--primary-button) !important;
        color: white !important;
    }

    .fi-btn-primary:hover,
    button[type="submit"].fi-btn:hover,
    .fi-btn[type="submit"]:hover {
        background: var(--primary-button-hover) !important;
        border-color: var(--primary-button-hover) !important;
    }

    /* Links and accents */
    a.fi-link,
    .fi-link {
        color: var(--gradient-start) !important;
    }

    a.fi-link:hover,
    .fi-link:hover {
        color: var(--gradient-mid) !important;
    }

    /* Cards with subtle gradient */
    .fi-card,
    .fi-section {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px) !important;
    }

    .dark .fi-card,
    .dark .fi-section {
        background: rgba(15, 23, 42, 0.95) !important;
    }

    /* Navigation with gradient accent */
    .fi-sidebar-nav {
        background: rgba(255, 255, 255, 0.98) !important;
        border-right: 1px solid rgba(20, 184, 166, 0.2) !important;
    }

    .dark .fi-sidebar-nav {
        background: rgba(15, 23, 42, 0.98) !important;
        border-right-color: rgba(20, 184, 166, 0.3) !important;
    }

    /* Active navigation item */
    .fi-sidebar-item-active {
        background: linear-gradient(90deg, rgba(20, 184, 166, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%) !important;
        border-left: 3px solid var(--gradient-start) !important;
    }

    /* Top bar with gradient */
    .fi-topbar {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px) !important;
        border-bottom: 1px solid rgba(20, 184, 166, 0.2) !important;
    }

    .dark .fi-topbar {
        background: rgba(15, 23, 42, 0.95) !important;
        border-bottom-color: rgba(20, 184, 166, 0.3) !important;
    }

    /* Hide any logo in topbar */
    .fi-topbar-brand-logo,
    .fi-topbar [class*="logo"] img {
        display: none !important;
    }

    /* Hide Laravel brand name in sidebar */
    .fi-sidebar [class*="brand"],
    .fi-sidebar [class*="logo"] span,
    .fi-sidebar a[href*="dashboard"] span:not(.fi-icon),
    .fi-sidebar-header [class*="brand"],
    .fi-sidebar-header [class*="logo"],
    .fi-sidebar [class*="header"] span:contains("Laravel"),
    .fi-sidebar [class*="header"] a:contains("Laravel") {
        display: none !important;
    }

    /* Style sidebar logo container */
    .fi-sidebar-header,
    .fi-sidebar [class*="header"] {
        padding: 1rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Sidebar logo styling */
    .fi-sidebar-logo-custom {
        height: 180px !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
    }

    /* Login page logo styling */
    .fi-login-logo-custom {
        height: 200px !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
        margin: 0 auto !important;
    }

    /* Hide Laravel text on login page */
    .fi-simple-page [class*="heading"]:has-text("Laravel"),
    .fi-simple-page h1:has-text("Laravel"),
    .fi-simple-page h2:has-text("Laravel") {
        display: none !important;
    }
</style>

<script>
// Remove any logo from topbar
(function() {
    function removeTopbarLogo() {
        const topbar = document.querySelector('.fi-topbar');
        if (!topbar) return;

        // Remove any logo images
        const logoImages = topbar.querySelectorAll('.fi-topbar-brand-logo, img[alt*="Job Seeker"], img[alt*="Logo"]');
        logoImages.forEach(img => {
            img.remove();
        });

        // Restore default brand text if it was replaced
        const brandElements = topbar.querySelectorAll('[class*="brand"], a[href*="dashboard"]');
        brandElements.forEach(el => {
            if (el.querySelector('.fi-topbar-brand-logo')) {
                // Restore original content or default text
                if (el.textContent.trim() === '' || el.querySelector('img')) {
                    el.innerHTML = 'Laravel';
                }
            }
        });
    }

    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', removeTopbarLogo);
    } else {
        removeTopbarLogo();
    }

    // Also try after delays
    setTimeout(removeTopbarLogo, 300);
    setTimeout(removeTopbarLogo, 1000);

    // Watch for navigation changes
    window.addEventListener('livewire:navigate', removeTopbarLogo);
    window.addEventListener('livewire:update', removeTopbarLogo);
})();

// Add logo to sidebar and remove Laravel text
(function() {
    function setupSidebarLogo() {
        const sidebar = document.querySelector('.fi-sidebar');
        if (!sidebar) return;

        // Find sidebar header
        const headerSelectors = [
            '[class*="header"]',
            '[class*="brand"]',
            'a[href*="dashboard"]',
            '.fi-sidebar > div:first-child',
            '.fi-sidebar > a:first-child'
        ];

        let headerElement = null;
        for (const selector of headerSelectors) {
            const elements = sidebar.querySelectorAll(selector);
            for (const el of elements) {
                if (el.closest('.fi-sidebar') && (el.textContent.includes('Laravel') || el.closest('.fi-sidebar') === sidebar)) {
                    headerElement = el;
                    break;
                }
            }
            if (headerElement) break;
        }

        // Fallback: use first child of sidebar
        if (!headerElement) {
            headerElement = sidebar.querySelector('> div:first-child, > a:first-child');
        }

        if (headerElement && !headerElement.querySelector('.fi-sidebar-logo-custom')) {
            // Hide Laravel text
            const allElements = headerElement.querySelectorAll('*');
            allElements.forEach(el => {
                if (el.textContent && el.textContent.trim() === 'Laravel') {
                    el.style.display = 'none';
                }
            });

            // Hide direct text nodes
            const walker = document.createTreeWalker(
                headerElement,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );

            let node;
            while (node = walker.nextNode()) {
                if (node.textContent.trim() === 'Laravel') {
                    node.parentElement.style.display = 'none';
                }
            }

            // Create and add logo
            const logoImg = document.createElement('img');
            logoImg.src = '{{ asset("storage/WhatsApp_Image_2026-01-18_at_17.29.19-removebg-preview.png") }}';
            logoImg.alt = 'Job Seeker Hub Logo';
            logoImg.className = 'fi-sidebar-logo-custom';

            // Clear existing content and add logo
            const existingContent = headerElement.innerHTML;
            headerElement.innerHTML = '';
            headerElement.appendChild(logoImg);

            // Make it a link to dashboard if it's not already
            if (headerElement.tagName !== 'A') {
                const link = document.createElement('a');
                link.href = headerElement.getAttribute('href') || '/admin';
                link.className = headerElement.className;
                link.style.cssText = headerElement.style.cssText;
                link.appendChild(logoImg);
                headerElement.parentNode.replaceChild(link, headerElement);
            }
        }
    }

    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupSidebarLogo);
    } else {
        setupSidebarLogo();
    }

    // Also try after delays
    setTimeout(setupSidebarLogo, 300);
    setTimeout(setupSidebarLogo, 1000);

    // Watch for navigation changes
    window.addEventListener('livewire:navigate', setupSidebarLogo);
    window.addEventListener('livewire:update', setupSidebarLogo);
})();

// Replace Laravel with logo on login page
(function() {
    let retryCount = 0;
    const MAX_RETRIES = 15;

    function setupLoginLogo() {
        // Check if we're on login page
        if (!window.location.pathname.includes('/login')) {
            return;
        }

        // Find all elements that might contain "Laravel"
        const allElements = document.querySelectorAll('*');
        let targetElement = null;

        for (const el of allElements) {
            // Skip if already processed
            if (el.querySelector('.fi-login-logo-custom')) {
                continue;
            }

            // Check if element contains "Laravel" text
            const text = el.textContent || '';
            if (text.trim() === 'Laravel' || (text.includes('Laravel') && el.children.length === 0)) {
                // Check if it's a heading or brand element
                if (el.tagName.match(/^H[1-6]$/) || 
                    el.className.includes('heading') || 
                    el.className.includes('title') || 
                    el.className.includes('brand') ||
                    el.closest('.fi-simple-page')) {
                    targetElement = el;
                    break;
                }
            }
        }

        // Also try to find by common Filament selectors
        if (!targetElement) {
            const selectors = [
                '.fi-simple-page h1',
                '.fi-simple-page h2',
                '.fi-simple-page [class*="heading"]',
                '.fi-simple-page [class*="title"]',
                '.fi-simple-page [class*="brand"]',
                'h1',
                'h2'
            ];

            for (const selector of selectors) {
                const elements = document.querySelectorAll(selector);
                for (const el of elements) {
                    if (el.textContent && el.textContent.includes('Laravel')) {
                        targetElement = el;
                        break;
                    }
                }
                if (targetElement) break;
            }
        }

        if (targetElement && !targetElement.querySelector('.fi-login-logo-custom')) {
            // Create logo element
            const logoImg = document.createElement('img');
            logoImg.src = '{{ asset("storage/WhatsApp_Image_2026-01-18_at_17.29.19-removebg-preview.png") }}';
            logoImg.alt = 'Job Seeker Hub Logo';
            logoImg.className = 'fi-login-logo-custom';

            // Replace content with logo
            targetElement.innerHTML = '';
            targetElement.style.display = 'flex';
            targetElement.style.justifyContent = 'center';
            targetElement.style.alignItems = 'center';
            targetElement.style.margin = '0 auto';
            targetElement.appendChild(logoImg);
            
            retryCount = 0; // Reset on success
            return true;
        }

        return false;
    }

    function trySetupLoginLogo() {
        if (retryCount >= MAX_RETRIES) {
            return;
        }

        if (!setupLoginLogo()) {
            retryCount++;
            setTimeout(trySetupLoginLogo, 200);
        }
    }

    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', trySetupLoginLogo);
    } else {
        trySetupLoginLogo();
    }

    // Also try after delays
    setTimeout(trySetupLoginLogo, 300);
    setTimeout(trySetupLoginLogo, 800);
    setTimeout(trySetupLoginLogo, 1500);
    setTimeout(trySetupLoginLogo, 2500);

    // Watch for navigation changes
    window.addEventListener('livewire:navigate', function() {
        retryCount = 0;
        trySetupLoginLogo();
    });
    window.addEventListener('livewire:update', function() {
        retryCount = 0;
        trySetupLoginLogo();
    });
})();
</script>
