@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Pengambilan Pesanan</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li class="active">Pengambilan Pesanan</li>
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
                        Pengambilan Pesanan
                    </div>
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        {{-- @if ($exportMessage = (new \App\Lib\ExportJob())->message(request()->all(), auth()->user()))
                            <div class="alert alert-success">
                                {!! $exportMessage !!}
                            </div>
                        @endif --}}
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif
                        <div role="tabpanel" style="margin-bottom: 10px">
                            <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist">
                                @foreach ($product_orders as $tab => $data)
                                <li role="presentation" class="">
                                    <a href="#{{ $tab }}" aria-controls="{{$tab}}" data-toggle="tab" aria-expanded="false">{{ strtoupper($tab)
                                        }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-content">
                            @foreach ($product_orders as $tab => $values)
                            <div role="tabpanel" class="tab-pane" id="{{ $tab }}">
                                @include('administrator.product-order-pickup.list-' . $tab, ['values' => $values])
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
@endsection
@push('styles')
    <style>
        .button-collection {
            margin-bottom: 5px;
        }

        .d-block {
            display: block;
        }

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
            margin: 0 auto;
        }
    </style>
@endpush
@push('scripts')
    <script>
        var url = document.location.toString();
        var activeTab = `{{ $product_orders->keys()->first() ?? NULL }}`;
        var urlProductOrderPickup = "{{ url('administrator/product-order-pickup/pickup/') }}";
        var urlSendPickupConfirmation = "{{ url('administrator/product-order-pickup/send-pickup-confirmation/') }}";
        $(document).ready(function () {
            $(".pickupchecker").on("change", function() {
                var orderId = $(this).attr("data-orderid");
                var isPickup = $(this).is(':checked');

                if (isPickup) {
                    if (confirm("Apakah anda yakin untuk mengupdate status pengambilan?")) {
                        confirmPickup(orderId)
                        .then(data => {
                            $('#pickup-message-'+orderId).html("<small class='color7'>"+data.message+"</small>");
                            $(this).prop('disabled', true);
                            sendMailConfirmation(orderId);
                        })
                        .catch(error => {
                            $('#pickup-message-'+orderId).html("<small class='color10'>"+error.message+"</small>");
                            $(this).prop('checked', false);
                        });
                    } else {
                        $(this).prop('checked', false);
                    }
                }
            })
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
        });


        async function confirmPickup(id) {
            const response = await fetch(`${urlProductOrderPickup}/${id}`, {
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                method: "PUT",
                credentials: "same-origin",
                body: JSON.stringify({
                    id: id,
                }),
            })

            if (!response.ok) {
                const message = `An error has occured: ${response.status}`;
                throw new Error(message);
            }

            const data = await response.json();
            return data;
        }

        async function sendMailConfirmation(id) {
            const response = await fetch(`${urlSendPickupConfirmation}?id=${id}`);
            if (!response.ok) {
                const message = `An error has occured: ${response.status}`;
                throw new Error(message);
            }

            const data = await response.json();
            return data;
        }

    </script>
@endpush

