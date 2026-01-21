@extends('layouts.admin.main')
@section('content')
    @php($status="Show")
    @php($status_header="Show")
    @push('style')
        <style>
            td {
                padding: 10px; /* Applies 10px padding on all sides of the cell content */
            }

            /* Reset & Umum */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                padding: 20px;
                background-color: #f5f5f5;
            }

            h1, h2 {
                text-align: center;
                margin-bottom: 20px;
            }

            .gallery {
                margin-bottom: 40px;
            }

            /* Metode 1: Zoom pada Hover */
            .zoom-hover {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
            }

            .image-container {
                width: 250px;
                height: 200px;
                overflow: hidden;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .image-container:hover img {
                transform: scale(1.5); /* Zoom 150% */
            }

            /* Metode 2: Lightbox */
            .lightbox-gallery {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                justify-content: center;
            }

            .lightbox-item {
                width: 200px;
                height: 150px;
                border-radius: 8px;
                overflow: hidden;
                cursor: pointer;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .lightbox-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.2s ease;
            }

            .lightbox-item:hover img {
                transform: scale(1.1);
            }

            /* Lightbox Modal */
            .lightbox {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .lightbox img {
                max-width: 90%;
                max-height: 90%;
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            }

            .close-btn {
                position: absolute;
                top: 20px;
                right: 40px;
                color: white;
                font-size: 40px;
                cursor: pointer;
            }

            .close-btn:hover {
                color: #ff6b6b;
            }
        </style>
    @endpush
    <div class="page-header">
        <h1 class="title">Update Stok</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.product-acceptance.index')}}">Update Stok</a></li>
            <li class="active">{{$status_header}}</li>
        </ol>
    </div>

    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="widget ">

                    <div class="widget-content">
                        <div class="form-horizontal">

                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail Product</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="padding: 1em">
                                        <table width="50%">
                                            <tr>
                                                <td>Product</td>
                                                <td><strong> : {{ ($data->product) ? $data->product->name : '-' }}</strong></td>
                                            </tr>

                                            <tr>
                                                <td>Product Type</td>
                                                <td><strong> : {{ ($data->productType) ? $data->productType->name : '-' }}</strong></td>
                                            </tr>

                                            <tr>
                                                <td>Vendor</td>
                                                <td><strong> : {{ ($data->vendor) ? $data->vendor->name : '-' }}</strong></td>
                                            </tr>

                                            <tr>
                                                <td>Tanggal</td>
                                                <td><strong> : {{ $data->date }}</strong></td>
                                            </tr>

                                            <tr>
                                                <td>Petugas</td>
                                                <td><strong> : {{ ($data->user) ? $data->user->username : '' }}</strong></td>
                                            </tr>
                                        </table>

                                        <hr>
                                        <h4>Detail Stok</h4>

                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Size</th>
                                                <th>Stock</th>
                                                <th>Vendor Price Siswa</th>
                                                <th>Price Siswa</th>
                                                <th>Vendor Price PPDB</th>
                                                <th>Vendor PPDB</th>
                                            </tr>
                                            @foreach($data->details as $item)
                                                <tr style="background: {{ ($item->stock > 0) ? '#dcf8e9' : '' }}">
                                                    <td>{{ $item->size }}</td>
                                                    <td>{{ $item->stock }}</td>
                                                    <td>{{ $item->price_vendor_regular }}</td>
                                                    <td>{{ $item->price_siswa }}</td>
                                                    <td>{{ $item->price_vendor_ppdb }}</td>
                                                    <td>{{ $item->price_ppdb }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>

@endpush
