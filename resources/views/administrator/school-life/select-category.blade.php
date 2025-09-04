@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting School Life</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Setting School Life</li>
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
                        Select Category
                    </div>
                    
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <p><strong>NB: </strong> klik dan geser tanda <i class="fa fa-exchange"></i> untuk merubah urutan menu pada halaman web.</p>
                            <div id="update_order_status" style="display: none;"></div>
                        </div>
                        <ul id="order_category">
                        @foreach($categories as $category)
                            <li data-category-id="{{ $category->id }}" class="list-group-item">
                                <span class="handle"><i class="fa fa-exchange"></i></span>
                                <a href="{{ route('admin.school-life.index', $category['id']) }}">
                                    <strong>{{ $category->name }}</strong>
                                </a>
                                <span class="badge">{{ $category->schoolLifes->count() }}</span>
                            </li>
                        @endforeach
                        </ul>
                        
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.school-life.category.index') }}" class="btn btn-sm btn-default">
                                <i class="fa fa-cog"></i> Manage Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection
@push('styles')
    <style>
        .list-group-item {
            display: flex;
            align-items:center;
        }

        .handle {
            min-width: 18px;
            height: 15px;
            display: inline-block;
            cursor: move;
            margin-right: 15px;
        }

        #order_category {
            padding-left: 0;
            margin-bottom: 40px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            function updateOrder(orderIds) {
                var updateOrderStatus = $('#update_order_status');
                $.ajax({
                    url: '{{route('admin.school-life.category.order')}}',
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    method: 'POST',
                    data: {ids:orderIds},
                    beforeSend: function () {
                        updateOrderStatus.html('<i class="fa fa-refresh fa-spin"></i> mengupdate...');
                        updateOrderStatus.show();
                    },
                    success: function(data) {
                        if (data.success) { 
                            updateOrderStatus.html('<i class="fa fa-check"></i> '+data.message);
                        }
                    },
                    complete: function() {
                        setTimeout(function() { 
                            updateOrderStatus.hide();    
                        }, 2000);
                    }
                });
            }

            var target = $("#order_category");
            target.sortable({
                handle: '.handle',
                axis: "y",
                update: function (e, ui) {
                    var orderData = target.sortable('toArray', { attribute: 'data-category-id' });
                    updateOrder(orderData.join(','));
                }
            });
        });
    </script>
@endpush

