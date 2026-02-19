<div class="user-info-dropdown">
    <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            <span class="user-icon">
                <img src="{{ $user->picture }}" alt="user" />
            </span>
            <span class="user-name">{{ $user->name }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
            <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="dw dw-user1"></i>
                Profile</a>
            <a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="dw dw-settings2"></i> Setting</a>
            <a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                <i class="dw dw-logout"></i> LogOut
            </a>
            <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                @csrf

            </form>
        </div>
    </div>
</div>
