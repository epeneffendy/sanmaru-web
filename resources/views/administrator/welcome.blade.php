@extends('layouts.landing-page.main')
@section('content')
    <div class="container-fluid">
        <div class="row centered-middle">
            <div class="col-md-12 text-center">
                <div class="logo">
                    <img src="{{asset('img/Sanmaru Logo.png')}}" width="200">
                </div>
                <div class="subtitle">
                    @lang('pages.subtitle')
                <div>
                    <a class="btn btn-borderless" href="{{ route('admin.login') }}">@lang('pages.login')</a>
                </div>
            </div>
        </div>
        {{--@if (Route::has('login'))--}}
            {{--<div class="top-right links">--}}
                {{--@auth--}}
                    {{--<a href="{{ url('/home') }}">@lang('pages.home')</a>--}}
                {{--@else--}}
                    {{--@if (Route::has('register'))--}}
                        {{--<a href="{{ route('register') }}">@lang('pages.register')</a>--}}
                    {{--@endif--}}
                {{--@endauth--}}
            {{--</div>--}}
        {{--@endif--}}

        {{--<div class="content">--}}
            {{--<div class="logo">--}}
                {{--<img src="{{asset('img/Sanmaru Logo.png')}}">--}}
            {{--</div>--}}
            {{--<div class="links">--}}
                {{--@lang('pages.subtitle')--}}
            {{--</div>--}}
            {{--<div>--}}
                {{--<a href="{{ route('login') }}">@lang('pages.login')</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
@endsection
