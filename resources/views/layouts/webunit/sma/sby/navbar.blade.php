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
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle {{ Route::is('webunit.about*') ? 'active' : '' }}" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      About Us
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                      <li id="sub-menu">
                          <a href="{{ route('webunit.about.history', ['webunit' => $webUnit]) }}" class="nav-link dropdown-item"> <i class="icon icon-history d-inline-block mr-2"></i> History</a>
                      </li>
                      <li id="sub-menu">
                          <a href="{{ route('webunit.about.about', ['webunit' => $webUnit]) }}" class="nav-link dropdown-item"> <i class="icon icon-about d-inline-block mr-2"></i> About</a>
                      </li>
                      <li id="sub-menu">
                          <a href="{{ route('webunit.about.welcome', ['webunit' => $webUnit]) }}" class="nav-link dropdown-item"> <i class="icon icon-warm-welcome d-inline-block mr-2"></i> Warm Welcome</a>
                      </li>
                      <li id="sub-menu">
                          <a href="{{ route('webunit.about.core-values', ['webunit' => $webUnit]) }}" class="nav-link dropdown-item"> <i class="icon icon-holding-heart d-inline-block mr-2"></i> Our Core Values</a>
                      </li>
                  </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ppdb.profile', 'SMA-SURABAYA' ) }}">PPDB</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="https://sobat.sanmarosu-jatim.sch.id/">Moodle</a>
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
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ Route::is('webunit.about*') ? 'active' : '' }}" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            About Us
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li id="sub-menu">
                <a href="{{ route('webunit.about.history', ['webunit' => $webUnit]) }}" class="nav-link dropdown-item"> <i class="icon icon-history d-inline-block mr-2"></i> History</a>
            </li>
            <li id="sub-menu">
                <a href="{{ route('webunit.about.about', ['webunit' => $webUnit]) }}" class="nav-link dropdown-item"> <i class="icon icon-about d-inline-block mr-2"></i> About</a>
            </li>
            <li id="sub-menu">
                <a href="{{ route('webunit.about.welcome', ['webunit' => $webUnit]) }}" class="nav-link dropdown-item"> <i class="icon icon-warm-welcome d-inline-block mr-2"></i> Warm Welcome</a>
            </li>
        </ul>
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
