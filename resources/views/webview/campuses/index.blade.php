@extends('layouts.webview.main')
@section('title', 'Campuses')
@section('content')
    <div class="container-fluid page-bg" style="background-image: url('{{ asset('front/images/bg-dot-page.png') }}')">
        <h1 class="text-center text-green">Campuses</h1>
        <div class="container">
            <div class="row py-5 row-card-mobile">
                @if (isset($campuses))
                @foreach($campuses as $campus)
                <div class="col-4 px-4 col-mobile">
                    <div class="d-flex flex-column justify-content-center card-campuses">
                        <img class="align-self-center mb-4" src="{{ asset('front/images/icon-kampus-sby.png') }}" alt="">
                        <h3 class="text-center text-green">{{ $campus->name }}</h3>
                        <hr class="hr-yellow">
                        @foreach($campus->campusUnits as $campusUnit)
                            @if($campusUnit->unit->name !== 'KB-SURABAYA' && $campusUnit->unit->name !== 'KB-SIDOARJO')
                            <a href="#" class="btn btn-campuses my-3" data-toggle="modal" data-target="#exampleModalCenter" data-url="{{ route('web.campus.unit.show', $campusUnit->id) }}">
                                <div class="d-flex align-items-center justify-center-between">
                                    <img src="{{ asset('front/images/Serviam-school.png') }}" alt="">
                                    <h4 class="text-left text-grey mx-3">{{ $campusUnit->unit->letter_header_name }} {{ $campusUnit->unit->city }}</h4>
                                    <img src="{{ asset('front/images/icon-arrow-right-green.png') }}" alt="">
                                </div>
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $(document).on('click', '.btn-campuses', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            $('.modal-content').html('')
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
            })
            .done(function (data) {
                $('.modal-content').html('');
                $('.modal-content').html(data);
            })
            .fail(function () {
                $('.modal-content').html('<i class="fa fa-sign"></i>Sorry, there is something wrong.');
            });
        });
    });
</script>
@endpush
