@extends('layouts.ppdb-online.main')
@section('content')
    <div class="row-height">

        {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="display: none">
          <a class="navbar-brand" href="{{ route('ppdb.index') }}"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">KEMBALI KE HALAMAN UTAMA</button></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

            </ul>
            <form class="form-inline my-2 my-lg-0">
              <a href="{{ route('ppdb.logout') }}"><button class="btn btn-outline-success my-2 my-sm-0" type="button">LOGOUT</button></a>
            </form>
          </div>
        </nav> --}}

        {{-- <div class="col-lg-5 content-left">
            <div class="content-left-wrapper">
                <div>
                    <figure>
                        <img src="{{asset('frontend-ppdb-online/img/welcome-image.svg')}}" alt="" class="img-fluid img-welcome">
                    </figure>
                </div>
            </div>
            <!-- /content-left-wrapper -->
        </div> --}}
        <!-- /content-left -->

        <div class="wrapper-content-desktop">
            @include('layouts.ppdb-online.tab-bar')
            {{-- IF STATUS COMPLETE --}}
            @if ($user->status === \App\Models\PPDBUser::STATUS_COMPLETE)
                <div class="container">
                    <div class="row row-content">
                        <h2 class="text-black">Selamat datang,</h2>
                        <p class="text-subtitle-3">
                            Terima kasih sudah melakukan registrasi PPDB, untuk melanjutkan silahkan upload file bukti pembayaran registrasi
                        </p>
                        <a href="#" class="btn-green" data-toggle="modal" data-target="#buktiModal"><img src="{{asset('frontend-ppdb-online/img/Icon/upload.png')}}" alt=""><span>Upload</span></a>
                    </div>
                </div>
            @endif

            {{-- IF STATUS !( COMPLETE and INCOMPLETE ) --}}
            @if ($user->status != \App\Models\PPDBUser::STATUS_COMPLETE && $user->status != \App\Models\PPDBUser::STATUS_INCOMPLETE)
                
            <div class="container" style="padding: 3rem">
                <div class="" style="padding-bottom: 2rem">
                    <img src="{{asset('frontend-ppdb-online/img/progress-bar/Progress Bar 1.png')}}" alt="" class="progress-bar-ppdb">
                </div>
                <div class="status-ppdb">
                    <div class="col">
                        <div class="status-container">
                            <div class="status-indicator">
                                <div class="">
                                    <div class="status-circle status-circle-green"></div>
                                </div>
                                <div class="col-5">
                                    <span class="text-subtitle-1 status-detail">Seleksi Administrasi</span>
                                </div>
                                <div class="col-6 d-flex align-items-center">
                                    <div class="status-tab status-tab-green-full">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Lolos</span>
                                    </div>
                                    <div class="status-tab status-tab-grey">
                                        <a href="{{route('ppdb.informasi-ppdb')}}" class="btn-detail">Detail</a>
                                    </div>
                                </div>
                            </div>
                            <div class="status-line"></div>
                            <div class="status-description">
                            </div>
                        </div>
                        <div class="status-container">
                            <div class="status-indicator">
                                <div class="">
                                    <div class="status-circle status-circle-green"></div>
                                </div>
                                <div class="col-5">
                                    <span class="text-subtitle-1 status-detail">Seleksi Online</span>
                                </div>
                                <div class="col-6 d-flex align-items-center">
                                    <div class="status-tab status-tab-green-full">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Lolos</span>
                                    </div>
                                    <div class="status-tab status-tab-grey">
                                        <a href="{{route('ppdb.informasi-ppdb')}}" class="btn-detail">Detail</a>
                                    </div>
                                </div>
                            </div>
                            <div class="status-line"></div>
                            <div class="status-description">
                            </div>
                        </div>
                        <div class="status-container">
                            <div class="status-indicator">
                                <div class="">
                                    <div class="status-circle"></div>
                                </div>
                                <div class="col-5">
                                    <span class="text-subtitle-1 status-detail">Seleksi Online</span>
                                </div>
                                <div class="col-6 d-flex align-items-center">
                                    {{-- <div class="status-tab status-tab-green-full">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Lolos</span>
                                    </div> --}}
                                    <div class="status-tab status-tab-grey">
                                        <a href="{{route('ppdb.informasi-ppdb')}}" class="btn-detail">Detail</a>
                                    </div>
                                </div>
                            </div>
                            <div class="status-line"></div>
                            <div class="status-description">
                            </div>
                        </div>
                        {{-- if last index then don't render status-line --}}
                        <div class="status-container">
                            <div class="status-indicator">
                                <div class="">
                                    <div class="status-circle status-circle-red"></div>
                                </div>
                                <div class="col-5">
                                    <span class="text-subtitle-1 status-detail">Seleksi Wawancara</span>
                                </div>
                                <div class="col-6 d-flex align-items-center">
                                    <div class="status-tab status-tab-red-full">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Tidak Lolos</span>
                                    </div>
                                    <div class="status-tab status-tab-grey">
                                        <a href="{{route('ppdb.informasi-ppdb')}}" class="btn-detail">Detail</a>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="status-line"></div> --}}
                            <div class="status-description">
                            </div>
                        </div>
                        
                    </div>
                    <br>
                    <div class="clear-50"></div>
                </div>
            </div>
            @endif
        </div>

        <div class="wrapper-content-mobile">
            <div class="col-lg-7 content-top" id="start">
                <div id="wizard_container">
                    <div id="top-wizard"></div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="header-form">
                        <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam-top">
                        <p>Selamat Datang,<br><span class="span-name text-extra-bold">{{@$user['name']}}</span></p>
                        <p class="text-grey text-tanggal">Tanggal Mendaftar: 22/01/20221</p>
                        <p>Jalur Khusus SMA Santa Maria</p>
    
                        <a href="{{route('ppdb.faq-ppdb')}}" class="btn btn-green align-self-center">FAQ</a>
                        <a href="#" class="btn btn-grey align-self-center" data-toggle="modal" data-target="#buktiModal">Upload Bukti Daftar</a>
                    </div>
                    <div class="status-ppdb">
                        <div class="col">
                            <div class="status-container">
                                <div class="status-indicator">
                                    <div class="status-circle status-circle-green"></div>
                                    <div class="status-line"></div>
                                </div>
                                <div class="status-description">
                                    <p class="text-body status-detail">Seleksi Administrasi</p>
                                    <div class="d-flex align-items-center">
                                        <div class="status-tab status-tab-green">
                                            <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Lolos</span>
                                        </div>
                                        <div class="status-tab status-tab-grey">
                                            <a href="{{route('ppdb.informasi-ppdb')}}" class="btn-detail">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="status-container">
                                <div class="status-indicator">
                                    <div class="status-circle status-circle-green"></div>
                                    <div class="status-line"></div>
                                </div>
                                <div class="status-description">
                                    <p class="text-body status-detail">Seleksi Tes Tulis</p>
                                    <div class="d-flex align-items-center">
                                        <div class="status-tab status-tab-green">
                                            <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Lolos</span>
                                        </div>
                                        <div class="status-tab status-tab-grey">
                                            <a href="{{route('ppdb.informasi-ppdb')}}" class="btn-detail">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- if last index then don't render status-line --}}
                            <div class="status-container">
                                <div class="status-indicator">
                                    <div class="status-circle status-circle-red"></div>
                                    {{-- <div class="status-line"></div> --}}
                                </div>
                                <div class="status-description">
                                    <p class="text-body status-detail">Seleksi Wawancara</p>
                                    <div class="d-flex align-items-center">
                                        <div class="status-tab status-tab-red">
                                            <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Tidak Lolos</span>
                                        </div>
                                        {{-- <div class="status-tab status-tab-grey">
                                            <a href="{{route('ppdb.informasi-ppdb')}}" class="btn-detail">Detail</a>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="clear-50"></div>
                </div>
                <!-- /Wizard container -->
            </div>
        </div>
        <!-- /content-right-->
    </div>
    <div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-pembayaran">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Upload Bukti Pendaftaran</h5>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div> --}}
                <div class="modal-body">
                    <form id="wrapped" method="POST" enctype="multipart/form-data"
                        action="{{route('ppdb.welcome.submit')}}">
                        <div>
                            
                            <div class="modal-container">
                                @if(empty(@$user['payment_form']))
                                    <label for="payment_form" class="custom-file-upload">
                                        <img src="{{asset('frontend-ppdb-online/img/Icon/Folder.png')}}" alt="">
                                    </label>
                                    <input type="file" name="payment_form" accept="image/x-png,image/jpeg"
                                            class="custom-file-input" id="payment_form"
                                            aria-describedby="inputGroupFileAddon01">
                                    <p class="text-title-2">Pilih file dari perangkat komputer Anda.</p>
                                    <p class="text-title-2 text-grey">Supports: JPG, JPEG, PDF</p>
                                @endif

                                @if(!empty(@$user['payment_form']))
                                    <img src="{{asset('frontend-ppdb-online/img/Icon/Wait.png')}}" alt="">
                                    <h1 class="text-orange">Menunggu Validasi</h1>
                                    <p class="text-title-2">
                                        Tunggu validasi dari sekolah ya
                                    </p>
                                @endif
                            </div>


                            {{-- @if(!empty(@$user['payment_form']))
                                <label class="label" style="display:block; padding: 5px; background-color: #d4edda; margin-bottom: 10px; font-size: 700; text-align: center;">Pembayaran anda menunggu validasi</label>
                            @endif
                
                            <p class="text-welcome">
                                Silakan upload bukti pembayaran formulir Anda.
                            </p>
                            
                            <div class="container">
                                <div class="row input-payment-form">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" name="payment_form" accept="image/x-png,image/jpeg"
                                                    class="custom-file-input" id="payment_form"
                                                    aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="payment_form"
                                                    style="white-space: nowrap;overflow: auto; display:block">{{(!empty(@$user['payment_form']))?str_replace('payment_form/','',$user['payment_form']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                    @if(!empty(@$user['payment_form']))
                                        <div class="alert alert-success" id="message_payment_form" role="alert">
                                            <a target="_blank" href="{{ $user->getPaymentFormImageUrl() }}">
                                                Lihat File
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" id="message_payment_form" role="alert">Belum
                                            Lengkap
                                        </div>
                                    @endif
                                </div>
                            </div> --}}

                        </div>
                        @csrf
                    </form>


                {{-- <div class="modal-detail-content">
                    <div class="content-header">
                        <img src="{{ $product->image }}" />
                        <div class="content-header-right">
                            {{ $product->name }}
                            <span class="content-header-price" id="label-harga">{{ $product->price_range }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-detail-ukuran">
                    <div class="label-ukuran">Ukuran</div>
                    <div class="pilihan-ukuran">
                        <ul>
                        @forelse ($product->details as $detail)
                            <li {!! $detail->stock == 0 ? 'class="disabled"' : NULL !!} data-product-id="{{ $product->id }}" data-id="{{ $detail->id }}" data-price="{{ $detail->price }}" data-stock="{{ $detail->stock }}">{{ $detail->size }}</li>
                        @empty
                            tidak ada ukurang
                        @endforelse
                        </ul>
                    </div>
                </div>
                <div class="modal-detail-jumlah">
                    <div class="label-jumlah">Jumlah</div>
                    <div class="pilihan-jumlah">
                        <span class="minus minus-active" data-type="minus"></span>
                        <span class="jumlah" id="jumlah">1</span>
                        <span class="plus plus-active" data-type="plus"></span>
                    </div>
                </div> --}}
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-green" data-dismiss="modal" aria-hidden="true">Tutup</button>
            </div> --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).on('change',"#payment_form",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-payment-form')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_payment_form').removeClass("alert-success");
                        $('#message_payment_form').removeClass("alert-danger");
                        $('#message_payment_form').addClass("alert-info");
                        $('#message_payment_form').text("Uploading...");
                        $('.input-group-notice').html('');
                    },
                    error: function (data) {
                        $('#message_payment_form').removeClass("alert-success");
                        $('#message_payment_form').addClass("alert-danger");
                        $('#message_payment_form').removeClass("alert-info");
                        $('#message_payment_form').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_payment_form').addClass("alert-success");
                        $('#message_payment_form').removeClass("alert-danger");
                        $('#message_payment_form').removeClass("alert-info");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_payment_form').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        swal("Sukses upload Image\nbukti pembayaran akan diproses oleh admin. \nAnda akan mendapatkan konfirmasi via email.", {
                            icon: "success"
                        });
                    }
                });
                return false;
            }
        });
    </script>
@endpush
