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
            <div class="col-md-12 pading-b-20 margin-b-20">
                <h4 class="font-title">Hasil Import - Cek Pembayaran Pendaftaran</h4>
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
                </p>
                <form action="{{ route('admin.payment.store') }}" id="cek-pembayaran-form" method="POST">
                    @csrf
                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>Virtual Account Number</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Unit</th>
                                <th>Email Verified</th>
                                <th>Bukti Pembayaran</th>
                                <th>Nominal yang dibayar</th>
                                <th>Pilihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ppdb_users as $key => $user)
                                @if(@$params['payment_method'] == 'cimb')
                                    @php($va_number = $import_datas->get($key)['Virtual Account Number'])
                                    @php($va_amount = $import_datas->get($key)['Virtual Account Amount'])
                                    @php($va_payment_date = $import_datas->get($key)['Posting Date'])
                                @elseif(@$params['payment_method'] == 'mandiri')
                                    @php($va_number = $import_datas->get($key)['NIS'])
                                    @php($va_amount = $import_datas->get($key)['Nominal Pembayaran'])
                                    @php($va_payment_date = $import_datas->get($key)['Tanggal Pembayaran'])
                                @endif
                                <tr>
                                    <td>{{ $va_number }}</td>
                                    <td>
                                        {{ $va_payment_date }}
                                        <input type="date" name="payment_dates[{{$user->id}}]" value={{ date('Y-m-d', strtotime($va_payment_date)) }} style="display:none;">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->unit->name }}</td>
                                    <td>
                                        @if ($user->isEmailVerified)
                                            <i class="fa fa-check" style="color: #266c34;"></i>
                                        @else
                                            <i class="fa fa-times" style="color: #EF4836"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->payment_form)
                                            <a class="btn btn-sm btn-info" href="{{ $user->getPaymentFormImageUrl() }}" target="_blank">klik disini</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ App\Helpers\PriceHelper::rupiah($va_amount) }}
                                    </td>
                                    <td>
                                        @if (!$user->isEmailVerified)
                                            <i class="fa fa-times" style="color: #EF4836"></i>
                                        @elseif ($user->isPaymentStatusVerified)
                                            <i class="fa fa-check" style="color: #266c34;"></i>
                                        @else
                                            <div class="checkbox checkbox-success">
                                                <input id="checkbox-{{ $user->id }}" class="checkbox-users" checked="true" name="users[{{ $user->id }}]" data-payment_date="{{$va_payment_date}}" type="checkbox" value="1">
                                                <label for="checkbox-{{ $user->id }}"></label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if (!$ppdb_users->isEmpty())
                        <button class="btn btn-success" id="button-submit"><i class="fa fa-save"></i> Simpan</button>
                    @endif
                </form>

                @if (count($errors))
                    <p>Daftar data virtual account import yang tidak ada pada data pendaftar</p>
                    <ul>
                    @foreach ($errors as $key => $value)
                        <li><i class="fa fa-times" style="color: #EF4836"></i> {{ $value }}</li>
                    @endforeach
                    </ul>
                @endif
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).on('click', '#button-submit', function(e) {
            e.preventDefault();
            swal({
                title: "PERHATIAN",
                text: "Apakah Anda yakin untuk mengubah status pembayaran?",
                icon: "warning",
                buttons: [
                    'tidak!',
                    'Ya, Saya yakin!'
                ],
                dangerMode: false,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    if ($('.checkbox-users:checked').length) {
                        $('#cek-pembayaran-form').submit();
                    } else {
                        swal('warning', 'tidak ada data pendaftar yang dapat diubah status pembayarannya', 'error');
                    }
                }
            });
        });
    </script>
@endpush
