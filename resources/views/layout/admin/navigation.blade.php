<aside class="navigation-bar-container">


    <!-- User Profile Section -->
    <div class="user-profile-section">
        <a href="{{ route('admin-profile') }}" class="user-profile-link">
            <div class="user-profile-avatar">
                <div class="user-initials-avatar">
                    @php
                        $firstName = Auth::user()->info && Auth::user()->info->first_name
                            ? Auth::user()->info->first_name
                            : (explode(' ', Auth::user()->name)[0] ?? 'U');
                        $lastName = Auth::user()->info && Auth::user()->info->last_name
                            ? Auth::user()->info->last_name
                            : (explode(' ', Auth::user()->name)[1] ?? '');
                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                    @endphp
                    {{ $initials }}
                </div>
            </div>
            <div class="user-profile-info">
                <div class="user-profile-name">{{ Auth::user()->name }}</div>
                <div class="user-profile-role">
                    @if(Auth::user()->role)
                        {{ Auth::user()->role->role }}
                    @else
                        Administrator
                    @endif
                </div>
            </div>
        </a>
    </div>

    <nav class="navigation-bar">
        <ul>
            <div>
                <li class="{{ request()->routeIs('admin-dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin-dashboard') }}">
                        <i class="bi bi-microsoft"></i>
                        Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-account-management') ? 'active' : '' }}">
                    <a href="{{ route('admin-account-management') }}">
                        <i class="bi bi-person-fill-gear"></i>
                        Account Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-appointment') ? 'active' : '' }}">
                    <a href="{{ route('admin-appointment') }}">
                        <i class="bi bi-calendar4-week"></i>
                        Appointments
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-content-management') ? 'active' : '' }}">
                    <a href="{{ route('admin-content-management') }}">
                        <i class="bi bi-folder-fill"></i>
                        Content Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-post-procedural') ? 'active' : '' }}">
                    <a href="{{ route('admin-post-procedural') }}">
                        <i class="bi bi-file-earmark-post"></i>
                        Post-Procedural Form
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-toothtalk') ? 'active' : '' }}">
                    <a href="{{ route('admin-toothtalk') }}">
                        <i class="bi bi-chat-left-text"></i>
                        Toothtalk
                    </a>
                </li>
            </div>
            <div>
                <li class="{{ request()->routeIs('admin-notification') ? 'active' : '' }}">
                    <a href="{{ route('admin-notification') }}">
                        <i class="bi bi-bell"></i>
                        <span style="position: relative; display: inline-flex; align-items: center; gap: 0.5rem;">
                            Notifications
                            @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                                <span class="notification-badge">{{ $pendingRequestsCount }}</span>
                            @endif
                        </span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-activity-logs*') ? 'active' : '' }}">
                    <a href="{{ route('admin-activity-logs') }}">
                        <i class="bi bi-clock-history"></i>
                        Activity Logs
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-profile') ? 'active' : '' }}">
                    <a href="{{ route('admin-profile') }}">
                        <i class="bi bi-person"></i>
                        Profile
                    </a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-light text-decoration-none">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </div>
        </ul>
    </nav>
</aside>

