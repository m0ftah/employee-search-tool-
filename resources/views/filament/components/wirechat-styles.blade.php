@wirechatStyles

<style>
    /* ============================================
       Back Button - Beside "Chats" Header
       ============================================ */
    
    /* Back button container - positioned in header */
    [wirechat] .wirechat-back-button {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        padding: 0.5rem 0.75rem !important;
        background: transparent !important;
        border: 1.5px solid var(--wc-border) !important;
        border-radius: 0.5rem !important;
        color: var(--wc-text-secondary) !important;
        text-decoration: none !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        margin-right: 0.75rem !important;
    }
    
    [wirechat] .wirechat-back-button:hover {
        background: var(--wc-surface) !important;
        border-color: var(--wc-primary) !important;
        color: var(--wc-primary) !important;
        transform: translateX(-2px) !important;
    }
    
    [wirechat] .wirechat-back-button svg {
        width: 16px !important;
        height: 16px !important;
        transition: transform 0.2s ease !important;
    }
    
    [wirechat] .wirechat-back-button:hover svg {
        transform: translateX(-2px) !important;
    }
    
    .dark [wirechat] .wirechat-back-button {
        border-color: var(--wc-border-dark) !important;
        color: var(--wc-text-muted) !important;
    }
    
    .dark [wirechat] .wirechat-back-button:hover {
        background: var(--wc-surface-dark) !important;
        border-color: var(--wc-primary) !important;
        color: var(--wc-primary) !important;
    }
    
    /* Style the header to accommodate back button */
    [wirechat] [class*="header"] h1,
    [wirechat] [class*="header"] h2,
    [wirechat] [class*="header"] h3,
    [wirechat] header h1,
    [wirechat] header h2,
    [wirechat] header h3,
    [wirechat] [class*="title"] {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
    }
    /* ============================================
       Simple & User-Friendly Wirechat UI
       ============================================ */
    
    :root {
        /* Modern Teal to Orange Gradient Theme */
        --wc-primary: #14b8a6; /* Teal */
        --wc-primary-hover: #0d9488;
        --wc-primary-light: rgba(20, 184, 166, 0.1);
        --wc-primary-gradient: linear-gradient(135deg, #14b8a6 0%, #f97316 100%);
        --wc-bg: #ffffff;
        --wc-bg-dark: #0f172a;
        --wc-surface: #f8fafc;
        --wc-surface-dark: #1e293b;
        --wc-border: #e2e8f0;
        --wc-border-dark: #334155;
        --wc-text: #1e293b;
        --wc-text-secondary: #64748b;
        --wc-text-dark: #f1f5f9;
        --wc-text-muted: #94a3b8;
        
        /* Wirechat CSS variables override */
        --wc-light-primary: var(--wc-bg) !important;
        --wc-dark-primary: var(--wc-bg-dark) !important;
        --wc-light-secondary: var(--wc-surface) !important;
        --wc-dark-secondary: var(--wc-surface-dark) !important;
        --wc-light-border: var(--wc-border) !important;
        --wc-dark-border: var(--wc-border-dark) !important;
    }
    
    .dark {
        --wc-light-primary: var(--wc-bg-dark) !important;
        --wc-dark-primary: var(--wc-bg-dark) !important;
        --wc-light-secondary: var(--wc-surface-dark) !important;
        --wc-dark-secondary: var(--wc-surface-dark) !important;
        --wc-light-border: var(--wc-border-dark) !important;
        --wc-dark-border: var(--wc-border-dark) !important;
    }

    /* ============================================
       Main Container - Clean Layout
       ============================================ */
    
    /* Remove unnecessary borders and shadows */
    [wirechat] > div,
    [wirechat] > section,
    [wirechat] .wirechat-container {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    /* ============================================
       Sidebar - Simple & Clean
       ============================================ */
    
    /* Chat list sidebar */
    [wirechat] aside,
    [wirechat] [class*="sidebar"],
    [wirechat] [class*="chats-list"] {
        background: #f8fafc !important;
        border-right: 1px solid var(--wc-border) !important;
        padding: 0 !important;
        width: 100% !important;
        min-width: 280px !important;
        max-width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
    }

    .dark [wirechat] aside,
    .dark [wirechat] [class*="sidebar"],
    .dark [wirechat] [class*="chats-list"] {
        background: #1e293b !important;
        border-right-color: var(--wc-border-dark) !important;
    }

    /* Sidebar header - simplified */
    [wirechat] [class*="header"],
    [wirechat] header {
        padding: 1.25rem 1rem !important;
        margin-bottom: 0 !important;
        border-bottom: 1px solid var(--wc-border) !important;
        background: white !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
    }
    
    .dark [wirechat] [class*="header"],
    .dark [wirechat] header {
        background: #0f172a !important;
        border-bottom-color: var(--wc-border-dark) !important;
    }

    .dark [wirechat] [class*="header"],
    .dark [wirechat] header {
        border-bottom-color: var(--wc-border-dark) !important;
    }

    /* ============================================
       Search Bar - Simple & Intuitive
       ============================================ */
    
    [wirechat] input[type="search"],
    [wirechat] input[type="text"][placeholder*="Search"],
    [wirechat] input[placeholder*="search"] {
        width: calc(100% - 2rem) !important;
        padding: 0.75rem 1rem 0.75rem 2.75rem !important;
        border: 1.5px solid var(--wc-border) !important;
        border-radius: 0.75rem !important;
        background: white !important;
        font-size: 0.9375rem !important;
        transition: all 0.2s ease !important;
        margin: 1rem !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
        color: var(--wc-text) !important;
    }
    
    [wirechat] input[type="search"]:focus,
    [wirechat] input[type="text"][placeholder*="Search"]:focus {
        outline: none !important;
        border-color: var(--wc-primary) !important;
        background: white !important;
        box-shadow: 0 0 0 3px var(--wc-primary-light), 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    .dark [wirechat] input[type="search"],
    .dark [wirechat] input[type="text"][placeholder*="Search"] {
        background: var(--wc-surface-dark) !important;
        border-color: var(--wc-border-dark) !important;
        color: var(--wc-text-dark) !important;
    }

    .dark [wirechat] input[type="search"]:focus,
    .dark [wirechat] input[type="text"][placeholder*="Search"]:focus {
        background: var(--wc-bg-dark) !important;
        border-color: var(--wc-primary) !important;
    }

    /* ============================================
       Chat Items - Clean & Clickable
       ============================================ */
    
    /* Chat item list - Modern & Clean */
    [wirechat] [class*="chat-item"],
    [wirechat] [class*="conversation"],
    [wirechat] a[href*="/chats/"],
    [wirechat] [role="listitem"],
    [wirechat] [class*="conversation-item"] {
        display: flex !important;
        align-items: center !important;
        gap: 0.875rem !important;
        padding: 0.875rem 1rem !important;
        margin: 0.25rem 0.5rem !important;
        border-radius: 0.875rem !important;
        cursor: pointer !important;
        background: transparent !important;
        border: none !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
        color: inherit !important;
    }

    /* Hover state - subtle */
    [wirechat] [class*="chat-item"]:hover,
    [wirechat] [class*="conversation"]:hover,
    [wirechat] a[href*="/chats/"]:hover,
    [wirechat] [class*="conversation-item"]:hover {
        background: white !important;
        transform: translateX(2px) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
    }

    .dark [wirechat] [class*="chat-item"]:hover,
    .dark [wirechat] [class*="conversation"]:hover,
    .dark [wirechat] a[href*="/chats/"]:hover,
    .dark [wirechat] [class*="conversation-item"]:hover {
        background: var(--wc-surface-dark) !important;
        transform: translateX(2px) !important;
    }

    /* Active chat - clear indicator */
    [wirechat] [class*="chat-item"][aria-current="page"],
    [wirechat] [class*="active"],
    [wirechat] a[href*="/chats/"][aria-current="page"],
    [wirechat] [class*="conversation-item"][aria-current="page"] {
        background: linear-gradient(90deg, rgba(20, 184, 166, 0.12) 0%, rgba(20, 184, 166, 0.06) 100%) !important;
        border-left: 3px solid var(--wc-primary) !important;
        padding-left: calc(1rem - 3px) !important;
        font-weight: 500 !important;
        box-shadow: 0 2px 4px rgba(20, 184, 166, 0.1) !important;
    }

    .dark [wirechat] [class*="chat-item"][aria-current="page"],
    .dark [wirechat] [class*="active"],
    .dark [wirechat] [class*="conversation-item"][aria-current="page"] {
        background: rgba(20, 184, 166, 0.2) !important;
        border-left-color: var(--wc-primary) !important;
    }

    /* ============================================
       Avatars - Simple & Clear
       ============================================ */
    
    /* Avatar styling - Modern & Clean */
    [wirechat] img[class*="avatar"],
    [wirechat] [class*="avatar"] img,
    [wirechat] img[alt*="avatar"],
    [wirechat] [class*="avatar"] {
        width: 48px !important;
        height: 48px !important;
        border-radius: 50% !important;
        border: 2px solid var(--wc-border) !important;
        object-fit: cover !important;
        flex-shrink: 0 !important;
        background: var(--wc-surface) !important;
        transition: all 0.2s ease !important;
    }

    .dark [wirechat] img[class*="avatar"],
    .dark [wirechat] [class*="avatar"] img,
    .dark [wirechat] [class*="avatar"] {
        border-color: var(--wc-border-dark) !important;
        background: var(--wc-surface-dark) !important;
    }

    /* Active chat avatar highlight */
    [wirechat] [class*="active"] img[class*="avatar"],
    [wirechat] [aria-current="page"] img[class*="avatar"],
    [wirechat] [class*="active"] [class*="avatar"] {
        border-color: var(--wc-primary) !important;
        border-width: 2.5px !important;
        box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.2) !important;
    }
    
    /* Avatar hover effect */
    [wirechat] [class*="chat-item"]:hover img[class*="avatar"],
    [wirechat] [class*="chat-item"]:hover [class*="avatar"],
    [wirechat] [class*="conversation"]:hover img[class*="avatar"],
    [wirechat] a[href*="/chats/"]:hover img[class*="avatar"] {
        border-color: var(--wc-primary) !important;
        transform: scale(1.05) !important;
    }

    /* ============================================
       Chat Info - Clean Typography
       ============================================ */
    
    /* User name */
    [wirechat] [class*="user-name"],
    [wirechat] [class*="name"],
    [wirechat] strong {
        font-weight: 500 !important;
        font-size: 0.9375rem !important;
        color: var(--wc-text) !important;
        margin: 0 !important;
        line-height: 1.4 !important;
        display: block !important;
    }

    .dark [wirechat] [class*="user-name"],
    .dark [wirechat] [class*="name"],
    .dark [wirechat] strong {
        color: var(--wc-text-dark) !important;
    }

    /* Last message preview */
    [wirechat] [class*="last-message"],
    [wirechat] [class*="preview"],
    [wirechat] [class*="snippet"],
    [wirechat] p {
        font-size: 0.8125rem !important;
        color: var(--wc-text-secondary) !important;
        margin: 0.25rem 0 0 0 !important;
        line-height: 1.4 !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        display: block !important;
    }

    .dark [wirechat] [class*="last-message"],
    .dark [wirechat] [class*="preview"],
    .dark [wirechat] p {
        color: var(--wc-text-muted) !important;
    }

    /* Time stamp */
    [wirechat] [class*="time"],
    [wirechat] [class*="timestamp"],
    [wirechat] time,
    [wirechat] small {
        font-size: 0.75rem !important;
        color: var(--wc-text-secondary) !important;
        margin-left: auto !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .dark [wirechat] [class*="time"],
    .dark [wirechat] [class*="timestamp"],
    .dark [wirechat] time {
        color: var(--wc-text-muted) !important;
    }

    /* ============================================
       Message Area - Clean & Readable
       ============================================ */
    
    /* Main chat area - Beautiful gradient background */
    [wirechat] main,
    [wirechat] [class*="chat-area"],
    [wirechat] [class*="messages"],
    [wirechat] [class*="message-container"] {
        background: linear-gradient(to bottom, #f0fdfa 0%, #ffffff 30%, #ffffff 70%, #fff7ed 100%) !important;
        padding: 1.5rem 1rem !important;
        flex: 1 !important;
        overflow-y: auto !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.75rem !important;
        min-height: 100% !important;
        position: relative !important;
    }
    
    .dark [wirechat] main,
    .dark [wirechat] [class*="chat-area"],
    .dark [wirechat] [class*="messages"],
    .dark [wirechat] [class*="message-container"] {
        background: linear-gradient(to bottom, #0f172a 0%, #1e293b 30%, #1e293b 70%, #1c1917 100%) !important;
    }

    /* ============================================
       Message Bubbles - Simple & Clear
       ============================================ */
    
    /* Message container */
    [wirechat] [class*="message"] {
        display: flex !important;
        margin: 0.25rem 0 !important;
        padding: 0 0.5rem !important;
        align-items: flex-end !important;
        gap: 0.5rem !important;
    }

    /* Sent messages (right) */
    [wirechat] [class*="message"][class*="sent"],
    [wirechat] [class*="message"][class*="own"] {
        justify-content: flex-end !important;
    }

    [wirechat] [class*="message-bubble"][class*="sent"],
    [wirechat] [class*="message-bubble"][class*="own"],
    [wirechat] [class*="message"]:has([class*="sent"]) > div {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
        color: white !important;
        padding: 0.75rem 1rem !important;
        border-radius: 1.125rem 1.125rem 0.25rem 1.125rem !important;
        max-width: 70% !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        font-size: 0.9375rem !important;
        line-height: 1.6 !important;
        box-shadow: 0 2px 4px rgba(20, 184, 166, 0.2) !important;
    }

    /* Received messages (left) */
    [wirechat] [class*="message"]:not([class*="sent"]):not([class*="own"]) {
        justify-content: flex-start !important;
    }

    [wirechat] [class*="message-bubble"]:not([class*="sent"]):not([class*="own"]) {
        background: white !important;
        color: var(--wc-text) !important;
        padding: 0.75rem 1rem !important;
        border-radius: 1.125rem 1.125rem 1.125rem 0.25rem !important;
        max-width: 70% !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        font-size: 0.9375rem !important;
        line-height: 1.6 !important;
        border: 1px solid var(--wc-border) !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }

    .dark [wirechat] [class*="message-bubble"]:not([class*="sent"]):not([class*="own"]) {
        background: var(--wc-surface-dark) !important;
        color: var(--wc-text-dark) !important;
        border-color: var(--wc-border-dark) !important;
    }

    /* Message timestamp */
    [wirechat] [class*="message"] [class*="time"],
    [wirechat] [class*="message"] time,
    [wirechat] [class*="message"] small {
        font-size: 0.6875rem !important;
        color: var(--wc-text-muted) !important;
        margin-top: 0.25rem !important;
        opacity: 0.8 !important;
    }

    /* ============================================
       Date Separators - Subtle & Clear
       ============================================ */
    
    [wirechat] [class*="date-separator"],
    [wirechat] [class*="divider"],
    [wirechat] hr {
        display: flex !important;
        align-items: center !important;
        margin: 1.5rem 0 !important;
        border: none !important;
        text-align: center !important;
    }

    [wirechat] [class*="date-separator"]::before,
    [wirechat] [class*="date-separator"]::after,
    [wirechat] [class*="divider"]::before,
    [wirechat] [class*="divider"]::after {
        content: '' !important;
        flex: 1 !important;
        height: 1px !important;
        background: var(--wc-border) !important;
    }

    .dark [wirechat] [class*="date-separator"]::before,
    .dark [wirechat] [class*="date-separator"]::after {
        background: var(--wc-border-dark) !important;
    }

    [wirechat] [class*="date-separator"] span,
    [wirechat] [class*="date-label"] {
        padding: 0.25rem 0.75rem !important;
        background: var(--wc-surface) !important;
        border-radius: 0.75rem !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: var(--wc-text-secondary) !important;
        margin: 0 0.75rem !important;
    }

    .dark [wirechat] [class*="date-separator"] span,
    .dark [wirechat] [class*="date-label"] {
        background: var(--wc-surface-dark) !important;
        color: var(--wc-text-muted) !important;
    }

    /* ============================================
       Input Area - Simple & Intuitive
       ============================================ */
    
    /* Input container */
    [wirechat] footer,
    [wirechat] [class*="footer"],
    [wirechat] [class*="input-area"],
    [wirechat] [class*="composer"] {
        padding: 1.25rem 1rem !important;
        border-top: 1px solid var(--wc-border) !important;
        background: white !important;
        position: relative !important;
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05) !important;
    }
    
    .dark [wirechat] footer,
    .dark [wirechat] [class*="footer"],
    .dark [wirechat] [class*="input-area"] {
        background: var(--wc-bg-dark) !important;
        border-top-color: var(--wc-border-dark) !important;
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.3) !important;
    }

    /* Message input field */
    [wirechat] textarea[placeholder*="message"],
    [wirechat] textarea[placeholder*="Type"],
    [wirechat] textarea[placeholder*="type"],
    [wirechat] [class*="message-input"],
    [wirechat] [class*="input"] {
        width: 100% !important;
        min-height: 48px !important;
        max-height: 120px !important;
        padding: 0.875rem 3.5rem 0.875rem 1.25rem !important;
        border: 1.5px solid var(--wc-border) !important;
        border-radius: 1.5rem !important;
        background: var(--wc-surface) !important;
        font-size: 0.9375rem !important;
        line-height: 1.5 !important;
        color: var(--wc-text) !important;
        resize: none !important;
        transition: all 0.2s ease !important;
        font-family: inherit !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
    }

    [wirechat] textarea[placeholder*="message"]:focus,
    [wirechat] textarea[placeholder*="Type"]:focus,
    [wirechat] [class*="message-input"]:focus {
        outline: none !important;
        border-color: var(--wc-primary) !important;
        background: white !important;
        box-shadow: 0 0 0 3px var(--wc-primary-light), 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    .dark [wirechat] textarea[placeholder*="message"],
    .dark [wirechat] [class*="message-input"] {
        background: var(--wc-surface-dark) !important;
        border-color: var(--wc-border-dark) !important;
        color: var(--wc-text-dark) !important;
    }

    .dark [wirechat] textarea[placeholder*="message"]:focus,
    .dark [wirechat] [class*="message-input"]:focus {
        background: var(--wc-bg-dark) !important;
    }

    /* Send button - Modern gradient */
    [wirechat] button[type="submit"],
    [wirechat] button[aria-label*="send"],
    [wirechat] button[aria-label*="Send"],
    [wirechat] [class*="send-button"],
    [wirechat] [class*="send"] {
        position: absolute !important;
        right: 1.5rem !important;
        bottom: 1.5rem !important;
        width: 42px !important;
        height: 42px !important;
        border-radius: 50% !important;
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
        border: none !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 8px rgba(20, 184, 166, 0.3) !important;
        z-index: 10 !important;
    }

    [wirechat] button[type="submit"]:hover,
    [wirechat] button[aria-label*="send"]:hover,
    [wirechat] [class*="send-button"]:hover {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%) !important;
        transform: scale(1.08) !important;
        box-shadow: 0 4px 12px rgba(20, 184, 166, 0.4) !important;
    }
    
    [wirechat] button[type="submit"]:active {
        transform: scale(0.95) !important;
    }
    
    [wirechat] button[type="submit"] svg,
    [wirechat] button[aria-label*="send"] svg {
        width: 20px !important;
        height: 20px !important;
    }

    /* Input action buttons (emojis, attachments) */
    [wirechat] button[aria-label*="emoji"],
    [wirechat] button[aria-label*="attachment"],
    [wirechat] button[aria-label*="file"],
    [wirechat] [class*="action-button"] {
        position: absolute !important;
        left: 1.25rem !important;
        bottom: 1.25rem !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        background: transparent !important;
        border: none !important;
        color: var(--wc-text-secondary) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
    }

    [wirechat] button[aria-label*="emoji"]:hover,
    [wirechat] button[aria-label*="attachment"]:hover {
        background: var(--wc-surface) !important;
        color: var(--wc-primary) !important;
    }

    .dark [wirechat] button[aria-label*="emoji"],
    .dark [wirechat] button[aria-label*="attachment"] {
        color: var(--wc-text-muted) !important;
    }

    .dark [wirechat] button[aria-label*="emoji"]:hover,
    .dark [wirechat] button[aria-label*="attachment"]:hover {
        background: var(--wc-surface-dark) !important;
    }

    /* ============================================
       Scrollbars - Minimal & Clean
       ============================================ */
    
    [wirechat] ::-webkit-scrollbar {
        width: 6px !important;
        height: 6px !important;
    }

    [wirechat] ::-webkit-scrollbar-track {
        background: transparent !important;
    }

    [wirechat] ::-webkit-scrollbar-thumb {
        background: var(--wc-border) !important;
        border-radius: 3px !important;
        transition: background 0.15s ease !important;
    }

    [wirechat] ::-webkit-scrollbar-thumb:hover {
        background: var(--wc-text-secondary) !important;
    }

    .dark [wirechat] ::-webkit-scrollbar-thumb {
        background: var(--wc-border-dark) !important;
    }

    .dark [wirechat] ::-webkit-scrollbar-thumb:hover {
        background: var(--wc-text-muted) !important;
    }

    /* ============================================
       Badges & Notifications - Subtle
       ============================================ */
    
    [wirechat] [class*="badge"],
    [wirechat] [class*="notification"],
    [wirechat] [class*="unread"],
    [wirechat] span[class*="count"] {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
        color: white !important;
        border-radius: 0.75rem !important;
        padding: 0.125rem 0.5rem !important;
        font-size: 0.6875rem !important;
        font-weight: 600 !important;
        min-width: 1.25rem !important;
        height: 1.25rem !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        box-shadow: 0 2px 4px rgba(20, 184, 166, 0.3) !important;
    }

    /* ============================================
       Empty State - Welcoming
       ============================================ */
    
    [wirechat] [class*="empty"],
    [wirechat] [class*="welcome"],
    [wirechat] [class*="no-messages"],
    [wirechat] [class*="no-conversations"] {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 3rem 2rem !important;
        text-align: center !important;
        color: var(--wc-text-secondary) !important;
        min-height: 300px !important;
    }
    
    /* Empty state icon */
    [wirechat] [class*="empty"] svg,
    [wirechat] [class*="welcome"] svg,
    [wirechat] [class*="no-messages"] svg {
        width: 64px !important;
        height: 64px !important;
        color: var(--wc-text-muted) !important;
        margin-bottom: 1rem !important;
        opacity: 0.5 !important;
    }
    
    /* Empty state text */
    [wirechat] [class*="empty"] h3,
    [wirechat] [class*="empty"] h4,
    [wirechat] [class*="welcome"] h3,
    [wirechat] [class*="welcome"] h4 {
        font-size: 1.125rem !important;
        font-weight: 600 !important;
        color: var(--wc-text) !important;
        margin-bottom: 0.5rem !important;
    }
    
    [wirechat] [class*="empty"] p,
    [wirechat] [class*="welcome"] p {
        font-size: 0.9375rem !important;
        color: var(--wc-text-secondary) !important;
        line-height: 1.6 !important;
        max-width: 400px !important;
    }

    .dark [wirechat] [class*="empty"],
    .dark [wirechat] [class*="welcome"] {
        color: var(--wc-text-muted) !important;
    }

    /* ============================================
       Typography - Clean & Readable
       ============================================ */
    
    [wirechat] {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        font-size: 0.9375rem !important;
        line-height: 1.5 !important;
    }

    [wirechat] h1, [wirechat] h2, [wirechat] h3 {
        font-weight: 600 !important;
        line-height: 1.3 !important;
        margin: 0 !important;
    }

    [wirechat] p {
        margin: 0 !important;
        line-height: 1.5 !important;
    }

    /* ============================================
       General Improvements
       ============================================ */
    
    /* Remove unnecessary shadows and borders */
    [wirechat] * {
        box-sizing: border-box !important;
    }

    [wirechat] button {
        border-radius: 0.5rem !important;
        transition: all 0.15s ease !important;
        cursor: pointer !important;
        font-weight: 500 !important;
    }

    [wirechat] a {
        text-decoration: none !important;
        color: inherit !important;
        transition: all 0.15s ease !important;
    }

    /* Focus states for accessibility */
    [wirechat] button:focus-visible,
    [wirechat] input:focus-visible,
    [wirechat] textarea:focus-visible {
        outline: 2px solid var(--wc-primary) !important;
        outline-offset: 2px !important;
    }

    /* Remove default button styles */
    [wirechat] button {
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
    }

    /* Loading states */
    [wirechat] [class*="loading"],
    [wirechat] [aria-busy="true"] {
        opacity: 0.6 !important;
        pointer-events: none !important;
    }

    /* ============================================
       Responsive - Mobile Friendly
       ============================================ */
    
    @media (max-width: 768px) {
        [wirechat] aside,
        [wirechat] [class*="sidebar"] {
            width: 100% !important;
            max-width: 100% !important;
        }

        [wirechat] [class*="message-bubble"] {
            max-width: 85% !important;
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem !important;
        }
    }

    /* ============================================
       Smooth Animations
       ============================================ */
    
    [wirechat] * {
        transition: background-color 0.15s ease,
                    border-color 0.15s ease,
                    color 0.15s ease !important;
    }

    /* ============================================
       New Chat Modal - Beautiful UI
       ============================================ */
    
    /* Modal backdrop - Universal selectors */
    [wirechat] [role="dialog"],
    [wirechat] [x-dialog],
    [wirechat] [x-show],
    [wirechat] .modal-backdrop,
    [wirechat] [class*="modal-backdrop"],
    [wirechat] [class*="overlay"],
    [wirechat] [class*="backdrop"],
    body > [style*="position: fixed"]:has([wirechat]),
    .fi-modal-overlay:has([wirechat]),
    [wirechat] + [style*="position: fixed"],
    [wirechat] ~ [style*="position: fixed"] {
        background: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(4px) !important;
        z-index: 9999 !important;
    }

    /* Modal container - Only for Wirechat modals */
    [wirechat] [role="dialog"] > div,
    [wirechat] [role="dialog"] > div > div,
    [wirechat] [x-dialog] > div,
    [wirechat] [x-dialog] > div > div,
    [wirechat] [class*="modal-content"],
    [wirechat] [class*="modal-panel"],
    [wirechat] [class*="dialog-panel"] {
        background: var(--wc-bg) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid var(--wc-border) !important;
        max-width: 500px !important;
        width: 90% !important;
        max-height: 85vh !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        margin: auto !important;
        position: relative !important;
    }

    .dark [wirechat] [role="dialog"] > div,
    .dark [wirechat] [class*="modal-content"] {
        background: var(--wc-bg-dark) !important;
        border-color: var(--wc-border-dark) !important;
    }

    /* Modal header */
    [wirechat] [class*="modal-header"],
    [wirechat] [class*="dialog-header"],
    [wirechat] [role="dialog"] h2,
    [wirechat] [role="dialog"] h3 {
        padding: 1.5rem 1.5rem 1rem 1.5rem !important;
        border-bottom: 1px solid var(--wc-border) !important;
        background: transparent !important;
        margin: 0 !important;
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        color: var(--wc-text) !important;
    }

    .dark [wirechat] [class*="modal-header"],
    .dark [wirechat] [role="dialog"] h2,
    .dark [wirechat] [role="dialog"] h3 {
        border-bottom-color: var(--wc-border-dark) !important;
        color: var(--wc-text-dark) !important;
    }

    /* Close button */
    [wirechat] [class*="modal-close"],
    [wirechat] [class*="close-button"],
    [wirechat] button[aria-label*="close"],
    [wirechat] button[aria-label*="Close"] {
        position: absolute !important;
        top: 1rem !important;
        right: 1rem !important;
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        background: var(--wc-surface) !important;
        border: 1px solid var(--wc-border) !important;
        color: var(--wc-text-secondary) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        z-index: 10 !important;
    }

    [wirechat] [class*="modal-close"]:hover,
    [wirechat] [class*="close-button"]:hover {
        background: var(--wc-primary-light) !important;
        border-color: var(--wc-primary) !important;
        color: var(--wc-primary) !important;
        transform: scale(1.1) !important;
    }

    .dark [wirechat] [class*="modal-close"],
    .dark [wirechat] [class*="close-button"] {
        background: var(--wc-surface-dark) !important;
        border-color: var(--wc-border-dark) !important;
        color: var(--wc-text-muted) !important;
    }

    /* Modal body */
    [wirechat] [class*="modal-body"],
    [wirechat] [class*="dialog-body"],
    [wirechat] [role="dialog"] > div > div:not([class*="header"]) {
        padding: 1rem 1.5rem !important;
        overflow-y: auto !important;
        flex: 1 !important;
    }

    /* Search input in modal - Only Wirechat modals */
    [wirechat] [role="dialog"] input[type="search"],
    [wirechat] [role="dialog"] input[type="text"][placeholder*="Search"],
    [wirechat] [class*="modal"] input[type="search"],
    [wirechat] [class*="modal"] input[type="text"] {
        width: 100% !important;
        padding: 0.75rem 1rem 0.75rem 2.75rem !important;
        border: 1.5px solid var(--wc-border) !important;
        border-radius: 0.75rem !important;
        background: var(--wc-surface) !important;
        font-size: 0.9375rem !important;
        color: var(--wc-text) !important;
        margin-bottom: 1rem !important;
        transition: all 0.15s ease !important;
    }

    [wirechat] [role="dialog"] input[type="search"]:focus,
    [wirechat] [role="dialog"] input[type="text"]:focus,
    [wirechat] [class*="modal"] input[type="search"]:focus {
        outline: none !important;
        border-color: var(--wc-primary) !important;
        background: var(--wc-bg) !important;
        box-shadow: 0 0 0 3px var(--wc-primary-light) !important;
    }

    .dark [wirechat] [role="dialog"] input[type="search"],
    .dark [wirechat] [class*="modal"] input[type="search"] {
        background: var(--wc-surface-dark) !important;
        border-color: var(--wc-border-dark) !important;
        color: var(--wc-text-dark) !important;
    }

    /* User list in modal - Only Wirechat modals */
    [wirechat] [role="dialog"] [class*="user-list"],
    [wirechat] [role="dialog"] [class*="user-item"],
    [wirechat] [class*="modal"] [class*="user-list"] > *,
    [wirechat] [class*="modal"] [role="listitem"] {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        padding: 0.875rem 1rem !important;
        margin: 0.25rem 0 !important;
        border-radius: 0.75rem !important;
        cursor: pointer !important;
        background: transparent !important;
        border: 1px solid transparent !important;
        transition: all 0.15s ease !important;
    }

    [wirechat] [role="dialog"] [class*="user-item"]:hover,
    [wirechat] [class*="modal"] [role="listitem"]:hover {
        background: var(--wc-surface) !important;
        border-color: var(--wc-border) !important;
        transform: translateX(4px) !important;
    }

    .dark [wirechat] [role="dialog"] [class*="user-item"]:hover,
    .dark [wirechat] [class*="modal"] [role="listitem"]:hover {
        background: var(--wc-surface-dark) !important;
        border-color: var(--wc-border-dark) !important;
    }

    /* User avatar in modal */
    [wirechat] [role="dialog"] img[class*="avatar"],
    [wirechat] [class*="modal"] img[class*="avatar"],
    [wirechat] [role="dialog"] [class*="avatar"] img {
        width: 48px !important;
        height: 48px !important;
        border-radius: 50% !important;
        border: 2px solid var(--wc-border) !important;
        object-fit: cover !important;
        flex-shrink: 0 !important;
    }

    /* User name in modal */
    [wirechat] [role="dialog"] [class*="user-name"],
    [wirechat] [class*="modal"] [class*="user-name"],
    [wirechat] [role="dialog"] strong {
        font-weight: 500 !important;
        font-size: 0.9375rem !important;
        color: var(--wc-text) !important;
        margin: 0 !important;
    }

    .dark [wirechat] [role="dialog"] [class*="user-name"],
    .dark [wirechat] [role="dialog"] strong {
        color: var(--wc-text-dark) !important;
    }

    /* Empty state in modal */
    [wirechat] [role="dialog"] [class*="empty"],
    [wirechat] [class*="modal"] [class*="empty-state"],
    [wirechat] [role="dialog"] [class*="no-results"] {
        padding: 2rem 1rem !important;
        text-align: center !important;
        color: var(--wc-text-secondary) !important;
    }

    .dark [wirechat] [role="dialog"] [class*="empty"] {
        color: var(--wc-text-muted) !important;
    }

    /* Modal footer/actions */
    [wirechat] [role="dialog"] [class*="modal-footer"],
    [wirechat] [role="dialog"] [class*="dialog-footer"],
    [wirechat] [class*="modal"] [class*="actions"] {
        padding: 1rem 1.5rem 1.5rem 1.5rem !important;
        border-top: 1px solid var(--wc-border) !important;
        display: flex !important;
        gap: 0.75rem !important;
        justify-content: flex-end !important;
        background: transparent !important;
    }

    .dark [wirechat] [role="dialog"] [class*="modal-footer"] {
        border-top-color: var(--wc-border-dark) !important;
    }

    /* Buttons in modal */
    [wirechat] [role="dialog"] button[type="button"]:not([class*="close"]),
    [wirechat] [class*="modal"] button:not([class*="close"]) {
        padding: 0.625rem 1.25rem !important;
        border-radius: 0.75rem !important;
        font-weight: 500 !important;
        font-size: 0.9375rem !important;
        transition: all 0.15s ease !important;
    }

    [wirechat] [role="dialog"] button[type="submit"],
    [wirechat] [class*="modal"] button[type="submit"],
    [wirechat] [role="dialog"] button[class*="primary"] {
        background: var(--wc-primary) !important;
        color: white !important;
        border: none !important;
    }

    [wirechat] [role="dialog"] button[type="submit"]:hover,
    [wirechat] [class*="modal"] button[type="submit"]:hover {
        background: var(--wc-primary-hover) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3) !important;
    }

    [wirechat] [role="dialog"] button[type="button"]:not([type="submit"]):not([class*="close"]) {
        background: var(--wc-surface) !important;
        color: var(--wc-text) !important;
        border: 1px solid var(--wc-border) !important;
    }

    [wirechat] [role="dialog"] button[type="button"]:not([type="submit"]):not([class*="close"]):hover {
        background: var(--wc-primary-light) !important;
        border-color: var(--wc-primary) !important;
        color: var(--wc-primary) !important;
    }

    .dark [wirechat] [role="dialog"] button[type="button"]:not([type="submit"]):not([class*="close"]) {
        background: var(--wc-surface-dark) !important;
        border-color: var(--wc-border-dark) !important;
        color: var(--wc-text-dark) !important;
    }

    /* Loading state in modal */
    [wirechat] [role="dialog"] [class*="loading"],
    [wirechat] [class*="modal"] [class*="spinner"] {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 2rem !important;
    }

    /* Scrollbar in modal */
    [wirechat] [role="dialog"] ::-webkit-scrollbar,
    [wirechat] [class*="modal"] ::-webkit-scrollbar {
        width: 6px !important;
    }

    [wirechat] [role="dialog"] ::-webkit-scrollbar-thumb {
        background: var(--wc-border) !important;
        border-radius: 3px !important;
    }

    .dark [wirechat] [role="dialog"] ::-webkit-scrollbar-thumb {
        background: var(--wc-border-dark) !important;
    }

    /* Animation for modal */
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    [wirechat] [role="dialog"] > div,
    [wirechat] [class*="modal-content"] {
        animation: modalFadeIn 0.2s ease-out !important;
    }

    /* ============================================
       Filament Modal System - Wirechat Integration
       ============================================ */
    
    /* Filament modal overlay */
    .fi-modal-overlay {
        background: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(4px) !important;
    }

    /* Filament modal container */
    .fi-modal-container,
    .fi-modal {
        background: var(--wc-bg) !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid var(--wc-border) !important;
        max-width: 500px !important;
        width: 90% !important;
        overflow: hidden !important;
    }

    .dark .fi-modal-container,
    .dark .fi-modal {
        background: var(--wc-bg-dark) !important;
        border-color: var(--wc-border-dark) !important;
    }

    /* Filament modal content */
    .fi-modal-content {
        padding: 1.5rem !important;
    }

    /* Filament modal header */
    .fi-modal-header {
        padding: 1.5rem 1.5rem 1rem 1.5rem !important;
        border-bottom: 1px solid var(--wc-border) !important;
        margin-bottom: 1rem !important;
    }

    .dark .fi-modal-header {
        border-bottom-color: var(--wc-border-dark) !important;
    }

    /* Filament modal close button */
    .fi-modal-close,
    .fi-icon-btn[aria-label*="close"] {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        background: var(--wc-surface) !important;
        border: 1px solid var(--wc-border) !important;
        color: var(--wc-text-secondary) !important;
        transition: all 0.15s ease !important;
    }

    .fi-modal-close:hover,
    .fi-icon-btn[aria-label*="close"]:hover {
        background: var(--wc-primary-light) !important;
        border-color: var(--wc-primary) !important;
        color: var(--wc-primary) !important;
        transform: scale(1.1) !important;
    }

    /* ============================================
       Universal Modal Detection - Most Aggressive
       ============================================ */
    
    /* Target modals that appear outside [wirechat] but contain search inputs */
    body > [style*="position: fixed"]:has(input[type="search"]),
    body > [style*="position: fixed"]:has(input[placeholder*="Search"]),
    body > [style*="position: fixed"]:has(input[placeholder*="search"]) {
        background: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(4px) !important;
        z-index: 9999 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Style the content div inside fixed modals with search */
    body > [style*="position: fixed"]:has(input[type="search"]) > div,
    body > [style*="position: fixed"]:has(input[placeholder*="Search"]) > div,
    body > [style*="position: fixed"]:has(input[placeholder*="search"]) > div {
        background: #ffffff !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid #e2e8f0 !important;
        max-width: 500px !important;
        width: 90% !important;
        max-height: 85vh !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        margin: auto !important;
        position: relative !important;
        padding: 1.5rem !important;
    }

    /* Dark mode support */
    .dark body > [style*="position: fixed"]:has(input[type="search"]) > div {
        background: #0f172a !important;
        border-color: #334155 !important;
    }

    /* Style search inputs in these modals */
    body > [style*="position: fixed"]:has(input[type="search"]) input[type="search"],
    body > [style*="position: fixed"]:has(input[placeholder*="Search"]) input[type="search"],
    body > [style*="position: fixed"]:has(input[placeholder*="Search"]) input[type="text"] {
        width: 100% !important;
        padding: 0.75rem 1rem 0.75rem 2.75rem !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        background: #f8fafc !important;
        font-size: 0.9375rem !important;
        color: #1e293b !important;
        margin-bottom: 1rem !important;
        transition: all 0.15s ease !important;
    }

    body > [style*="position: fixed"]:has(input[type="search"]) input[type="search"]:focus {
        outline: none !important;
        border-color: #f59e0b !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1) !important;
    }

    /* ============================================
       Ultra-Aggressive Modal Styling - Highest Priority
       ============================================ */
    
    /* Target ANY element that is fixed and contains search - highest specificity */
    body > div[style*="position: fixed"]:has(input),
    body > div[style*="position: fixed"]:has(input[type="search"]),
    html body > div[style*="position: fixed"]:has(input[placeholder*="Search"]) {
        background: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(4px) !important;
        z-index: 99999 !important;
    }

    /* Force white background on ANY div inside fixed modal */
    body > div[style*="position: fixed"] > div,
    body > div[style*="position: fixed"] > div > div,
    html body > div[style*="position: fixed"]:has(input) > div,
    body > div[style*="position: fixed"] div:has(input[type="search"]),
    body > div[style*="position: fixed"] div:has(input[placeholder*="Search"]) {
        background: #ffffff !important;
        background-color: #ffffff !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    /* Target ALL children of fixed modal that might be the content */
    body > div[style*="position: fixed"]:has(input) * {
        background: inherit !important;
    }
    
    /* But force white on the main container */
    body > div[style*="position: fixed"]:has(input) > *:first-child,
    body > div[style*="position: fixed"]:has(input) > *:nth-child(1) {
        background: #ffffff !important;
        background-color: #ffffff !important;
        opacity: 1 !important;
    }

    /* Override any transparent or rgba backgrounds */
    [style*="background: transparent"],
    [style*="background-color: transparent"],
    [style*="background: rgba(0, 0, 0, 0)"],
    [style*="background-color: rgba(0, 0, 0, 0)"] {
        background: #ffffff !important;
        background-color: #ffffff !important;
    }

    /* But only if it's inside a fixed modal with search */
    body > div[style*="position: fixed"]:has(input) [style*="background: transparent"],
    body > div[style*="position: fixed"]:has(input) [style*="background-color: transparent"] {
        background: #ffffff !important;
        background-color: #ffffff !important;
    }

</style>

<script>
    // Aggressive Wirechat Modal Styling
    (function() {
        'use strict';
        
        function findAndStyleModal() {
            // Method 1: Find by fixed position + search input
            const allElements = document.querySelectorAll('*');
            let modalFound = false;
            
            allElements.forEach(element => {
                const computedStyle = window.getComputedStyle(element);
                const isFixed = computedStyle.position === 'fixed';
                const hasSearch = element.querySelector('input[type="search"], input[placeholder*="Search"], input[placeholder*="search"], input[placeholder*="Type"]');
                
                if (isFixed && hasSearch && !element.dataset.wirechatProcessed) {
                    modalFound = true;
                    element.dataset.wirechatProcessed = 'true';
                    
                    // Style backdrop
                    element.style.setProperty('background', 'rgba(0, 0, 0, 0.5)', 'important');
                    element.style.setProperty('backdrop-filter', 'blur(4px)', 'important');
                    element.style.setProperty('z-index', '9999', 'important');
                    element.style.setProperty('display', 'flex', 'important');
                    element.style.setProperty('align-items', 'center', 'important');
                    element.style.setProperty('justify-content', 'center', 'important');
                    element.style.setProperty('top', '0', 'important');
                    element.style.setProperty('left', '0', 'important');
                    element.style.setProperty('right', '0', 'important');
                    element.style.setProperty('bottom', '0', 'important');
                    
                    // Find content container - try multiple methods
                    let contentContainer = null;
                    
                    // Method 1: Direct child with search
                    Array.from(element.children).forEach(child => {
                        if (child.querySelector && (child.querySelector('input[type="search"]') || child.querySelector('input[placeholder*="Search"]'))) {
                            contentContainer = child;
                        }
                    });
                    
                    // Method 2: Any div with search input
                    if (!contentContainer) {
                        const divsWithSearch = element.querySelectorAll('div');
                        divsWithSearch.forEach(div => {
                            if (div.querySelector('input[type="search"]') || div.querySelector('input[placeholder*="Search"]')) {
                                if (!contentContainer || div.offsetHeight > contentContainer.offsetHeight) {
                                    contentContainer = div;
                                }
                            }
                        });
                    }
                    
                    // Method 3: First div child
                    if (!contentContainer && element.children.length > 0) {
                        contentContainer = element.children[0];
                    }
                    
                    // Force white background on ALL possible containers BEFORE checking contentContainer
                    // First, force on direct children
                    Array.from(element.children).forEach(child => {
                        const computedBg = window.getComputedStyle(child).backgroundColor;
                        const hasSearch = child.querySelector && (child.querySelector('input[type="search"]') || child.querySelector('input[placeholder*="Search"]'));
                        
                        if (hasSearch || computedBg === 'rgba(0, 0, 0, 0)' || computedBg === 'transparent' || !computedBg) {
                            child.style.setProperty('background', '#ffffff', 'important');
                            child.style.setProperty('background-color', '#ffffff', 'important');
                            child.style.setProperty('background-image', 'none', 'important');
                            child.style.setProperty('opacity', '1', 'important');
                            child.style.setProperty('visibility', 'visible', 'important');
                            child.style.setProperty('border-radius', '1.5rem', 'important');
                            child.style.setProperty('padding', '1.5rem', 'important');
                            child.style.setProperty('box-shadow', '0 20px 25px -5px rgba(0, 0, 0, 0.1)', 'important');
                            child.style.setProperty('border', '1px solid #e2e8f0', 'important');
                            if (hasSearch) {
                                child.style.setProperty('max-width', '500px', 'important');
                                child.style.setProperty('width', '90%', 'important');
                            }
                        }
                    });
                    
                    // Also force on any div containing search
                    const allDivs = element.querySelectorAll('div');
                    allDivs.forEach(div => {
                        if (div.querySelector('input[type="search"]') || div.querySelector('input[placeholder*="Search"]')) {
                            div.style.setProperty('background', '#ffffff', 'important');
                            div.style.setProperty('background-color', '#ffffff', 'important');
                            div.style.setProperty('background-image', 'none', 'important');
                            div.style.setProperty('opacity', '1', 'important');
                            div.style.setProperty('visibility', 'visible', 'important');
                        }
                    });
                    
                    if (contentContainer) {
                        // Force white background - always apply, even if already styled
                        contentContainer.style.setProperty('background', '#ffffff', 'important');
                        contentContainer.style.setProperty('background-color', '#ffffff', 'important');
                        contentContainer.style.setProperty('background-image', 'none', 'important');
                        contentContainer.style.setProperty('border-radius', '1.5rem', 'important');
                        contentContainer.style.setProperty('box-shadow', '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)', 'important');
                        contentContainer.style.setProperty('border', '1px solid #e2e8f0', 'important');
                        contentContainer.style.setProperty('max-width', '500px', 'important');
                        contentContainer.style.setProperty('width', '90%', 'important');
                        contentContainer.style.setProperty('max-height', '85vh', 'important');
                        contentContainer.style.setProperty('overflow', 'hidden', 'important');
                        contentContainer.style.setProperty('display', 'flex', 'important');
                        contentContainer.style.setProperty('flex-direction', 'column', 'important');
                        contentContainer.style.setProperty('margin', 'auto', 'important');
                        contentContainer.style.setProperty('position', 'relative', 'important');
                        contentContainer.style.setProperty('padding', '1.5rem', 'important');
                        contentContainer.style.setProperty('opacity', '1', 'important');
                        contentContainer.style.setProperty('visibility', 'visible', 'important');
                        contentContainer.style.setProperty('z-index', '10000', 'important');
                        
                        // Also force white on all direct children that might be transparent
                        Array.from(contentContainer.children).forEach(child => {
                            const childBg = window.getComputedStyle(child).backgroundColor;
                            if (!childBg || childBg === 'rgba(0, 0, 0, 0)' || childBg === 'transparent') {
                                child.style.setProperty('background', '#ffffff', 'important');
                                child.style.setProperty('background-color', '#ffffff', 'important');
                            }
                        });
                        
                        if (!contentContainer.dataset.wirechatStyled) {
                            contentContainer.dataset.wirechatStyled = 'true';
                        }
                        
                        // Style all inputs
                        const allInputs = contentContainer.querySelectorAll('input');
                        allInputs.forEach(input => {
                            if (input.type === 'search' || input.placeholder && input.placeholder.toLowerCase().includes('search')) {
                                input.style.setProperty('width', '100%', 'important');
                                input.style.setProperty('padding', '0.75rem 1rem 0.75rem 2.75rem', 'important');
                                input.style.setProperty('border', '1.5px solid #e2e8f0', 'important');
                                input.style.setProperty('border-radius', '0.75rem', 'important');
                                input.style.setProperty('background', '#f8fafc', 'important');
                                input.style.setProperty('background-color', '#f8fafc', 'important');
                                input.style.setProperty('font-size', '0.9375rem', 'important');
                                input.style.setProperty('color', '#1e293b', 'important');
                                input.style.setProperty('margin-bottom', '1rem', 'important');
                            }
                        });
                        
                        // Style user list items
                        const allDivs = contentContainer.querySelectorAll('div');
                        allDivs.forEach(div => {
                            const hasAvatar = div.querySelector('img[class*="avatar"], img[alt*="avatar"]');
                            const hasStrong = div.querySelector('strong');
                            
                            if (hasAvatar || (hasStrong && div.querySelector('img'))) {
                                div.style.setProperty('display', 'flex', 'important');
                                div.style.setProperty('align-items', 'center', 'important');
                                div.style.setProperty('gap', '0.75rem', 'important');
                                div.style.setProperty('padding', '0.875rem 1rem', 'important');
                                div.style.setProperty('margin', '0.25rem 0', 'important');
                                div.style.setProperty('border-radius', '0.75rem', 'important');
                                div.style.setProperty('cursor', 'pointer', 'important');
                                div.style.setProperty('background', 'transparent', 'important');
                                div.style.setProperty('border', '1px solid transparent', 'important');
                                
                                div.addEventListener('mouseenter', function() {
                                    this.style.setProperty('background', '#f8fafc', 'important');
                                    this.style.setProperty('border-color', '#e2e8f0', 'important');
                                    this.style.setProperty('transform', 'translateX(4px)', 'important');
                                });
                                
                                div.addEventListener('mouseleave', function() {
                                    this.style.setProperty('background', 'transparent', 'important');
                                    this.style.setProperty('border-color', 'transparent', 'important');
                                    this.style.setProperty('transform', 'translateX(0)', 'important');
                                });
                            }
                        });
                    }
                }
            });
            
            return modalFound;
        }
        
        // Run immediately
        findAndStyleModal();
        
        // Run on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', findAndStyleModal);
        }
        
        // Aggressive mutation observer
        const observer = new MutationObserver(function() {
            setTimeout(findAndStyleModal, 50);
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
        
        // Very frequent check as fallback
        setInterval(findAndStyleModal, 200);
        
        // Also listen for click events that might open modals
        document.addEventListener('click', function(e) {
            setTimeout(findAndStyleModal, 100);
            setTimeout(findAndStyleModal, 300);
            setTimeout(findAndStyleModal, 500);
            setTimeout(findAndStyleModal, 800);
        });
        
        // Listen for Livewire/Alpine events
        window.addEventListener('livewire:load', findAndStyleModal);
        window.addEventListener('livewire:update', findAndStyleModal);
        document.addEventListener('alpine:init', findAndStyleModal);
        
        // Ultra-aggressive override - force white background constantly
        const styleOverride = setInterval(function() {
            // Find all fixed position elements
            const allElements = document.querySelectorAll('*');
            allElements.forEach(element => {
                const computedStyle = window.getComputedStyle(element);
                if (computedStyle.position === 'fixed') {
                    const hasSearch = element.querySelector('input[type="search"], input[placeholder*="Search"], input[placeholder*="search"]');
                    
                    if (hasSearch) {
                        // Force backdrop
                        element.style.setProperty('background', 'rgba(0, 0, 0, 0.5)', 'important');
                        element.style.setProperty('backdrop-filter', 'blur(4px)', 'important');
                        element.style.setProperty('z-index', '99999', 'important');
                        
                        // Force white on ALL divs inside
                        const allDivs = element.querySelectorAll('div');
                        allDivs.forEach(div => {
                            const divBg = window.getComputedStyle(div).backgroundColor;
                            const hasSearchInput = div.querySelector('input[type="search"], input[placeholder*="Search"]');
                            
                            // If it has search OR is transparent, make it white
                            if (hasSearchInput || divBg === 'rgba(0, 0, 0, 0)' || divBg === 'transparent' || !divBg) {
                                div.style.setProperty('background', '#ffffff', 'important');
                                div.style.setProperty('background-color', '#ffffff', 'important');
                                div.style.setProperty('background-image', 'none', 'important');
                                div.style.setProperty('opacity', '1', 'important');
                                div.style.setProperty('visibility', 'visible', 'important');
                                
                                // Add styling if it's the main container
                                if (hasSearchInput) {
                                    div.style.setProperty('border-radius', '1.5rem', 'important');
                                    div.style.setProperty('padding', '1.5rem', 'important');
                                    div.style.setProperty('box-shadow', '0 20px 25px -5px rgba(0, 0, 0, 0.1)', 'important');
                                    div.style.setProperty('border', '1px solid #e2e8f0', 'important');
                                    div.style.setProperty('max-width', '500px', 'important');
                                    div.style.setProperty('width', '90%', 'important');
                                }
                            }
                        });
                        
                        // Also check direct children
                        Array.from(element.children).forEach(child => {
                            const childBg = window.getComputedStyle(child).backgroundColor;
                            const hasSearchInput = child.querySelector && (child.querySelector('input[type="search"]') || child.querySelector('input[placeholder*="Search"]'));
                            
                            if (hasSearchInput || childBg === 'rgba(0, 0, 0, 0)' || childBg === 'transparent' || !childBg) {
                                child.style.setProperty('background', '#ffffff', 'important');
                                child.style.setProperty('background-color', '#ffffff', 'important');
                                child.style.setProperty('background-image', 'none', 'important');
                                child.style.setProperty('opacity', '1', 'important');
                                child.style.setProperty('visibility', 'visible', 'important');
                                
                                if (hasSearchInput) {
                                    child.style.setProperty('border-radius', '1.5rem', 'important');
                                    child.style.setProperty('padding', '1.5rem', 'important');
                                    child.style.setProperty('box-shadow', '0 20px 25px -5px rgba(0, 0, 0, 0.1)', 'important');
                                    child.style.setProperty('border', '1px solid #e2e8f0', 'important');
                                    child.style.setProperty('max-width', '500px', 'important');
                                    child.style.setProperty('width', '90%', 'important');
                                }
                            }
                        });
                    }
                }
            });
        }, 50);
    })();
    
    /* ============================================
       Back Button - Inject beside "Chats" Header
       ============================================ */
    (function() {
        let attempts = 0;
        const maxAttempts = 20;
        
        function createBackButton() {
            attempts++;
            
            // Check if button already exists
            if (document.querySelector('.wirechat-back-button')) {
                return;
            }
            
            // Only add button if we're on a WireChat page
            if (!window.location.pathname.includes('/chats')) {
                return;
            }
            
            // Try multiple strategies to find the "Chats" header
            let targetElement = null;
            let titleElement = null;
            
            // Strategy 1: Find element containing "Chats" text
            const allElements = document.querySelectorAll('*');
            for (let el of allElements) {
                if (el.textContent && el.textContent.trim().toLowerCase().includes('chats') && 
                    (el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || 
                     el.classList.toString().includes('title') || el.classList.toString().includes('header'))) {
                    titleElement = el;
                    targetElement = el.parentElement;
                    break;
                }
            }
            
            // Strategy 2: Find by class names
            if (!targetElement) {
                const header = document.querySelector('[class*="header"]') ||
                              document.querySelector('header') ||
                              document.querySelector('[class*="title"]');
                if (header) {
                    targetElement = header;
                    titleElement = header.querySelector('h1, h2, h3, span, div, p') || header.firstElementChild;
                }
            }
            
            // Strategy 3: Find any h1/h2/h3
            if (!targetElement) {
                const headings = document.querySelectorAll('h1, h2, h3');
                for (let heading of headings) {
                    if (heading.textContent && heading.textContent.trim().toLowerCase().includes('chat')) {
                        titleElement = heading;
                        targetElement = heading.parentElement;
                        break;
                    }
                }
            }
            
            // Strategy 4: Find sidebar header area
            if (!targetElement) {
                const sidebar = document.querySelector('aside') || 
                               document.querySelector('[class*="sidebar"]') ||
                               document.querySelector('[class*="chats"]');
                if (sidebar) {
                    const headerInSidebar = sidebar.querySelector('header, [class*="header"], h1, h2, h3');
                    if (headerInSidebar) {
                        targetElement = headerInSidebar;
                        titleElement = headerInSidebar.querySelector('h1, h2, h3, span, div') || headerInSidebar.firstElementChild;
                    }
                }
            }
            
            if (!targetElement) {
                if (attempts < maxAttempts) {
                    setTimeout(createBackButton, 200);
                }
                return;
            }
            
            // Create back button
            const backButton = document.createElement('a');
            backButton.className = 'wirechat-back-button';
            backButton.href = document.referrer && document.referrer.includes(window.location.hostname) 
                ? document.referrer 
                : '/admin';
            const backText = @json(__('app.back'));
            backButton.innerHTML = `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>${backText}</span>
            `;
            
            // Add click handler
            backButton.addEventListener('click', function(e) {
                e.preventDefault();
                const referrer = document.referrer;
                if (referrer && referrer.includes(window.location.hostname)) {
                    window.location.href = referrer;
                } else {
                    window.location.href = '/admin';
                }
            });
            
            // Insert button before the title element or at the start of target
            if (titleElement && titleElement.parentNode === targetElement) {
                targetElement.insertBefore(backButton, titleElement);
            } else if (targetElement.firstChild) {
                targetElement.insertBefore(backButton, targetElement.firstChild);
            } else {
                targetElement.appendChild(backButton);
            }
        }
        
        // Create button on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                attempts = 0;
                createBackButton();
            });
        } else {
            attempts = 0;
            createBackButton();
        }
        
        // Also try after delays to catch dynamically loaded content
        setTimeout(function() { attempts = 0; createBackButton(); }, 300);
        setTimeout(function() { attempts = 0; createBackButton(); }, 800);
        setTimeout(function() { attempts = 0; createBackButton(); }, 1500);
        setTimeout(function() { attempts = 0; createBackButton(); }, 2500);
        
        // Watch for route changes (Livewire/Alpine)
        window.addEventListener('livewire:load', function() { attempts = 0; createBackButton(); });
        window.addEventListener('livewire:navigate', function() { attempts = 0; createBackButton(); });
        window.addEventListener('livewire:update', function() { attempts = 0; createBackButton(); });
        
        // Watch for DOM changes
        const observer = new MutationObserver(function() {
            if (!document.querySelector('.wirechat-back-button') && attempts < maxAttempts) {
                setTimeout(function() { attempts = 0; createBackButton(); }, 100);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    })();
</script>
