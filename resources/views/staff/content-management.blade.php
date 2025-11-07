@extends('layout.staff.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/content-management.css') }}">
<style>
/* Announcement Form - Compact Design */
.announcement-image-container {
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

/* Drag & Drop highlight */
.announcement-image-container.drag-over {
    border-color: #2196F3 !important;
    box-shadow: 0 0 0 3px rgba(33,150,243,0.15) !important;
    background: #f0f8ff !important;
}

.announcement-image-container:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

.date-time-section {
    border: 1px solid #e0e0e0;
    background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    transition: all 0.3s ease;
}

.date-time-section:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

.form-control-sm, .form-select-sm {
    border-radius: 6px;
    border: 1px solid #ddd;
    transition: all 0.2s ease;
}

.form-control-sm:focus, .form-select-sm:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.form-label.small {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.date-time-section h6 {
    font-size: 0.9rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #667eea;
    margin-bottom: 0.75rem;
}

.form-check-switch .form-check-input {
    cursor: pointer;
    width: 2.5rem;
    height: 1.25rem;
}

.form-check-switch .form-check-label {
    cursor: pointer;
    user-select: none;
}

/* Compact spacing */
.row.g-2 > * {
    padding-left: calc(var(--bs-gutter-x) * 0.25);
    padding-right: calc(var(--bs-gutter-x) * 0.25);
}

.row.g-3 > * {
    padding-left: calc(var(--bs-gutter-x) * 0.5);
    padding-right: calc(var(--bs-gutter-x) * 0.5);
}

/* Dark mode support */
[data-theme="dark"] .announcement-image-container {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .announcement-image-container:hover {
    border-color: #667eea !important;
}

[data-theme="dark"] .date-time-section {
    background: linear-gradient(to bottom, #1e293b 0%, #0f172a 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .date-time-section:hover {
    border-color: #667eea !important;
}

[data-theme="dark"] .date-time-section h6 {
    color: #60a5fa !important;
    border-bottom-color: #667eea !important;
}

[data-theme="dark"] .form-control-sm,
[data-theme="dark"] .form-select-sm {
    background-color: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-control-sm:focus,
[data-theme="dark"] .form-select-sm:focus {
    background-color: var(--dm-card-bg, #1e293b) !important;
    border-color: #667eea !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* New Announcement Modal - Blue Icon */
.new-announcement-icon-wrapper {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4) !important;
    animation: bluePulse 2s infinite;
}

@keyframes bluePulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 12px 30px rgba(59, 130, 246, 0.6);
    }
}

.new-announcement-icon-wrapper i {
    color: white !important;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .new-announcement-icon-wrapper {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.5) !important;
}

/* Ticker Section - Improved Layout */
.ticker-controls-section {
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.ticker-controls-section:hover {
    border-color: #667eea;
    background: #f8f9fa !important;
}

.ticker-preview-section {
    margin-top: 0.5rem;
}

.ticker-preview-box {
    transition: all 0.3s ease;
    min-height: 38px;
    display: flex;
    align-items: center;
}

.ticker-preview-box:hover {
    background: rgba(255, 193, 7, 0.15) !important;
    border-color: rgba(255, 193, 7, 0.4) !important;
}

.form-check-switch .form-check-input {
    cursor: pointer;
}

.form-check-switch .form-check-label {
    cursor: pointer;
    user-select: none;
}

[data-theme="dark"] .ticker-controls-section {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .ticker-controls-section:hover {
    border-color: #667eea !important;
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .ticker-preview-box {
    background: rgba(255, 193, 7, 0.1) !important;
    border-color: rgba(255, 193, 7, 0.2) !important;
}

[data-theme="dark"] .ticker-preview-box:hover {
    background: rgba(255, 193, 7, 0.15) !important;
    border-color: rgba(255, 193, 7, 0.3) !important;
}

[data-theme="dark"] .ticker-preview-box span {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Dark mode for cards with bg-white headers */
[data-theme="dark"] .card {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .card-header.bg-white {
    background: var(--dm-card-bg, #1e293b) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .card-header.bg-white h5 {
    color: #60a5fa !important;
}

[data-theme="dark"] .card-body {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Dark mode for placeholder images */
[data-theme="dark"] .placeholder-image.bg-white {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

/* Dark mode for mail preview boxes */
[data-theme="dark"] .mail-preview.bg-white {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Dark mode for multiple patient items */
[data-theme="dark"] .multiple-patient-item.bg-white {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .multiple-patient-item.bg-white:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

/* FORCE FIXED LAYOUT - HIGHEST PRIORITY */
    html {
        overflow-y: scroll !important;
        width: 100vw !important;
        overflow-x: hidden !important;
    }

    body, body.admin-body {
        overflow: hidden !important;
        height: 100vh !important;
        max-height: 100vh !important;
        width: 100% !important;
        position: relative !important;
    }

    .navigation-bar-container,
    aside.navigation-bar-container {
        width: 80px !important;
        min-width: 80px !important;
        max-width: 80px !important;
        flex: 0 0 80px !important;
        flex-shrink: 0 !important;
        flex-grow: 0 !important;
        flex-basis: 80px !important;
        position: relative !important;
        z-index: 100 !important;
        font-size: 16px !important;
        transition: width 0.3s ease, min-width 0.3s ease, max-width 0.3s ease, flex-basis 0.3s ease !important;
    }

    /* Expanded state on hover */
    .navigation-bar-container:hover,
    aside.navigation-bar-container:hover {
        width: 260px !important;
        min-width: 260px !important;
        max-width: 260px !important;
        flex-basis: 260px !important;
    }

    .navigation-bar-container *,
    aside.navigation-bar-container * {
        font-size: inherit !important;
    }

    .navigation-bar-container .logo a,
    aside.navigation-bar-container .logo a {
        font-size: 1.1rem !important;
    }

    .navigation-bar-container .logo i,
    aside.navigation-bar-container .logo i {
        font-size: 1.5rem !important;
    }

    .navigation-bar-container .navigation-bar li,
    aside.navigation-bar-container .navigation-bar li {
        font-size: 16px !important;
    }

    main {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        flex: 1 1 auto !important;
        width: calc(100vw - 80px) !important;
        max-width: calc(100vw - 80px) !important;
        position: relative !important;
        transition: width 0.3s ease, max-width 0.3s ease !important;
    }

    /* Adjust main content when sidebar is hovered */
    body:has(.navigation-bar-container:hover) main,
    body:has(aside.navigation-bar-container:hover) main {
        width: calc(100vw - 260px) !important;
        max-width: calc(100vw - 260px) !important;
    }

    .container-fluid {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    /* FIX NAVIGATION BUTTONS - Collapsed state (icons only) */
    .navigation-bar-container .nav-item-link,
    .navigation-bar-container .nav-logout-btn,
    aside.navigation-bar-container .nav-item-link,
    aside.navigation-bar-container .nav-logout-btn {
        width: calc(100% - 1rem) !important;
        min-width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        height: auto !important;
        min-height: 40px !important;
        max-height: 40px !important;
        padding: 0.5rem 0.5rem !important;
        margin: 0 0.5rem !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
        transition: background-color 0.3s ease, color 0.3s ease, padding 0.3s ease, justify-content 0.3s ease !important;
        justify-content: center !important;
    }

    /* Expanded state on hover */
    .navigation-bar-container:hover .nav-item-link,
    .navigation-bar-container:hover .nav-logout-btn,
    aside.navigation-bar-container:hover .nav-item-link,
    aside.navigation-bar-container:hover .nav-logout-btn {
        padding: 0.5rem 0.875rem !important;
        justify-content: flex-start !important;
    }

    .navigation-bar-container .nav-item-link:hover,
    .navigation-bar-container .nav-logout-btn:hover,
    aside.navigation-bar-container .nav-item-link:hover,
    aside.navigation-bar-container .nav-logout-btn:hover {
        width: calc(100% - 1rem) !important;
        min-width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        height: auto !important;
        min-height: 40px !important;
        max-height: 40px !important;
        padding: 0.5rem 0.875rem !important;
        margin: 0 0.5rem !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .nav-menu-list li.active .nav-item-link,
    .navigation-bar-container .nav-menu-list li.active .nav-logout-btn,
    aside.navigation-bar-container .nav-menu-list li.active .nav-item-link,
    aside.navigation-bar-container .nav-menu-list li.active .nav-logout-btn {
        width: calc(100% - 1rem) !important;
        min-width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        height: auto !important;
        min-height: 40px !important;
        max-height: 40px !important;
        padding: 0.5rem 0.875rem !important;
        margin: 0 0.5rem !important;
        transform: none !important;
        scale: 1 !important;
        box-shadow: none !important;
    }

    .navigation-bar-container .nav-item-link:active,
    .navigation-bar-container .nav-item-link:focus,
    .navigation-bar-container .nav-item-link:focus-visible,
    .navigation-bar-container .nav-logout-btn:active,
    .navigation-bar-container .nav-logout-btn:focus,
    .navigation-bar-container .nav-logout-btn:focus-visible,
    aside.navigation-bar-container .nav-item-link:active,
    aside.navigation-bar-container .nav-item-link:focus,
    aside.navigation-bar-container .nav-item-link:focus-visible,
    aside.navigation-bar-container .nav-logout-btn:active,
    aside.navigation-bar-container .nav-logout-btn:focus,
    aside.navigation-bar-container .nav-logout-btn:focus-visible {
        width: calc(100% - 1rem) !important;
        min-width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        height: auto !important;
        min-height: 40px !important;
        max-height: 40px !important;
        padding: 0.5rem 0.875rem !important;
        margin: 0 0.5rem !important;
        transform: none !important;
        scale: 1 !important;
        outline: none !important;
        border: none !important;
    }

    .navigation-bar-container .nav-icon-wrapper,
    aside.navigation-bar-container .nav-icon-wrapper {
        width: 28px !important;
        min-width: 28px !important;
        max-width: 28px !important;
        height: 28px !important;
        min-height: 28px !important;
        max-height: 28px !important;
        transform: none !important;
        scale: 1 !important;
        box-sizing: border-box !important;
    }

    .navigation-bar-container .nav-item-link:hover .nav-icon-wrapper,
    .navigation-bar-container .nav-logout-btn:hover .nav-icon-wrapper,
    aside.navigation-bar-container .nav-item-link:hover .nav-icon-wrapper,
    aside.navigation-bar-container .nav-logout-btn:hover .nav-icon-wrapper {
        width: 28px !important;
        min-width: 28px !important;
        max-width: 28px !important;
        height: 28px !important;
        min-height: 28px !important;
        max-height: 28px !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .nav-menu-list li.active .nav-icon-wrapper,
    aside.navigation-bar-container .nav-menu-list li.active .nav-icon-wrapper {
        width: 28px !important;
        min-width: 28px !important;
        max-width: 28px !important;
        height: 28px !important;
        min-height: 28px !important;
        max-height: 28px !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .nav-icon-wrapper i,
    aside.navigation-bar-container .nav-icon-wrapper i {
        font-size: 0.9rem !important;
        transform: none !important;
        scale: 1 !important;
        display: inline-block !important;
        line-height: 1 !important;
    }

    .navigation-bar-container .nav-item-link:hover .nav-icon-wrapper i,
    .navigation-bar-container .nav-logout-btn:hover .nav-icon-wrapper i,
    aside.navigation-bar-container .nav-item-link:hover .nav-icon-wrapper i,
    aside.navigation-bar-container .nav-logout-btn:hover .nav-icon-wrapper i {
        font-size: 0.9rem !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .nav-menu-list li.active .nav-icon-wrapper i,
    aside.navigation-bar-container .nav-menu-list li.active .nav-icon-wrapper i {
        font-size: 0.9rem !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .nav-item-text,
    aside.navigation-bar-container .nav-item-text {
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        flex: 1 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        opacity: 0 !important;
        max-width: 0 !important;
        transition: opacity 0.3s ease, max-width 0.3s ease !important;
    }

    /* Show text on hover */
    .navigation-bar-container:hover .nav-item-text,
    aside.navigation-bar-container:hover .nav-item-text {
        opacity: 1 !important;
        max-width: 200px !important;
    }

    .navigation-bar-container .nav-menu-list li.active .nav-item-text,
    aside.navigation-bar-container .nav-menu-list li.active .nav-item-text {
        font-size: 0.8rem !important;
        font-weight: 600 !important;
    }

    /* FIX SECTION LABELS - Collapsed state (icons only) */
    .navigation-bar-container .nav-section-label,
    aside.navigation-bar-container .nav-section-label {
        display: flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        padding: 0.375rem 0.5rem !important;
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        margin-bottom: 0.125rem !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        min-height: auto !important;
        max-height: auto !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
        transition: padding 0.3s ease, justify-content 0.3s ease !important;
        justify-content: center !important;
    }

    /* Expanded state on hover */
    .navigation-bar-container:hover .nav-section-label,
    aside.navigation-bar-container:hover .nav-section-label {
        padding: 0.375rem 1rem !important;
        justify-content: flex-start !important;
    }

    .navigation-bar-container .nav-section-label:hover,
    aside.navigation-bar-container .nav-section-label:hover {
        display: flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        padding: 0.375rem 1rem !important;
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .nav-section-label:active,
    .navigation-bar-container .nav-section-label:focus,
    .navigation-bar-container .nav-section-label:focus-visible,
    aside.navigation-bar-container .nav-section-label:active,
    aside.navigation-bar-container .nav-section-label:focus,
    aside.navigation-bar-container .nav-section-label:focus-visible {
        display: flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        padding: 0.375rem 1rem !important;
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        transform: none !important;
        scale: 1 !important;
        outline: none !important;
        border: none !important;
    }

    .navigation-bar-container .nav-section-label i,
    aside.navigation-bar-container .nav-section-label i {
        font-size: 0.7rem !important;
        opacity: 0.8 !important;
        width: auto !important;
        min-width: auto !important;
        max-width: auto !important;
        height: auto !important;
        transform: none !important;
        scale: 1 !important;
        display: inline-block !important;
        line-height: 1 !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
        transition: margin 0.3s ease !important;
    }

    /* Show margin on hover */
    .navigation-bar-container:hover .nav-section-label i,
    aside.navigation-bar-container:hover .nav-section-label i {
        margin-right: 0.5rem !important;
    }

    .navigation-bar-container .nav-section-label:hover i,
    aside.navigation-bar-container .nav-section-label:hover i {
        font-size: 0.7rem !important;
        opacity: 0.8 !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .nav-section-label span,
    aside.navigation-bar-container .nav-section-label span {
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        white-space: nowrap !important;
        overflow: visible !important;
        text-overflow: clip !important;
        flex: 0 0 auto !important;
        transform: none !important;
        scale: 1 !important;
        opacity: 0 !important;
        max-width: 0 !important;
        transition: opacity 0.3s ease, max-width 0.3s ease !important;
    }

    /* Show section label text on hover */
    .navigation-bar-container:hover .nav-section-label span,
    aside.navigation-bar-container:hover .nav-section-label span {
        opacity: 1 !important;
        max-width: 200px !important;
    }

    .navigation-bar-container .nav-section-label:hover span,
    aside.navigation-bar-container .nav-section-label:hover span {
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        transform: none !important;
        scale: 1 !important;
    }

    /* FIX USER PROFILE SECTION - FRED CALUAG - NO SIZE CHANGES */
    .navigation-bar-container .user-profile-section,
    aside.navigation-bar-container .user-profile-section {
        padding: 0.75rem !important;
        flex-shrink: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .user-profile-link,
    aside.navigation-bar-container .user-profile-link {
        display: flex !important;
        align-items: center !important;
        gap: 0.625rem !important;
        padding: 0.5rem !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
        transition: background-color 0.3s ease, padding 0.3s ease, justify-content 0.3s ease !important;
        justify-content: center !important;
    }

    /* Expanded state on hover */
    .navigation-bar-container:hover .user-profile-link,
    aside.navigation-bar-container:hover .user-profile-link {
        justify-content: flex-start !important;
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }

    .navigation-bar-container .user-profile-link:hover,
    aside.navigation-bar-container .user-profile-link:hover {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .user-profile-avatar,
    aside.navigation-bar-container .user-profile-avatar {
        flex-shrink: 0 !important;
        width: 40px !important;
        min-width: 40px !important;
        max-width: 40px !important;
        height: 40px !important;
        min-height: 40px !important;
        max-height: 40px !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .user-initials-avatar,
    aside.navigation-bar-container .user-initials-avatar {
        width: 40px !important;
        min-width: 40px !important;
        max-width: 40px !important;
        height: 40px !important;
        min-height: 40px !important;
        max-height: 40px !important;
        font-size: 0.95rem !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
    }

    .navigation-bar-container .user-profile-info,
    aside.navigation-bar-container .user-profile-info {
        flex: 1 !important;
        min-width: 0 !important;
        width: auto !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
        opacity: 0 !important;
        max-width: 0 !important;
        overflow: hidden !important;
        transition: opacity 0.3s ease, max-width 0.3s ease !important;
    }

    /* Show user profile info on hover */
    .navigation-bar-container:hover .user-profile-info,
    aside.navigation-bar-container:hover .user-profile-info {
        opacity: 1 !important;
        max-width: calc(100% - 50px) !important;
    }

    .navigation-bar-container .user-profile-name,
    aside.navigation-bar-container .user-profile-name {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
        line-height: 1.2 !important;
        margin-bottom: 0.1rem !important;
    }

    .navigation-bar-container .user-profile-role,
    aside.navigation-bar-container .user-profile-role {
        font-size: 0.7rem !important;
        font-weight: 400 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        transform: none !important;
        scale: 1 !important;
        line-height: 1.2 !important;
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 text-dark fw-bold">Content Management</h1>
        </div>
    </div>

    <!-- Announcement Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2">
                    <h5 class="mb-0 text-primary fw-bold" style="font-size: 1.1rem;">Update Announcement</h5>
                    <a href="{{ route('staff-announcement-archives') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-archive me-1"></i>View Archives
                    </a>
                </div>
                <div class="card-body p-3">
                    <form id="announcementForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <!-- Image Upload - Compact Sidebar -->
                            <div class="col-lg-3 col-md-4">
                                <div class="announcement-image-container bg-light rounded p-2">
                                    <div class="image-preview mb-2" id="imagePreview">
                                        @if($announcement && $announcement->image_path)
                                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="Announcement" class="img-fluid rounded shadow-sm w-100" style="height: 180px; object-fit: cover;">
                                        @else
                                            <div class="placeholder-image d-flex align-items-center justify-content-center bg-white rounded shadow-sm border" style="height: 180px;">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" id="announcementImage" name="image" accept="image/*" class="d-none">
                                    <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="document.getElementById('announcementImage').click()">
                                        <i class="bi bi-upload me-1"></i>Change Photo
                                    </button>
                                    <small class="text-muted d-block text-center" style="font-size: 0.75rem;">
                                        <i class="bi bi-info-circle me-1"></i>3840 x 2000 px
                                    </small>
                                </div>
                            </div>

                            <!-- Announcement Details - Main Content -->
                            <div class="col-lg-9 col-md-8">
                                <!-- Basic Information -->
                                <div class="row g-2 mb-3">
                                    <div class="col-12">
                                        <label for="announcementTitle" class="form-label fw-bold small mb-1">Heading <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="announcementTitle" name="title"
                                               value="{{ $announcement->title ?? '' }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="announcementSubheading" class="form-label fw-bold small mb-1">Subheading <span class="text-muted" style="font-size: 0.75rem;">(Optional)</span></label>
                                        <input type="text" class="form-control form-control-sm" id="announcementSubheading" name="subheading"
                                               value="{{ $announcement->subheading ?? '' }}" placeholder="e.g., Our Clinic is closed for 3 days and will resume by November 6">
                                    </div>
                                </div>

                                <!-- Date & Time Section - Grouped -->
                                <div class="date-time-section bg-light rounded p-3 mb-3">
                                    <h6 class="mb-2 fw-bold text-primary small d-flex align-items-center">
                                        <i class="bi bi-calendar3 me-2"></i>Date & Time Schedule
                                    </h6>
                                    <div class="row g-2">
                                        <!-- Date Range -->
                                        <div class="col-md-6">
                                            <label for="dateStart" class="form-label fw-semibold small mb-1">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm" id="dateStart" name="date_start"
                                                   value="{{ $announcement && $announcement->date_start ? $announcement->date_start->format('Y-m-d') : '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dateEnd" class="form-label fw-semibold small mb-1">End Date <span class="text-muted" style="font-size: 0.75rem;">(Optional)</span></label>
                                            <input type="date" class="form-control form-control-sm" id="dateEnd" name="date_end"
                                                   value="{{ $announcement && $announcement->date_end ? $announcement->date_end->format('Y-m-d') : '' }}">
                                        </div>

                                        <!-- Time Duration Toggle -->
                                        <div class="col-12">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="isWholeDay" name="is_whole_day" value="1"
                                                       {{ ($announcement->is_whole_day ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold small" for="isWholeDay">
                                                    <i class="bi bi-calendar-day me-1"></i>Whole Day Event
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Time Range -->
                                        <div id="timeRangeContainer" style="{{ ($announcement->is_whole_day ?? false) ? 'display:none;' : '' }}" class="col-12">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label for="timeStart" class="form-label fw-semibold small mb-1">Start Time</label>
                                                    <input type="time" class="form-control form-control-sm" id="timeStart" name="time_start"
                                                           value="{{ $announcement && $announcement->time_start ? \Carbon\Carbon::parse($announcement->time_start)->format('H:i') : '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="timeEnd" class="form-label fw-semibold small mb-1">End Time</label>
                                                    <input type="time" class="form-control form-control-sm" id="timeEnd" name="time_end"
                                                           value="{{ $announcement && $announcement->time_end ? \Carbon\Carbon::parse($announcement->time_end)->format('H:i') : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="announcementContent" class="form-label fw-bold small mb-1">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-sm" id="announcementContent" name="content"
                                              rows="5" required>{{ $announcement->content ?? '' }}</textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-sm px-3">
                                        <i class="bi bi-check-circle me-1"></i>Publish
                                    </button>
                                    <button type="button" id="btnNewAnnouncement" class="btn btn-success btn-sm px-3">
                                        <i class="bi bi-plus-circle me-1"></i>Add Another
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Header Ticker Section -->
    <div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-primary fw-bold">Top Header Ticker Notification</h5>
            </div>
            <div class="card-body">
                <form id="tickerForm">
                    @csrf
                    <!-- Ticker Message Input Section -->
                    <div class="mb-3">
                        <label for="tickerText" class="form-label fw-bold small mb-1 d-flex align-items-center">
                            <i class="bi bi-megaphone-fill me-2 text-primary"></i>Ticker Message
                        </label>
                        <input type="text" class="form-control form-control-sm" id="tickerText" name="ticker_text"
                               value="{{ $announcement->ticker_text ?? '🔰 Write a ticker for this new announcement! 🔰' }}" required>
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                            <i class="bi bi-info-circle me-1"></i>This message will scroll across the top of the patient portal
                        </small>
                    </div>

                    <!-- Controls Section -->
                    <div class="ticker-controls-section bg-light rounded p-2 mb-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6 col-lg-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="showTicker" name="show_ticker"
                                           {{ ($announcement->show_ticker ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="showTicker">
                                        Display Ticker on Portal
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-5 text-end">
                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                    <i class="bi bi-check-circle me-1"></i>Update Ticker
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="ticker-preview-section">
                        <label class="form-label fw-bold small mb-2 d-flex align-items-center">
                            <i class="bi bi-eye-fill me-2 text-info"></i>Live Preview
                        </label>
                        <div class="ticker-preview-box bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded p-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-megaphone-fill text-warning me-2"></i>
                                <span id="tickerPreview" class="small text-dark fw-medium">{{ $announcement->ticker_text ?? '🔰 Write a ticker for this new announcement! 🔰' }}</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Services Section (Compact Grid Table - st-*) -->
<div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-bold">Service Management</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="bi bi-plus-circle me-1"></i>Add Service
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="st-table" role="table" aria-label="Services">
                        <div class="st-row st-head" role="row">
                            <div class="st-cell text-center" style="width:60px" role="columnheader">ID</div>
                            <div class="st-cell text-center" style="width:120px" role="columnheader">Icon</div>
                            <div class="st-cell" role="columnheader">Service</div>
                            <div class="st-cell text-center" style="width:120px" role="columnheader">Duration</div>
                            <div class="st-cell" role="columnheader">Description</div>
                            <div class="st-cell text-center" style="width:170px" role="columnheader">Action</div>
                        </div>
                        @forelse($services as $index => $service)
                        <div class="st-row" role="row">
                            <div class="st-cell text-center" role="cell">{{ $index + 1 }}</div>
                            <div class="st-cell text-center" role="cell">
                                <div class="st-icon-wrap">
                                    <div class="st-icon-box">
                                        @php $ic = $service->icon_class; @endphp
                                        @if($ic && \Illuminate\Support\Str::startsWith($ic,'uploaded:'))
                                            <img src="{{ asset('storage/' . \Illuminate\Support\Str::after($ic,'uploaded:')) }}" alt="icon" class="st-icon-img">
                                        @else
                                            <i class="bi {{ $ic ?: 'bi-gear' }}"></i>
                                        @endif
                                    </div>
                                    <button type="button" class="st-btn-change" onclick="changeIcon({{ $service->id }})">Change</button>
                                </div>
                            </div>
                            <div class="st-cell" role="cell">{{ $service->service_name }}</div>
                            <div class="st-cell text-center" role="cell">{{ $service->default_duration_minutes ?? '—' }} mins.</div>
                            <div class="st-cell" role="cell">{{ $service->description }}</div>
                            <div class="st-cell text-center" role="cell">
                                <div class="d-inline-flex gap-2">
                                    <button type="button" class="btn-edit btn-icon" aria-label="Edit" title="Edit" onclick="editService({{ $service->id }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn-delete btn-icon" aria-label="Delete" title="Delete" onclick="deleteService({{ $service->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="st-row">
                            <div class="st-cell text-center" style="grid-column:1/-1">No services available</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Mail Settings Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary fw-bold">Patient Mail Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tabs -->
                        <div class="col-md-2">
                            <div class="nav flex-column nav-pills" id="mail-tabs" role="tablist">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#send-email"
                                        type="button" role="tab">
                                    <i class="bi bi-send me-1"></i>Send Email
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#initial-confirmation"
                                        type="button" role="tab">Initial Confirmation</button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#reminders"
                                        type="button" role="tab">Reminders</button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#cancellation"
                                        type="button" role="tab">Cancellation</button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#rescheduling"
                                        type="button" role="tab">Rescheduling</button>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="col-md-10">
                            <div class="tab-content">
                                <!-- Send Email Tab -->
                                <div class="tab-pane fade show active" id="send-email" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0">
                                        <i class="bi bi-envelope-fill me-2 text-primary"></i>Send Email to Patient
                                    </h6>

                                        <!-- Receiver Type Tabs -->
                                        <ul class="nav nav-pills" id="receiverTypeTabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="single-tab" data-bs-toggle="pill" data-bs-target="#single-receiver" type="button" role="tab">
                                                    <i class="bi bi-person me-1"></i>Single
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="multiple-tab" data-bs-toggle="pill" data-bs-target="#multiple-receiver" type="button" role="tab">
                                                    <i class="bi bi-people me-1"></i>Multiple
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Tab Content -->
                                    <div class="tab-content" id="receiverTypeTabContent">
                                        <!-- Single Receiver Tab -->
                                        <div class="tab-pane fade show active" id="single-receiver" role="tabpanel">
                                            <div class="row g-3">
                                                <!-- Left Column: Selection & Template -->
                                                <div class="col-xl-7 col-lg-8">
                                                    <div class="card border-0 shadow-sm email-section-card">
                                                        <div class="card-header bg-white border-bottom py-2">
                                                            <h6 class="mb-0 fw-bold">
                                                                <i class="bi bi-person-check me-2 text-primary"></i>Patient & Appointment
                                                            </h6>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <div class="row g-2">
                                        <div class="col-md-6">
                                                                    <label for="singleSelectPatient" class="form-label fw-semibold small">Patient <span class="text-danger">*</span></label>
                                                                    <select class="form-select form-select-sm" id="singleSelectPatient" required>
                                                    <option value="">-- Choose a patient --</option>
                                                </select>
                                                                    <small class="text-muted small">Only patients with appointments</small>
                                            </div>
                                                                <div class="col-md-6">
                                                                    <label for="singleSelectAppointment" class="form-label fw-semibold small">Appointment <span class="text-danger">*</span></label>
                                                                    <select class="form-select form-select-sm" id="singleSelectAppointment" required disabled>
                                                    <option value="">-- Select patient first --</option>
                                                </select>
                                            </div>
                                                            </div>
                                            </div>
                                        </div>

                                                    <div class="card border-0 shadow-sm email-section-card mt-3">
                                                        <div class="card-header bg-white border-bottom py-2">
                                                            <h6 class="mb-0 fw-bold">
                                                                <i class="bi bi-envelope-check me-2 text-primary"></i>Email Template
                                                    </h6>
                                                    </div>
                                                        <div class="card-body p-3">
                                                            <div class="mb-3">
                                                                <textarea class="form-control" id="singleMailTemplate" rows="5"
                                                                          placeholder="Enter your email template...">{{ $mailTemplates['follow_up']->content ?? 'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment for %service%.' }}</textarea>
                                                                <small class="text-muted small">Placeholders: <code>%firstname%</code>, <code>%datetime%</code>, <code>%service%</code></small>
                                                </div>
                                                            <div class="d-grid">
                                                                <button type="button" class="btn btn-primary" id="btnSendSingleEmail" disabled>
                                                                    <i class="bi bi-send-fill me-2"></i>Send Email
                                                                </button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                                <!-- Right Column: Preview -->
                                                <div class="col-xl-5 col-lg-4">
                                                    <div class="card border-0 shadow-sm email-preview-card sticky-top">
                                                        <div class="card-header email-preview-header border-0 py-2">
                                                            <h6 class="mb-0 fw-bold">
                                                                <i class="bi bi-eye me-2"></i>Preview
                                                    </h6>
                                                </div>
                                                        <div class="card-body p-3" style="min-height: 400px;">
                                                            <div id="singleEmailPreview">
                                                                <div class="text-center py-4">
                                                                    <i class="bi bi-envelope" style="font-size: 2.5rem; opacity: 0.3; color: #6c757d;"></i>
                                                                    <p class="text-muted mt-3 mb-0 small">Select patient and appointment<br>to see preview</p>
                                                    </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <!-- Multiple Receiver Tab -->
                                        <div class="tab-pane fade" id="multiple-receiver" role="tabpanel">
                                            <div class="row g-3">
                                                <!-- Left Column: Patient Selection & Template -->
                                                <div class="col-xl-7 col-lg-8">
                                                    <div class="card border-0 shadow-sm email-section-card">
                                                        <div class="card-header bg-white border-bottom py-2 d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0 fw-bold">
                                                                <i class="bi bi-people me-2 text-primary"></i>Select Patients
                                                            </h6>
                                                            <span class="badge bg-primary" id="multiplePatientCount">0 selected</span>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <div class="row g-2 mb-3">
                                                                <div class="col-md-8">
                                                                    <label for="multipleFilterPatients" class="form-label fw-semibold small">Filter</label>
                                                                    <select class="form-select form-select-sm" id="multipleFilterPatients">
                                                                        <option value="all">All Patients with Appointments</option>
                                                    <option value="today">Appointments Today</option>
                                                    <option value="tomorrow">Appointments Tomorrow</option>
                                                    <option value="this_week">Appointments This Week</option>
                                                                        <option value="rescheduled">Rescheduled</option>
                                                                        <option value="pending">Pending/Unconfirmed</option>
                                                                        <option value="completed">Completed (Last 7 Days)</option>
                                                                        <option value="upcoming">All Upcoming</option>
                                                </select>
                                            </div>
                                                                <div class="col-md-4 d-flex align-items-end">
                                                                    <div class="form-check w-100">
                                                                        <input class="form-check-input" type="checkbox" id="multipleSelectAll">
                                                                        <label class="form-check-label small" for="multipleSelectAll">Select All</label>
                                                                    </div>
                                                                </div>
                                            </div>

                                                            <div>
                                                                <label class="form-label fw-semibold small mb-2">Patients List</label>
                                                                <div class="border rounded patients-list-container">
                                                                    <div id="multiplePatientsList" class="p-2">
                                                                        <div class="text-center py-3">
                                                                            <i class="bi bi-people" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                                                            <p class="text-muted mt-2 mb-0 small">Select a filter to load patients</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                            </div>
                                        </div>

                                                    <div class="card border-0 shadow-sm email-section-card mt-3">
                                                        <div class="card-header bg-white border-bottom py-2">
                                                    <h6 class="mb-0 fw-bold">
                                                                <i class="bi bi-envelope-check me-2 text-primary"></i>Email Template
                                                    </h6>
                                                </div>
                                                        <div class="card-body p-3">
                                                            <div class="mb-3">
                                                                <textarea class="form-control" id="multipleMailTemplate" rows="5"
                                                                          placeholder="Enter your email template...">{{ $mailTemplates['follow_up']->content ?? 'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment for %service%.' }}</textarea>
                                                                <small class="text-muted small">Placeholders: <code>%firstname%</code>, <code>%datetime%</code>, <code>%service%</code></small>
                                                    </div>
                                                            <div class="d-grid">
                                                                <button type="button" class="btn btn-success" id="btnSendMultipleEmail" disabled>
                                                                    <i class="bi bi-send-fill me-2"></i>Send Email
                                                                </button>
                                                </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Column: Preview -->
                                                <div class="col-xl-5 col-lg-4">
                                                    <div class="card border-0 shadow-sm email-preview-card sticky-top">
                                                        <div class="card-header email-preview-header border-0 py-2">
                                                            <h6 class="mb-0 fw-bold">
                                                                <i class="bi bi-eye me-2"></i>Preview
                                                            </h6>
                                                        </div>
                                                        <div class="card-body p-3" style="min-height: 400px;">
                                                            <div id="multipleEmailPreview">
                                                                <div class="text-center py-4">
                                                                    <i class="bi bi-envelope" style="font-size: 2.5rem; opacity: 0.3; color: #6c757d;"></i>
                                                                    <p class="text-muted mt-3 mb-0 small">Select patients to see<br>email preview</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Initial Confirmation -->
                                <div class="tab-pane fade" id="initial-confirmation" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="initial-confirmation-template" rows="4"
                                                  data-type="initial_confirmation">{{ $mailTemplates['initial_confirmation']->content ?? 'Good Day! %firstname%, you have a schedule appointment on %datetime% with Dr. Justin Valera regarding on your %service% treatment.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('initial_confirmation')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="initial-confirmation-preview">
                                            Good Day! <span class="text-primary fw-bold">Angel Cuadernal</span>, you have a schedule appointment on
                                            <span class="text-primary fw-bold">April 15, 2025 3:00 PM</span> with Dr. Justin Valera regarding on your
                                            <span class="text-primary fw-bold">Flexible Dentures</span> treatment.
                                        </p>
                                    </div>
                                </div>

                                <!-- Reminders -->
                                <div class="tab-pane fade" id="reminders" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="reminders-template" rows="4"
                                                  data-type="reminder">{{ $mailTemplates['reminder']->content ?? 'Reminder: %firstname%, you have an appointment on %datetime% with Dr. Justin Valera for %service%.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('reminder')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="reminders-preview">
                                            Reminder: <span class="text-primary fw-bold">Angel Cuadernal</span>, you have an appointment on
                                            <span class="text-primary fw-bold">April 15, 2025 3:00 PM</span> with Dr. Justin Valera for
                                            <span class="text-primary fw-bold">Flexible Dentures</span>.
                                        </p>
                                    </div>
                                </div>

                                <!-- Cancellation -->
                                <div class="tab-pane fade" id="cancellation" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="cancellation-template" rows="4"
                                                  data-type="cancellation">{{ $mailTemplates['cancellation']->content ?? 'Dear %firstname%, your appointment on %datetime% has been cancelled.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('cancellation')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="cancellation-preview">
                                            Dear <span class="text-primary fw-bold">Angel Cuadernal</span>, your appointment on
                                            <span class="text-primary fw-bold">April 15, 2025 3:00 PM</span> has been cancelled.
                                        </p>
                                    </div>
                                </div>

                                <!-- Rescheduling -->
                                <div class="tab-pane fade" id="rescheduling" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="rescheduling-template" rows="4"
                                                  data-type="rescheduling">{{ $mailTemplates['rescheduling']->content ?? 'Hello %firstname%, your appointment has been rescheduled to %datetime%.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('rescheduling')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="rescheduling-preview">
                                            Hello <span class="text-primary fw-bold">Angel Cuadernal</span>, your appointment has been rescheduled to
                                            <span class="text-primary fw-bold">April 20, 2025 2:00 PM</span>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Add New Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addServiceForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="service_name" class="form-label fw-semibold">
                                <i class="bi bi-card-heading text-primary me-2"></i>Service Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="service_name" name="service_name"
                                   placeholder="e.g., Teeth Cleaning, Root Canal, etc." required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="icon_class" class="form-label fw-semibold">
                                <i class="bi bi-emoji-smile text-primary me-2"></i>Service Icon
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <img id="selected_icon_img" class="d-none theme-adapt" style="width:24px;height:24px;object-fit:contain;" alt="icon">
                                    <i class="bi bi-gear fs-5" id="selected_icon_preview"></i>
                                </span>
                                <input type="text" class="form-control" id="icon_class" name="icon_class"
                                       placeholder="Click 'Choose Icon' or 'Upload Icon'" readonly>
                                <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('icon_class', 'selected_icon_preview')">
                                    <i class="bi bi-grid-3x3-gap me-1"></i>Choose Icon
                                </button>
                                <button type="button" class="btn btn-outline-secondary ms-2" id="btnUploadIconAdd">
                                    <i class="bi bi-upload me-1"></i>Upload Icon
                                </button>
                                <input type="file" id="icon_upload" name="icon_upload" class="d-none" accept="image/*,.ico">
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Select an icon that represents this service
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label fw-semibold">
                                <i class="bi bi-currency-peso text-success me-2"></i>Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light fw-bold">₱</span>
                                <input type="number" class="form-control" id="price" name="price"
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Enter the service price in Philippine Peso
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="default_duration_minutes" class="form-label fw-semibold">
                                <i class="bi bi-clock text-info me-2"></i>Duration (minutes) <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="default_duration_minutes" name="default_duration_minutes" required>
                                <option value="">Select duration</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                                <option value="150">2.5 hours</option>
                                <option value="180">3 hours</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Typical appointment duration
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-file-text text-secondary me-2"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                      placeholder="Describe what this service includes, benefits, and any important details..." required></textarea>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>This will be shown to patients when booking
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Add Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editServiceForm">
                @csrf
                <input type="hidden" id="edit_service_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_service_name" class="form-label fw-semibold">
                                <i class="bi bi-card-heading text-primary me-2"></i>Service Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="edit_service_name" name="service_name"
                                   placeholder="e.g., Teeth Cleaning, Root Canal, etc." required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_icon_class" class="form-label fw-semibold">
                                <i class="bi bi-emoji-smile text-primary me-2"></i>Service Icon
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <img id="edit_selected_icon_img" class="d-none theme-adapt" style="width:24px;height:24px;object-fit:contain;" alt="icon">
                                    <i class="bi bi-gear fs-5" id="edit_selected_icon_preview"></i>
                                </span>
                                <input type="text" class="form-control" id="edit_icon_class" name="icon_class"
                                       placeholder="Click 'Choose Icon' or 'Upload Icon'" readonly>
                                <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('edit_icon_class', 'edit_selected_icon_preview')">
                                    <i class="bi bi-grid-3x3-gap me-1"></i>Choose Icon
                                </button>
                                <button type="button" class="btn btn-outline-secondary ms-2" id="btnUploadIcon">
                                    <i class="bi bi-upload me-1"></i>Upload Icon
                                </button>
                                <input type="file" id="edit_icon_upload" name="icon_upload" class="d-none" accept="image/*,.ico">
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Select an icon that represents this service
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_price" class="form-label fw-semibold">
                                <i class="bi bi-currency-peso text-success me-2"></i>Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light fw-bold">₱</span>
                                <input type="number" class="form-control" id="edit_price" name="price"
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Enter the service price in Philippine Peso
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_default_duration_minutes" class="form-label fw-semibold">
                                <i class="bi bi-clock text-info me-2"></i>Duration (minutes) <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="edit_default_duration_minutes" name="default_duration_minutes" required>
                                <option value="">Select duration</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                                <option value="150">2.5 hours</option>
                                <option value="180">3 hours</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Typical appointment duration
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_description" class="form-label fw-semibold">
                                <i class="bi bi-file-text text-secondary me-2"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="edit_description" name="description" rows="4"
                                      placeholder="Describe what this service includes, benefits, and any important details..." required></textarea>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>This will be shown to patients when booking
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle me-2"></i>Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- New Announcement Confirmation Modal -->
<div class="modal fade" id="newAnnouncementConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-modal-content">
            <div class="modal-body text-center p-4">
                <div class="delete-icon-wrapper new-announcement-icon-wrapper mb-3">
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 4rem; color: white;"></i>
                </div>
                <h5 class="delete-modal-title mb-2">Create New Announcement</h5>
                <p class="delete-modal-message mb-4">Are you sure you want to create a new announcement?<br>The current announcement will be archived.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmNewAnnouncementBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Service Confirmation Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content delete-modal-content">
            <div class="modal-body text-center p-4">
                <div class="delete-icon-wrapper mb-3">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                </div>
                <h5 class="delete-modal-title mb-2">Delete Service</h5>
                <p class="delete-modal-message mb-4">Are you sure you want to delete this service?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-delete" id="confirmDeleteServiceBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose an Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="iconSearchInput" placeholder="Search icons...">
                <div class="icon-picker-grid" id="iconPickerGrid">
                    <!-- Icons will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-picker-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 10px;
    max-height: 400px;
    overflow-y: auto;
}

.icon-picker-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.icon-picker-item:hover {
    border-color: #2196F3;
    background: #e3f2fd;
    transform: scale(1.05);
}

.icon-picker-item.selected {
    border-color: #2196F3;
    background: #2196F3;
    color: white;
}

.icon-picker-item i {
    font-size: 2rem;
    margin-bottom: 5px;
}

.icon-picker-item span {
    font-size: 0.7rem;
    text-align: center;
    word-break: break-word;
}
</style>

<script>
const servicesData = @json($services);

// Variables for new announcement confirmation
let pendingNewAnnouncementData = null;
let pendingNewAnnouncementBtn = null;

// New Announcement Button Handler
document.getElementById('btnNewAnnouncement')?.addEventListener('click', function() {
    const form = document.getElementById('announcementForm');
    const formData = new FormData(form);

    // Validate form
    const title = document.getElementById('announcementTitle').value;
    const content = document.getElementById('announcementContent').value;

    if (!title || !content) {
        showToast('Please fill in title and content', 'error');
        return;
    }

    // Store form data and button reference for later use
    pendingNewAnnouncementData = formData;
    pendingNewAnnouncementBtn = this;

    // Show confirmation modal instead of browser confirm
    const confirmModal = new bootstrap.Modal(document.getElementById('newAnnouncementConfirmModal'));
    confirmModal.show();
});

// Confirm New Announcement Handler
document.getElementById('confirmNewAnnouncementBtn')?.addEventListener('click', function() {
    if (!pendingNewAnnouncementData || !pendingNewAnnouncementBtn) return;

    // Hide modal
    const confirmModal = bootstrap.Modal.getInstance(document.getElementById('newAnnouncementConfirmModal'));
    confirmModal.hide();

    // Set default ticker message for new announcement
    pendingNewAnnouncementData.set('ticker_text', '🔰 Write a ticker for this new announcement! 🔰');

    // Disable button and show loading
    const btn = pendingNewAnnouncementBtn;
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

    fetch('/staff/content-management/announcement/new', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: pendingNewAnnouncementData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('New announcement created successfully! Old announcement has been archived.', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('Error creating new announcement: ' + (data.message || 'Unknown error'), 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error creating new announcement', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    })
    .finally(() => {
        // Clear pending data
        pendingNewAnnouncementData = null;
        pendingNewAnnouncementBtn = null;
    });
});

// Announcement Form Handler (Edit only - does not archive)
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/staff/content-management/announcement', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Announcement updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error updating announcement: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating announcement', 'error');
    });
});

// Whole Day Toggle
document.getElementById('isWholeDay')?.addEventListener('change', function() {
    const timeContainer = document.getElementById('timeRangeContainer');
    if (this.checked) {
        timeContainer.style.display = 'none';
        document.getElementById('timeStart').value = '';
        document.getElementById('timeEnd').value = '';
    } else {
        timeContainer.style.display = 'block';
    }
});

// Date End validation - ensure it's not before date_start
document.getElementById('dateEnd')?.addEventListener('change', function() {
    const dateStart = document.getElementById('dateStart').value;
    if (dateStart && this.value && this.value < dateStart) {
        alert('End date cannot be before start date');
        this.value = '';
    }
});

// Image preview
document.getElementById('announcementImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML =
                `<img src="${e.target.result}" alt="Preview" class="img-fluid rounded shadow-sm w-100" style="height: 180px; object-fit: cover;">`;
        };
        reader.readAsDataURL(file);
    }
});

// Drag & Drop support for announcement image
(function() {
    const dropZone = document.querySelector('.announcement-image-container');
    const fileInput = document.getElementById('announcementImage');
    if (!dropZone || !fileInput) return;

    const preventDefaults = (e) => { e.preventDefault(); e.stopPropagation(); };

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter','dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false);
    });
    ['dragleave','drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if (!dt || !dt.files || dt.files.length === 0) return;
        const file = dt.files[0];
        if (!file.type.startsWith('image/')) return; // only images

        const transfer = new DataTransfer();
        transfer.items.add(file);
        fileInput.files = transfer.files;
        fileInput.dispatchEvent(new Event('change'));
    }, false);
})();

// Ticker Form Handler
document.getElementById('tickerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        ticker_text: formData.get('ticker_text'),
        show_ticker: document.getElementById('showTicker').checked
    };

    fetch('/staff/content-management/ticker', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Ticker notification updated successfully!', 'success');
        } else {
            showToast('Error updating ticker: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating ticker', 'error');
    });
});

// Ticker preview update
document.getElementById('tickerText').addEventListener('input', function(e) {
    document.getElementById('tickerPreview').textContent = e.target.value || '🔰 Write a ticker for this new announcement! 🔰';
});

// Add Service Form Handler
document.getElementById('addServiceForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/staff/content-management/service', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Service added successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('addServiceModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error adding service: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding service', 'error');
    });
});

// Edit Service Function
function editService(id) {
    const service = servicesData.find(s => s.id === id);
    if (!service) return;

    document.getElementById('edit_service_id').value = service.id;
    document.getElementById('edit_service_name').value = service.service_name;
    document.getElementById('edit_icon_class').value = service.icon_class || '';
    document.getElementById('edit_price').value = service.price;
    document.getElementById('edit_default_duration_minutes').value = service.default_duration_minutes;
    document.getElementById('edit_description').value = service.description;

    // Update icon preview
    const iconPreview = document.getElementById('edit_selected_icon_preview');
    const iconImg = document.getElementById('edit_selected_icon_img');
    iconImg.classList.add('d-none');
    iconPreview.classList.remove('d-none');
    if (service.icon_class && service.icon_class.startsWith('uploaded:')) {
        iconImg.src = `/storage/${service.icon_class.replace('uploaded:','')}`;
        iconImg.classList.remove('d-none');
        iconPreview.classList.add('d-none');
    } else if (service.icon_class) {
        iconPreview.className = service.icon_class + ' fs-5';
    } else {
        iconPreview.className = 'bi bi-gear fs-5';
    }

    new bootstrap.Modal(document.getElementById('editServiceModal')).show();
}

// Edit Service Form Handler
document.getElementById('editServiceForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const serviceId = document.getElementById('edit_service_id').value;
    const formData = new FormData(this);

    fetch(`/staff/content-management/service/${serviceId}`, {
        method: 'POST',
        headers: {
            'X-HTTP-Method-Override': 'PUT',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Service updated successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('editServiceModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error updating service: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating service', 'error');
    });
});

// Delete Service Function
let serviceToDelete = null;

function deleteService(id) {
    serviceToDelete = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteServiceModal'));
    deleteModal.show();
}

// Confirm Delete Handler
document.getElementById('confirmDeleteServiceBtn').addEventListener('click', function() {
    if (!serviceToDelete) return;

    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteServiceModal'));
    deleteModal.hide();

    fetch(`/staff/content-management/service/${serviceToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Service deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error deleting service: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error deleting service', 'error');
    })
    .finally(() => {
        serviceToDelete = null;
    });
});

// Toast Notification Function
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `custom-toast custom-toast-${type}`;
    toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;

    document.getElementById('toastContainer').appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Save Mail Template Function
function saveMailTemplate(type) {
    const textarea = document.querySelector(`[data-type="${type}"]`);
    const content = textarea.value;

    fetch(`/staff/content-management/mail-template/${type}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            subject: `Appointment ${type.replace('_', ' ')}`,
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Mail template updated successfully!', 'success');
        } else {
            showToast('Error updating mail template: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating mail template', 'error');
    });
}

// Update template preview in tab (live preview as you type)
function updateTemplatePreview(textarea) {
    const type = textarea.dataset.type;
    const content = textarea.value;

    // Map template types to preview IDs
    const previewMap = {
        'initial_confirmation': 'initial-confirmation-preview',
        'reminder': 'reminders-preview',
        'cancellation': 'cancellation-preview',
        'rescheduling': 'rescheduling-preview',
        'follow_up': 'follow-ups-preview'
    };

    const previewId = previewMap[type];
    if (!previewId) return;

    const previewElement = document.getElementById(previewId);
    if (!previewElement) return;

    // Replace placeholders with sample data
    const sampleContent = content
        .replace(/%firstname%/g, '<span class="text-primary fw-bold">Angel Cuadernal</span>')
        .replace(/%datetime%/g, '<span class="text-primary fw-bold">April 15, 2025 3:00 PM</span>')
        .replace(/%rescheduledtime%/g, '<span class="text-primary fw-bold">April 20, 2025 2:00 PM</span>')
        .replace(/%service%/g, '<span class="text-primary fw-bold">Flexible Dentures</span>');

    previewElement.innerHTML = sampleContent;
}

// Upload Icon handlers (delegated for staff modal)
document.addEventListener('click', function(e) {
    const btn = e.target.closest('#btnUploadIcon');
    const btnAdd = e.target.closest('#btnUploadIconAdd');

    if (btn) {
        // Edit Service Modal
        const modal = btn.closest('.modal');
        const input = modal ? modal.querySelector('#edit_icon_upload') : document.getElementById('edit_icon_upload');
        if (input) input.click();
    } else if (btnAdd) {
        // Add Service Modal
        const modal = btnAdd.closest('.modal');
        const input = modal ? modal.querySelector('#icon_upload') : document.getElementById('icon_upload');
        if (input) input.click();
    }
});

document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'edit_icon_upload') {
        // Edit Service Modal
        const file = e.target.files[0];
        if (!file) return;
        const modal = e.target.closest('.modal');
        const img = modal ? modal.querySelector('#edit_selected_icon_img') : document.getElementById('edit_selected_icon_img');
        const icon = modal ? modal.querySelector('#edit_selected_icon_preview') : document.getElementById('edit_selected_icon_preview');
        const cls = modal ? modal.querySelector('#edit_icon_class') : document.getElementById('edit_icon_class');
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        icon.classList.add('d-none');
        if (cls) cls.value = 'uploaded:pending';
    } else if (e.target && e.target.id === 'icon_upload') {
        // Add Service Modal
        const file = e.target.files[0];
        if (!file) return;
        const modal = e.target.closest('.modal');
        const img = modal ? modal.querySelector('#selected_icon_img') : document.getElementById('selected_icon_img');
        const icon = modal ? modal.querySelector('#selected_icon_preview') : document.getElementById('selected_icon_preview');
        const cls = modal ? modal.querySelector('#icon_class') : document.getElementById('icon_class');
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        icon.classList.add('d-none');
        if (cls) cls.value = 'uploaded:pending';
    }
});

// Change Icon Function
function changeIcon(id) {
    const service = servicesData.find(s => s.id === id);
    if (!service) return;

    // Open the edit modal and focus on icon field
    editService(id);
    setTimeout(() => {
        const iconInput = document.getElementById('edit_icon_class');
        if (iconInput) {
            iconInput.focus();
            iconInput.select();
        }
    }, 300);
}

// ============================================
// SEND EMAIL TAB FUNCTIONALITY - REVISED
// ============================================

// Global variables
let allPatientsData = [];
let singleSelectedAppointment = null;
let multipleLoadedPatients = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAllPatients();

    // Initialize Single Receiver tab
    initializeSingleReceiverTab();

    // Initialize Multiple Receiver tab
    initializeMultipleReceiverTab();
});

// Load all patients with appointments
function loadAllPatients() {
    fetch('/staff/content-management/patients-with-appointments')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allPatientsData = data.patients;
                populateSinglePatientDropdown();
            }
        })
        .catch(error => {
            console.error('Error loading patients:', error);
            showToast('Error loading patients', 'error');
        });
}

// ============================================
// SINGLE RECEIVER TAB
// ============================================

function initializeSingleReceiverTab() {
    // Patient selection
    document.getElementById('singleSelectPatient')?.addEventListener('change', function() {
        const patientId = this.value;
        const appointmentSelect = document.getElementById('singleSelectAppointment');

    if (!patientId) {
        appointmentSelect.innerHTML = '<option value="">-- Select patient first --</option>';
        appointmentSelect.disabled = true;
            updateSinglePreview();
            updateSingleSendButton();
        return;
    }

        // Load appointments
    fetch(`/staff/content-management/patient-appointments/${patientId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                    populateSingleAppointmentDropdown(data.appointments);
                appointmentSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error loading appointments:', error);
            showToast('Error loading appointments', 'error');
        });
});

    // Appointment selection
    document.getElementById('singleSelectAppointment')?.addEventListener('change', function() {
        if (this.value) {
            const option = this.options[this.selectedIndex];
            singleSelectedAppointment = JSON.parse(option.dataset.appointment);
        } else {
            singleSelectedAppointment = null;
        }
        updateSinglePreview();
        updateSingleSendButton();
    });

    // Template editor - live preview
    document.getElementById('singleMailTemplate')?.addEventListener('input', function() {
        updateSinglePreview();
    });

    // Send button
    document.getElementById('btnSendSingleEmail')?.addEventListener('click', function() {
        sendSingleEmail();
    });
}

function populateSinglePatientDropdown() {
    const select = document.getElementById('singleSelectPatient');
    if (!select) return;

    select.innerHTML = '<option value="">-- Choose a patient --</option>';
    allPatientsData.forEach(patient => {
        const option = document.createElement('option');
        option.value = patient.id;
        option.textContent = `${patient.name} (${patient.email}) - ${patient.appointments_count} appointment(s)`;
        select.appendChild(option);
    });
}

function populateSingleAppointmentDropdown(appointments) {
    const select = document.getElementById('singleSelectAppointment');
    select.innerHTML = '<option value="">-- Choose an appointment --</option>';

    appointments.forEach(apt => {
        const option = document.createElement('option');
        option.value = apt.id;
        const date = new Date(apt.start_datetime.replace(' ', 'T'));
        const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const timeStr = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        const rescheduledBadge = apt.rescheduled_at ? ' (Rescheduled)' : '';
        option.textContent = `${dateStr} ${timeStr} - ${apt.service?.service_name || 'No service'}${rescheduledBadge}`;
        option.dataset.appointment = JSON.stringify(apt);
        select.appendChild(option);
    });
}

function updateSinglePreview() {
    const previewDiv = document.getElementById('singleEmailPreview');
    if (!previewDiv) return;

    if (!singleSelectedAppointment) {
        previewDiv.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-envelope" style="font-size: 2.5rem; opacity: 0.3; color: #6c757d;"></i>
                <p class="text-muted mt-3 mb-0 small">Select patient and appointment<br>to see preview</p>
            </div>
        `;
        return;
    }

    const template = document.getElementById('singleMailTemplate').value ||
                     'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment for %service%.';

    const patient = singleSelectedAppointment.patient;
    const firstName = patient.info?.first_name || patient.name.split(' ')[0];
    const date = new Date(singleSelectedAppointment.start_datetime.replace(' ', 'T'));
    const dateStr = date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    const timeStr = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    const datetime = `${dateStr} ${timeStr}`;
    const service = singleSelectedAppointment.service?.service_name || 'your appointment';

    let preview = template
        .replace(/%firstname%/g, `<strong class="text-primary">${firstName}</strong>`)
        .replace(/%datetime%/g, `<strong class="text-primary">${datetime}</strong>`)
        .replace(/%service%/g, `<strong class="text-primary">${service}</strong>`);

    previewDiv.innerHTML = `
        <div class="email-preview-content">
            <div class="mb-2 pb-2 border-bottom small">
                <div class="text-muted mb-1"><strong>From:</strong> JValera Dental Clinic</div>
                <div class="text-muted mb-1"><strong>To:</strong> ${patient.email}</div>
                <div class="text-muted"><strong>Subject:</strong> Follow Up Appointment</div>
        </div>
            <div class="email-body p-2 bg-light rounded mt-2">
                <h6 class="text-primary fw-bold mb-2 small">JValera Dental Clinic</h6>
                <div class="email-text small">${preview}</div>
            </div>
        </div>
    `;
}

function updateSingleSendButton() {
    const btn = document.getElementById('btnSendSingleEmail');
    if (!btn) return;

    const patientId = document.getElementById('singleSelectPatient')?.value;
    const appointmentId = document.getElementById('singleSelectAppointment')?.value;
    btn.disabled = !(patientId && appointmentId);
}

function sendSingleEmail() {
    const appointmentId = document.getElementById('singleSelectAppointment')?.value;
    const template = document.getElementById('singleMailTemplate')?.value;

    if (!appointmentId || !singleSelectedAppointment) {
        showToast('Please select patient and appointment', 'error');
        return;
    }

    const btn = document.getElementById('btnSendSingleEmail');
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    // Save template before sending (always save to ensure latest version is used)
    saveMailTemplate('follow_up', template).then(() => {
        performSendSingleEmail(appointmentId, btn, originalText);
    }).catch(() => {
        // Continue even if save fails
        performSendSingleEmail(appointmentId, btn, originalText);
    });
}

function performSendSingleEmail(appointmentId, btn, originalText) {
    fetch('/staff/content-management/send-patient-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            appointment_id: appointmentId,
            email_type: 'follow_up'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const patient = singleSelectedAppointment.patient;
            showToast(`Email sent successfully to ${patient.email}`, 'success');
        } else {
            showToast('Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error sending email', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        updateSingleSendButton();
    });
}

// ============================================
// MULTIPLE RECEIVER TAB
// ============================================

function initializeMultipleReceiverTab() {
    // Filter change
    document.getElementById('multipleFilterPatients')?.addEventListener('change', function() {
        loadMultiplePatients(this.value);
    });

    // Select all checkbox
    document.getElementById('multipleSelectAll')?.addEventListener('change', function(e) {
        document.querySelectorAll('.multiple-patient-checkbox').forEach(cb => {
            cb.checked = e.target.checked;
        });
        updateMultiplePreview();
        updateMultipleSendButton();
        updateMultiplePatientCount();
    });

    // Template editor - live preview
    document.getElementById('multipleMailTemplate')?.addEventListener('input', function() {
        updateMultiplePreview();
    });

    // Send button
    document.getElementById('btnSendMultipleEmail')?.addEventListener('click', function() {
        sendMultipleEmails();
    });

    // Load all patients by default
    loadMultiplePatients('all');
}

function loadMultiplePatients(filter) {
    const container = document.getElementById('multiplePatientsList');

    if (filter === 'all') {
        // Load all patients
        if (allPatientsData.length === 0) {
            container.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span></div>';
            fetch('/staff/content-management/patients-with-appointments')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                        allPatientsData = data.patients;
                        displayMultiplePatientsList(data.patients);
            }
        })
        .catch(error => {
            console.error('Error:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error loading patients</div>';
                });
        } else {
            displayMultiplePatientsList(allPatientsData);
        }
    } else {
        // Load filtered patients
        container.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span></div>';

        fetch(`/staff/content-management/patients-by-situation?situation=${filter}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    multipleLoadedPatients = data.patients;
                    displayMultiplePatientsList(data.patients);
                } else {
            showToast('Error loading patients', 'error');
                    container.innerHTML = '<div class="alert alert-info">No patients found</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error loading patients', 'error');
                container.innerHTML = '<div class="alert alert-danger">Error loading patients</div>';
            });
    }
}

function displayMultiplePatientsList(patients) {
    const container = document.getElementById('multiplePatientsList');

    if (!patients || patients.length === 0) {
        container.innerHTML = `
            <div class="text-center py-3">
                <i class="bi bi-people" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="text-muted mt-2 mb-0 small">No patients found</p>
            </div>
        `;
        updateMultiplePatientCount();
        return;
    }

    // Convert to format we need
    let patientList = [];
    if (patients[0] && patients[0].patient_id) {
        // Already in the right format from API
        patientList = patients;
    } else {
        // Convert from allPatientsData format
        patientList = patients.map(p => ({
            patient_id: p.id,
            patient_name: p.name,
            patient_email: p.email,
            appointments: []
        }));
    }

    multipleLoadedPatients = patientList;

    let html = '';
    patientList.forEach(patient => {
        html += `
            <div class="multiple-patient-item mb-1 p-2 border rounded bg-white hover-shadow">
                <div class="form-check mb-0">
                    <input class="form-check-input multiple-patient-checkbox" type="checkbox"
                           id="mult-patient-${patient.patient_id}"
                                   value="${patient.patient_id}"
                           data-patient-name="${patient.patient_name}"
                           data-patient-email="${patient.patient_email}">
                    <label class="form-check-label w-100 mb-0 cursor-pointer" for="mult-patient-${patient.patient_id}">
                        <div class="fw-semibold small mb-1">${patient.patient_name}</div>
                        <small class="text-muted small">${patient.patient_email}</small>
                    </label>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;

    // Add event listeners
    document.querySelectorAll('.multiple-patient-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateMultiplePreview();
            updateMultipleSendButton();
            updateMultiplePatientCount();
        });
    });

    updateMultiplePatientCount();
}

function updateMultiplePatientCount() {
    const selected = document.querySelectorAll('.multiple-patient-checkbox:checked').length;
    const badge = document.getElementById('multiplePatientCount');
    if (badge) {
        badge.textContent = `${selected} selected`;
    }
}

function updateMultiplePreview() {
    const previewDiv = document.getElementById('multipleEmailPreview');
    if (!previewDiv) return;

    const selectedCheckboxes = document.querySelectorAll('.multiple-patient-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        previewDiv.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-envelope" style="font-size: 2.5rem; opacity: 0.3; color: #6c757d;"></i>
                <p class="text-muted mt-3 mb-0 small">Select patients to see<br>email preview</p>
            </div>
        `;
        return;
    }

    const template = document.getElementById('multipleMailTemplate')?.value ||
                     'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment for %service%.';

    // Use first selected patient for preview
    const firstPatient = selectedCheckboxes[0];
    const firstName = firstPatient.dataset.patientName.split(' ')[0];
    const preview = template
        .replace(/%firstname%/g, `<strong class="text-primary">${firstName}</strong>`)
        .replace(/%datetime%/g, `<strong class="text-primary">Sample Date & Time</strong>`)
        .replace(/%service%/g, `<strong class="text-primary">Sample Service</strong>`);

    previewDiv.innerHTML = `
        <div class="email-preview-content">
            <div class="alert alert-info small mb-2 py-2">
                <i class="bi bi-info-circle me-1"></i>
                Format preview for <strong>${selectedCheckboxes.length}</strong> patient(s)
            </div>
            <div class="mb-2 pb-2 border-bottom small">
                <div class="text-muted mb-1"><strong>From:</strong> JValera Dental Clinic</div>
                <div class="text-muted mb-1"><strong>To:</strong> ${firstPatient.dataset.patientEmail}${selectedCheckboxes.length > 1 ? ` (+${selectedCheckboxes.length - 1})` : ''}</div>
                <div class="text-muted"><strong>Subject:</strong> Follow Up Appointment</div>
            </div>
            <div class="email-body p-2 bg-light rounded mt-2">
                <h6 class="text-primary fw-bold mb-2 small">JValera Dental Clinic</h6>
                <div class="email-text small">${preview}</div>
            </div>
        </div>
    `;
}

function updateMultipleSendButton() {
    const btn = document.getElementById('btnSendMultipleEmail');
    if (!btn) return;

    const selected = document.querySelectorAll('.multiple-patient-checkbox:checked').length;
    btn.disabled = selected === 0;
}

function sendMultipleEmails() {
    const selectedCheckboxes = document.querySelectorAll('.multiple-patient-checkbox:checked');
    const template = document.getElementById('multipleMailTemplate')?.value;

    if (selectedCheckboxes.length === 0) {
        showToast('Please select at least one patient', 'error');
        return;
    }

    // Get appointments for selected patients
    const patientIds = Array.from(selectedCheckboxes).map(cb => parseInt(cb.value));
    const appointmentIds = [];

    // For each selected patient, we need to get their appointments
    Promise.all(patientIds.map(patientId =>
        fetch(`/staff/content-management/patient-appointments/${patientId}`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.appointments.length > 0) {
                    // Use the most recent appointment for each patient
                    const latestAppt = data.appointments[0];
                    appointmentIds.push(latestAppt.id);
                }
            })
            .catch(err => console.error('Error fetching appointments for patient', patientId, err))
    )).then(() => {
    if (appointmentIds.length === 0) {
        showToast('No appointments found for selected patients', 'error');
        return;
    }

        const btn = document.getElementById('btnSendMultipleEmail');
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

        // Save template before sending (always save to ensure latest version is used)
        const savePromise = saveMailTemplate('follow_up', template);

        savePromise.then(() => {
            // Send bulk email
    fetch('/staff/content-management/send-bulk-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            appointment_ids: appointmentIds,
            email_type: 'follow_up'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const successMsg = `Successfully sent ${data.data.success_count} email(s)`;
            showToast(successMsg, 'success');

            if (data.data.failed_count > 0) {
                        showToast(`${data.data.failed_count} email(s) failed to send`, 'warning');
            }
        } else {
                    showToast('Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
                showToast('Error sending emails', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
                updateMultipleSendButton();
    });
});
    });
}

// Helper function to save mail template
function saveMailTemplate(type, content) {
    return fetch(`/staff/content-management/mail-template/${type}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            subject: `Appointment ${type.replace('_', ' ')}`,
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.warn('Template save warning:', data.message);
        }
        return data;
    })
    .catch(error => {
        console.error('Error saving template:', error);
        return { success: false };
    });
}

// ============================================
// ICON PICKER FUNCTIONALITY
// ============================================

// Dental & Medical Icons for Dental Clinic Services
const availableIcons = [
    // === MEDICAL & HEALTH ICONS ===
    'bi-heart-pulse-fill', 'bi-heart-pulse', 'bi-heart-fill', 'bi-heart', 'bi-heart-half',
    'bi-bandaid-fill', 'bi-bandaid', 'bi-capsule-pill', 'bi-capsule',
    'bi-activity', 'bi-clipboard2-pulse-fill', 'bi-clipboard2-pulse',
    'bi-hospital-fill', 'bi-hospital', 'bi-prescription2', 'bi-prescription',
    'bi-clipboard2-check-fill', 'bi-clipboard2-check', 'bi-clipboard2-heart',
    'bi-clipboard2-fill', 'bi-clipboard2', 'bi-clipboard-check', 'bi-clipboard-plus',
    'bi-journal-medical', 'bi-life-preserver',

    // === DENTAL TOOLS & PROCEDURES ===
    'bi-scissors', 'bi-eyedropper', 'bi-thermometer-half', 'bi-thermometer',
    'bi-tools', 'bi-wrench-adjustable-circle-fill', 'bi-gear-fill', 'bi-gear',

    // === QUALITY & CARE SYMBOLS ===
    'bi-star-fill', 'bi-star', 'bi-star-half',
    'bi-award-fill', 'bi-award',
    'bi-trophy-fill', 'bi-trophy',
    'bi-gem', 'bi-diamond-fill', 'bi-diamond',
    'bi-shield-fill-check', 'bi-shield-check', 'bi-shield-fill', 'bi-shield',
    'bi-patch-check-fill', 'bi-patch-check',
    'bi-check-circle-fill', 'bi-check-circle', 'bi-check2-circle',

    // === APPOINTMENTS & SCHEDULING ===
    'bi-calendar-check-fill', 'bi-calendar-check', 'bi-calendar-event-fill', 'bi-calendar-event',
    'bi-calendar-plus-fill', 'bi-calendar-plus', 'bi-calendar-fill', 'bi-calendar',
    'bi-clock-fill', 'bi-clock', 'bi-clock-history', 'bi-alarm-fill', 'bi-alarm',
    'bi-stopwatch-fill', 'bi-stopwatch',

    // === COMMUNICATION & CONTACT ===
    'bi-telephone-fill', 'bi-telephone', 'bi-phone-fill', 'bi-phone',
    'bi-envelope-fill', 'bi-envelope', 'bi-envelope-heart-fill', 'bi-envelope-heart',
    'bi-chat-dots-fill', 'bi-chat-dots', 'bi-chat-fill', 'bi-chat',
    'bi-megaphone-fill', 'bi-megaphone', 'bi-bell-fill', 'bi-bell',

    // === PEOPLE & PATIENTS ===
    'bi-person-fill', 'bi-person', 'bi-person-check-fill', 'bi-person-check',
    'bi-person-hearts', 'bi-person-heart', 'bi-people-fill', 'bi-people',
    'bi-person-badge-fill', 'bi-person-badge',

    // === SMILE & DENTAL AESTHETICS ===
    'bi-emoji-smile-fill', 'bi-emoji-smile', 'bi-emoji-laughing-fill', 'bi-emoji-laughing',
    'bi-brightness-high-fill', 'bi-brightness-high', 'bi-sun-fill', 'bi-sun',
    'bi-magic', 'bi-stars', 'bi-sparkles',

    // === TREATMENT & CARE ===
    'bi-hand-thumbs-up-fill', 'bi-hand-thumbs-up',
    'bi-lightning-charge-fill', 'bi-lightning-charge',
    'bi-droplet-fill', 'bi-droplet', 'bi-water',
    'bi-peace-fill', 'bi-peace',
    'bi-flower1', 'bi-flower2', 'bi-flower3',

    // === PREMIUM & SPECIAL SERVICES ===
    'bi-rocket-takeoff-fill', 'bi-rocket-takeoff', 'bi-rocket-fill', 'bi-rocket',
    'bi-speedometer2', 'bi-speedometer',
    'bi-gift-fill', 'bi-gift',
    'bi-balloon-heart-fill', 'bi-balloon-heart',

    // === DENTAL SPECIALTIES ===
    'bi-grid-3x3-gap-fill', 'bi-grid-3x3-gap', 'bi-grid-fill', 'bi-grid',
    'bi-collection-fill', 'bi-collection',
    'bi-box-seam-fill', 'bi-box-seam',

    // === INFORMATION & NAVIGATION ===
    'bi-info-circle-fill', 'bi-info-circle', 'bi-info-square-fill',
    'bi-question-circle-fill', 'bi-question-circle',
    'bi-exclamation-circle-fill', 'bi-exclamation-circle',
    'bi-arrow-right-circle-fill', 'bi-arrow-right-circle',
    'bi-arrow-clockwise', 'bi-arrow-repeat',

    // === SHAPES FOR DESIGN ===
    'bi-circle-fill', 'bi-circle', 'bi-square-fill', 'bi-square',
    'bi-hexagon-fill', 'bi-hexagon', 'bi-octagon-fill', 'bi-octagon',

    // === USEFUL GENERAL ICONS ===
    'bi-plus-circle-fill', 'bi-plus-circle', 'bi-plus-lg',
    'bi-dash-circle-fill', 'bi-dash-circle',
    'bi-x-circle-fill', 'bi-x-circle',
    'bi-bookmark-fill', 'bi-bookmark', 'bi-bookmark-heart-fill',
    'bi-eye-fill', 'bi-eye',
    'bi-house-heart-fill', 'bi-house-heart', 'bi-house-fill', 'bi-house',
    'bi-shop', 'bi-building-fill', 'bi-building'
];

let currentIconInputId = '';
let currentIconPreviewId = '';
let iconPickerModal = null;

// Initialize icon picker modal
document.addEventListener('DOMContentLoaded', function() {
    iconPickerModal = new bootstrap.Modal(document.getElementById('iconPickerModal'));
    populateIconGrid();

    // Search functionality
    document.getElementById('iconSearchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterIcons(searchTerm);
    });
});

// Open icon picker
function openIconPicker(inputId, previewId) {
    currentIconInputId = inputId;
    currentIconPreviewId = previewId;

    // Clear search
    document.getElementById('iconSearchInput').value = '';
    filterIcons('');

    // Highlight currently selected icon
    const currentIcon = document.getElementById(inputId).value;
    highlightSelectedIcon(currentIcon);

    iconPickerModal.show();
}

// Populate icon grid
function populateIconGrid() {
    const grid = document.getElementById('iconPickerGrid');
    grid.innerHTML = '';

    availableIcons.forEach(iconClass => {
        const iconName = iconClass.replace('bi-', '').replace(/-/g, ' ');
        const item = document.createElement('div');
        item.className = 'icon-picker-item';
        item.dataset.icon = iconClass;
        item.innerHTML = `
            <i class="bi ${iconClass}"></i>
            <span>${iconName}</span>
        `;
        item.addEventListener('click', () => selectIcon(iconClass));
        grid.appendChild(item);
    });
}

// Filter icons
function filterIcons(searchTerm) {
    const items = document.querySelectorAll('.icon-picker-item');
    items.forEach(item => {
        const iconName = item.dataset.icon.toLowerCase();
        if (iconName.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Highlight selected icon
function highlightSelectedIcon(iconClass) {
    document.querySelectorAll('.icon-picker-item').forEach(item => {
        item.classList.remove('selected');
        if (item.dataset.icon === iconClass) {
            item.classList.add('selected');
        }
    });
}

// Select icon
function selectIcon(iconClass) {
    // Update input field
    document.getElementById(currentIconInputId).value = iconClass;

    // Update preview
    const preview = document.getElementById(currentIconPreviewId);
    preview.className = `bi ${iconClass}`;

    // Highlight selected
    highlightSelectedIcon(iconClass);

    // Close modal after short delay
    setTimeout(() => {
        iconPickerModal.hide();
    }, 300);
}

// Update edit service function to update preview
const originalEditService = editService;
editService = function(id) {
    originalEditService(id);

    // Update icon preview in edit modal
    const service = servicesData.find(s => s.id === id);
    if (service && service.icon_class) {
        const preview = document.getElementById('edit_selected_icon_preview');
        if (preview) {
            preview.className = `bi ${service.icon_class}`;
        }
    }
};
</script>

<style>
/* Send Email Section - Improved Layout Styles */
.email-section-card {
    border-radius: 8px;
    transition: all 0.2s ease;
}

.email-section-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
}

.email-preview-card {
    border-radius: 8px;
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 120px);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.email-preview-card .card-body {
    overflow-y: auto;
    flex: 1;
}

.patients-list-container {
    max-height: 280px;
    overflow-y: auto;
    background: #f8f9fa;
}

.patients-list-container::-webkit-scrollbar {
    width: 6px;
}

.patients-list-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.patients-list-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.patients-list-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.multiple-patient-item {
    transition: all 0.2s ease;
    cursor: pointer;
}

.multiple-patient-item:hover {
    background-color: #f0f0f0 !important;
    border-color: #667eea !important;
}

.multiple-patient-item.hover-shadow:hover {
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.15);
}

.cursor-pointer {
    cursor: pointer;
}

.email-preview-card .card-body::-webkit-scrollbar {
    width: 6px;
}

.email-preview-card .card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.email-preview-card .card-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.email-preview-card .card-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .email-preview-card {
        position: relative;
        top: 0;
        margin-top: 1rem;
        max-height: none;
    }
}

@media (max-width: 768px) {
    .email-section-card,
    .email-preview-card {
        margin-bottom: 1rem;
    }

    .email-preview-card {
        position: relative;
        top: 0;
    }

    .patients-list-container {
        max-height: 200px;
    }
}

/* Dark mode support */
[data-theme="dark"] .patients-list-container {
    background: #1e293b;
    border-color: #334155;
}

[data-theme="dark"] .multiple-patient-item {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

[data-theme="dark"] .multiple-patient-item:hover {
    background-color: #334155 !important;
    border-color: #667eea !important;
}

[data-theme="dark"] .email-preview-card .card-body {
    background-color: #1e293b;
}

[data-theme="dark"] .email-section-card .card-body {
    background-color: #1e293b;
}

/* Form controls in dark mode */
[data-theme="dark"] .form-select,
[data-theme="dark"] .form-control {
    background-color: #1e293b;
    border-color: #334155;
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] .form-select:focus,
[data-theme="dark"] .form-control:focus {
    background-color: #1e293b;
    border-color: #667eea;
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] .form-label {
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] code {
    background-color: #334155;
    color: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
}

/* Preview Card Header - Ensure visibility in light mode */
.email-preview-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
}

.email-preview-header h6 {
    color: white !important;
}

.email-preview-header i {
    color: white !important;
}

[data-theme="dark"] .email-preview-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

[data-theme="dark"] .email-preview-header h6 {
    color: white !important;
}

[data-theme="dark"] .email-preview-header i {
    color: white !important;
}
</style>

@endsection

