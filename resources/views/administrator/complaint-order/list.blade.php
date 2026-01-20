@extends('layouts.admin.main')
@push('styles')
    <link href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}" rel="stylesheet"/>
    <style>
        /* The container */
        .container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            font-weight: normal;
        }

        /* Hide the browser's default checkbox */
        .container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>
@endpush
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Complaint</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
            <li class="active">Complaint</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">

            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default table-responsive">
                    <div class="panel-title" style="margin-bottom: 1em">
                        Complaint
                        <div class="btn-group padding-t-10 pull-right">
                            <button id="modal-periode" class="btn btn-sm btn-primary">
                                <i class="fa fa-calendar"></i> Periode Komplain
                            </button>
                        </div>

                    </div>

                    <div class="panel-body">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors') !!}
                            </div>
                        @endif

                        <div class="fixed-table-head period">
                            <table id="datatables-complaint-order" class="table display">
                                <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Type Siswa</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Tanggal Komplain</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $key => $item)
                                    <tr>
                                        <td class="text-center">{{$key + 1}}</td>
                                        <td class="text-center">{{$item->user->ppdb->unit->name}}</td>
                                        <td class="text-center">{{$item->user->name}}</td>
                                        <td class="text-center">{{$item->user->type}}</td>
                                        <td class="text-center">{{$item->product->name}}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') }}</td>
                                        <td class="text-center">
                                            @if($item->status == \App\Models\ComplaintOrders::STATUS_WAITING)
                                                <label class="label label-warning">Menunggu</label>
                                            @endif
                                            @if($item->status == \App\Models\ComplaintOrders::STATUS_PROCESS)
                                                <label class="label label-primary">Proses</label>
                                            @endif
                                            @if($item->status == \App\Models\ComplaintOrders::STATUS_DONE)
                                                <label class="label label-success">Selesai</label>
                                            @endif
                                            @if($item->status == \App\Models\ComplaintOrders::STATUS_CANCEL)
                                                <label class="label label-danger">Batal</label>
                                            @endif
                                            @if($item->status == \App\Models\ComplaintOrders::STATUS_REJECTED)
                                                <label class="label label-danger">Ditolak</label>
                                            @endif
                                            @if($item->status == \App\Models\ComplaintOrders::STATUS_PICKUP)
                                                <label class="label label-warning">Menunggu Pengambilan</label>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('admin.complaint.show',$item->id) }}"
                                               class="btn btn-xs btn-warning">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.uniform-deadline.add') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="show-periode-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 30%">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Periode Komplain</h4>
                </div>
                <div class="modal-body">
                    @foreach($periode as $item)
                        <div class="row">
                            <h4>Periode Komplain Siswa {{ ($item->type == 'siswa') ? 'Reguler' : 'PPDB' }}</h4>
                            <div class="row" style="margin-bottom: 1em">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Periode Awal:</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="date_start_{{$item->type}}"
                                               name="date_start" value="{{$item->date_start}}" placeholder="" required {{ ($item->status == 'all') ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-bottom: 1em">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Periode Akhir</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="date_end_{{$item->type}}"
                                               name="date_end" value="{{$item->date_end}}" placeholder="" required {{ ($item->status == 'all') ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-4">&nbsp;</div>
                                    <div class="col-sm-8">
                                        <label class="container">Aktif sesuai periode
                                            <input type="checkbox" id="status_{{$item->type}}" {{ ($item->status == 'period') ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button id="setting_periode" class="btn btn-sm btn-success setting_periode"
                            style="margin-top: 5px">
                        <i class="fa fa-check"> Simpan</i>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/datatables/datatables.css')}}">
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        const base_prefix = "/administrator/complaint/";
        $(document).ready(function () {
            $('#datatables-complaint-order').DataTable();
        });

        $(document).on('click', '#modal-periode', function (e) {
            $('#show-periode-modal').modal();
        });

        $(document).on('change', '#status_siswa', function (e) {
            if ($(this).is(':checked')) {
                $('#date_start_siswa').attr('disabled', false)
                $('#date_end_siswa').attr('disabled', false)
            } else {
                $('#date_start_siswa').attr('disabled', 'disabled')
                $('#date_end_siswa').attr('disabled', 'disabled')
            }
        });

        $(document).on('change', '#status_ppdb', function (e) {
            if ($(this).is(':checked')) {
                $('#date_start_ppdb').attr('disabled', false)
                $('#date_end_ppdb').attr('disabled', false)
            } else {
                $('#date_start_ppdb').attr('disabled', 'disabled')
                $('#date_end_ppdb').attr('disabled', 'disabled')
            }
        });

        $(document).on('click', '#setting_periode', function (e) {
            var date_start_siswa = $('#date_start_siswa').val();
            var date_end_siswa = $('#date_end_siswa').val();
            var date_end_siswa = $('#date_end_siswa').val();
            var status_siswa = $("#status_siswa").is(":checked")
            var date_start_ppdb = $('#date_start_ppdb').val();
            var date_end_ppdb = $('#date_end_ppdb').val();
            var status_ppdb = $("#status_ppdb").is(":checked")

            $.post(
                base_prefix + 'setting-period',
                {
                    "_token": "{{ csrf_token() }}",
                    'date_start_siswa': date_start_siswa,
                    'date_end_siswa': date_end_siswa,
                    'status_siswa': (status_siswa) ? 'period' : 'all',
                    'date_start_ppdb': date_start_ppdb,
                    'date_end_ppdb': date_end_ppdb,
                    'status_ppdb': (status_ppdb) ? 'period' : 'all',
                },
                function (data) {
                    swal('Informasi', 'Periode Komplain Berhasil Disetting!', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            );
        });
    </script>
@endpush
