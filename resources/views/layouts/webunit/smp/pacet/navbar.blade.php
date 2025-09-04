<nav id="navbar" class="navbar fixed-top navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('web.home') }}">
            <img src="{{asset('front/images/Campus-Santa-Maria-Logo.svg')}}" alt="" height="55" />
        </a>
        <button class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('webunit.home*') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('webunit.home', ['webunit' => $webUnit]) }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('webunit.about*') ? 'active' : '' }}"
                        href="{{ route('webunit.about.about', ['webunit' => $webUnit]) }}">
                        About Us
                    </a>
                    {{-- <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li id="sub-menu">
                            <a href="" class="nav-link dropdown-item dropdown-toggle" data-toggle="dropdown">KB Santa
                                Maria</a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link dropdown-item" href="{{ route('sma.about.history') }}">KB
                                        Surabaya</a></li>
                                <li><a class="nav-link dropdown-item" href="{{ route('sma.about.history') }}">KB
                                        Sidoarjo</a></li>
                            </ul>
                        </li>
                        <li id="sub-menu">
                            <a href="" class="nav-link dropdown-item dropdown-toggle" data-toggle="dropdown">TK Santa
                                Maria</a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link dropdown-item" href="{{ route('sma.about.history') }}">TK
                                        Surabaya</a></li>
                                <li><a class="nav-link dropdown-item" href="{{ route('sma.about.history') }}">TK
                                        Sidoarjo</a></li>
                            </ul>
                        </li>
                    </ul> --}}
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ppdb.profile', 'SMP-PACET' ) }}">PPDB</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="https://moodle.sanmarosu-jatim.sch.id/">Moodle</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('webunit.news*') ? 'active' : '' }}"
                        href="{{ route('webunit.news', ['webunit' => $webUnit]) }}">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('webunit.facilities*') ? 'active' : '' }}"
                        href="{{ route('webunit.facilities', ['webunit' => $webUnit]) }}">Facilities</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<nav id="sidebar" class="d-xl-none d-lg-none d-md-block d-sm-block d-block navbar-light">
  <div class="container">
    <header class="d-flex justify-content-between">
      <a href="#" class="close-toggler">
        <img src="{{asset('web-kbtk/sda/icons/icon-back-toggler.svg')}}" alt="" class="mr-1">
        Back
      </a>
    </header>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link {{ Route::is('webunit.home*') ? 'active' : '' }}" aria-current="page" href="{{ route('webunit.home', ['webunit' => $webUnit]) }}">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Route::is('webunit.about*') ? 'active' : '' }}"
            href="{{ route('webunit.about.about', ['webunit' => $webUnit]) }}">
            About Us
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="https://ppdb.sanmarosu-jatim.sch.id/">PPDB</a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Route::is('webunit.news*') ? 'active' : '' }}" href="{{ route('webunit.news', ['webunit' => $webUnit]) }}">News</a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Route::is('webunit.facilities*') ? 'active' : '' }}" href="{{ route('webunit.facilities', ['webunit' => $webUnit]) }}">Facilities</a>
      </li>
    </ul>
  </div>
</nav>
