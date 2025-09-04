<nav id="navbar" class="navbar fixed-top navbar-expand-lg navbar-light desktop">
    <div class="container">
        <a class="navbar-brand" href="{{ route('kantin.index') }}">
            <img src="{{asset('webkantin/images/logo-sanmar.png')}}" alt="Logo Santa Maria" height="80" />
        </a>
        <ul class="navbar-nav">
            <li class="nav-item">
                @if (Auth::guard('siswa')->user())
                    <a class="nav-link danger" href="https://kantin.sanmarosu-jatim.sch.id/logout">
                        Logout
                    </a>
                @else
                    <a class="nav-link main-green" href="https://kantin.sanmarosu-jatim.sch.id/login">
                        Login
                    </a>
                @endif
            </li>
        </ul>
    </div>
</nav>
