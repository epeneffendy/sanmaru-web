@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.vendor.update',array($vendor['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.vendor.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Vendor</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.vendor.index')}}">Vendor</a></li>
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
                        <h3>{{$status_header}} Vendor</h3>
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
                            <input type="hidden" value="{{@$vendor['id']}}" name="id" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Vendor Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{old('name') ?? @$vendor['name']}}" placeholder="Enter name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="email" id="email"
                                           value="{{old('email') ?? @$vendor['email']}}" placeholder="Enter email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="address">Alamat:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address" id="address"
                                              placeholder="Enter address" required>{{old('address') ?? @$vendor['address']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="city">City:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="city" id="city"
                                           value="{{old('city') ?? @$vendor['city']}}" placeholder="Enter city" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mobile_phone">Mobile Phone:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="mobile_phone" id="mobile_phone"
                                           value="{{old('mobile_phone') ?? @$vendor['mobile_phone']}}" placeholder="Enter phone number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="pic">PIC Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="pic" id="pic"
                                           value="{{old('pic') ?? @$vendor['pic']}}" placeholder="Enter PIC" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="nota_number">Nota Number:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nota_number" id="nota_number"
                                           value="{{old('nota_number') ?? @$vendor['nota_number']}}" placeholder="Enter nota number" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="nota_date">Nota Date:</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="nota_date" id="nota_date"
                                           value="{{old('nota_date') ?? @$vendor['nota_date']}}" placeholder="Enter nota date" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
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
