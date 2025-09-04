@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Posting ERP</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Posting ERP</li>
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
                        <h3>Posting ERP</h3>
                    </div>
                    <div class="widget-content">

                        <div id="block-progress" style="display:none;">
                            <input type="hidden" value=0 id="total-data">
                            <input type="hidden" value=0 id="progress">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" id="store-progress" role="progressbar" style="width: 0%;"></div>
                            </div>
                        </div>

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
                        
                        <form role="form" method="POST" class="form-horizontal" id="form-posting">
                            <div class="form-group">
                                <label for="category" class="col-sm-2 control-label form-label">Pilih Jenis Posting</label>
                                <div class="col-sm-10">
                                    <select name="category" id="category" class="form-control">
                                        @foreach($categories as $key => $category)
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : NULL }} >{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="unit_id" class="col-sm-2 control-label form-label">Pilih Unit</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option>----</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="period_id" class="col-sm-2 control-label form-label">Pilih Periode</label>
                                <div class="col-sm-10">
                                    <select name="period_id" id="period_id" class="form-control">
                                        <!-- -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default btn-form-submit">Posting</button>
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
    <script>
        $(document).ready(function () {
            $('#block-progress').hide();
            $('#unit_id').change(function () {
                let url = "{{ url('administrator/erp-posting/unit-periods') }}";
                let unitId = $(this).val();
                $('#period_id').html('');
                $.get(`${url}/${unitId}`, function (data) {
                    let html = '';
                    $.each(data, function (index, value) {
                        html += '<option value="'+value.id+'">'+value.name+'</option>';
                    });
                    $('#period_id').append(html);
                });
            });
        });

        $(document).on('click', '.btn-form-submit', function (e) {
            e.preventDefault();
            $('#total-data').val(0);
            $('#progress').val(0);
            $('#alert-message').html('');
            if (confirm("apakah Anda ingin melanjutkan proses data ?")) {
                initData();
            }
        });

        function initData() {
            $('#block-progress').show();
            $.ajax({
                type: "POST",
                url: "{{ url('administrator/erp-posting/init-store') }}",
                data: $('#form-posting').serialize(),
                headers: {'x-csrf-token': "{{ csrf_token() }}"},
                success: function(row) {
                    $('#total-data').val(row);
                    if (row > 0) {
                        process(0);
                    } else {
                        alert('data tidak ditemukan');
                        $('#block-progress').hide();
                    }
                },
                error: function (err) {
                    if (err.status === 422) {
                        let html = '<div class="alert alert-danger"><ul>';
                        $.each(err.responseJSON.errors, function (index, value) {
                            html += '<li>'+value+'</li>';
                        });
                        html +='</ul></div>';
                        $('#alert-message').append(html);
                        $('#block-progress').hide();
                    }
                }
            });
        }

        function process(nRow) {
            var nProgress = $('#total-data').val();
            var nPerPro = nRow * 100 / nProgress;
            if (nPerPro > 100) nPerPro = 100;
            $('#progress').val(nRow);
            $('#store-progress').css("width", nPerPro+"%");
            
            $.ajax({
                type: "POST",
                url: "{{ url('administrator/erp-posting') }}/"+nRow,
                data: $('#form-posting').serialize(),
                headers: {'x-csrf-token': "{{ csrf_token() }}"},
                success: function(row) {
                    if (row > 0) {
                        process(row);
                    } else {
                        $('#alert-message').append(`
                            <div class="alert alert-success">
                                Proses selesai.
                            </div>
                        `);
                    }
                }
            });
        }
    </script>
@endpush 
