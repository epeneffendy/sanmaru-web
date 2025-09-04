@extends('layouts.landing-page.main')
@section('content')
    <div class="login-form">
        <form action="{{ route('admin.login.post') }}" method="post" autocomplete="off">
            @csrf
            <div class="top">
                <img src="{{asset('img/Sanmaru Logo.png')}}" alt="icon" class="icon">
                <h1>@lang('pages.title')</h1>
                <h4>@lang('pages.subtitle')</h4>
            </div>
            <div class="form-area">
                @if (session('status'))
                    <div class="alert alert-danger">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="group">
                    <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control"
                           placeholder="@lang('pages.username')" required>
                    <i class="fa fa-user"></i>
                </div>
                <div class="group">
                    <input type="password" id="password" name="password" value="{{ old('password') }}"
                           class="form-control"
                           placeholder="@lang('pages.password')" required>
                    <i class="fa fa-key"></i>
                </div>
                <div class="checkbox checkbox-primary">
                    <input id="checkbox101" name="remember" type="checkbox" checked>
                    <label for="checkbox101"> @lang('pages.remember_me')</label>
                </div>
                <button type="submit" class="btn btn-default btn-block text-uppercase">@lang('pages.login')</button>
            </div>
        </form>
        <div class="footer-links row">
            <div class="col-xs-12 text-center"><a href="#"><i class="fa fa-lock"></i> @lang('pages.forgot_password')</a>
            </div>
        </div>
    </div>
@endsection
