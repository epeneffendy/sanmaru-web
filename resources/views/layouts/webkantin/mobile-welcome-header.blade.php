<div class="mobile-welcome-header">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('kantin.index') }}">
                <img class="logo-image me-2" src="{{asset('webkantin/images/serviam.png')}}" alt="Serviam" height="48">
            </a>
            <div class="mt-2">
                <h6 class="text-sm reguler grey">Selamat Datang</h6>
                <h6 class="text-md bold black">Kantin Online Santa Maria</h6>
            </div>
        </div>
        <div>
            @if (Auth::guard('siswa')->user())
                <a class="nav-link danger" href="{{ route('logout') }}"> 
                    Logout
                </a>
            @else
                <a class="nav-link main-green" href="{{ route('login') }}">
                    Login
                </a>
            @endif
        </div>
    </div>
    <div class="row align-items-center ">
        <div class="col">
            <div class="input-group search-bar">
                <span class="input-group-text" id="search-form"><i class="icon icon-search"></i></span>
                <input type="text" class="form-control text-sm reguler grey" placeholder="Search" aria-label="Search" aria-describedby="search-form">
            </div>
        </div>
        <div class="col-2">
            <a href="{{ route('kantin.history') }}"><i class="icon icon-receipt-green"></i></a>
        </div>
    </div>
</div>