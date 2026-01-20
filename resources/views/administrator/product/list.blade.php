@extends('layouts.admin.main')

@inject('productTypeEnum', 'App\Enums\ProductTypeEnum')

@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Product</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li class="active">Product</li>
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
                        Data Master Product
                    </div>
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif
                        <div role="tabpanel">
                            <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist">
                                @foreach ($collections as $tab => $data)
                                    @if($tab == 'seragam')
                                        {{--                                    <li role="presentation" class="{{ $activeTab == $tab ? 'active' : null }}">--}}
                                        {{--                                        <a id="#{{ $tab }}Tab" href="#{{ $tab }}" aria-controls="{{$tab}}" data-toggle="tab" aria-expanded="false">{{ strtoupper($tab) }}</a>--}}
                                        {{--                                    </li>--}}
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-content">
                            @foreach ($collections as $tab => $data)
                                <div role="tabpanel" class="tab-pane" id="{{ $tab }}">
                                    @include('administrator.product.table.list_' . $tab, $data)
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>

    <!-- END CONTAINER -->
    <!-- Modal -->
    <div id="import-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Import Product</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form class="fieldset-form" method="POST" enctype="multipart/form-data" action={{ route('admin.product.import')}}>
                            @csrf
                            <fieldset>
                                <legend>Import menggunakan template .xls</legend>
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" style="margin-top: -7px;" value="add" name="type" checked=""> Tambah Data</label>
                                    <label class="radio-inline"><input type="radio" style="margin-top: -7px;" value="overwrite" name="type" checked=""> Perbaharui Data</label>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-default btn-upload-import" type="submit"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="result">
                    </div>
                    <div class="loadings" style="display:none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal -->
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/sweet-alert/sweet-alert.css') }}" />
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
        .button-action {
            margin-bottom: 5px;
        }
        .swal-title {
            color:red;
        }
        .swal-text {
            text-align: center;
        }
    </style>
@endpush
@push('scripts')
<script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('.btn-upload-modal').click(function(e) {
                e.preventDefault();
                $('#import-modal').modal();
            });
        });
    </script>
    <script>
        var url = document.location.toString();
        var activeTab = `{{ $collections->keys()->first() ?? NULL }}`;

        $(document).ready(function () {
            if (url.match('#')) {
                activeTab = url.split('#')[1];
            }

            if (activeTab) {
                $('a[href="#'+activeTab+'"]').parent().addClass('active');
                $('#'+activeTab).addClass('active in')
            }

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });
        })

    </script>
    <script type="text/javascript">
        // SweetAlert Alert
        function showAlert() {
            window.swal({
                title: "You can't delete a released product",
                icon: "error",
                text: "Maaf, anda tidak bisa menghapus produk ini karena masih ada pesanan aktif produk terkait",
                // cancelButtonText: "Cancel",
                showCancelButton: false,
                showConfirmButton: false,
            }).then(console.log)
                .catch(console.error);
        }

        @if(session('recorded'))
            showAlert();
            @endif
    </script>
@endpush
