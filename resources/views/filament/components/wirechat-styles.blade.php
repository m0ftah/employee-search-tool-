@wirechatStyles

<style>
    /* ============================================
       Simple & User-Friendly Wirechat UI
       ============================================ */
    
    :root {
        --wc-primary: #f59e0b;
        --wc-primary-hover: #d97706;
        --wc-primary-light: rgba(245, 158, 11, 0.08);
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
        background: var(--wc-bg) !important;
        border-right: 1px solid var(--wc-border) !important;
        padding: 1rem !important;
        width: 320px !important;
        min-width: 280px !important;
        max-width: 380px !important;
    }

    .dark [wirechat] aside,
    .dark [wirechat] [class*="sidebar"],
    .dark [wirechat] [class*="chats-list"] {
        background: var(--wc-bg-dark) !important;
        border-right-color: var(--wc-border-dark) !important;
    }

    /* Sidebar header - simplified */
    [wirechat] [class*="header"],
    [wirechat] header {
        padding: 0 0 1rem 0 !important;
        margin-bottom: 0.75rem !important;
        border-bottom: 1px solid var(--wc-border) !important;
        background: transparent !important;
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
        width: 100% !important;
        padding: 0.625rem 1rem 0.625rem 2.5rem !important;
        border: 1px solid var(--wc-border) !important;
        border-radius: 0.5rem !important;
        background: var(--wc-surface) !important;
        font-size: 0.875rem !important;
        color: var(--wc-text) !important;
        transition: all 0.15s ease !important;
        margin-bottom: 1rem !important;
    }

    [wirechat] input[type="search"]:focus,
    [wirechat] input[type="text"][placeholder*="Search"]:focus {
        outline: none !important;
        border-color: var(--wc-primary) !important;
        background: var(--wc-bg) !important;
        box-shadow: 0 0 0 3px var(--wc-primary-light) !important;
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
    }

    /* ============================================
       Chat Items - Clean & Clickable
       ============================================ */
    
    /* Chat item list */
    [wirechat] [class*="chat-item"],
    [wirechat] [class*="conversation"],
    [wirechat] a[href*="/chats/"],
    [wirechat] [role="listitem"] {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        padding: 0.75rem !important;
        margin: 0.25rem 0 !important;
        border-radius: 0.75rem !important;
        cursor: pointer !important;
        background: transparent !important;
        border: none !important;
        transition: background-color 0.15s ease !important;
        text-decoration: none !important;
        color: inherit !important;
    }

    /* Hover state - subtle */
    [wirechat] [class*="chat-item"]:hover,
    [wirechat] [class*="conversation"]:hover,
    [wirechat] a[href*="/chats/"]:hover {
        background: var(--wc-surface) !important;
    }

    .dark [wirechat] [class*="chat-item"]:hover,
    .dark [wirechat] [class*="conversation"]:hover,
    .dark [wirechat] a[href*="/chats/"]:hover {
        background: var(--wc-surface-dark) !important;
    }

    /* Active chat - clear indicator */
    [wirechat] [class*="chat-item"][aria-current="page"],
    [wirechat] [class*="active"],
    [wirechat] a[href*="/chats/"][aria-current="page"] {
        background: var(--wc-primary-light) !important;
        border-left: 3px solid var(--wc-primary) !important;
        padding-left: calc(0.75rem - 3px) !important;
    }

    .dark [wirechat] [class*="chat-item"][aria-current="page"],
    .dark [wirechat] [class*="active"] {
        background: rgba(245, 158, 11, 0.15) !important;
    }

    /* ============================================
       Avatars - Simple & Clear
       ============================================ */
    
    [wirechat] img[class*="avatar"],
    [wirechat] [class*="avatar"] img,
    [wirechat] img[alt*="avatar"] {
        width: 44px !important;
        height: 44px !important;
        border-radius: 50% !important;
        border: 2px solid var(--wc-border) !important;
        object-fit: cover !important;
        flex-shrink: 0 !important;
        background: var(--wc-surface) !important;
    }

    .dark [wirechat] img[class*="avatar"],
    .dark [wirechat] [class*="avatar"] img {
        border-color: var(--wc-border-dark) !important;
        background: var(--wc-surface-dark) !important;
    }

    /* Active chat avatar highlight */
    [wirechat] [class*="active"] img[class*="avatar"],
    [wirechat] [aria-current="page"] img[class*="avatar"] {
        border-color: var(--wc-primary) !important;
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
    
    /* Main chat area */
    [wirechat] main,
    [wirechat] [class*="chat-area"],
    [wirechat] [class*="messages"] {
        background: var(--wc-bg) !important;
        padding: 1.5rem 1rem !important;
        flex: 1 !important;
        overflow-y: auto !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.5rem !important;
    }

    .dark [wirechat] main,
    .dark [wirechat] [class*="chat-area"],
    .dark [wirechat] [class*="messages"] {
        background: var(--wc-bg-dark) !important;
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
        background: var(--wc-primary) !important;
        color: white !important;
        padding: 0.625rem 0.875rem !important;
        border-radius: 1rem 1rem 0.25rem 1rem !important;
        max-width: 70% !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        font-size: 0.9375rem !important;
        line-height: 1.5 !important;
        box-shadow: none !important;
    }

    /* Received messages (left) */
    [wirechat] [class*="message"]:not([class*="sent"]):not([class*="own"]) {
        justify-content: flex-start !important;
    }

    [wirechat] [class*="message-bubble"]:not([class*="sent"]):not([class*="own"]) {
        background: var(--wc-surface) !important;
        color: var(--wc-text) !important;
        padding: 0.625rem 0.875rem !important;
        border-radius: 1rem 1rem 1rem 0.25rem !important;
        max-width: 70% !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        font-size: 0.9375rem !important;
        line-height: 1.5 !important;
        border: 1px solid var(--wc-border) !important;
        box-shadow: none !important;
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
        padding: 1rem !important;
        border-top: 1px solid var(--wc-border) !important;
        background: var(--wc-bg) !important;
        position: relative !important;
    }

    .dark [wirechat] footer,
    .dark [wirechat] [class*="footer"],
    .dark [wirechat] [class*="input-area"] {
        background: var(--wc-bg-dark) !important;
        border-top-color: var(--wc-border-dark) !important;
    }

    /* Message input field */
    [wirechat] textarea[placeholder*="message"],
    [wirechat] textarea[placeholder*="Type"],
    [wirechat] textarea[placeholder*="type"],
    [wirechat] [class*="message-input"],
    [wirechat] [class*="input"] {
        width: 100% !important;
        min-height: 44px !important;
        max-height: 120px !important;
        padding: 0.75rem 3rem 0.75rem 1rem !important;
        border: 1px solid var(--wc-border) !important;
        border-radius: 1.25rem !important;
        background: var(--wc-surface) !important;
        font-size: 0.9375rem !important;
        line-height: 1.5 !important;
        color: var(--wc-text) !important;
        resize: none !important;
        transition: all 0.15s ease !important;
        font-family: inherit !important;
    }

    [wirechat] textarea[placeholder*="message"]:focus,
    [wirechat] textarea[placeholder*="Type"]:focus,
    [wirechat] [class*="message-input"]:focus {
        outline: none !important;
        border-color: var(--wc-primary) !important;
        background: var(--wc-bg) !important;
        box-shadow: 0 0 0 3px var(--wc-primary-light) !important;
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

    /* Send button */
    [wirechat] button[type="submit"],
    [wirechat] button[aria-label*="send"],
    [wirechat] button[aria-label*="Send"],
    [wirechat] [class*="send-button"],
    [wirechat] [class*="send"] {
        position: absolute !important;
        right: 1.25rem !important;
        bottom: 1.25rem !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        background: var(--wc-primary) !important;
        border: none !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        box-shadow: none !important;
    }

    [wirechat] button[type="submit"]:hover,
    [wirechat] button[aria-label*="send"]:hover,
    [wirechat] [class*="send-button"]:hover {
        background: var(--wc-primary-hover) !important;
        transform: scale(1.05) !important;
    }

    [wirechat] button[type="submit"]:active {
        transform: scale(0.95) !important;
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
        background: var(--wc-primary) !important;
        color: white !important;
        border-radius: 0.75rem !important;
        padding: 0.125rem 0.5rem !important;
        font-size: 0.6875rem !important;
        font-weight: 600 !important;
        min-width: 1.125rem !important;
        height: 1.125rem !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
    }

    /* ============================================
       Empty State - Welcoming
       ============================================ */
    
    [wirechat] [class*="empty"],
    [wirechat] [class*="welcome"],
    [wirechat] [class*="no-messages"] {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 3rem 2rem !important;
        text-align: center !important;
        color: var(--wc-text-secondary) !important;
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
</style>
