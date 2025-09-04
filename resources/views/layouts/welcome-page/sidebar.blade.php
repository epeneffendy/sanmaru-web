@inject('routeService', 'App\Services\RouteService')
<div class="sidebar-container">
    <?php $ppdb_user = ['name' => 'Macan blonteng',
                            'photo' => null,
                            'register_number' => '201020022',
                            'created_at' => '2020-02-16 12:34:56', ];
            $user = ['mobile_phone' => '012345678901']?>

    <div class="col d-flex flex-column justify-content-between h-100 align-items-start">
        <div class="container d-flex justify-content-center flex-column">
            <div class="photo-profile align-self-center mb-3" style="background-image: url({{ isset($ppdbUser['photo']) && $ppdbUser['photo'] ? \App\Helpers\ImageHelper::imageUrl($ppdbUser['photo'])  : asset('frontend-ppdb-online/img/profile.png') }})"></div>
            {{-- <img src="{{asset('frontend-ppdb-online/img/pp.png')}}" class="photo-profile" alt=""> --}}
            <p class="text-title-1 text-white text-center">{{ auth('siswa')->user()->name }}</p>

            <div class="row" style="flex-wrap: nowrap">
                <div class="col-1">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Jalur.png')}}" alt="" class="mr-3" width="15">
                </div>
                <div class="col-11">
                    <span class="text-title-3 text-white">NIS: {{ auth('siswa')->user()->student->nis }}</span>
                </div>
            </div>
            <div class="row" style="flex-wrap: nowrap">
                <div class="col-1">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Jalur.png')}}" alt="" class="mr-3" width="15">
                </div>
                <div class="col-11">
                    <span class="text-title-3 text-white">Unit: {{ auth('siswa')->user()->student->class->unit->name }} [{{ auth('siswa')->user()->student->class->unit->unit_code }}] </span>
                </div>
            </div>
            <div class="row" style="flex-wrap: nowrap">
                <div class="col-1">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Jalur.png')}}" alt="" class="mr-3" width="15">
                </div>
                <div class="col-11">
                    <span class="text-title-3 text-white">Kelas: {{ auth('siswa')->user()->student->class->name }}</span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="container">
                <div class="row">
                    <a href="{{ route('welcome') }}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='home'?'active':'') : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Home-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Home-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Home</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{ route('embed-product') }}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='product'?'active':'') : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Shop-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Shop-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Shop</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{ str_replace(request()->getHost(), $routeService->getKantinSubdomain(), route('kantin.index')) }}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='kantin'?'active':'') : '' }}" target="_blank">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Cart-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Cart-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Kantin</span>
                    </a>
                </div>
                <div class="row">
                    <a href="{{ route('profile') }}" class="sidebar-menu-item {{ isset($nav) ? ($nav['parent']=='profile'?'active':'') : '' }}">
                        <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Profile-Normal.png')}}" alt="">
                        <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Profile-Active.png')}}" alt="">
                        <span class="text-title-2 text-white">Profil Saya</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <a href="{{ route('logout') }}" class="sidebar-menu-item">
                    <img class="icon-normal" src="{{asset('frontend-ppdb-online/img/Icon/Logout.png')}}" alt="">
                    <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Logout-Active.png')}}" alt="">
                    <span class="text-title-2 text-white ">Keluar</span>
                </a>
            </div>
        </div>

    </div>
</div>
