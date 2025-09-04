@extends('layouts.ppdb-landing-page.main')
@section('content')

<!-- to do, use this template as unit page profile -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="navbar-brand">
    <a href="{{ route('ppdb.index') }}" class="btn btn-outline-success my-2 my-sm-0">BACK</a>
    @if ($unit->city === "Surabaya")
      @if (strpos($unit->name, 'KB') !== false)
        <a href="{{ route('webunit.home', str_replace('kb-surabaya', 'kbtk-sby', strtolower($unit->name))) }}">
      @elseif (strpos($unit->name, 'TK') !== false)
        <a href="{{ route('webunit.home', str_replace('tk-surabaya', 'kbtk-sby', strtolower($unit->name))) }}">
      @else
        <a href="{{ route('webunit.home', str_replace('surabaya', 'sby', strtolower($unit->name))) }}">
      @endif
    @elseif ($unit->city === "Sidoarjo")
      @if (strpos($unit->name, 'KB') !== false)
        <a href="{{ route('webunit.home', str_replace('kb-sidoarjo', 'kbtk-sda', strtolower($unit->name))) }}">
      @elseif (strpos($unit->name, 'TK') !== false)
        <a href="{{ route('webunit.home', str_replace('tk-sidoarjo', 'kbtk-sda', strtolower($unit->name))) }}">
      @else
        <a href="{{ route('webunit.home', str_replace('sidoarjo', 'sda', strtolower($unit->name))) }}">
      @endif
    @else
      <a href="{{ route('webunit.home', strtolower($unit->name)) }}">
    @endif
        <button class="btn btn-outline-success my-2 my-sm-0" type="button">VISIT WEB SCHOOL</button>
      </a>
  </div>
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#home">HOME</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#about">TENTANG SEKOLAH</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#advantages">KEUNGGULAN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#testimonial">TESTIMONI</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#procedure">PROSEDUR</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#period">JADWAL PENDAFTARAN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#price">BIAYA MASUK</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0 desktop">
      <a href="{{ route('ppdb.register', ['unitName' => $unit->name]) }}"><button class="btn btn-outline-success my-2 my-sm-0" type="button">DAFTAR</button></a>
      &nbsp;&nbsp;&nbsp;
      <a href="{{ route('ppdb.login') }}"><button class="btn btn-outline-success my-2 my-sm-0" type="button">LOGIN</button></a>
    </form>
  </div>
  <div class="menu-bottom mobile">
    <div class="button-register-mobile"><a href="{{ route('ppdb.register', ['unitName' => $unit->name]) }}">DAFTAR</a></div>
    <div class="button-login-mobile"><a href="{{ route('ppdb.login') }}">MASUK</a></div>
  </div>
</nav>
<div class="section-home-unit" id="home" style='background: url({{ !empty($unit->banner_path) ? \App\Helpers\ImageHelper::imageUrl($unit->banner_path) : '/frontend-ppdb-online/img/unit-banner.jpg' }}) center center no-repeat; background-size: cover; margin-left: -15px; margin-right: -15px;'>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="unit-headline text-center">
          {{ $unit->name }}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-about-unit" id="about">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-5">
        <div class="unit-about-title">
          <h2>TENTANG<br>SEKOLAH</h2>
        </div>
        <div class="unit-about-image">

        </div>
      </div>
      <div class="col-sm-7">
        <div class="unit-about-desc">
            {!! $unit->about !!}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-advantages-unit" id="advantages">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-5">
        <div class="unit-advantages-image">
          <img src="{!! $unit->keunggulan_image_path !!}">
        </div>
      </div>
      <div class="col-sm-7">
        <div class="unit-advantages-title">
          <h2>KEUNGGULAN</h2>
        </div>
        <div class="unit-advantages-desc">
            {!! $unit->keunggulan !!}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-testimonial-unit" id="testimonial">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12 text-center">
        <div class="unit-testimonial-title">
          <h2>TESTIMONI</h2>
        </div>
      </div>
      @foreach (@$unit->testimonies as $testimony)
      <div class="col-sm-5 {{ ($loop->index+1) % 2 === 1 ? 'offset-sm-1' : NULL }}">
        <div class="unit-testimonial-card">
          <div class="thumbnail">
            <img src="{{ !empty($testimony->photo_path) ? \App\Helpers\ImageHelper::imageUrl($testimony->photo_path) : '/frontend-ppdb-online/img/unit-advantages.png' }}">
          </div>
          <div class="title">
            <h4>{!! $testimony->subject !!}<br>{!! $testimony->job !!}</h4>
            <p>{!! $testimony->content !!}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="section-procedure-unit" id="procedure">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <div class="unit-procedure-title">
          <h2>PROSEDUR SPMB</h2>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="unit-procedure-card">
          <div class="desc">
            {!! $unit->procedure !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-period-unit" id="period">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <div class="unit-period-title">
          <h2>JADWAL PENDAFTARAN</h2>
        </div>
      </div>
    </div>
    <div class="row">
      @forelse ($unit->activePeriods as $period)
      @php
        $colSize = 6;
        if ($loop->count >= 3) {
            $colSize = 6;
        }

      @endphp
        <div class="col-sm-10 offset-sm-1">
            <div class="unit-period-card">
            <div class="title">
                {{ $period->name }}
            </div>
            <div class="desc">
                {!! $period->description !!}
            </div>
            </div>
        </div>
      @empty
        Belum ada jadwal PPDB
      @endforelse
    </div>
  </div>
</div>

<div class="section-prize-unit" id="price">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <div class="unit-prize-title">
          <h2>RINCIAN BIAYA</h2>
        </div>
      </div>
      @forelse($unit->costs as $cost)
        <div class="col-sm-6">
          <div class="unit-prize-card">
            <div class="title">{!! $cost->title !!}</div>
            <div class="desc">
              {!! $cost->description !!}
            </div>
          </div>
        </div>
      @empty
        Belum ada rincian biaya
      @endforelse
    </div>
  </div>
</div>

@include('ppdb-online.footer')
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        scroll($('.nav-link'));
    });

    function scroll(element) {
        $(element).click(function(e) {
            e.preventDefault();
            var target = document.querySelector($(this).attr('href'));
            $('.nav-item').removeClass('active');
            target.scrollIntoView({behavior: 'smooth'});
            $(this).parent('.nav-link').addClass('active');
        });
    }
</script>
@endpush
@push('styles')
<style>
  @media (max-width: 768px) {
    body#ppdb .menu-bottom.mobile {
      display: block !important;
    }
  }
</style>
@endpush

