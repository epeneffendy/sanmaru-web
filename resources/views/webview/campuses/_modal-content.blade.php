<div class="container-fluid modal-detail-campus">
    <div class="row row-card-mobile">
        <div class="col-5 modal-bg" style="background-image: url('{{ @$campusUnit->getImagePathUrl() }}')">
        </div>
        <div class="col-7 px-lg-5 py-5 col-mobile">
                <h2 class="text-center text-green">{{ $campusUnit->unit->letter_header_name }} {{ $campusUnit->unit->city }}</h2>
                <hr class="hr-yellow">
                <div class="container-deskripsi">
                    <h4 class="text-center text-grey2 m-2">Tentang sekolah:</h4>
                    {!! @$campusUnit->html_about !!}
                    <h4 class="text-center text-grey2 m-2">Keunggulan</h4>
                    {!! @$campusUnit->html_keunggulan !!}
                </div>
                <div class="modal-button-visit-school">
                    <a href="{{ @$campusUnit->permalink }}" class="btn button-outline-green m-2">VISIT SCHOOL ></a>
                </div>
                
            </div>
        </div>
    </div>
    <button type="button" class="close button-close-modal" data-dismiss="modal" aria-label="Close">
            <img src="{{ asset('front/images/icon-close.png') }}" alt="" />
    </button>
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset('css/content-styles.css') }}">
@endpush
