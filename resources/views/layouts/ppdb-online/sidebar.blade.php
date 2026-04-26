<div class="sidebar-container">
    <?php $ppdbUser = request()->session()->get('user')['ppdb'];?>
    <div class="col d-flex flex-column justify-content-between h-100 align-items-start">
        <div class="container d-flex justify-content-center flex-column">
            <div class="photo-profile align-self-center mb-3" style="background-image: url({{ isset($ppdbUser['photo']) && $ppdbUser['photo'] ? \App\Helpers\ImageHelper::imageUrl($ppdbUser['photo'])  : asset('frontend-ppdb-online/img/profile.png') }})"></div>
            {{-- <img src="{{asset('frontend-ppdb-online/img/pp.png')}}" class="photo-profile" alt=""> --}}
            <p class="text-title-1 text-white text-center">{{ @$ppdbUser['name'] }}</p>

            <div class="row" style="flex-wrap: nowrap">
                <div class="col-1">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Jalur.png')}}" alt="" class="mr-3" width="15">
                </div>
                <div class="col-11">
                    <span class="text-title-3 text-white">{{ @$ppdbUser['period']['name'] }}</span>
                </div>
            </div>
            <div class="row" style="flex-wrap: nowrap">
                <div class="col-1">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Jalur.png')}}" alt="" class="mr-3" width="15">
                </div>
                <div class="col-11">
                    <span class="text-title-3 text-white">No registrasi: {{ @$ppdbUser['register_number'] }}</span>
                </div>
            </div>
            <div class="row" style="flex-wrap: nowrap">
                <div class="col-1">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Jalur.png')}}" alt="" class="mr-3" width="15">
                </div>
                <div class="col-11">
                    <span class="text-title-3 text-white">Tanggal Mendaftar: {{ date('d/m/Y', strtotime(@$ppdbUser['created_at'])) }}</span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="container">
                <div class="row">
                    <a href="{{route('ppdb.welcome')}}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='home'?'active':'') : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Home-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Home-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Home</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{route('ppdb.data-siswa-ppdb')}}" class="sidebar-menu-item {{ isset($nav) ? ( $nav['parent'] == 'data' ? 'active' : '' ) : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Data-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Data-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Administrasi</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{route('ppdb.finance-ppdb')}}" class="sidebar-menu-item {{ isset($nav) ? ( $nav['parent'] == 'finance' ? 'active' : '' ) : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Data-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Data-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Keuangan</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{route('ppdb.embed-product.index')}}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='product'?'active':'') : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Shop-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Shop-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Shop</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{route('ppdb.profile-siswa')}}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='profile'?'active':'') : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Profile-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Profile-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Profil Saya</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{route('ppdb.notification.index')}}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='notification'?'active':'') : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/notification-normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/notification-active.png')}}" alt="">
                        <span class="text-title-2 text-white">Notifikasi</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <a href="{{route('ppdb.logout')}}" class="sidebar-menu-item">
                    <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Logout.png')}}" alt="">
                    <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Logout-Active.png')}}" alt="">
                    <span class="text-title-2 text-white ">Keluar</span>
                </a>
            </div>
        </div>

    </div>
</div>
