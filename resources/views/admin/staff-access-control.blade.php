@extends('layout.admin.app')
@section('content')

<style>
    .staff-access-control-container {
        padding: 1.5rem;
        max-width: 100%;
    }

    .page-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 1.05rem;
    }

    .staff-list-container {
        display: grid;
        gap: 1.5rem;
    }

    .staff-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }

    .staff-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: #3b82f6;
    }

    .staff-card.active {
        border-color: #3b82f6;
    }

    .staff-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        cursor: pointer;
    }

    .staff-chevron {
        font-size: 1.25rem;
        color: #64748b;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .staff-card.active .staff-chevron {
        transform: rotate(180deg);
    }

    .staff-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .staff-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
    }

    .staff-details h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.25rem 0;
    }

    .staff-details p {
        font-size: 0.9rem;
        color: #64748b;
        margin: 0;
    }


    .access-controls-section {
        display: none;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid #e2e8f0;
    }

    .access-controls-section.active {
        display: block;
    }

    .control-group {
        margin-bottom: 2rem;
    }

    .control-group-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .control-group-title i {
        color: #3b82f6;
    }

    .controls-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.75rem;
    }

    .control-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.625rem 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .control-item:hover {
        background: #f1f5f9;
        border-color: #3b82f6;
    }

    .control-label {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-weight: 500;
        color: #1e293b;
        flex: 1;
        font-size: 0.875rem;
    }

    .control-label i {
        color: #64748b;
        font-size: 0.875rem;
    }

    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        background: #cbd5e1;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .toggle-switch.active {
        background: #3b82f6;
    }

    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        background: white;
        border-radius: 50%;
        top: 3px;
        left: 3px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .toggle-switch.active::after {
        left: 23px;
    }

    .save-controls {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid #e2e8f0;
        gap: 1rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #64748b;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
        color: #475569;
    }

    /* Password Modal */
    .password-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .password-modal.active {
        display: flex;
    }

    .password-modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .password-modal-header {
        margin-bottom: 1.5rem;
    }

    .password-modal-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.5rem 0;
    }

    .password-modal-header p {
        color: #64748b;
        margin: 0;
    }

    .password-input-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .password-input-wrapper input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .password-input-wrapper input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .password-modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-verify {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-verify:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-close-modal {
        background: #e2e8f0;
        color: #64748b;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-close-modal:hover {
        background: #cbd5e1;
        color: #475569;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    /* Floating Toast Styles */
    .floating-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 320px;
        max-width: 500px;
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        opacity: 0;
        transform: translateX(400px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid;
    }

    .floating-toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .floating-toast-success {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
    }

    .floating-toast-error {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
    }

    .toast-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }

    .toast-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .floating-toast-success .toast-icon {
        color: #10b981;
    }

    .floating-toast-error .toast-icon {
        color: #ef4444;
    }

    .toast-message {
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1.5;
    }

    .toast-close-btn {
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
        flex-shrink: 0;
        width: 24px;
        height: 24px;
    }

    .toast-close-btn:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #1e293b;
    }

    .toast-close-btn i {
        font-size: 0.875rem;
    }

    /* Dark Mode Styles */
    [data-theme="dark"] .staff-access-control-container {
        background: var(--dm-bg-primary, #0f172a);
    }

    [data-theme="dark"] .page-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }

    [data-theme="dark"] .staff-card {
        background: var(--dm-card-bg, #1e293b);
        border-color: var(--dm-border-color, #334155);
    }

    [data-theme="dark"] .staff-card:hover {
        border-color: #3b82f6;
    }

    [data-theme="dark"] .staff-card-header {
        border-bottom-color: var(--dm-border-color, #334155);
    }

    [data-theme="dark"] .staff-details h3 {
        color: var(--dm-text-primary, #f1f5f9);
    }

    [data-theme="dark"] .staff-details p {
        color: var(--dm-text-muted, #94a3b8);
    }

    [data-theme="dark"] .access-controls-section {
        border-top-color: var(--dm-border-color, #334155);
    }

    [data-theme="dark"] .control-group-title {
        color: var(--dm-text-primary, #f1f5f9);
    }

    [data-theme="dark"] .control-item {
        background: var(--dm-bg-secondary, #1e293b);
        border-color: var(--dm-border-color, #334155);
    }

    [data-theme="dark"] .control-item:hover {
        background: var(--dm-bg-tertiary, #334155);
        border-color: #3b82f6;
    }

    [data-theme="dark"] .control-label {
        color: var(--dm-text-primary, #f1f5f9);
    }

    [data-theme="dark"] .save-controls {
        border-top-color: var(--dm-border-color, #334155);
    }

    [data-theme="dark"] .password-modal {
        background: rgba(0, 0, 0, 0.75);
    }

    [data-theme="dark"] .password-modal-content {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border: 2px solid rgba(59, 130, 246, 0.3);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(59, 130, 246, 0.1);
    }

    [data-theme="dark"] .password-modal-header h3 {
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .password-modal-header p {
        color: #cbd5e1;
    }

    [data-theme="dark"] .password-input-wrapper input {
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(59, 130, 246, 0.4);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .password-input-wrapper input::placeholder {
        color: #94a3b8;
    }

    [data-theme="dark"] .password-input-wrapper input:focus {
        background: rgba(15, 23, 42, 0.95);
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2), 0 4px 12px rgba(0, 0, 0, 0.4);
        outline: none;
    }

    [data-theme="dark"] .btn-close-modal {
        background: rgba(51, 65, 85, 0.8);
        color: #e2e8f0;
        border: 1px solid rgba(148, 163, 184, 0.3);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    [data-theme="dark"] .btn-close-modal:hover {
        background: rgba(71, 85, 105, 0.9);
        color: #ffffff;
        border-color: rgba(148, 163, 184, 0.5);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transform: translateY(-1px);
    }

    [data-theme="dark"] .btn-verify {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    [data-theme="dark"] .btn-verify:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
    }

    [data-theme="dark"] .error-message {
        color: #f87171;
        background: rgba(239, 68, 68, 0.1);
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Dark Mode Toast Styles */
    [data-theme="dark"] .floating-toast {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    [data-theme="dark"] .floating-toast-success {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #1e293b 0%, #0f2027 100%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(16, 185, 129, 0.2);
    }

    [data-theme="dark"] .floating-toast-error {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, #1e293b 0%, #1f1f1f 100%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(239, 68, 68, 0.2);
    }

    [data-theme="dark"] .toast-message {
        color: #ffffff;
    }

    [data-theme="dark"] .toast-close-btn {
        color: #94a3b8;
    }

    [data-theme="dark"] .toast-close-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    [data-theme="dark"] .floating-toast-success .toast-icon {
        color: #34d399;
    }

    [data-theme="dark"] .floating-toast-error .toast-icon {
        color: #f87171;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .staff-access-control-container {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .staff-card {
            padding: 1rem;
        }

        .controls-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 1200px) {
        .controls-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 992px) {
        .controls-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .controls-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .staff-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .edit-btn {
            display: none;
        }

        .save-controls {
            flex-direction: column;
        }

        .save-controls button {
            width: 100%;
        }
    }
</style>

<div class="staff-access-control-container">
    <div class="page-header">
        <h1><i class="bi bi-shield-lock me-2"></i>Staff Access Control</h1>
        <p>Manage navigation and feature access for each staff member. Changes will reflect immediately on the staff side.</p>
    </div>

    <div class="staff-list-container">
        @forelse($staffMembers as $staff)
            @php
                $accessControl = $staff->accessControl;
                $initials = '';
                if ($staff->info) {
                    $firstName = $staff->info->first_name ?? '';
                    $lastName = $staff->info->last_name ?? '';
                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                } else {
                    $nameParts = explode(' ', $staff->name);
                    $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr($nameParts[1] ?? '', 0, 1));
                }
            @endphp
            <div class="staff-card" data-staff-id="{{ $staff->id }}" onclick="toggleStaffControls({{ $staff->id }}, event)">
                <div class="staff-card-header">
                    <div class="staff-info">
                        <div class="staff-avatar">{{ $initials }}</div>
                        <div class="staff-details">
                            <h3>{{ $staff->name }}</h3>
                            <p>{{ $staff->email }}</p>
                        </div>
                    </div>
                    <i class="bi bi-chevron-down staff-chevron" id="chevron-{{ $staff->id }}"></i>
                </div>

                <div class="access-controls-section" id="controls-{{ $staff->id }}" onclick="event.stopPropagation()">
                    <!-- Navigation Access Controls -->
                    <div class="control-group">
                        <div class="control-group-title">
                            <i class="bi bi-list-ul"></i>
                            Navigation Access
                        </div>
                        <div class="controls-grid">
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-person-fill-gear"></i>
                                    <span>User Management</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->access_user_management ? 'active' : '' }}" 
                                     data-control="access_user_management" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-folder-fill"></i>
                                    <span>Content Management</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->access_content_management ? 'active' : '' }}" 
                                     data-control="access_content_management" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-file-earmark-post"></i>
                                    <span>Post-Procedural Form</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->access_post_procedural ? 'active' : '' }}" 
                                     data-control="access_post_procedural" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-chat-left-text"></i>
                                    <span>Chatbot Helper</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->access_toothtalk ? 'active' : '' }}" 
                                     data-control="access_toothtalk" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-chat-dots"></i>
                                    <span>Live Chat</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->access_live_chat ? 'active' : '' }}" 
                                     data-control="access_live_chat" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Access Controls -->
                    <div class="control-group">
                        <div class="control-group-title">
                            <i class="bi bi-gear"></i>
                            Feature Access
                        </div>
                        <div class="controls-grid">
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-megaphone"></i>
                                    <span>Manage Announcement</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_manage_announcements ? 'active' : '' }}" 
                                     data-control="can_manage_announcements" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-x-lg"></i>
                                    <span>Delete Archives</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_delete_archives ? 'active' : '' }}" 
                                     data-control="can_delete_archives" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-heart-pulse"></i>
                                    <span>Manage Service</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_manage_services ? 'active' : '' }}" 
                                     data-control="can_manage_services" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-envelope"></i>
                                    <span>Manage Mails</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_manage_mails ? 'active' : '' }}" 
                                     data-control="can_manage_mails" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-file-earmark-edit"></i>
                                    <span>Edit Patient Record</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_edit_patient_records ? 'active' : '' }}" 
                                     data-control="can_edit_patient_records" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-chat-left-text"></i>
                                    <span>Respond to Chat</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_respond_to_chat ? 'active' : '' }}" 
                                     data-control="can_respond_to_chat" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-paperclip"></i>
                                    <span>Can Attach Files</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_attach_files ? 'active' : '' }}" 
                                     data-control="can_attach_files" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                            <div class="control-item">
                                <label class="control-label">
                                    <i class="bi bi-download"></i>
                                    <span>Export Data</span>
                                </label>
                                <div class="toggle-switch {{ $accessControl && $accessControl->can_export_data ? 'active' : '' }}" 
                                     data-control="can_export_data" 
                                     onclick="toggleControl(this, {{ $staff->id }}, event)"></div>
                            </div>
                        </div>
                    </div>

                    <div class="save-controls" id="save-controls-{{ $staff->id }}" onclick="event.stopPropagation()">
                        <button class="btn-save" onclick="event.stopPropagation(); saveAccessControl({{ $staff->id }});">
                            <i class="bi bi-check-circle me-1"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-person-x" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <h3 class="mt-3 mb-2">No Staff Members Found</h3>
                    <p class="text-muted">There are no staff members in the system yet.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Password Verification Modal -->
<div class="password-modal" id="passwordModal">
    <div class="password-modal-content">
        <div class="password-modal-header">
            <h3><i class="bi bi-shield-lock me-2"></i>Password Verification</h3>
            <p>Please enter your password to save the changes to staff access control.</p>
        </div>
        <div class="password-input-wrapper">
            <input type="password" id="adminPassword" class="form-control" placeholder="Enter your password" autocomplete="current-password">
            <div class="error-message" id="passwordError"></div>
        </div>
        <div class="password-modal-actions">
            <button class="btn-close-modal" onclick="closePasswordModal()">Cancel</button>
            <button class="btn-verify" onclick="verifyPassword()">
                <i class="bi bi-check-circle me-1"></i>Verify
            </button>
        </div>
    </div>
</div>

<script>
let currentStaffId = null;
let originalControls = {};

function closePasswordModal() {
    document.getElementById('passwordModal').classList.remove('active');
    document.getElementById('adminPassword').value = '';
    document.getElementById('passwordError').classList.remove('show');
    currentStaffId = null;
}

function verifyPassword() {
    const password = document.getElementById('adminPassword').value;
    const errorDiv = document.getElementById('passwordError');

    if (!password) {
        errorDiv.textContent = 'Please enter your password.';
        errorDiv.classList.add('show');
        return;
    }

    // Show loading state
    const verifyBtn = document.querySelector('.btn-verify');
    const originalText = verifyBtn.innerHTML;
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Verifying...';

    fetch('{{ route("admin-staff-access-control.verify-password") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ password: password })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Password verification response:', data);
        if (data && data.success) {
            // If we have pending access controls, save them now
            if (window.pendingAccessControls && currentStaffId) {
                saveAccessControlAfterVerification(currentStaffId, window.pendingAccessControls, password);
                window.pendingAccessControls = null;
            } else {
                closePasswordModal();
            }
        } else {
            errorDiv.textContent = (data && data.message) || 'Incorrect password. Please try again.';
            errorDiv.classList.add('show');
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = error.message || 'An error occurred. Please try again.';
        errorDiv.classList.add('show');
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = originalText;
    });
}

function toggleStaffControls(staffId, event) {
    if (event) {
        event.stopPropagation();
    }
    
    const controlsSection = document.getElementById(`controls-${staffId}`);
    const staffCard = document.querySelector(`[data-staff-id="${staffId}"]`);
    const chevron = document.getElementById(`chevron-${staffId}`);
    
    // Toggle active class
    staffCard.classList.toggle('active');
    controlsSection.classList.toggle('active');
}

function toggleControl(element, staffId, event) {
    if (event) {
        event.stopPropagation();
    }
    // Toggle the switch directly - no password needed
    element.classList.toggle('active');
}

function saveAccessControl(staffId) {
    const controlsSection = document.getElementById(`controls-${staffId}`);
    const toggles = controlsSection.querySelectorAll('.toggle-switch');
    const accessControls = {};

    // Collect all toggle states
    toggles.forEach(toggle => {
        const controlName = toggle.getAttribute('data-control');
        accessControls[controlName] = toggle.classList.contains('active');
    });

    // Store the access controls to save after password verification
    window.pendingAccessControls = accessControls;
    currentStaffId = staffId;
    
    // Show password modal for verification
    document.getElementById('passwordModal').classList.add('active');
    document.getElementById('adminPassword').value = '';
    document.getElementById('adminPassword').focus();
    document.getElementById('passwordError').classList.remove('show');
}

function saveAccessControlAfterVerification(staffId, accessControls, password) {
    // Show loading state
    const saveBtn = document.querySelector(`#save-controls-${staffId} .btn-save`);
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Saving...';

    fetch(`/admin/staff-access-control/${staffId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            password: password,
            access_controls: accessControls
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('success', 'Staff access control updated successfully!');
            
            // Update original controls
            originalControls[staffId] = accessControls;
            
            // Close password modal
            closePasswordModal();
            
            // Reload page after a short delay to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast('error', data.message || 'Failed to update access control.');
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', error.message || 'An error occurred. Please try again.');
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Handle Enter key in password modal
document.getElementById('adminPassword')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        verifyPassword();
    }
});

function showToast(type, message) {
    // Remove any existing toasts
    const existingToasts = document.querySelectorAll('.floating-toast');
    existingToasts.forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `floating-toast floating-toast-${type}`;
    
    const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
    const iconColor = type === 'success' ? '#10b981' : '#ef4444';
    
    toast.innerHTML = `
        <div class="toast-content">
            <i class="bi bi-${icon} toast-icon"></i>
            <span class="toast-message">${message}</span>
        </div>
        <button type="button" class="toast-close-btn" onclick="this.parentElement.remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
    setTimeout(() => {
        toast.remove();
        }, 300);
    }, 5000);
}
</script>

@endsection