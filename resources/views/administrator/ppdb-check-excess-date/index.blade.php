@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Cek kelebihan pengaturan tanggal pembayaran gedung cicilan</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Cek kelebihan pengaturan tanggal pembayaran gedung cicilan</li>
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
                        <h3>Cek kelebihan pengaturan tanggal pembayaran gedung cicilan</h3>
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
                        
                        <form role="form" method="POST" class="form-horizontal" action="{{ route('admin.ppdb-check-excess-date.check') }}">
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
                                    <button type="submit" class="btn btn-default btn-form-submit">Check</button>
                                </div>
                            </div>
                            @csrf
                        </form>

                        @if(session()->has('ppdbs'))
                        <h4>Result: </h4>
                        <table class="table table-display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Register Number</th>
                                    <th>Unit</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Angsuran 1</th>
                                    <th>Angsuran 2</th>
                                    <th>Angsuran 3</th>
                                    <th>Angsuran 4</th>
                                    <th>Angsuran 5</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach(session('ppdbs') as $ppdb)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $ppdb->name }}</td>
                                    <td>{{ $ppdb->register_number }}</td>
                                    <td>{{ $ppdb->unit->name }}</td>
                                    <td>{{ $ppdb->angsuran_start }}</td>
                                    <td>{{ $ppdb->angsuran_1 }}</td>
                                    <td>{{ $ppdb->angsuran_2 }}</td>
                                    <td>{{ $ppdb->angsuran_3 }}</td>
                                    <td>{{ $ppdb->angsuran_4 }}</td>
                                    <td>{{ $ppdb->angsuran_5 }}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>

                        @endif 
                        
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
                let url = "{{ url('administrator/ppdb-check-excess-date/unit-periods') }}";
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

    </script>
@endpush 
