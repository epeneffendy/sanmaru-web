@extends('layouts.admin.main')
@section('content')
        @php($action=route('admin.distribution-order.insert'))
        @php($status="Save")
        @php($status_header="Tambah")

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Distribution Order</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.distribution-order.index')}}">Distribution Order</a></li>
            <li class="active">{{$status_header}}</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget ">
                    <div class="widget-header">
                        <h3>{{$status_header}} Data</h3>
                    </div> <!-- /widget-header -->
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
                        <form role="form" method="POST" action="{{$action}}" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Unit:</label>
                                <div class="col-sm-6">
                                    <select name="unit_id" id="unit_id" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                        @foreach (@$units as $unit)
                                            <option value="{{ $unit->id }}" >{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Siswa:</label>
                                <div class="col-sm-6">
                                    <select name="type_student" id="type_student" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                            <option value="ppdb" >Siswa PPDB</option>
                                            <option value="reguler" >Siswa Reguler</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_date" class="control-label col-sm-2">Tanggal Distribusi</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="date" id="date" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_date" class="control-label col-sm-2">Rentan Waktu Order</label>
                                <div class="col-sm-6">
                                    <input type="text" id="date_range_seragam" name="date_range" placeholder="rentang waktu" value="" class="form-control input-sm date-range-field" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_date" class="control-label col-sm-2">Keterangan</label>
                                <div class="col-sm-6">
                                    <textarea name="description" class="form-control" id="description" cols="30" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" id="fetch-data" class="btn btn-default">Search</button>
                                </div>
                            </div>

                            <hr>
                            <div id="list_order_uniform"></div>



                            @csrf
                        </form>
                    </div>
                </div> <!-- /widget-content -->
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection

@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>

    <script>
        const base_prefix = "/administrator/distribution-order";
        $('.date-range-field').daterangepicker();

        $('#fetch-data').click(function () {
            let success = true;
            let message = 'validation success';

            let unit = $('#unit_id').val();
            let student = $('#type_student').val();
            let date = $('#date').val();
            let date_range = $('#date_range_seragam').val();
            let description = $('#description').val();

            if(unit == 0){
                success = false;
                message = 'Silahkan pilih filter unit terlebih dahulu!';
            }

            if(student == 0){
                success = false;
                message = 'Silahkan pilih filter siswa terlebih dahulu!';
            }

            if(date_range == ''){
                success = false;
                message = 'Silahkan pilih filter Rentan Waktu Order terlebih dahulu!';
            }

            if(success){
                findOrderByFilter(unit, student, date, date_range, description)
            }else{
                alert(message)
            }
        });


        function findOrderByFilter(unit, student, date, date_range, description){
            $.post(
                base_prefix + '/find_uniform_order',
                {
                    "_token": "{{ csrf_token() }}",
                    'unit_id' : unit,
                    'student' : student,
                    'date' : date,
                    'date_range' : date_range,
                    'description' :description
                },
                function (data) {
                    $('#list_order_uniform').html(data)
                }
            );
        }
    </script>
@endpush
