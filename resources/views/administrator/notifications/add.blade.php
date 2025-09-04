@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Notifications</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Notifications</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget">
                    <div class="widget-header">
                        <h3>Notifications</h3>
                    </div>
                    <div class="widget-content">
                        <div id="alert-message">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        </div>

                        <form role="form" method="POST" class="form-horizontal" id="form-posting" data-action="{{ route('admin.notification.store') }}">
                            @csrf
                            <div class="form-group">
                                <label class="control-label col-sm-2 form-label" for="year">Tahun Ajaran:</label>
                                <div class="col-sm-10">
                                    <select name="year" id="year" class="form-control">
                                        <option value="">Semua</option>
                                        @foreach ($years as $year)
                                        <option value="{{ $year->year }}" {{ old('year') == $year->year }}>{{ $year->year }} - {{ $year->year + 1 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 form-label" for="unit">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit" id="unit" class="form-control">
                                        <option value="">Semua</option>
                                        @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit') === $unit->id ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 form-label" for="period">Periode:</label>
                                <div class="col-sm-10" >
                                    <select name="periode" id="periode" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ppdb_user_id" class="col-sm-2 control-label form-label">Penerima</label>
                                <div class="col-sm-10">
                                    <select name="ppdb_user_id[]" id="ppdb_user_id" class="form-control select-autocomplete" data-style="btn-success" data-dropup-auto="false" data-actions-box="true" multiple>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 form-label" for="title">Judul</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" placeholder="Enter Judul">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 form-label" for="body">Pesan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="body" id="body" rows="3" placeholder="Enter Pesan">{!! old('body') !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <div class="checkbox checkbox-success">
                                        <input type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email', 1) ? 'checked' : '' }}>
                                        <label for="send_email">Kirim email pemberitahuan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn btn-default" id="btn-form-submit">Posting</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection

@push('scripts')
<script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
<script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
<script>
    $(document).ready(function() {
        $('select.select-autocomplete').selectpicker({liveSearch: true});
        var unitId = $("#unit").find(":selected").val()
        var year = $("#year").find(":selected").val()
        fetchPeriodOption(unitId, year)

        $('#btn-form-submit').on('click', function(e) {
            e.preventDefault();
            swal({
                title: "PERHATIAN",
                text: "Apakah anda yakin menyampaikan informasi yang sudah dibuat ?",
                icon: "warning",
                buttons: [
                    'Tidak',
                    'Ya, Saya yakin!'
                ],
                dangerMode: false,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $('#form-posting').attr('action', $('#form-posting').data('action'));
                    $('#form-posting').submit();
                }
            });
        });
    });
    $("#unit").change(function() {
        var unitId = $(this).find(":selected").val()
        var year = $("#year").find(":selected").val()
        fetchPeriodOption(unitId, year)
    })
    $("#year").change(function() {
        var unitId = $("#unit").find(":selected").val()
        var year = $(this).find(":selected").val()
        fetchPeriodOption(unitId, year)
    })
    $("#periode").change(function() {
        var unitId = $("#unit").find(":selected").val()
        var year = $('#year').find(":selected").val()
        var period = $(this).find(":selected").val()
        fetchPPDBUserOption(unitId, year, period)
    })

    function fetchPeriodOption(unitId, year) {
        $("#periode").empty()
        $.get("{{ route('admin.notification.fetch-period') }}", { unit: unitId, year: year }, function(periods) {
            $("#periode").append('<option value="">Semua</option>');
            $.each(periods, function(index, period) {
                element = '<option value="' + period.id + '">[' + period.unit.name + '] ' + period.name + '</option>'
                $("#periode").append(element)
            })
            fetchPPDBUserOption(unitId, year, (periods[0] == undefined ? null : periods[0].id));
        }, 'json');
    }

    function fetchPPDBUserOption(unitId, year, period) {
        $("#ppdb_user_id").empty()
        $.get("{{ route('admin.notification.fetch-ppdb-user') }}", { unit: unitId, year: year, period: period }, function(users) {
            $.each(users, function(index, user) {
                element = '<option value="' + user.id + '">' + user.name + '</option>'
                $("#ppdb_user_id").append(element)
            })
            $('select.select-autocomplete').selectpicker('refresh')
        }, 'json');
    }
</script>
@endpush
