@extends('layouts.webkantin.main')

@inject('helper', 'App\Helpers\Helper')
@inject('priceHelper', 'App\Helpers\PriceHelper')
@inject('productOrderModel', 'App\Models\ProductOrder')

@section('content')

<div class="mobile-header">
    <a href="{{ route('kantin.index') }}">
        <i class="icon icon-back"></i>
    </a>
    <h5 class="text-lg medium mt-2 me-2">History Order</h5>
    <div></div>
</div>

<div id="history">
    <section class="home-section-1">
        <div class="container">
            <div class="menu-wrapper">
                <div class="row desktop">
                    <!-- Search Bar -->
                    <div class="col">
                        <form>
                        <div class="input-group search-bar">
                            <span class="input-group-text" id="search-form"><i class="icon icon-search"></i></span>
                            <input type="text" class="form-control text-sm reguler grey" placeholder="Search" aria-label="Search" aria-describedby="search-form" name="keyword" value="{{ request('keyword') }}">
                        </div>
                        </form>
                    </div>
                    <div class="col-2 col-lg-1">
                        <a class="btn btn-primary d-flex align-items-center justify-content-center" href="{{ route('kantin.history') }}"><i class="icon icon-receipt"></i></a>
                    </div>
                    <div class="col-2 col-lg-1">
                        {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#createOrderModal" class="btn btn-primary text-sm bold d-flex align-items-center justify-content-center"> <i class="icon icon-cart me-2"></i> Buat order</button> --}}
                        <a href="{{ route('kantin.cart.index') }}" class="btn btn-primary text-sm bold d-flex align-items-center justify-content-center"> <i class="icon icon-cart"></i></a>
                    </div>

                    <!-- Create Order Modal -->
                    <div class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="createOrderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form>
                                    <div class="modal-body">
                                        <div class="container">
                                            <h4 class="form-header">Masukkan identitas pemesan</h4>
                                            <input type="text" class="form-control text-sm reguler grey" placeholder="Nama pemesan" aria-describedby="orderName">
                                            <input type="text" class="form-control text-sm reguler grey" placeholder="Unit sekolah"  aria-describedby="orderSchoolUnit">
                                            <input type="tel" class="form-control text-sm reguler grey" placeholder="Nomor handphone"  aria-describedby="orderPhone">
                                            <div class="input-group">
                                                <span class="input-group-text" id="orderDay"><i class="icon icon-calendar"></i></span>
                                                <select class="form-select text-sm reguler grey" aria-label="orderDay">
                                                    <option selected>Pilih Hari</option>
                                                    <option value="Senin">Senin</option>
                                                    <option value="Selasa">Selasa</option>
                                                    <option value="Rabu">Rabu</option>
                                                    <option value="Kamis">Kamis</option>
                                                    <option value="Jumat">Jumat</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary text-md bold">Selesai</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-48">
                    <div class="col-md-4">
                        <h5 class="text-lg bold black mb-4 desktop">History Order</h5>
                    </div>
                    <div class="col-12">
                        <!-- HISTORY TAB -->
                        <ul class="nav nav-tabs row" id="HistoryTab" role="tablist">
                            <li class="nav-item col-6" role="presentation">
                                <button class="nav-link active text-sm" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab" aria-controls="ongoing" aria-selected="true">Sedang Berjalan</button>
                            </li>
                            <li class="nav-item col-6" role="presentation">
                                <button class="nav-link text-sm" id="done-tab" data-bs-toggle="tab" data-bs-target="#done" type="button" role="tab" aria-controls="done" aria-selected="false">Riwayat</button>
                            </li>
                        </ul>
                        <!-- END OF HISTORY TAB -->

                        <!-- HISTORY CONTENT TAB -->
                        <div class="tab-content" id="HistoryTabContent">
                            <!-- ON GOING CONTENT TAB -->
                            <div class="tab-pane fade show active" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
                                <div class="accordion mt-3" id="historyAccordion">
                                    @foreach ($productOrders as $i => $order)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{$i}}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$i}}" aria-expanded=" {{ $i <= 0 ? 'true' : 'false' }}" aria-controls="collapse-{{$i}}">
                                                <div class="row w-100">
                                                    <div class="col-4 col-md-6">
                                                        <div class="text-sm grey">{{ optional($order)->invoice_no }}</div>
                                                    </div>
                                                    <div class="col-8 col-md-6 text-end">
                                                        <div class="text-sm grey text-right me-4">{{ $helper::tanggal(optional($order)->created_at) }}</div>
                                                    </div>
                                                    <div class="col-12 thumbnail-wrapper">
                                                        <div class="d-flex">
                                                            <img src="{{ $order->getFirstImageThumbnailKantin() }}" class="thumbnail-img" alt="Kantin Santa Maria" onerror="this.onerror=null;this.src='{{ app('url')->to('webkantin/images/menu-1.png') }}';">
                                                            <div>
                                                                <p class="text-sm bold black">Pesan Order</p>
                                                                <p class="text-sm bold black">{{ $priceHelper::rupiah(optional($order)->grand_total) }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="line"></div>
                                                    </div>
                                                </div>
                                            </button>
                                            </h2>
                                            <div id="collapse-{{$i}}" class="accordion-collapse collapse {{ $i <= 0 ? 'show' : '' }}" aria-labelledby="heading-{{$i}}" data-bs-parent="#historyAccordion">
                                                <div class="accordion-body">
                                                    <div class="row justify-content-between">
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="delivery-image"></div>
                                                                <div class="desc-wrapper ms-3">
                                                                    <p class="text-lg black bold">{{ optional($order->user)->name }}</p>
                                                                    <p class="text-sm black regular">{{ optional($order->user)->phone_number }}</p>
                                                                    <p class="text-sm grey regular">Pesan Order</p>
                                                                </div>
                                                            </div>
                                                            <h5 class="text-lg black bold mt-5 mb-4">Order Summary</h5>
                                                            @foreach ($order->productOrderDetails as $item)
                                                                <div class="d-flex justify-content-between">
                                                                    <h5 class="text-sm reguler black mb-4">
                                                                        <span class="me-4 medium">{{ $item->quantity }}X</span>
                                                                        {{ optional($item->product)->name }}
                                                                        @isset($item->productDetail) ({{ $item->productDetail->size }}) @endisset
                                                                    </h5>
                                                                    <h5 class="text-sm reguler black mb-4">{{ $priceHelper::rupiah($item->price) }}</h5>
                                                                </div>
                                                            @endforeach
                                                            <div class="line mb-3"></div>
                                                            <div class="d-flex justify-content-between">
                                                                <h5 class="text-sm medium black mb-4">Subtotal</h5>
                                                                <h5 class="text-sm reguler black mb-4">{{ $priceHelper::rupiah(optional($order)->grand_total) }}</h5>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <h5 class="text-sm medium black mb-4">Total</h5>
                                                                <h5 class="text-sm reguler black mb-4">{{ $priceHelper::rupiah(optional($order)->grand_total) }}</h5>
                                                            </div>
                                                        </div>
                                                        @if ($order->needPickup())
                                                        <div class="col-4">
                                                            <h5 class="text-lg black bold">Rincian Pesanan</h5>
                                                            <div class="d-flex">
                                                                <img class="order-qris btn-show-qris" src="{{asset('webkantin/images/barcode.png')}}" alt="QRIS" data-id="{{ $order->id }}">
                                                                <div class="desc-wrapper ms-3 mt-3">
                                                                    <p class="text-sm black medium">Pesanan dapat diambil pada</p>
                                                                    <p class="text-sm secondary-green medium">{{ $helper::tanggal(optional($order)->pickup_date_schedule) }}</p>
                                                                    <a class="btn btn-secondary border-radius-4 text-sm" href="{{ route('kantin.history.order.pdf', $order->id) }}">Download invoice</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="line mt-5"></div>
                                                </div>
                                            </div>
                                            <div class="accordion-footer">
                                                {!! $order->kantin_status_label !!}

                                                @if (empty($order->payment_image))
                                                    <button class="btn btn-secondary border-radius-4 text-sm btn-upload-bukti" data-id="{{ $order->id }}">Upload bukti bayar</button>
                                                @else
                                                    <button class="btn btn-secondary border-radius-4 text-sm btn-show-bukti" data-id="{{ $order->id }}">Lihat bukti bayar</button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- ENF OF ON GOING CONTENT TAB -->

                            <!-- DONE CONTENT TAB -->
                            <div class="tab-pane fade" id="done" role="tabpanel" aria-labelledby="done-tab">
                                <div class="accordion mt-3" id="historyAccordion">
                                    @foreach ($hisoryProductOrders as $i => $order)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{$i}}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$i}}" aria-expanded=" {{ $i <= 0 ? 'true' : 'false' }}" aria-controls="collapse-{{$i}}">
                                                <div class="row w-100">
                                                    <div class="col-4 col-md-6">
                                                        <div class="text-sm grey">{{ optional($order)->invoice_no }}</div>
                                                    </div>
                                                    <div class="col-8 col-md-6 text-end">
                                                        <div class="text-sm grey text-right me-4">{{ $helper::tanggal(optional($order)->created_at) }}</div>
                                                    </div>
                                                    <div class="col-12 thumbnail-wrapper">
                                                        <div class="d-flex">
                                                            <img src="{{ $order->getFirstImageThumbnailKantin() }}" class="thumbnail-img" alt="Kantin Santa Maria" onerror="this.onerror=null;this.src='{{ app('url')->to('webkantin/images/menu-1.png') }}';">
                                                            <div>
                                                                <p class="text-sm bold black">Pesan Order</p>
                                                                <p class="text-sm bold black">{{ $priceHelper::rupiah(optional($order)->grand_total) }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="line"></div>
                                                    </div>
                                                </div>
                                            </button>
                                            </h2>
                                            <div id="collapse-{{$i}}" class="accordion-collapse collapse {{ $i <= 0 ? 'show' : '' }}" aria-labelledby="heading-{{$i}}" data-bs-parent="#historyAccordion">
                                                <div class="accordion-body">
                                                    <div class="row justify-content-between">
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="delivery-image"></div>
                                                                <div class="desc-wrapper ms-3">
                                                                    <p class="text-lg black bold">{{ optional($order->user)->name }}</p>
                                                                    <p class="text-sm black regular">{{ optional($order->user)->phone_number }}</p>
                                                                    <p class="text-sm grey regular">Pesan Order</p>
                                                                </div>
                                                            </div>
                                                            <h5 class="text-lg black bold mt-5 mb-4">Order Summary</h5>
                                                            @foreach ($order->productOrderDetails as $item)
                                                                <div class="d-flex justify-content-between">
                                                                    <h5 class="text-sm reguler black mb-4">
                                                                        <span class="me-4 medium">{{ $item->quantity }}X</span>
                                                                        {{ optional($item->product)->name }}
                                                                        @isset($item->productDetail) ({{ $item->productDetail->size }}) @endisset
                                                                    </h5>
                                                                    <h5 class="text-sm reguler black mb-4">{{ $priceHelper::rupiah($item->price) }}</h5>
                                                                </div>
                                                            @endforeach
                                                            <div class="line mb-3"></div>
                                                            <div class="d-flex justify-content-between">
                                                                <h5 class="text-sm medium black mb-4">Subtotal</h5>
                                                                <h5 class="text-sm reguler black mb-4">{{ $priceHelper::rupiah(optional($order)->grand_total) }}</h5>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <h5 class="text-sm medium black mb-4">Total</h5>
                                                                <h5 class="text-sm reguler black mb-4">{{ $priceHelper::rupiah(optional($order)->grand_total) }}</h5>
                                                            </div>
                                                        </div>
                                                        @if ($order->needPickup())
                                                        <div class="col-4">
                                                            <h5 class="text-lg black bold">Rincian Pesanan</h5>
                                                            <div class="d-flex">
                                                                <img class="order-qris btn-show-qris" src="{{asset('webkantin/images/barcode.png')}}" alt="QRIS" data-id="{{ $order->id }}">
                                                                <div class="desc-wrapper ms-3 mt-3">
                                                                    <p class="text-sm black medium">Pesanan dapat diambil pada</p>
                                                                    <p class="text-sm secondary-green medium">{{ $helper::tanggal(optional($order)->pickup_date_schedule) }}</p>
                                                                    <a class="btn btn-secondary border-radius-4 text-sm" href="{{ route('kantin.history.order.pdf', $order->id) }}">Download invoice</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="line mt-5"></div>
                                                </div>
                                            </div>
                                            <div class="accordion-footer">
                                                {!! $order->kantin_status_label !!}

                                                @if (empty($order->payment_image))
                                                    <button class="btn btn-secondary border-radius-4 text-sm btn-upload-bukti" data-id="{{ $order->id }}">Upload bukti bayar</button>
                                                @else
                                                    <button class="btn btn-secondary border-radius-4 text-sm btn-show-bukti" data-id="{{ $order->id }}">Lihat bukti bayar</button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- END OF DONE CONTENT TAB -->
                        </div>
                        <!-- END OF HISTORY CONTENT TAB -->

                        <!-- UPLOAD SUCCESS MODAL -->
                        <div class="modal fade" id="successPaymentModal" tabindex="-1" aria-labelledby="successPaymentModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="container">
                                            <div class="d-flex justify-content-center my-4">
                                                <div class="image-wrapper">
                                                    <img src="{{asset('webkantin/images/payment-success.png')}}" alt="Kantin Santa Maria">
                                                </div>
                                            </div>
                                            <h5 class="display-xs bold black-solid">Upload Bukti Bayar Sukses</h5>
                                            <p class="text-md reguler grey">Tunggu konfirmasi bukti pembayaran selesai diproses ya!</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer mb-5">
                                        <a href="{{ route('kantin.history') }}" class="btn btn-primary text-md bold">Kembali ke halaman history</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END OF UPLOAD SUCCESS MODAL -->

                        <!-- UPLOAD FAILED MODAL -->
                        <div class="modal fade" id="failedPaymentModal" tabindex="-1" aria-labelledby="failedPaymentModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="container">
                                            <div class="d-flex justify-content-center my-4">
                                                <div class="image-wrapper">
                                                    <img src="{{asset('webkantin/images/payment-failed.png')}}" alt="Kantin Santa Maria">
                                                </div>
                                            </div>
                                            <h5 class="display-xs bold black-solid">Upload Bukti Bayar Gagal</h5>
                                            <p class="text-md reguler grey">Uuppsss, coba ulangi proses pembayaranmu ya</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer mb-5">
                                        <a href="{{ route('kantin.history') }}" class="btn btn-primary text-md bold">Kembali ke history</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END OF UPLOAD FAILED MODAL -->

                        <!-- UPLOAD PAYMENT PROOF MODAL -->
                        <div class="modal fade" id="uploadPaymentProofModal" tabindex="-1" aria-labelledby="uploadPaymentProofModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="form-upload" method="POST" enctype="multipart/form-data" action="{{route('kantin.history.upload.file')}}">
                                        <input type="hidden" name="id" value="" id="id-upload" />
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <div class="container">
                                                <div class="d-flex justify-content-center my-4">
                                                    <div class="image-wrapper">
                                                        <img src="{{asset('webkantin/images/upload.png')}}" alt="Kantin Santa Maria">
                                                    </div>
                                                </div>
                                                <h5 class="display-xs bold black-solid">Upload Bukti Bayar</h5>
                                                <p class="text-md reguler grey">File upload berupa image/pdf</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer mb-5">
                                            <input class="hidden-input-file" type="file" accept="image/x-png,image/jpeg,application/pdf" id="file-upload" name="payment_image" />
                                            <label for="file-upload" class="btn btn-primary text-md bold" />Upload</label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--END OF UPLOAD PAYMENT PROOF MODAL -->

                        <!-- SHOW PAYMENT PROOF MODAL -->
                        <div class="modal fade" id="showPaymentProofModal" tabindex="-1" aria-labelledby="showPaymentProofModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <input type="hidden" name="id" value="" id="id-upload" />
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="container">
                                            <div class="d-flex justify-content-center my-4">
                                                <div class="preview-image">
                                                    <img class="responsive" src="{{asset('webkantin/images/upload.png')}}" id="proof-payment-image" />
                                                </div>
                                            </div>
                                            <h5 class="display-xs bold black-solid" id="label-modal-show-detail">Bukti Bayar</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--END OF SHOW PAYMENT PROOF MODAL -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
        }
    </style>
@endpush

@push('scripts')
<script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.btn-upload-bukti').click(function () {
            $('#id-upload').val($(this).data('id'));
            $('#uploadPaymentProofModal').modal('toggle');
        });
        $('.btn-show-bukti').click(function () {
            $.ajax({
                type: "GET",
                url: "{{route('kantin.history.order.detail')}}/" + $(this).data('id'),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#message_payment_form').removeClass("alert-success");
                    $('#message_payment_form').removeClass("alert-danger");
                    $('#message_payment_form').addClass("alert-info");
                    $('#message_payment_form').text("Loading...");
                    $('.input-group-notice').html('');
                },
                error: function (data) {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                },
                success: function (data) {
                    $('#proof-payment-image').attr('src', data.payment_image);
                    $('#label-modal-show-detail').text("Bukti Bayar");
                    $('#showPaymentProofModal').modal('toggle');
                }
            });
        });
        $('.btn-show-qris').click(function () {
            $.ajax({
                type: "GET",
                url: "{{route('kantin.history.order.detail')}}/" + $(this).data('id'),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#message_payment_form').removeClass("alert-success");
                    $('#message_payment_form').removeClass("alert-danger");
                    $('#message_payment_form').addClass("alert-info");
                    $('#message_payment_form').text("Loading...");
                    $('.input-group-notice').html('');
                },
                error: function (data) {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                },
                success: function (data) {
                    $('#proof-payment-image').parent().html(data.qris);
                    $('#label-modal-show-detail').text("QRIS");
                    $('#showPaymentProofModal').modal('toggle');
                }
            });
        });
    });

    $(document).on('change',"#file-upload",function () {
        if ($(this).val()) {
            var self = $(this);
            var formData = new FormData($('#form-upload')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                type: "POST",
                url: "{{route('kantin.history.upload.file')}}",
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
                    $('#failedPaymentModal').modal('toggle');
                },
                success: function (data) {
                    $('#uploadPaymentProofModal').modal('toggle');
                    $('#successPaymentModal').modal('toggle');
                }
            });

            return false;
        }
    });
</script>
@endpush
