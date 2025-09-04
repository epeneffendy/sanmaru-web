@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master PPDB</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">PPDB</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">

            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-title">
                        Data Master PPDB
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

                        <div class="button-collection" style="margin: 15px 0">
                            <button class="btn btn-success btn-sm" id="div_export_temp"  style="display: none"><i class="fa fa-file-excel-o"></i> Export</button>
                            <a href="{{ route('admin.ppdb.export', request()->except('page')) }}" download  class="btn btn-success btn-sm div_export" style="display: none"><i class="fa fa-file-excel-o"></i> Export</a>
                            @if (\App\Helpers\Helper::isPpdbRole())
                                <a href="{{ route('admin.ppdb.add') }}" class="btn btn-primary btn-sm"><i
                                        class="fa fa-plus"></i> Tambah data</a>
                            @endif
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET"
                                      action="{{ route('admin.ppdb.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text"
                                           style="display:none;">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="search">Cari</label>
                                            <input type="text" name="search" placeholder="Search"
                                                   value="{{ @$params['search'] }}" class="form-control input-sm"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="scope">Berdasarkan</label>
                                            <select name="scope" class="form-control input-sm">
                                                <option
                                                    value="name" {{ @$params['scope'] == 'name' ? 'selected' : NULL }} >
                                                    Nama
                                                </option>
                                                <option
                                                    value="register_number" {{ @$params['scope'] == 'register_number' ? 'selected' : NULL }}>
                                                    Nomor Registrasi
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="unit">Unit</label>
                                            <select name="unit" id="unit" class="form-control input-sm">
                                                <option value="0">== SEMUA ==</option>
                                                @foreach (@$units as $unit)
                                                    <option
                                                        value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="school_year">Tahun Ajaran</label>
                                            <select name="school_year" id="school_year" class="form-control input-sm">
                                                @php($y = date('Y') + 1)
                                                <option
                                                    value="all" {{ (@$params['school_year'] == 'all') ? 'selected' : NULL }}>
                                                    == SEMUA ==
                                                </option>
                                                @for($i = 2020; $i <= $y; $i++)
{{--                                                    <option--}}
{{--                                                        value="{{ $i }}" {{ (@$params['school_year'] == $i) ? 'selected' : ($i == (now()->month > 6 ? (now()->year + 1) : now()->year) && @$params['school_year'] != 'all' ? 'selected' : NULL) }}>{{ $i }}--}}
{{--                                                        - {{ $i + 1 }}</option>--}}
                                                    <option value="{{ $i }}" {{empty($params) ?  ($i == $y) ? 'selected' : '' : (($params['school_year'] == $i) ? 'selected' : '') }}>{{ $i }} - {{ $i + 1 }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="active_period">Periode</label>
                                            <select name="period" id="periode" class="form-control input-sm">
                                                <option value="" {{ @$params['period'] == '' ? 'selected' : NULL }}>==
                                                    SEMUA ==
                                                </option>
                                                <option
                                                    value="ongoing" {{ @$params['period'] == 'ongoing' ? 'selected' : NULL }}>
                                                    Sedang berlangsung
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="status">Sort by Status</label>
                                            <select name="status" class="form-control input-sm">
                                                <option value="0">== SEMUA ==</option>
                                                <option
                                                    value="email_verified" {{ @$params['status']=='email_verified' ? 'selected' : NULL }}>
                                                    Verified
                                                </option>
                                                <option
                                                    value="payment_status" {{ @$params['status']=='payment_status' ? 'selected' : NULL }}>
                                                    Biaya Formulir
                                                </option>
                                                <option
                                                    value="student_data" {{ @$params['status']=='student_data' ? 'selected' : NULL }}>
                                                    Data
                                                </option>
                                                <option
                                                    value="parent_data" {{ @$params['status']=='parent_data' ? 'selected' : NULL }}>
                                                    Parent
                                                </option>
                                                <option
                                                    value="statement_letter" {{ @$params['status']=='statement_letter' ? 'selected' : NULL }}>
                                                    Surat Pernyataan
                                                </option>
                                                <option
                                                    value="accepted" {{ @$params['status']=='accepted' ? 'selected' : NULL }}>
                                                    Accepted
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="pull-right btn btn-sm btn-success"
                                                    style="margin-left: 5px">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.ppdb.index') }}"
                                               class="pull-right btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="fixed-table-head">
                            <table id="datatables-master-ppdb" class="table table-responsive table-striped display"
                                   style="width: 100%; border-top-width: medium; border-top-style: solid;">
                                <thead>
                                <tr>
                                    <th rowspan="2" class="text-center">No</th>
                                    <th rowspan="2">Detail Calon Siswa</th>
                                    <th colspan="6" style="width: 100px" class="text-center">Status</th>
                                    <th rowspan="2" style="width: 220px" class="text-center">Option</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Verified</th>
                                    <th class="text-center">Biaya Formulir</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Parent</th>
                                    <th class="text-center">Surat Pernyataan</th>
                                    <th class="text-right">Accepted</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($number = ($data->currentPage() - 1) * $data->perPage())
                                @foreach($data as $key => $value)
                                    @php($number++)
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>
                                            <b style="text-transform: uppercase">{{$value['name']}}</b><br/>
                                            <u>{{$value->user->username}}</u><br/>
                                            <label class="label label-info">{{$value->user->email}}</label><br/>
                                            <label class="label label-warning label-sm">no
                                                registrasi: {{$value->register_number}}</label><br/>
                                            <label
                                                class="label label-danger label-sm">{{@$value->unit->name}}</label><br/>
                                            <label class="label label-info label-xs">{{ @$value->period->name }}</label><br/>
                                            <label class="label label-xs"
                                                   style="background-color: gray">{{ $value->origin_school }}</label><br/>
                                            <small>phone: {{$value->user->mobile_phone}}</small><br/>
                                            {{$value->gender}}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="btn btn-circle btn-sm {{ $value->isEmailVerified ? "btn-success" : "btn-danger" }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isEmailVerified)
                                                        <i class="fa fa-check" title="Email Verified"></i>
                                                    @else
                                                        <i class="fa fa-times" title="Email belum Verified"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                            @if (!$value->isEmailVerified)
                                                <br/>
                                                <br/>
                                                <span class="btn btn-sm btn-default send-confirmation"
                                                      data-id="{{ $value->id }}"><i class="fa fa-envelope"></i></span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->isPaymentFormWithVirtualAccount)
                                                @if($value->payment_date == '')
                                                    <span
                                                        class="btn btn-circle btn-sm btn-danger">
                                                        <icon class="icon-times"><i class="fa fa-times"
                                                                                    title="Belum melakukan pembayaran"></i></icon>
                                                    </span>
                                                @else
                                                    <span class="btn btn-circle btn-sm btn-success">
                                                        <icon class="icon-times"><i class="fa fa-check"
                                                                                    title="Pembayaran Terkonfirmasi"></i></icon>
                                                    </span>
                                                    <br/>
                                                    <br/>
                                                    <span
                                                        class="label label-info">Pembayaran Terkonfirmasi</span>
                                                    <br>
                                                    <span
                                                        class="label label-success">Rp.{{number_format($value->total_payment_form)}}</span>
                                                @endif
                                                <br/>
                                                <br/>
                                                @if($value->payment_date == '')
                                                    @if (\App\Helpers\Helper::isVaBcaEnable())
                                                        @if($value->isWaitingPayment)
                                                            @if(@$value->payment_option == 'BCA')
                                                                <a href="{{route('admin.ppdb.check-inquiry-status',@$value->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @else
                                                <span
                                                    class="btn btn-circle btn-sm {{ $value->isPaymentStatusComplete ? "btn-success" : ($value->isPaymentStatusVerified ? "btn-primary" : "btn-danger") }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isPaymentStatusComplete)
                                                        <i class="fa fa-check" title="Lengkap"></i>
                                                    @elseif ($value->isPaymentStatusVerified)
                                                        <i class="fa fa-check" title="Verified"></i>
                                                    @else
                                                        <i class="fa fa-times" title="Belum Lengkap"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                            @endif


                                        </td>
                                        <td class="text-center">
                                            @if(@$value->payment_option == 'BCA')
                                                <span
                                                    class="btn btn-circle btn-sm {{ $value->isDataCompleteWhitoutBca ? "btn-success" : "btn-danger" }}">
                                                    <icon class="icon-plus">
                                                        @if ($value->isDataCompleteWhitoutBca)
                                                            <i class="fa fa-check" title="Lengkap"></i>
                                                        @else
                                                            <i class="fa fa-times" title="Belum Lengkap"></i>
                                                        @endif
                                                    </icon>
                                                </span>
                                            @else
                                                <span
                                                    class="btn btn-circle btn-sm {{ $value->isDataComplete ? "btn-success" : "btn-danger" }}">
                                                    <icon class="icon-plus">
                                                        @if ($value->isDataComplete)
                                                            <i class="fa fa-check" title="Lengkap"></i>
                                                        @else
                                                            <i class="fa fa-times" title="Belum Lengkap"></i>
                                                        @endif
                                                    </icon>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="btn btn-circle btn-sm {{ $value->isParentsComplete ? "btn-success" : "btn-danger" }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isParentsComplete)
                                                        <i class="fa fa-check" title="Lengkap"></i>
                                                    @else
                                                        <i class="fa fa-times" title="Belum Lengkap"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="btn btn-circle btn-sm {{ $value->IsStatementLetterUploaded ? ($value->IsStatementLetterConfirmed ? "btn-success btn-modal-statement-letter-success" : "btn-warning btn-modal-statement-letter") : "btn-danger" }}"
                                                data-id="{{$value->id}}" data-name="{{$value->name}}"
                                                data-register_number="{{$value->register_number}}"
                                                data-unit_id="{{$value->unit->id}}"
                                                data-unit_name="{{$value->unit->name}}">
                                                <icon class="icon-plus">
                                                    @if ($value->IsStatementLetterConfirmed)
                                                        <i class="fa fa-check"></i>
                                                    @elseif ($value->isStatementLetterUploaded)
                                                        <i class="fa fa-question"></i>
                                                    @else
                                                        <i class="fa fa-times"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                            @if ($value->development_fee_option && !$value->isOrderConfirmed)
                                                <button data-toggle="modal"
                                                        data-target="#reset-development-payment-modal"
                                                        class="btn btn-sm btn-warning" style="margin-top: 5px"
                                                        onclick="return confirm('Apakah anda yakin akan mereset tahapan ini? Surat pernyataan akan terhapus');">
                                                    Reset
                                                </button>
                                                <!-- Modal -->
                                                <div id="reset-development-payment-modal" class="modal fade"
                                                     role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal">&times;
                                                                </button>
                                                                <h4 class="modal-title">Nofitication for student</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('admin.ppdb.reset-development-payment-method', $value) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <p style="text-align: left;">Silahkan isi alasan
                                                                        mereset surat pernyataan.</p>
                                                                    <input type="hidden" id="year" name="year"
                                                                           value="{{ $value->school_year }}">
                                                                    <input type="hidden" id="unit" name="unit"
                                                                           value="{{ $value->unit_id }}">
                                                                    <input type="hidden" id="periode" name="periode"
                                                                           value="{{ $value->periode}}">
                                                                    <input type="hidden" id="ppdb_user_id"
                                                                           name="ppdb_user_id[]"
                                                                           value="{{ $value->id}}">
                                                                    <input type="hidden" id="title" name="title"
                                                                           value="[RESET] Surat Pernyataan {{ $value->name }}">
                                                                    <div class="form-group row">
                                                                        <label for="body"
                                                                               class="col-md-4 col-form-label text-md-right">Alasan
                                                                            Reset</label>
                                                                        <div class="col-md-6">
                                                                            <textarea class="form-control" name="body"
                                                                                      id="body" rows="3"
                                                                                      placeholder="Enter Pesan">{!! old('body') !!}</textarea>

                                                                            @error('body')
                                                                            <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-sm-10 col-sm-offset-2">
                                                                            <div class="checkbox checkbox-success">
                                                                                <input type="checkbox" name="send_email"
                                                                                       id="send_email"
                                                                                       value="1" {{ old('send_email', 1) ? 'checked' : '' }}>
                                                                                <label for="send_email">Kirim email
                                                                                    pemberitahuan</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-0"
                                                                         style="text-align: right; padding-right:10px">
                                                                        <button type="submit" class="btn btn-warning">
                                                                            Reset
                                                                        </button>

                                                                        <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">Cancel
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="btn btn-circle btn-sm {{ ($value->isSubmitted && $value->isOrderConfirmed) ? "btn-warning btn-modal-confirm-student" : "btn-danger" }}"
                                                data-id="{{$value->id}}" data-name="{{$value->name}}"
                                                data-register_number="{{$value->register_number}}"
                                                data-unit_id="{{$value->unit->id}}"
                                                data-unit_name="{{$value->unit->name}}"
                                                data-periode="{{$value->periode}}">
                                                <icon class="icon-plus">
                                                    @if ($value->isSubmitted && $value->isOrderConfirmed)
                                                        <i class="fa fa-question"></i>
                                                    @else
                                                        <i class="fa fa-times"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                        </td>

                                        <td class="text-right">
                                            <?php
                                            $show = 'show';
                                            if ($value->isPaymentStatusComplete) {
                                                $show = 'show-payment';
                                            }
                                            ?>
                                            <a href="{{ route('admin.ppdb.'.$show, $value['id']) }}" title="Show"
                                               class="btn btn-xs btn-success">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            {{-- <a href="{{ route('admin.ppdb.edit',$value['id']) }}" title="Edit" class="btn btn-xs btn-info">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a> --}}
                                            <a href="{{ route('admin.ppdb.delete',$value['id']) }}" title="Delete"
                                               class="btn btn-xs btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this item?');">
                                                <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $data->appends(request()->except('page'))->links() }}
                    </div>

                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

    <!-- Modal -->
    <div id="modal-confirmation" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">&nbsp;</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" id="btn-confirm-modal" class="btn btn-success">&nbsp;</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-confirmation-success" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">&nbsp;</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('styles')
    <link href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}" rel="stylesheet"/>
    <style>
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.42;
            border-radius: 15px;
        }

        .btn-circle .fa {
            margin-right: 0;
        }

        .modal-form-label {
            margin: 0;
            padding: 0;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        var classes = {!! json_encode($classes) !!};
        $(document).ready(function () {
            load_filter();

            $('.send-confirmation').click(function (e) {
                var parent = this;
                swal({
                    title: 'Kirim email konfirmasi pendaftaran ?',
                    icon: "info",
                    dangerMode: false,
                    buttons: [
                        'Tidak',
                        'Ya'
                    ],
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        $.post('{{ route("admin.ppdb.send-confirmation", ["id"=>null]) }}/' + $(parent).data('id'), {
                            _token: '{{ csrf_token() }}'
                        });
                        setTimeout(() => {
                            swal({
                                title: 'Email Berhasil di Kirim',
                                icon: "success",
                                dangerMode: false
                            }).then(function (isConfirm) {
                                window.location.reload();
                            });
                        }, 1000);
                    }
                });
            });

            $(document).on('click', '.btn-modal-statement-letter', function (e) {
                e.preventDefault();
                var id = $(this).data('id'),
                    unitId = $(this).data('unit_id'),
                    fileUrl = "{{ route('show_file') }}";

                var html = `
                    <form role="form" action="{{ route("admin.ppdb.confirm-development-statement", ["id"=>null]) }}/` + id + `" method="POST" id="statement-letter-confirmation-form" class="form-horizontal">
                        @csrf
                <input type="hidden" name="id" value="` + id + `" />
                        <div><h4 class="text-primary modal-form-label">` + $(this).data('name') + `</h4></div>
                        <div>` + $(this).data('register_number') + `</div>
                        <div>` + $(this).data('unit_name') + `</div>
                        <div class="pull-right">
                            <a href="{{ route("admin.ppdb.get-development-file", ["id"=>null]) }}/` + $(this).data('id') + `" target="_blank">
                                open new tab
                            </a>
                        </div>
                        <div class="margin-t-5 text-center">
                        <iframe src="{{ route("admin.ppdb.get-development-file", ["id"=>null]) }}/` + $(this).data('id') + `" width="100%" height="300">
                        <div>
                    </form>
                `;
                $('#modal-confirmation .modal-title').html('Konfirmasi Surat Pernyataan Ini ?');
                $('#modal-confirmation .modal-body').html(html);
                $('#btn-confirm-modal').attr('data-id', id);
                $('#btn-confirm-modal').html('Setujui');
                $('#modal-confirmation').modal();
            });

            $(document).on('click', '.btn-modal-statement-letter-success', function (e) {
                e.preventDefault();
                var id = $(this).data('id'),
                    unitId = $(this).data('unit_id'),
                    fileUrl = "{{ route('show_file') }}";

                var html = `
                    <form role="form" action="{{ route("admin.ppdb.confirm-development-statement", ["id"=>null]) }}/` + id + `" method="POST" id="statement-letter-confirmation-form" class="form-horizontal">
                        @csrf
                <input type="hidden" name="id" value="` + id + `" />
                        <div><h4 class="text-primary modal-form-label">` + $(this).data('name') + `</h4></div>
                        <div>` + $(this).data('register_number') + `</div>
                        <div>` + $(this).data('unit_name') + `</div>
                        <div class="pull-right">
                            <a href="{{ route("admin.ppdb.get-development-file", ["id"=>null]) }}/` + $(this).data('id') + `" target="_blank">
                                open new tab
                            </a>
                        </div>
                        <div class="margin-t-5 text-center">
                        <iframe src="{{ route("admin.ppdb.get-development-file", ["id"=>null]) }}/` + $(this).data('id') + `" width="100%" height="300">
                        <div>
                    </form>
                `;
                $('#modal-confirmation-success .modal-title').html('Surat Pernyataan Siswa');
                $('#modal-confirmation-success .modal-body').html(html);
                $('#modal-confirmation-success').modal();
            });

            $(document).on('click', "#btn-confirm-modal", function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#statement-letter-confirmation-form').submit();
            });

            $('.btn-modal-confirm-student').click(function (e) {
                e.preventDefault();
                var id = $(this).data('id'),
                    unitId = $(this).data('unit_id'),
                    periode = $(this).data('periode'),
                    confirmUrl = `{{url('administrator/ppdb/${id}/accept-student')}}`;

                var html = `
                    <form role="form" id="student-confirmation-form" class="form-horizontal">
                        <input type="hidden" name="id" value="` + id + `" />
                        <input type="hidden" name="unit_id" value="` + unitId + `" />
                        <input type="hidden" name="periode" value="` + periode + `" />
                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                        <div class="validation-error"></div>
                        <div><h4 class="text-primary modal-form-label">` + $(this).data('name') + `</h4></div>
                        <div>` + $(this).data('register_number') + `</div>
                        <div>` + $(this).data('unit_name') + `</div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="class_id">Kelas:</label>
                            <div class="col-sm-8"><select name="class_id" id="class_id" class="form-control">
                                <option>--- Pilih kelas ---</option>`;

                $.each(classes, function (key, row) {
                    if (row.unit_id == unitId) {
                        html += `<option value="` + row.id + `">` + row.name + `</option>`
                    }
                });

                html += `</select></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="nis">NIS:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nis" id="nis">
                            </div>
                        </div>
                    </form>
                `;
                $('#modal-confirmation .modal-title').html('Terima menjadi siswa ?');
                $('#modal-confirmation .modal-body').html(html);
                $('#btn-confirm-modal').html('Simpan');
                $('#modal-confirmation').modal();
                $("#btn-confirm-modal").click(function (e) {
                    e.preventDefault();

                    $('#btn-confirm-modal').html('Please Wait');
                    $('#btn-confirm-modal').attr('disabled', true);

                    data = new FormData();
                    data.append('id', $("input[name=id]").val());
                    data.append('unit_id', $("input[name=unit_id]").val());
                    data.append('periode', $("input[name=periode]").val());
                    data.append('nis', $("input[name=nis]").val());
                    data.append('class_id', $("select[name=class_id] option:selected").val());
                    data.append('_token', "{{ csrf_token() }}");

                    $.ajax({
                        data: data,
                        type: "POST",
                        url: confirmUrl,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            console.log(response);
                            swal({
                                title: response.message,
                                icon: "success",
                                dangerMode: false
                            }).then(function (isConfirm) {
                                window.location.reload();
                            });
                        },
                        error: function (response) {
                            var errorHTML = "";
                            errorHTML += '<div class="alert alert-danger">';
                            if (response.status == 422) {
                                let errors = Object.values(response.responseJSON.errors).flat();
                                errorHTML += '<ul>';
                                $.each(errors, function (key, value) {
                                    errorHTML += `<li>${value}</li>`;
                                });
                                errorHTML += '</ul>';
                            } else {
                                errorHTML += response.responseJSON.message;
                            }
                            errorHTML += '</div>';
                            $('.validation-error').html(errorHTML);
                            $('#btn-confirm-modal').html('Simpan');
                            $('#btn-confirm-modal').attr('disabled', false);
                        }
                    })
                });
            })

            $(document).on('click', "#div_export_temp", function () {
                var periode = $('#periode').val();
                var unit = $('#unit').val();
                var school_year = $('#school_year').val();
                var url = window.location.search.substring(1);

                var message = 'Pilih Filter Periode, Unit dan Tahun Ajaran terlebih dahulu!';

                // if  (periode == ''){
                //     message = 'Pilih Periode telebih dahulu!';
                // }

                if  (unit == 0){
                    message = 'Pilih Unit telebih dahulu!';
                }

                if  (school_year == '0'){
                    message = 'Pilih Tahun Ajaran telebih dahulu!';
                }



                if(unit != 0 && school_year != ''){
                    console.log('url' + url)
                    if (url == ''){
                        swal({
                            icon: 'error',
                            title:"Gagal!",
                            text: 'Lakukan pencarian data terlebih dahulu sesuai filter sebelum melakukan export data!',
                        });
                    }
                }else{
                    swal({
                        icon: 'error',
                        title:"Gagal!",
                        text: message,
                    });
                }
            });
        });

        $(document).on('change', '#unit', function (e) {
            load_filter();
        });

        $(document).on('change', '#periode', function (e) {
            load_filter();
        });

        function load_filter(){
            var periode = $('#periode').val();
            var unit = $('#unit').val();
            var school_year = $('#school_year').val();
            var url = window.location.search.substring(1);
            console.log(url)
            if( unit != 0 && school_year != '' && url != ''){
                $('#div_export_temp').hide();
                $('.div_export').show();
                console.log("bisa")
            }else{
                $('#div_export_temp').show();
                $('.div_export').hide();
                console.log("tidak")

            }
        }
        function aaa(){
            console.log("okee")
        }




    </script>
@endpush
