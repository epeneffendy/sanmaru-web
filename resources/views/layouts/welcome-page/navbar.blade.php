<nav class="nav-mobile w-100">
    <div class="container-fluid nav-mobile-container">
        <div class="row justify-content-between align-items-center">
            <a href="{{ route('logout') }}">
                <img src="{{asset('frontend-ppdb-online/img/Icon/Logout.png')}}" alt="">
            </a>
            <div class="text-body-title">{{ isset($nav) ? $nav['child'] : "Home" }}</div>
            <div class="notification" style="min-width:25px">
                <!-- <a href="{{route('ppdb.notifikasi-ppdb')}}">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/coolicon.png')}}" alt="">
                </a> -->
            </div>
        </div>
    </div>
</nav>

<nav class="bottom-navbar-mobile w-100">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <a href="{{ route('welcome') }}" class="bottom-navbar-item {{ isset($nav) ? ($nav['parent']=='home'?'active':'') : '' }}">
                <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Home-Normal-Mobile.png')}}" alt="">
                <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Home-Active.png')}}" alt="">
            </a>

            <a href="{{ route('embed-product') }}" class="bottom-navbar-item {{ isset($nav) ? ($nav['parent']=='product'?'active':'') : ''}}">
                <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Shop-Normal-Mobile.png')}}" alt="">
                <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Shop-Active.png')}}" alt="">
            </a>

{{--            <a href="{{ route('kantin.index') }}" class="bottom-navbar-item">--}}
{{--                <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Cart-Normal-Mobile.png')}}" alt="">--}}
{{--                <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Cart-Active.png')}}" alt="">--}}
{{--            </a>--}}

            <a href="{{ route('profile') }}" class="bottom-navbar-item {{ isset($nav) ? ($nav['parent']=='profile'?'active':'') : ''}}">
                <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Profile-Normal-Mobile.png')}}" alt="">
                <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Profile-Active.png')}}" alt="">
            </a>
        </div>
    </div>
</nav>
