@extends('layouts.admin.main')
@section('content')
    @php($status="Show")
    @php($status_header="Show")
    @push('style')
        <style>
            td {
                padding: 10px; /* Applies 10px padding on all sides of the cell content */
            }

            /* Reset & Umum */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                padding: 20px;
                background-color: #f5f5f5;
            }

            h1, h2 {
                text-align: center;
                margin-bottom: 20px;
            }

            .gallery {
                margin-bottom: 40px;
            }

            /* Metode 1: Zoom pada Hover */
            .zoom-hover {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
            }

            .image-container {
                width: 250px;
                height: 200px;
                overflow: hidden;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .image-container:hover img {
                transform: scale(1.5); /* Zoom 150% */
            }

            /* Metode 2: Lightbox */
            .lightbox-gallery {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                justify-content: center;
            }

            .lightbox-item {
                width: 200px;
                height: 150px;
                border-radius: 8px;
                overflow: hidden;
                cursor: pointer;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .lightbox-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.2s ease;
            }

            .lightbox-item:hover img {
                transform: scale(1.1);
            }

            /* Lightbox Modal */
            .lightbox {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .lightbox img {
                max-width: 90%;
                max-height: 90%;
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            }

            .close-btn {
                position: absolute;
                top: 20px;
                right: 40px;
                color: white;
                font-size: 40px;
                cursor: pointer;
            }

            .close-btn:hover {
                color: #ff6b6b;
            }
        </style>
    @endpush
    <div class="page-header">
        <h1 class="title">Detail Komplain</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.complaint.index')}}">Komplain</a></li>
            <li class="active">{{$status_header}}</li>
        </ol>
    </div>

    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="widget ">
                    <div class="widget-header">
                        <div class="pull-right">
                            @if($data->status == \App\Models\ComplaintOrders::STATUS_WAITING)
                                <button id="complaint-proccess" class="btn btn-sm btn-info complaint-proccess"
                                        data-id="{{$data->id}}" style="margin-top: 5px">
                                    <i class="fa fa-check-square-o"> Proses Komplain</i>
                                </button>
                                <button id="modal-complaint-rejected"
                                        class="btn btn-sm btn-danger modal-complaint-rejected"
                                        data-id="{{$data->id}}" style="margin-top: 5px">
                                    <i class="fa fa-ban"> Tolak Komplain</i>
                                </button>
                            @endif

                            @if($data->status == \App\Models\ComplaintOrders::STATUS_PROCESS)
                                <button id="modal-complaint-pickup"
                                        class="btn btn-sm btn-warning modal-complaint-pickup"
                                        data-id="{{$data->id}}" style="margin-top: 5px">
                                    <i class="fa fa-calendar-o"> Atur Pengambilan</i>
                                </button>
                                <button id="modal-complaint-rejected"
                                        class="btn btn-sm btn-danger modal-complaint-rejected"
                                        data-id="{{$data->id}}" style="margin-top: 5px">
                                    <i class="fa fa-ban"> Tolak Komplain</i>
                                </button>
                            @endif

                            @if($data->status == \App\Models\ComplaintOrders::STATUS_PICKUP)
                                <button id="complaint-done" class="btn btn-sm btn-success complaint-done"
                                        data-id="{{$data->id}}" style="margin-top: 5px">
                                    <i class="fa fa-check-circle-o"> Komplain Selesai</i>
                                </button>
                            @endif

                            @if($data->status == \App\Models\ComplaintOrders::STATUS_DONE)
                                <label class="label label-success">Komplain Telah Diselesaikan</label><br>
                                <span style="font-style: italic">Petugas : {{$userUpdate->username}}</span><br>
                                <span
                                    style="font-style: italic">{{ \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y H:i:s') }}</span>
                            @endif

                            @if($data->status == \App\Models\ComplaintOrders::STATUS_REJECTED)
                                <label class="label label-danger">Komplain Ditolak</label><br>
                                <span style="font-style: italic">Petugas : {{$userUpdate->username}}</span><br>
                                <span
                                    style="font-style: italic">{{ \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y H:i:s') }}</span>
                            @endif

                            @if($data->status == \App\Models\ComplaintOrders::STATUS_CANCEL)
                                <label class="label label-danger">Komplain Telah Dibatalkan</label><br>
                                <span
                                    style="font-style: italic">{{ \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y H:i:s') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="form-horizontal">

                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail Order</h4>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-primary">#No Invoice
                                        : {{ $productOrder->invoice_no }}</h5>

                                    <div class="form-group" style="padding: 1em">
                                        <table width="100%">
                                            <tr>
                                                <td>Product</td>
                                                <td><strong> : {{ $data->product->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Tgl Order</td>
                                                <td><strong>
                                                        : {{ \Carbon\Carbon::parse($productOrder->created_at)->format('d-m-Y H:i:s') }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Status Pembayaran</td>
                                                <td><strong>
                                                        : {!! @$productOrder->labelKonfirmasiPembayaran !!}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail Komplain</h4>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-primary">#Status :
                                        @if($data->status == \App\Models\ComplaintOrders::STATUS_WAITING)
                                            <label class="label label-warning">Menunggu</label>
                                        @endif
                                        @if($data->status == \App\Models\ComplaintOrders::STATUS_PROCESS)
                                            <label class="label label-primary">Proses</label>
                                        @endif
                                        @if($data->status == \App\Models\ComplaintOrders::STATUS_DONE)
                                            <label class="label label-success">Selesai</label>
                                        @endif
                                        @if($data->status == \App\Models\ComplaintOrders::STATUS_CANCEL)
                                            <label class="label label-danger">Batal</label>
                                        @endif
                                        @if($data->status == \App\Models\ComplaintOrders::STATUS_REJECTED)
                                            <label class="label label-danger">Ditolak</label>
                                        @endif
                                        @if($data->status == \App\Models\ComplaintOrders::STATUS_PICKUP)
                                            <label class="label label-warning">Jadwal Pengambilan</label>
                                        @endif
                                    </h5>
                                    <div class="form-group" style="padding: 1em">
                                        <table width="100%">
                                            @if($data->status == \App\Models\ComplaintOrders::STATUS_PICKUP)
                                                <tr>
                                                    <td>Jadwal Pengambilan</td>
                                                    <td><strong style="color: green">
                                                            : {{ \Carbon\Carbon::parse($data->date_pickup)->format('d-m-Y') }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Lokasi Pengambilan</td>
                                                    <td><strong style="color: green">
                                                            : {{ $data->location_pickup }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><hr></td>
                                                </tr>
                                            @endif


                                            @if($data->status == \App\Models\ComplaintOrders::STATUS_REJECTED)
                                                <tr>
                                                    <td>Alasan Ditolak</td>
                                                    <td><strong style="color: red">
                                                            : {{ strtoupper($data->reason) }}</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>Tgl Komplain</td>
                                                <td><strong>
                                                        : {{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i:s') }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nama Siswa</td>
                                                <td>
                                                    <strong> : {{ $data->user->name }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>No. Telp</td>
                                                <td><strong> : {{ $data->phone }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td><strong> : {{ $data->email }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Alasan Komplain</td>
                                                <td><strong> : {{ $data->complaintCategory->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Keterangan Komplain</td>
                                                <td><strong> : {{ $data->description }}</strong></td>
                                            </tr>
                                        </table>

                                        <div class="row">
                                            <h5>Lampiran Komplain (Klik Gambar Untuk Zoom)</h5>
                                            <div class="text-title-3 font-italic text-black mt-2"
                                                 style="font-weight: bold">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        @if(@$data->attachment !== null)
                                                            <div
                                                                class="preview-image {{ @$data->attachment !== null ? NULL : 'hide' }}">
                                                                <img src="{{$data->imageAttachment}}"
                                                                     onclick="showImage('{{ $data->imageAttachment }}')"
                                                                     class="header-image" width="300" height="300"/>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if(@$data->attachment_addition !== null)
                                                            <div
                                                                class="preview-image {{ @$data->attachment_addition !== null ? NULL : 'hide' }}">
                                                                <img src="{{$data->imageAddition}}"
                                                                     onclick="showImage('{{ $data->imageAddition }}')"
                                                                     class="header-image" width="300" height="300"/>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if((@$data->attachment_extra !== null))
                                                            <div
                                                                class="preview-image {{ (@$data->attachment_extra !== null) ? NULL : 'hide' }}">
                                                                <img src="{{$data->imageExtra}}"
                                                                     onclick="showImage('{{ $data->imageExtra }}')"
                                                                     class="header-image" width="300" height="300"/>
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
                </div>
            </div>
        </div>
    </div>


    {{--    <div id="lightbox" class="lightbox" onclick="closeLightbox()">--}}
    {{--        <img id="lightbox-img" src="" alt="Zoom">--}}
    {{--        <span class="close-btn">&times;</span>--}}
    {{--    </div>--}}

    <div id="show-image-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Lampiran Komplain</h4>
                </div>
                <div class="modal-body">
                    <img class="header-image" id="lightbox-img" src="" width="500" height="500" alt="Zoom">
                </div>
            </div>
        </div>
    </div>

    <div id="show-rejected-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Alasan Ditolak</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="complaint_id" value=""/>
                    <textarea class="form-control" id="reason" name="reason"></textarea>
                </div>
                <div class="modal-footer">
                    <button id="complaint-rejected" class="btn btn-sm btn-danger complaint-rejected"
                            style="margin-top: 5px">
                        <i class="fa fa-ban"> Tolak Komplain</i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div id="show-pickup-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Atur Pengambilan</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="complaint_id" value=""/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="control-label col-sm-12" for="name">Jadwal Pengambilan<span
                                        style="color: red">*</span>:</label>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="date" name="date-pickup" id="date-pickup" value="" style="width: 100%">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <label class="control-label col-sm-12" for="name">Tempat Pengambilan<span
                                        style="color: red">*</span>:</label>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" name="location-pickup" id="location-pickup" value=""
                                           style="width: 100%">
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button id="complaint-pickup" class="btn btn-sm btn-success complaint-pickup"
                            style="margin-top: 5px">
                        <i class="fa fa-calender-o"> Atur Jadwal</i>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>

        function showImage(imageSrc) {
            $('#lightbox-img').attr("src", '');
            $('#show-image-modal').modal();
            $('#lightbox-img').attr("src", imageSrc)
        }

        $(document).on('click', '#complaint-proccess', function (e) {
            var id = $(this).data('id');
            var flag = 'proccess';
            $.post('{{ route('admin.complaint.change-status') }}', {
                _token: '{{ csrf_token() }}',
                'id': id,
                'flag': flag
            }, function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    swal(
                        'Gagal!',
                        data.message,
                        'error'
                    );
                }
            });
        });

        $(document).on('click', '#complaint-done', function (e) {
            var id = $(this).data('id');
            var flag = 'done';
            $.post('{{ route('admin.complaint.change-status') }}', {
                _token: '{{ csrf_token() }}',
                'id': id,
                'flag': flag
            }, function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    swal(
                        'Gagal!',
                        data.message,
                        'error'
                    );
                }
            });
        });

        $(document).on('click', '#complaint-rejected', function (e) {
            var id = $('#complaint_id').val();
            var reason = $('#reason').val();
            var flag = 'rejected';
            $.post('{{ route('admin.complaint.change-status') }}', {
                _token: '{{ csrf_token() }}',
                'id': id,
                'flag': flag,
                'reason': reason
            }, function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    swal(
                        'Gagal!',
                        data.message,
                        'error'
                    );
                }
            });
        });

        $(document).on('click', '#modal-complaint-rejected', function (e) {
            var id = $(this).data('id');
            $('#complaint_id').val('');
            $('#reason').val('');
            $('#complaint_id').val(id);
            $('#show-rejected-modal').modal();
        });

        $(document).on('click', '#modal-complaint-pickup', function (e) {
            var id = $(this).data('id');
            $('#complaint_id').val('');
            $('#date-pickup').val('');
            $('#complaint_id').val(id);
            $('#show-pickup-modal').modal();
        });

        $(document).on('click', '#complaint-pickup', function (e) {
            var id = $('#complaint_id').val();
            var date = $('#date-pickup').val();
            var location = $('#location-pickup').val();
            var flag = 'pickup';

            var success = true
            var message = 'validasi success';

            if (date == '') {
                success = false;
                message = 'Jadwal pengambilan tidak boleh kosong!';
            }

            if (location == '') {
                success = false;
                message = 'Tempat pengambilan tidak boleh kosong!';
            }
            if (success) {
                $.post('{{ route('admin.complaint.change-status') }}', {
                    _token: '{{ csrf_token() }}',
                    'id': id,
                    'date': date,
                    'location': location,
                    'flag': flag
                }, function (data) {
                    console.log(data)
                    if (data.success) {
                        window.location.reload(true)
                    } else {
                        swal(
                            'Gagal!',
                            data.message,
                            'error'
                        );
                    }
                });
            } else {
                swal(
                    'Gagal!',
                    message,
                    'error'
                );
            }
        });
    </script>
@endpush
