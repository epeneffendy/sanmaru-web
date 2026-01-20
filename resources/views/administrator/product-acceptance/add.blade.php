@extends('layouts.admin.main')
@section('content')
    @php($action=route('admin.product-acceptance.store'))
    @php($status="Save")
    @php($status_header="Tambah")

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Update Stock</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.product-acceptance.index')}}">Update Stock</a></li>
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
                                <label class="control-label col-sm-2" for="name">Product:</label>
                                <div class="col-sm-6">
                                    <select name="product_id" id="product_id" class="form-control selectpicker" >
                                        <option value="0">== Silahkan Pilih ==</option>
                                        @foreach (@$products as $product)
                                            <option value="{{ $product->id }}" >{{ $product->name .' - '. $product->level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Vendor:</label>
                                <div class="col-sm-6">
                                    <select name="vendor_id" id="vendor_id" class="form-control input-sm">
                                        <option value="0">== Silahkan Pilih ==</option>
                                        @foreach (@$vendors as $vendor)
                                            <option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_date" class="control-label col-sm-2">Tanggal Penerimaan</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="date" id="date" value="{{date('d-m-Y')}}">
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
                            <div id="list_product"></div>



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

    <div class="text-center">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });

        });
        const base_prefix = "/administrator/product-acceptance";

        $('#fetch-data').click(function () {
            let success = true;
            let message = 'validation success';

            let product = $('#product_id').val();

            if(product == 0){
                success = false;
                message = 'Silahkan pilih filter product terlebih dahulu!';
            }

            if(success){
                findOrderByFilter(product)
            }else{
                alert(message)
            }
        });


        function findOrderByFilter(product){
            $.post(
                base_prefix + '/find-by-product',
                {
                    "_token": "{{ csrf_token() }}",
                    'product_id' : product,
                },
                function (data) {
                    $('#list_product').html(data)
                }
            );
        }
    </script>
@endpush
