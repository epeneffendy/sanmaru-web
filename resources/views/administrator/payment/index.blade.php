@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Cek Pembayaran</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">Cek Pembayaran</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12 pading-b-20">
                <h4 class="font-title">Cek Pembayaran Pendaftaran</h4>
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
                <p>
                    Ini adalah fitur untuk melakukan pengcekan pembayaran PPDB, cara kerjanya adalah
                    <ol>
                        <li>Upload report virtual account dari bank yang berupa file csv</li>
                        <li>Sistem akan melakukan pengecekan apakah data masing-masing virtual account ada atau tidak</li>
                        <li>Sistem akan menampilkan data pendaftar beserta status pebayaran yang virtual account nya tertera dalam file report</li>
                        <li>Jika pendaftar belum melakukan verifikasi email, maka tidak akan bisa diubah status pembayarannya sampai email berhasil diverifikasi</li>
                        <li>Jika pendaftar sudah ter-verifikasi pembayarannya sebelumnya, maka akan muncul informasi (centang) bahwa pendaftar sudah ter-verifikasi sebelumnya</li>
                        <li>Admin bisa melakukan pengecekan dan validasi, apakah data yang dimasukkan sudah sesuai</li>
                    </ol>
                </p>
                <form action="{{ route('admin.payment.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label" for="file">File Report</label>
                        <input type="file" name="file" class="form-control" id="file" accept=".csv" />
                    </div>
                    <div class="form-group">
                        <div class="radio radio-danger radio-inline margin-l-5">
                            <input type="radio" id="cimb" value="cimb" name="payment_method" checked>
                            <label for="cimb"> CIMB Niaga </label>
                        </div>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" id="mandiri" value="mandiri" name="payment_method">
                            <label for="mandiri"> Mandiri </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" type="submit"><i class="fa fa-upload"></i> Import</button>
                        <a class="btn btn-secondary" href="{{ route('admin.payment.history') }}"><i class="fa fa-list"></i> History Import</a>
                    </div>
                </form>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection
