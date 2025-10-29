<aside class="navigation-bar-container">
    <div class="logo">
        <a href="{{ route('staff-dashboard') }}">
            <i class="bi bi-shield-shaded"></i>
            <span>Staff Panel</span>
        </a>
    </div>
    <nav class="navigation-bar">
        <ul>
            <div>
                <li class="{{ request()->routeIs('staff-dashboard') ? 'active' : '' }}">
                    <a href="{{ route('staff-dashboard') }}">
                        <i class="bi bi-microsoft"></i>
                        Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('staff-account-management') ? 'active' : '' }}">
                    <a href="{{ route('staff-account-management') }}">
                        <i class="bi bi-person-fill-gear"></i>
                        Account Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('staff-appointment') ? 'active' : '' }}">
                    <a href="{{ route('staff-appointment') }}">
                        <i class="bi bi-calendar4-week"></i>
                        Appointments
                    </a>
                </li>
                <li class="{{ request()->routeIs('staff-content-management') ? 'active' : '' }}">
                    <a href="{{ route('staff-content-management') }}">
                        <i class="bi bi-folder-fill"></i>
                        Content Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('staff-patient-records') ? 'active' : '' }}">
                    <a href="{{ route('staff-patient-records') }}">
                        <i class="bi bi-file-medical-fill"></i>
                        Patient Record Access
                    </a>
                </li>
                <li class="{{ request()->routeIs('staff-post-procedural') ? 'active' : '' }}">
                    <a href="{{ route('staff-post-procedural') }}">
                        <i class="bi bi-file-earmark-post"></i>
                        Post-Procedural Form
                    </a>
                </li>
                <li class="{{ request()->routeIs('staff-toothtalk') ? 'active' : '' }}">
                    <a href="{{ route('staff-toothtalk') }}">
                        <i class="bi bi-chat-left-text"></i>
                        Toothtalk
                    </a>
                </li>
            </div>
            <div>
                <li class="{{ request()->routeIs('staff-notification') ? 'active' : '' }}">
                    <a href="{{ route('staff-notification') }}">
                        <i class="bi bi-bell"></i>
                        <span style="position: relative; display: inline-flex; align-items: center; gap: 0.5rem;">
                            Notifications
                            @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                                <span class="notification-badge">{{ $pendingRequestsCount }}</span>
                            @endif
                        </span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('staff-profile') ? 'active' : '' }}">
                    <a href="{{ route('staff-profile') }}">
                        <i class="bi bi-person"></i>
                        Profile
                    </a>
                </li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('staff.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </div>
        </ul>
    </nav>
</aside>
