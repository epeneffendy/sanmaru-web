@extends('layouts.admin.main')
@section('content')
    @if (@$status == 'edit')
        @php($action = route('admin.ppdb-resignation.store', [@$ppdb['id']]))
        @php($status_btn = 'Update')
        @php($status_header = 'Edit')
    @else
        @php($action = route('admin.ppdb-resignation.store'))
        @php($status_btn = 'Save')
        @php($status_header = 'Tambah')
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Pengunduran Diri Siswa</h1>
        <ol class="breadcrumb">
            <li>SPMB</li>
            <li><a href="{{ route('admin.ppdb-resignation.index') }}">Pengunduran Diri Siswa</a></li>
            <li class="active">{{ $status_header }}</li>
        </ol>
    </div>

    <div class="container-padding">
        <div class="row">
            <div class="widget">
                <div class="widget-header">
                    <h3>{{ $status_header }} Data</h3>
                </div>
                <div class="widget-content">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form role="form" method="POST" action="{{ $action }}" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ @$ppdb['id'] }}" name="id" />


                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tahun Ajaran</label>
                            <div class="col-sm-10">
                                <select name="school_year" id="school_year" class="form-control selectpicker"
                                    data-style="btn-success">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($school_year ?? [] as $year)
                                        <option value="{{ $year }}"
                                            {{ @$ppdb['school_year'] == $year ? 'selected' : '' }}>
                                            {{ $year }} - {{ $year + 1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Unit</label>
                            <div class="col-sm-10">
                                <select class="form-control selectpicker" name="unit_id" id="unit_id"
                                    data-style="btn-success">
                                    <option value="">Pilih Unit</option>
                                    @foreach ($units ?? [] as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ @$ppdb['unit_id'] == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Siswa</label>
                            <div class="col-sm-10">
                                <select class="form-control selectpicker" name="ppdb_user_id" id="ppdb_user_id"
                                    data-style="btn-success" data-live-search="true" required>
                                    <option value="">Pilih Siswa</option>
                                </select>
                            </div>

                        </div>


                        <div class="form-group" id="reason_container"
                            style="display: {{ @$ppdb['status'] ? 'block' : 'none' }};">
                            <label class="col-sm-2 control-label">Reason</label>
                            <div class="col-sm-10">
                                <textarea name="reason" id="reason" class="form-control" rows="4" placeholder="Masukkan alasan">{{ @$ppdb['reason'] }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Attachment</label>
                            <div class="col-sm-10">
                                <input type="file" name="attachment" class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                @if (@$ppdb['attachment'])
                                    <div style="margin-top: 10px;">
                                        <a href="{{ $ppdb->getAttachmentImageUrl() }}" target="_blank"
                                            class="btn btn-sm btn-info">Lihat Lampiran</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">{{ $status_btn }}</button>
                                <a href="{{ route('admin.ppdb-resignation.index') }}" class="btn btn-danger">Batal</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('js/bootstrap-select/bootstrap-select.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });

            // Toggle reason field visibility based on status selection
            function toggleReason() {
                if ($('#status').val() !== '') {
                    $('#reason_container').show();
                } else {
                    $('#reason_container').hide();
                    $('#reason').val(''); // Clear reason if status is empty
                }
            }

            // Initialize on load
            toggleReason();

            // Listen for change
            $('#status').on('change', function() {
                toggleReason();
            });
        });

        $(document).ready(function() {
            var unit_id = $('#unit_id').val();
            var school_year = $('#school_year').val();
            if(unit_id && school_year) {
                fetchStudent(unit_id, school_year);
            }
        });

        $(document).on('change', '#unit_id, #school_year', function(e) {
            e.preventDefault();
            var unit_id = $('#unit_id').val();
            var school_year = $('#school_year').val();
            if(unit_id && school_year) {
                fetchStudent(unit_id, school_year);
            }
        });

        function fetchStudent(unit_id, school_year) {
            $("#ppdb_user_id").empty();
            $("#ppdb_user_id").append('<option value="">Pilih Siswa</option>');

            $.get("{{ route('admin.ppdb-resignation.fetch-student') }}", {
                unit_id: unit_id,
                school_year: school_year
            }, function(students) {
                var selectedStudent = '{{ @$arr_student ?? '' }}';
                var arrStudent = [];
                selectedStudent.split(',').forEach(function(v) {
                    arrStudent.push(v)
                });

                $.each(students, function(index, student) {
                    var element = '';
                    if (jQuery.inArray(index.toString(), arrStudent) >= 0) {
                        element = '<option value="' + index + '" selected >' + student + '</option>'
                    } else {
                        element = '<option value="' + index + '"  >' + student + '</option>'
                    }

                    $("#ppdb_user_id").append(element)
                })
                $("#ppdb_user_id").selectpicker("refresh")
            }, 'json')
        }
    </script>
@endpush
