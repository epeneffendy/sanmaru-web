@extends('layouts.webunit.kbtk.sby.main')
@section('content')
<div class="navbar-bg"></div>
<div class="container">
  <div class="about-nav">
    <a class="title-5 grey" href="{{ route('webunit.about.history', ['webunit' => $webUnit, 'unit' => $webunit_level]) }}">
      History
    </a>
    <a class="title-5 grey active" href="{{ route('webunit.about.about', ['webunit' => $webUnit, 'unit' => $webunit_level]) }}">
      About
    </a>
    <a class="title-5 grey" href="{{ route('webunit.about.welcome', ['webunit' => $webUnit, 'unit' => $webunit_level]) }}">
      A Warm Welcome
    </a>
  </div>
</div>

<div id="about">
  <div class="container">
    <div class="row"> 
      <div class="col-lg-12">
        <h4 class="title-5 orange mb-4">
          Tentang
        </h4>
        {!! $campusUnit->about !!}
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <h4 class="title-5 orange mb-4">
          Keunggulan
        </h4>
        {!! $campusUnit->keunggulan !!}
      </div>
    </div>
  </div>
  <section id="card-keunggulan">
    <img class="butterfly-bg" src="{{asset('web-kbtk/sby/images/butterfly-bg.png')}}"  alt="">
    <div class="container">
      <div class="row">
        <div class="col-6 d-flex align-items-stretch">
          <div class="card">
            <img class="card-img-top" src="{{asset('web-kbtk/sby/images/keunggulan-1.png')}}" alt="">
            <div class="card-body">
              <h5 class="card-title body-text-20 black-panther">Metode Sentra</h5>
              <p class="card-text body-text-16 grey">Merangsang siswa untuk aktif, kreatif, percaya diri dan mandiri melalui berbagai pengalaman</p>
            </div>
          </div>
        </div>
        <div class="col-6 d-flex align-items-stretch">
          <div class="card">
            <img class="card-img-top" src="{{asset('web-kbtk/sby/images/keunggulan-2.png')}}" alt="">
            <div class="card-body">
              <h5 class="card-title body-text-20 black-panther">Pengembangan Diri</h5>
              <p class="card-text body-text-16 grey">Mempersiapkan siswa dengan kemampuan kognitif, afektif, dan psikomotorik</p>
            </div>
          </div>
        </div>
        <div class="col-6 d-flex align-items-stretch">
          <div class="card">
            <img class="card-img-top" src="{{asset('web-kbtk/sby/images/keunggulan-3.png')}}" alt="">
            <div class="card-body">
              <h5 class="card-title body-text-20 black-panther">Semangat Serviam</h5>
              <p class="card-text body-text-16 grey">Menumbuhkan rasa cinta kepada Tuhan dan sesama</p>
            </div>
          </div>
        </div>
        <div class="col-6 d-flex align-items-stretch">
          <div class="card">
            <img class="card-img-top" src="{{asset('web-kbtk/sby/images/keunggulan-4.png')}}" alt="">
            <div class="card-body">
              <h5 class="card-title body-text-20 black-panther">Model Pembelajaran 4.0</h5>
              <p class="card-text body-text-16 grey">Interaksi langsung antara siswa, guru, dan pendamping</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>



@endsection