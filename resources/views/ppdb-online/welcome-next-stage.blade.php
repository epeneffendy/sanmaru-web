@extends('layouts.ppdb-online.main')
@section('content')
    <div class="row row-height">
        <div class="col-lg-5 content-left">
            <div class="content-left-wrapper">
                <div>
                    <figure>
                        <img src="{{asset('frontend-ppdb-online/img/welcome-image.svg')}}" alt="" class="img-fluid img-welcome">
                    </figure>
                </div>
            </div>
            <!-- /content-left-wrapper -->
        </div>
        <!-- /content-left -->

        <div class="col-lg-7 content-top" id="start">
            <div id="wizard_container">
                <div id="top-wizard"></div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="header-form">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam-top">
                </div>
                <a href="{{ route('ppdb.logout') }}"><button class="btn btn-outline-success" type="button">LOGOUT</button></a>
                <br>
                <form id="wrapped" method="POST" enctype="multipart/form-data"
                      action="{{route('ppdb.welcome.submit')}}">
                    <div>
                        <p>Selamat Datang,<br><span class="span-name">{{@$user['name']}}</span></p>

                        <div class="icon-step">
                            <img src="{{asset('frontend-ppdb-online/img/icon-step-3.png')}}">
                        </div>

                        <p class="text-welcome">
                            Anda telah selesai melengkapi data yang dibutuhkan.<br/>
                            Berikut merupakan tahapan selanjutnya yang harus terpenuhi.<br/>
                        </p>

                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Tahapan Selanjutnya</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                            @foreach($stages as $stage)
                                <tr>
                                    <td>
                                        {{ $stage->name }}
                                    </td>
                                    <td>
                                        {{ $stage->passed }}
                                    </td>
                                    <td>
                                        @if ($stage->passed === 'LOLOS')
                                            <span class="btn btn-info btn-information" data-target="#information-{{$stage->id}}">informasi</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @if ($stage->passed === 'LOLOS')
                                    <tr>
                                        <td colspan="3" id="information-{{ $stage->id }}" style="display: none">
                                            @if ($stage->note)
                                                <p style="font-weight: 700; padding: 15px; background: rgba(0,200,0,0.1);">
                                                    {!! nl2br($stage->note) !!}
                                                </p>
                                            @endif
                                            {!! $stage->information !!}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    @csrf
                </form>
                @if ($vouchers = \App\Models\Voucher::eligible(request()->session()->get('user')))
                    <b>Voucher yang tersedia</b>
                    <ol>
                    @foreach ($vouchers as $voucher)
                        <li><b>{{ $voucher['code'] }}</b> - {!! $voucher['note'] !!}</li>
                    @endforeach
                    </ol>
                @endif
                <div class="clear-50"></div>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
@endsection
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.btn-information').click(function(e) {
                var target = $(this).data('target');
                if ($(target).hasClass('show')) {
                    $(target).removeClass('show');
                    $(target).hide("fast");
                } else {
                    $(target).addClass('show');
                    $(target).show("slow");
                }
            });

        });
    </script>
@endpush
@push('styles')
    <style>
        p.text-welcome {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: bold;
            font-size: 25px;
            line-height: 29px;
            color: #89998B;
        }

        p.text-welcome b {
            color: #000000;
        }

        .btn-outline-success {
            position: fixed;
            right: 10px;
            top: 10px;
        }
    </style>
@endpush
