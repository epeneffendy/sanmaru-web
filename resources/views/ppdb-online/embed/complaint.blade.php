@extends('layouts.ppdb-online.main')

@push('styles')
    <link href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}" rel="stylesheet"/>
    <style>
        .nav {
            display: block;
            text-align: center;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
        }

        .nav span {
            /* font-family: Roboto; */
            padding: 14px 0;
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 21px;
            text-align: center;
            color: #06270A;
            display: block;
        }

        .title {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: normal;
            font-size: 16px;
            line-height: 19px;
            color: #06270A;
            margin-bottom: 10px;
        }

        .pemesan, .pembayaran, .total, .info, .voucher {
            margin: 25px;
        }

        .pemesan-content {
            background: #FFFFFF;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
            border-radius: 6px;
            padding: 10px;
            display: flex;
            width: 100%;
        }

        .pemesan-content img {
            width: 120px;
            height: auto;
            border-radius: 50%;
        }

        .pemesan-info {
            margin-left: 10px;
            flex: 1;
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 19px;
            color: #06270A;
            align-self: center;
        }

        .pemesan-info span {
            /* font-family: Roboto; */
            display: block;
            font-style: normal;
            font-weight: normal;
            font-size: 12px;
            line-height: 14px;
            color: #89998B;
        }

        .pemesanan-item {
            background: #FFFFFF;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
            border-radius: 6px;
            display: flex;
            margin-top: 15px;
            /* padding: .5rem; */
        }

        .pemesanan-item.cancel-order {
            box-shadow: 0px 2px 10px rgba(224, 0, 0, 1);
        }


        .pemesanan-item-info {
            flex: 1;
            align-self: center;
            /* margin-left: 13px; */
            position: relative;
        }

        .pemesanan-item-info__title {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: bold;
            font-size: 18px;
            line-height: 27px;
            color: #06270A;
        }

        .pemesanan-item-info__detail {
            /* font-family: AcuminPro; */
            font-size: 14px;
            line-height: 24px;
            color: #89998B;
        }

        .pemesanan-item-info__price {
            /* font-family: AcuminPro; */
            font-size: 16px;
            line-height: 24px;
            color: #42B549;
        }

        .pemesanan-item-info__button-cancel {
            /* position: absolute; */
            /* right: 5px; */
            /* bottom: 50%; */
        }

        .info {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: normal;
            font-size: 16px;
            line-height: 19px;
            color: #89998B;
            padding: 10px 0;
            border-bottom: 1px solid #E6EAE7;
        }

        @media only screen and (max-width: 425px) {
            .pemesan-content img {
                width: 80px;
            }

            .pemesanan-item img {
                width: 100%;
            }

            .pemesan, .pemesanan, .pembayaran, .total, .info, .voucher {
                margin: 25px 5px;
            }
        }

        .arrow-back {
            background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M16.1371 1.35743C16.6137 0.880857 17.3863 0.880857 17.8629 1.35743C18.3395 1.834 18.3395 2.60668 17.8629 3.08325L8.94616 12L17.8629 20.9167C18.3395 21.3933 18.3395 22.166 17.8629 22.6426C17.3863 23.1191 16.6137 23.1191 16.1371 22.6426L6.35743 12.8629C5.88086 12.3863 5.88086 11.6137 6.35743 11.1371L16.1371 1.35743Z' fill='%2389998B'/%3E%3C/svg%3E%0A");
            width: 24px;
            height: 24px;
            position: absolute;
            top: 12px;
            left: 15px;
            background-size: cover;
        }
    </style>
@endpush
@section('content')

    <div class="wrapper-content-desktop">
        <div class="container" style="padding: 3rem">
            <h2>Detail Pesanan</h2>

            <div class="row">
                <div class="pemesanan">
                    <div class="pemesanan-item">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="pemesanan-item-info">
                                        <div class="pemesanan-item-info__title"><h3>Nomor
                                                invoice: {{$productOrder->invoice_no}}</h3></div>
                                        <div class="pemesanan-item-info__price">Total Pembayaran
                                            : {{ \App\Helpers\PriceHelper::rupiah($productOrder->grand_total) }}</div>
                                        <div class="text-title-3 font-italic text-black">Waktu Pemesanan
                                            : {{$productOrder->created_at}}</div>
                                        <br>
                                        <div class="text-title-3 font-italic text-black mt-2">Detail Pemesanan</div>
                                        <div
                                            class="text-title-3 font-italic text-black">{{ $productOrder->productOrderDetails->count() }}
                                            Barang
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <br>
            <h2>Form Komplain</h2>
            <div class="row">
                <div class="pemesanan">
                    <div class="pemesanan-item">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="pemesanan-item-info">
                                        @if(session('message'))
                                            @if (session('success') == true)
                                                <div class="alert alert-success">
                                                    <ul>
                                                        <li>{{session('message')}}</li>
                                                    </ul>
                                                </div>
                                            @endif

                                            @if (session('success') == false)
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        <li>{{session('message')}}</li>
                                                    </ul>
                                                </div>
                                            @endif
                                        @endif
                                        <form id="form-complaint" role="form" method="POST"
                                              action="{{route('ppdb.embed-product.complaint-store')}}"
                                              class="form-horizontal" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <input type="hidden" name="product_order_id" id="product_order_id"
                                                       value="{{$productOrder->id}}">
                                                <label class="control-label col-sm-2" for="name">Product<span
                                                        style="color: red">*</span>:</label>
                                                <div class="col-sm-10">
                                                    <select name="product_id" id="product_id"
                                                            class="form-control input-sm" required>
                                                        <option value="">== Silahkan Pilih ==</option>
                                                        @foreach (@$products as $ind => $item)
                                                            <option
                                                                value="{{ $ind }}">{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="detail_produt_order"></div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">Alasan<span
                                                        style="color: red">*</span>:</label>
                                                <div class="col-sm-10">
                                                    <select name="complaint_category_id" id="complaint_category_id"
                                                            class="form-control input-sm" required>
                                                        <option value="">== Silahkan Pilih ==</option>
                                                        @foreach (@$complaintCategory as $ind => $item)
                                                            <option
                                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">Komplain<span
                                                        style="color: red">*</span>:</label>
                                                <div class="col-sm-10">
                                                    <textarea name="complaint" class="form-control" id="complaint"
                                                              cols="30" rows="2" required></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">No. Telp<span
                                                        style="color: red">*</span> :</label>
                                                <div class="col-sm-6">
                                                    <input type="number" name="phone" id="phone" class="form-control"
                                                           value="" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">Email<span
                                                        style="color: red">*</span> :</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="email" id="email" class="form-control"
                                                           value="" required>
                                                </div>
                                            </div>

                                            <label class="control-label col-sm-2" for="name">Lampiran Bukti<span
                                                    style="color: red">*</span> :</label>
                                            <div class="col-6">
                                                <div class="input-group mb-3">
                                                    <input type="file" name="attachment" id="fileInput" required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="file" name="attachment_addition" id="fileInput">
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="file" name="attachment_extra" id="fileInput">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-success">
                                                        Ajukan Komplain
                                                    </button>

                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <br>
            @if(count($historyComplaint) > 0)
                <h2>History Komplain</h2>
                @foreach ($historyComplaint as $item)



                    <div class="row">
                        <div class="pemesanan">
                            <div class="pemesanan-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <div class="pemesanan-item-info">
                                                <div class="pemesanan-item-info__title">
                                                    <h3>Nama Product: {{$item->product->name}}</h3>
                                                </div>

                                                <div class="pemesanan-item-info__price">Size
                                                    : {{$item->productDetail->size}} </div>
                                                <div class="text-title-3 font-italic text-black">Note
                                                    : {{$item->productOrderDetail->note}}</div>

                                                <br>

                                                <div class="text-title-3 font-italic text-black mt-2"
                                                     style="font-weight: bold">Alasan
                                                </div>
                                                <div class="text-title-3 font-italic text-black">
                                                    {{$item->complaintCategory->name}}
                                                </div>

                                                <div class="text-title-3 font-italic text-black mt-2"
                                                     style="font-weight: bold">Keterangan
                                                </div>
                                                <div class="text-title-3 font-italic text-black">
                                                    {{$item->complaint}}
                                                </div>

                                                <hr>
                                                <div class="text-title-3 font-italic text-black mt-2"
                                                     style="font-weight: bold">No. Telp
                                                    <div class="text-title-3 font-italic text-black">
                                                        {{$item->phone}}
                                                    </div>
                                                </div>

                                                <div class="text-title-3 font-italic text-black mt-2"
                                                     style="font-weight: bold">Email
                                                    <div class="text-title-3 font-italic text-black">
                                                        {{$item->email}}
                                                    </div>
                                                </div>

                                                <div class="text-title-3 font-italic text-black mt-2"
                                                     style="font-weight: bold">Tgl Komplain
                                                    <div class="text-title-3 font-italic text-black">
                                                        {{  \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i:s')  }}
                                                    </div>
                                                </div>

                                                @if($item->status == \App\Models\ComplaintOrders::STATUS_REJECTED)
                                                    <hr>
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                                            <div class="d-flex w-100 justify-content-between">
                                                                <h5 class="mb-1">Komplain Ditolak</h5>
                                                                <small>{{ $item->updated_at }}</small>
                                                            </div>
                                                            <p class="mb-1">{{ $item->reason }}</p>
                                                        </a>
                                                    </div>
                                                    <hr>
                                                @endif

                                                @if ($item->status === \App\Models\ComplaintOrders::STATUS_PICKUP)
                                                    <hr>
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                                            <div class="d-flex w-100 justify-content-between">
                                                            <h5 class="mb-1">Komplain diterima</h5>
                                                            <small>{{ $item->updated_at }}</small>
                                                            </div>
                                                            <p class="mb-1">Komplain anda telah selesai di proses, silahkan lakuan pengambilan seragam anda</p>
                                                            <p class="mb-1">

                                                                <table>
                                                                    <tr>
                                                                        <td style="font-weight: bold">Tanggal Pengambilan</td>
                                                                        <td> : {{  \Carbon\Carbon::parse($item->date_pickup)->format('d-m-Y ')  }} </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-weight: bold">Tempat Pengambilan</td>
                                                                        <td>: {{ $item->location_pickup }}</td>
                                                                    </tr>
                                                                </table>
                                                            </small>
                                                        </a>
                                                    </div>
{{--                                                    <div style="color: black">Silahkan unduh detail transaksi berikut--}}
{{--                                                        ini sebagai persyaratan pengambilan seragam--}}
{{--                                                    </div>--}}

{{--                                                    <a class="btn btn-green" style="margin-top: 10px"--}}
{{--                                                       href="{{ route('ppdb.embed-product.complaint.pdf', $item->id) }}">--}}
{{--                                                        <img class="icon-active"--}}
{{--                                                             src="{{asset('frontend-ppdb-online/img/Icon/Data-Normal.png')}}"--}}
{{--                                                             alt="" style="margin-right: 10px">--}}
{{--                                                        Download--}}
{{--                                                    </a>--}}
                                                @endif

                                                <div class="text-title-3 font-italic text-black mt-2"
                                                     style="font-weight: bold">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            @if(@$item->attachment !== null)
                                                                <div
                                                                    class="preview-image {{ @$item->attachment !== null ? NULL : 'hide' }}">
                                                                    <img src="{{$item->imageAttachment}}"
                                                                         class="header-image" width="300" height="300"/>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4">
                                                            @if(@$item->attachment_addition !== null)
                                                                <div
                                                                    class="preview-image {{ @$item->attachment_addition !== null ? NULL : 'hide' }}">
                                                                    <img src="{{$item->imageAddition}}"
                                                                         class="header-image" width="300" height="300"/>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4">
                                                            @if((@$item->attachment_extra !== null))
                                                                <div
                                                                    class="preview-image {{ (@$item->attachment_extra !== null) ? NULL : 'hide' }}">
                                                                    <img src="{{$item->imageExtra}}"
                                                                         class="header-image" width="300" height="300"/>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>


                                            <div class="row">
                                                <div class="col-md-12" style="display: flex">

                                                    <div
                                                        class="col d-flex justify-content-between align-items-end flex-column">
                                                        @if ($item->status === \App\Models\ComplaintOrders::STATUS_WAITING)
                                                            <div class="pemesanan-status pemesanan-status-yellow">
                                                                <img
                                                                    src="{{asset('frontend-ppdb-online/img/Icon/Tab/wait.png')}}"
                                                                    alt="">
                                                                <span>Menunggu</span>
                                                            </div>

                                                            <br>
                                                            <a href="{{ route('ppdb.embed-product.cancel-complaint',['id'=>$item->id]) }}"
                                                               class="text-title-3 text-grey">Batalkan Komplain</a>
                                                        @endif

                                                        @if ($item->status === \App\Models\ComplaintOrders::STATUS_DONE)
                                                            <div class="pemesanan-status pemesanan-status-green">
                                                                <img
                                                                    src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}"
                                                                    alt="">
                                                                <span>Selesai</span>
                                                            </div>
                                                        @endif

                                                        @if ($item->status === \App\Models\ComplaintOrders::STATUS_REJECTED)
                                                            <div class="pemesanan-status pemesanan-status-red">
                                                                <img
                                                                    src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}"
                                                                    alt="">
                                                                <span>Ditolak</span>
                                                            </div>
                                                        @endif

                                                        @if ($item->status === \App\Models\ComplaintOrders::STATUS_PROCESS)
                                                            <div class="pemesanan-status pemesanan-status-yellow">
                                                                <img
                                                                    src="{{asset('frontend-ppdb-online/img/Icon/Tab/wait.png')}}"
                                                                    alt="">
                                                                <span>Prosess</span>
                                                            </div>
                                                        @endif

                                                        @if ($item->status === \App\Models\ComplaintOrders::STATUS_CANCEL)
                                                            <div class="pemesanan-status pemesanan-status-red">
                                                                <img
                                                                    src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}"
                                                                    alt="">
                                                                <span>Batal</span>
                                                            </div>
                                                        @endif

                                                        @if($item->status == \App\Models\ComplaintOrders::STATUS_PICKUP)
                                                            <div class="pemesanan-status pemesanan-status-purple"
                                                                 style="width: 300px">
                                                                <img
                                                                    src="{{asset('frontend-ppdb-online/img/Icon/Tab/wait.png')}}"
                                                                    alt="">
                                                                <span>Menunggu Pengambilan</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <br>

        </div>
    </div>

    <div class="wrapper-content-mobile">

        <div class="row">
            <div class="pemesanan">
                <div class="pemesanan-item">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="pemesanan-item-info">
                                    <div class="pemesanan-item-info__title"><h3>Nomor
                                            invoice: {{$productOrder->invoice_no}}</h3></div>
                                    <div class="pemesanan-item-info__price">Total Pembayaran
                                        : {{ \App\Helpers\PriceHelper::rupiah($productOrder->grand_total) }}</div>
                                    <div class="text-title-3 font-italic text-black">Waktu Pemesanan
                                        : {{$productOrder->created_at}}</div>
                                    <br>
                                    <div class="text-title-3 font-italic text-black mt-2">Detail Pemesanan</div>
                                    <div
                                        class="text-title-3 font-italic text-black">{{ $productOrder->productOrderDetails->count() }}
                                        Barang
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <br>
        <h2>Form Komplain</h2>
        <div class="row">
            <div class="pemesanan">
                <div class="pemesanan-item">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="pemesanan-item-info">
                                    @if(session('message'))
                                        @if (session('success') == true)
                                            <div class="alert alert-success">
                                                <ul>
                                                    <li>{{session('message')}}</li>
                                                </ul>
                                            </div>
                                        @endif

                                        @if (session('success') == false)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    <li>{{session('message')}}</li>
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                    <form id="form-complaint" role="form" method="POST"
                                          action="{{route('embed-product.complaint-store')}}"
                                          class="form-horizontal" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <input type="hidden" name="product_order_id" id="product_order_id"
                                                   value="{{$productOrder->id}}">
                                            <label class="control-label col-sm-2" for="name">Product<span
                                                    style="color: red">*</span>:</label>
                                            <div class="col-sm-10">
                                                <select name="product_id" id="product_id"
                                                        class="form-control input-sm" required>
                                                    <option value="">== Silahkan Pilih ==</option>
                                                    @foreach (@$products as $ind => $item)
                                                        <option
                                                            value="{{ $ind }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="detail_produt_order"></div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="name">Alasan<span
                                                    style="color: red">*</span>:</label>
                                            <div class="col-sm-10">
                                                <select name="complaint_category_id" id="complaint_category_id"
                                                        class="form-control input-sm" required>
                                                    <option value="">== Silahkan Pilih ==</option>
                                                    @foreach (@$complaintCategory as $ind => $item)
                                                        <option
                                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="name">Komplain<span
                                                    style="color: red">*</span>:</label>
                                            <div class="col-sm-10">
                                                    <textarea name="complaint" class="form-control" id="complaint"
                                                              cols="30" rows="2" required></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="name">No. Telp<span
                                                    style="color: red">*</span> :</label>
                                            <div class="col-sm-6">
                                                <input type="number" name="phone" id="phone" class="form-control"
                                                       value="" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="name">Email<span
                                                    style="color: red">*</span> :</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="email" id="email" class="form-control"
                                                       value="" required>
                                            </div>
                                        </div>

                                        <label class="control-label col-sm-2" for="name">Lampiran Bukti<span
                                                style="color: red">*</span> :</label>
                                        <div class="col-6">
                                            <div class="input-group mb-10">
                                                <input type="file" name="attachment" id="fileInput" required>
                                            </div>
                                            <div class="input-group mb-10">
                                                <input type="file" name="attachment_addition" id="fileInput">
                                            </div>
                                            <div class="input-group mb-10">
                                                <input type="file" name="attachment_extra" id="fileInput">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success btn-block">
                                                Ajukan Komplain
                                            </button>
                                        </div>
                                    </div>

                                    @if(count($historyComplaint) > 0)
                                        <h2>History Komplain</h2>
                                        @foreach ($historyComplaint as $item)



                                            <div class="row">
                                                <div class="pemesanan">
                                                    <div class="pemesanan-item">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="pemesanan-item-info">
                                                                        <div class="pemesanan-item-info__title">
                                                                            <h3>Nama
                                                                                Product: {{$item->product->name}}</h3>
                                                                        </div>

                                                                        <div class="pemesanan-item-info__price">Size
                                                                            : {{$item->productDetail->size}} </div>
                                                                        <div
                                                                            class="text-title-3 font-italic text-black">
                                                                            Note
                                                                            : {{$item->productOrderDetail->note}}</div>

                                                                        <br>

                                                                        <div
                                                                            class="text-title-3 font-italic text-black mt-2"
                                                                            style="font-weight: bold">Alasan
                                                                        </div>
                                                                        <div
                                                                            class="text-title-3 font-italic text-black">
                                                                            {{$item->complaintCategory->name}}
                                                                        </div>

                                                                        <div
                                                                            class="text-title-3 font-italic text-black mt-2"
                                                                            style="font-weight: bold">Keterangan
                                                                        </div>
                                                                        <div
                                                                            class="text-title-3 font-italic text-black">
                                                                            {{$item->complaint}}
                                                                        </div>

                                                                        @if($item->status == \App\Models\ComplaintOrders::STATUS_REJECTED)
                                                                            <div
                                                                                class="text-title-3 font-italic text-black mt-2"
                                                                                style="font-weight: bold">Alasan ditolak
                                                                            </div>
                                                                            <div
                                                                                class="text-title-3 font-italic text-black">
                                                                                {{$item->reason}}
                                                                            </div>
                                                                        @endif


                                                                        <hr>
                                                                        <div
                                                                            class="text-title-3 font-italic text-black mt-2"
                                                                            style="font-weight: bold">No. Telp
                                                                            <div
                                                                                class="text-title-3 font-italic text-black">
                                                                                {{$item->phone}}
                                                                            </div>
                                                                        </div>

                                                                        <div
                                                                            class="text-title-3 font-italic text-black mt-2"
                                                                            style="font-weight: bold">Email
                                                                            <div
                                                                                class="text-title-3 font-italic text-black">
                                                                                {{$item->email}}
                                                                            </div>
                                                                        </div>

                                                                        <div
                                                                            class="text-title-3 font-italic text-black mt-2"
                                                                            style="font-weight: bold">Tgl Komplain
                                                                            <div
                                                                                class="text-title-3 font-italic text-black">
                                                                                {{  \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i:s')  }}
                                                                            </div>
                                                                        </div>

                                                                        @if ($item->status === \App\Models\ComplaintOrders::STATUS_PICKUP)
                                                                            <hr>
                                                                            <div style="color: black">Silahkan unduh
                                                                                detail transaksi berikut ini sebagai
                                                                                persyaratan pengambilan seragam
                                                                            </div>

                                                                            <a class="btn btn-green"
                                                                               style="margin-top: 10px"
                                                                               href="{{ route('embed-product.complaint.pdf', $item->id) }}">
                                                                                <img class="icon-active"
                                                                                     src="{{asset('frontend-ppdb-online/img/Icon/Data-Normal.png')}}"
                                                                                     alt="" style="margin-right: 10px">
                                                                                Download
                                                                            </a>
                                                                        @endif

                                                                        <div
                                                                            class="text-title-3 font-italic text-black mt-2"
                                                                            style="font-weight: bold">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    @if(@$item->attachment !== null)
                                                                                        <div
                                                                                            class="preview-image {{ @$item->attachment !== null ? NULL : 'hide' }}">
                                                                                            <img
                                                                                                src="{{$item->imageAttachment}}"
                                                                                                class="header-image"
                                                                                                width="100"
                                                                                                height="100"/>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    @if(@$item->attachment_addition !== null)
                                                                                        <div
                                                                                            class="preview-image {{ @$item->attachment_addition !== null ? NULL : 'hide' }}">
                                                                                            <img
                                                                                                src="{{$item->imageAddition}}"
                                                                                                class="header-image"
                                                                                                width="100"
                                                                                                height="100"/>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    @if((@$item->attachment_extra !== null))
                                                                                        <div
                                                                                            class="preview-image {{ (@$item->attachment_extra !== null) ? NULL : 'hide' }}">
                                                                                            <img
                                                                                                src="{{$item->imageExtra}}"
                                                                                                class="header-image"
                                                                                                width="100"
                                                                                                height="100"/>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <br>


                                                                    <div class="row">
                                                                        <div class="col-md-12" style="display: flex">

                                                                            <div
                                                                                class="col d-flex justify-content-between align-items-end flex-column">
                                                                                @if ($item->status === \App\Models\ComplaintOrders::STATUS_WAITING)
                                                                                    <div
                                                                                        class="status-tab status-tab-yellow">
                                                                                        <img
                                                                                            src="{{asset('frontend-ppdb-online/img/Icon/Tab/wait.png')}}"
                                                                                            alt="">
                                                                                        <span>Menunggu</span>
                                                                                    </div>

                                                                                    <br>
                                                                                    <a href="{{ route('ppdb.embed-product.cancel-complaint',['id'=>$item->id]) }}"
                                                                                       class="text-title-3 text-grey">Batalkan
                                                                                        Komplain</a>
                                                                                @endif

                                                                                @if ($item->status === \App\Models\ComplaintOrders::STATUS_DONE)
                                                                                    <div
                                                                                        class="status-tab status-tab-green">
                                                                                        <img
                                                                                            src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}"
                                                                                            alt="">
                                                                                        <span>Selesai</span>
                                                                                    </div>
                                                                                @endif

                                                                                @if ($item->status === \App\Models\ComplaintOrders::STATUS_REJECTED)
                                                                                    <div
                                                                                        class="status-tab status-tab-red">
                                                                                        <img
                                                                                            src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}"
                                                                                            alt="">
                                                                                        <span>Ditolak</span>
                                                                                    </div>
                                                                                @endif

                                                                                @if ($item->status === \App\Models\ComplaintOrders::STATUS_PROCESS)
                                                                                    <div
                                                                                        class="status-tab status-tab-yellow">
                                                                                        <img
                                                                                            src="{{asset('frontend-ppdb-online/img/Icon/Tab/wait.png')}}"
                                                                                            alt="">
                                                                                        <span>Prosess</span>
                                                                                    </div>
                                                                                @endif

                                                                                @if ($item->status === \App\Models\ComplaintOrders::STATUS_CANCEL)
                                                                                    <div class="status-tab status-tab-red">
                                                                                        <img
                                                                                            src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}"
                                                                                            alt="">
                                                                                        <span>Batal</span>
                                                                                    </div>
                                                                                @endif

                                                                                @if($item->status == \App\Models\ComplaintOrders::STATUS_PICKUP)
                                                                                        <div class="status-tab status-tab-yellow">
                                                                                            <img
                                                                                                src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}"
                                                                                                alt="">
                                                                                            <span>Menunggu Pengambilan</span>
                                                                                        </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <br>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        const base_prefix = "/embed-product";
        $(document).ready(function () {
            $("#product_id").on("change", function (e) {
                let product = $("#product_id").val();
                $.get(
                    base_prefix + '/fetch-product-order/' + product,
                    function (data) {
                        $('#detail_produt_order').html(data)
                    }
                );
            });

            $('#complaint-proses').click(function (e) {
                e.preventDefault();
                // var formData = new FormData(this)
                // formData.append('user_id', 123);
                // formData.append('description', 'Upload via AJAX');
                // console.log(formData);

                let formData;
                let success = true;
                let message = 'validation success';

                let product = $('#product_id').val();
                let complaint = $('#complaint').val();
                let product_order_id = $('#product_order_id').val();
                let complaint_category_id = $('#complaint_category_id').val();
                let phone = $('#phone').val();
                let email = $('#email').val();

                if (product == 0) {
                    success = false;
                    message = 'Silahkan pilih filter product terlebih dahulu!';
                }

                if (complaint == '') {
                    success = false;
                    message = 'Silahkan isi detail komplain terlebih dahulu!';
                }

                if (complaint_category_id == 0) {
                    success = false;
                    message = 'Silahkan isi alasan komplain terlebih dahulu!';
                }

                if (phone == '') {
                    success = false;
                    message = 'Silahkan isi no. telp terlebih dahulu!';
                }

                if (email == '') {
                    success = false;
                    message = 'Silahkan isi email terlebih dahulu!';
                }

                if (success) {
                    store(product, complaint, product_order_id, complaint_category_id, phone, email, formData)
                } else {
                    swal('Gagal!', message, 'error');
                }
            });

            function store(product, complaint, product_order_id, complaint_category_id, phone, email, formData) {

                $.post(
                    base_prefix + '/complaint-store',
                    {
                        "_token": "{{ csrf_token() }}",
                        'product_id': product,
                        'complaint': complaint,
                        'product_order_id': product_order_id,
                        'complaint_category_id': complaint_category_id,
                        'phone': phone,
                        'email': email,
                        'files': formData

                    },
                    function (data) {
                        if (data.success) {
                            location.reload();
                        } else {
                            swal('Gagal!', data.message, 'error');
                        }

                    }
                );
            }
        });

    </script>
@endpush
