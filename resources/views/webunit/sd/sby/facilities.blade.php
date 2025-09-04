@extends("layouts.webunit.sd.sby.main")
@section('content')
<div class="navbar-bg"></div>
@php($current = '')
@php($changed = '')

<div id="facilities">
  @foreach($categories as $category)
  @if($category->section == 'main')
  <section id="facilities-overview">
    <div class="container">
      @foreach($category->facilities as $facility)
      <h4 class="title-5 airplane black-panther">{{ $facility->name }}</h4>
      <div class="row mt-3">
        <div class="col-lg-12 body-text-16 grey mt-3">
        {!! $facility->description !!}
        </div>
      </div>
      <div class="row mt-4 desktop-version">
        @forelse($facility->galleries->chunk(3)->all() as $key => $chunked)
          @foreach($chunked as $gallery)
        <div class="col-lg-4">
          <img class="facilities-image" src="{{ $gallery->getContentImageUrl() }}" alt="{{ $gallery->title }}">
        </div>
          @endforeach
        @empty 
        @endforelse
      </div>
      <div class="row mt-4 mobile-version mobile-carousel">
        @forelse($facility->galleries->chunk(3)->all() as $key => $chunked)
         @foreach($chunked as $gallery)
         <div class="facilities-carousel-inner">
           <div class="col-lg-4">
             <img class="facilities-image" src="{{ $gallery->getContentImageUrl() }}" alt="{{ $gallery->title }}">
           </div>
         </div> 
          @endforeach
        @empty 
        @endforelse
      </div>
      @endforeach
    </div>
  </section>
  @elseif($category->section == 'sub')
    @if($changed == $current)
  <section id="indoor">
    <div class="container">
      <div class="d-lg-flex d-block">
        <div class="desktop-version">
          <h5 class="vertical-text body-text-16 green-salad">{{ $category->name }}</h5>
        </div> 
        <div class="d-block">
          @php($mt=0)
          @foreach($category->facilities as $facility)
          <h4 class="title-5 airplane black-panther {{$mt ? 'mt-'.$mt : NULL}}">{{ $facility->name }}</h4>
          <div class="row mt-4">
            <div class="col-lg-12 body-text-16 grey mt-3 mb-3">
              {!! $facility->description !!}
            </div>
          </div>
          <div class="row mt-4 desktop-version">
            @forelse($facility->galleries->chunk(3)->all() as $key => $chunked)
             @foreach($chunked as $gallery)
             <div class="col-lg-4">
               <img class="facilities-image" src="{{ $gallery->getContentImageUrl() }}" alt="{{ $gallery->title }}">
             </div>
              @endforeach
            @empty
            @endforelse
          </div>
          <div class="row mt-4 mobile-version mobile-carousel">
            @forelse($facility->galleries->chunk(3)->all() as $key => $chunked)
            @foreach($chunked as $gallery)
            <div class="indoor-carousel-inner">
              <div class="col-lg-4">
                <img class="facilities-image" src="{{ $gallery->getContentImageUrl() }}" alt="{{ $gallery->title }}">
              </div>
            </div> 
              @endforeach
            @empty 
            @endforelse
          </div>
          @php($mt=4)
          @endforeach
        </div>
      </div>
    </div>
  </section>
    @else
      @php($changed = $category->slug)
  <section id="outdoor">
  <div class="container">
      <div class="d-lg-flex d-block">
        <div class="d-block text-right">
          @php($mt=0)
          @foreach($category->facilities as $facility)
          <h4 class="title-5 airplane black-panther {{$mt ? 'mt-'.$mt : NULL}}">{{ $facility->name }}</h4>
          <div class="row mt-4">
            <div class="col-lg-12 body-text-16 grey mt-3 mb-3">
            {!! $facility->description !!}
            </div>
          </div>
          <div class="row mt-4 desktop-version">
            @forelse($facility->galleries->chunk(3)->all() as $key => $chunked)
              @foreach($chunked as $gallery)
            <div class="col-lg-4">
              <img class="facilities-image" src="{{ $gallery->getContentImageUrl() }}" alt="{{ $gallery->name }}">
            </div>
              @endforeach
            @empty 
            @endforelse
          </div>
          <div class="row mt-4 mobile-version mobile-carousel">
            @forelse($facility->galleries->chunk(3)->all() as $key => $chunked)
              @foreach($chunked as $gallery)
              <div class="gallery-carousel-inner">
                <div class="col-lg-4">
                  <img class="facilities-image" src="{{ $gallery->getContentImageUrl() }}" alt="{{ $gallery->name }}">
                </div>
              </div>
              @endforeach
            @empty 
            @endforelse
          </div>
          @endforeach
        </div>
        <div class="desktop-version" style="width:84px">
          <h5 class="vertical-text body-text-16 green-salad">{{ $category->name }}</h5>
        </div>      
      </div>
    </div>
  
  
  </section>
    @endif
  @php($current = $category->slug)
  @endif
  @endforeach
</div>
@endsection