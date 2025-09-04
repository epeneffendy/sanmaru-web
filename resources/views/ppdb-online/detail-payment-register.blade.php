@extends('layouts.ppdb-online.main')
@section('content')

    <div class="wrapper-content-desktop">
        <div class="container" style="padding: 3rem">
            <h2>Detail Pembayaran</h2>
            <div class="container">
                <div class="col">
                    @if($data->status != 'complete')
                        <div class="detail-payment-info">
                            <h2 class="text-black">Pembayaran Anda Telah Terkonfirmasi</h2>
                            <h3 class="text-grey">Pembayaran Telah Dilakukan Pada</h3>
                            <h2 class="text-black">{{ \App\Helpers\Helper::hariTanggalJam($data->payment_date) }}</h2>
                        </div>
                    @else
                        <div class="detail-payment-info">
                            <h2 class="text-black">Selesaikan pembayaran dalam</h2>
                            <p class="text-subtitle-4 countdown"></p>
                            <h3 class="text-grey">Batas akhir pembayaran</h3>
                            <h2 class="text-black">{{ \App\Helpers\Helper::hariTanggalJam($data->expired_at) }}</h2>
                        </div>
                    @endif

                </div>
            </div>

            <div class="container">
                <div class="pembayaran">
                    <div class="pembayaran-item">
                        @if(!empty($data->payment_option))
                            <div class="pembayaran-item__title">Bank {{ $data->payment_option }} <span class="{{ $data->payment_option }}"></span></div>
                            <div class="pembayaran-item__content">No. VA: <span>{{ $data->virtual_account_number }}</span></div>
                        @else
                            <div class="pembayaran-item__title">Bank BCA <span class="BCA"></span></div>
                            <div class="pembayaran-item__content">No. VA: <span>-</span></div>
                        @endif

                        <div class="pembayaran-item__total">Total pembayaran</div>
                        <div class="pembayaran-item__price">{{ \App\Helpers\PriceHelper::rupiah($data->total_payment_form) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-content-mobile">
        <div class="container" style="">
            <div class="row pl-3">
                <a href="{{URL::previous()}}" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
            </div>
            <div class="container">
                <div class="col">
                    <div class="detail-payment-info">
                        <h2 class="text-black">Selesaikan pembayaran dalam</h2>
                        <p class="text-subtitle-4 countdown"></p>
                        <h3 class="text-grey">Batas akhir pembayaran</h3>
                        <h2 class="text-black">{{ \App\Helpers\Helper::hariTanggalJam('2025-06-07 22:59:20') }}</h2>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="pembayaran">
                    <div class="pembayaran-item">
                        @if(!empty($data->payment_option))
                            <div class="pembayaran-item__title">Bank {{ $data->payment_option }} <span class="{{ $data->payment_option }}"></span></div>
                            <div class="pembayaran-item__content">No. VA: <span>{{ $data->virtual_account_number }}</span></div>
                        @else
                            <div class="pembayaran-item__title">Bank BCA</div>
                            <div class="pembayaran-item__content">No. VA: <span>VA</span></div>
                        @endif

                        <div class="pembayaran-item__total">Total pembayaran</div>
                        <div class="pembayaran-item__price">{{ \App\Helpers\PriceHelper::rupiah($data->total_payment_form) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var expiredAt = `{{$data->expired_at}}`;
            var end = new Date(expiredAt).getTime();
            var timer = setInterval(function () {
                var now = new Date().getTime();
                var distance = end - now;
                var days = Math.floor(distance / (1000*60*60*24));
                var hours = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
                var minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
                var seconds = Math.floor((distance % (1000*60)) / 1000);
                var countDownHtml = days+":"+hours+":"+minutes+":"+seconds;
                if (distance < 0) {
                    clearInterval(timer);
                    countDownHtml = "BATAS WAKTU PEMBAYARAN TELAH BERAKHIR";
                }
                $('.countdown').each(function () {
                    $(this).html(countDownHtml);
                });
            }, 1000);
        });
    </script>
@endpush
