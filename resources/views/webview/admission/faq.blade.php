@extends('layouts.webview.main')
@section('title', 'Pertanyaan Umum')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="header-admission w-100"
        style="background-image: url('{{ asset('front/images/headline-faq.png') }}')">
        <div class="container h-100 d-flex flex-column justify-content-center">
                    <h2 class="text-white my-4">Pertanyaan<br>Umum</h2>
                    <img src="{{ asset('front/images/headline-line.png') }}" width="75" height="7" alt="">
                </div>
        </div>
    </div>

    <div class="row">
        <div class="container-fluid bg-dot" style="background-image: url('{{ asset('front/images/bg-dot-page.png') }}'); background-size: contain;">
            <div class="accordion-faq accordion-faq-mobile" id="accordion">
                @foreach($faqs as $key => $faq)
                <div class="faq-question-{{$key}}">
                    <h4>
                    <a class="btn-faq w-100" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$key}}">{{ $faq->content }} </a>
                    </h4>
                </div>
                <div id="collapse-{{$key}}" class="faq-collapse collapse in">
                    <p class="faq-answer"></p><br>
                    {!! $faq->html_answer !!}
                </div>
                @endforeach

            </div>
            
        </div>

    </div>
</div>

@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/content-styles.css') }}">
    <style>
        img{
            max-width: 100%;
            max-height: 100%;
            display: block;
        }
    </style>
@endpush
@push('scripts')
<script>
    var coll = document.getElementsByClassName("btn-faq");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("collapse");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
@endpush

