<header>
    <div class="patient-header-container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
        </div>
        <nav class="navigation-menu">
            <ul>
                <li>
                    <a href="#">Clinic</a>
                </li>
                <li>
                    <a href="{{ route('patient-announcement') }}">Announcement</a>
                </li>
                <li>
                     <a href="#">About Us</a>
                </li>
                <li>
                    <a href="{{ route('patient-calendar') }}">Calendar</a>
                </li>
                <li>
                    <a href="{{ route('patient-record') }}">Record</a>
                </li>
            </ul>
        </nav>
        <div class="btn-container">
            <button type="button" ><i class="bi bi-bell-fill"></i></button>
            <a href="{{ url('patient/profile') }}"><img src="{{ asset('images/avatar.jpg')  }}" alt=""></a>
        </div>
    </div>
</header>
