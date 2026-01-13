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
</style>
