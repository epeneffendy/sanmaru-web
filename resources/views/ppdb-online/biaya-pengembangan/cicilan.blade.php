@extends('layouts.ppdb-online.main')
@section('content')
    @include('layouts.ppdb-online.tab-bar')
    <div class="container container-biaya">
        <div class="col">
            <div class="biaya-header col">
                <div class="row">
                    <div class="card-green">
                        <span>Nominal uang pengembangan Anda {{ \App\Helpers\PriceHelper::development($ppdb, true) }}</span>
                    </div>
                </div>
                <div class="row mt-4">
                    <p class="text-body-title text-primary-green">Pilih cara pembayaran biaya pengembangan</p>
                </div>
                <div class="row-button-biaya">
                    <a href="{{ route('ppdb.biaya-pengembangan.lunas') }}" class="btn-biaya" id="lunas">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-lunas.png') }}" class="image-passive" alt="">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-lunas-active.png') }}" class="image-active"
                             alt="">
                        <span class="text-body px-2">Pembayaran Lunas</span>
                    </a>
                    <a href="{{ route('ppdb.biaya-pengembangan.cicilan') }}" class="btn-biaya active" id="cicilan">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-cicilan.png') }}" class="image-passive"
                             alt="">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-cicilan-active.png') }}" class="image-active"
                             alt="">
                        <span class="text-body px-2">Pembayaran Cicilan</span>
                    </a>
                </div>
                <div class="row">
                    <p class="text-body">Tidak menemukan pilihan pembayaran? <a
                            href="{{ route('ppdb.biaya-pengembangan.lainnya') }}" id="lainnya">Klik
                            disini</a></p>
                </div>
            </div>

            <div class="nav-back">
                <div class="row mb-3">
                    <a href="{{ URL::previous() }}" class="d-flex align-items-center justify-content-around"><img
                            class="head-left" src="{{ asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png') }}"
                            alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="text-body-title text-primary-green">Pembayaran Cicilan</p>
                    <p class="text-body">Bayar 50% di awal dan dilanjutkan 5 bulan setelah pembayaran awal</p>
                    @php($startDateAngsuran = \App\Helpers\PriceHelper::getDevelopmentStartDateFinance($ppdb))
                    <p class="text-body">Tanggal mulai angsur: {{ \App\Helpers\Helper::tanggal($startDateAngsuran) }}</p>
                    <p class="text-body">Biaya awal: {{ \App\Helpers\PriceHelper::rupiah(0.5 * \App\Helpers\PriceHelper::development($ppdb)) }}</p>
                    <p class="text-body">Sisa angsuran: <span id="sisa-angsuran">{{ \App\Helpers\PriceHelper::rupiah(0.5 * \App\Helpers\PriceHelper::development($ppdb)) }}</span></p>
                </div>
            </div>

            <div class="row my-3">
                <div class="col">
                    <form action="" method="post" >
                        <div id="alert">
                            @if ($errors->any())
                                <div class="alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @csrf
                        <div class="container data-siswa-table">
                            <div class="row table-header-row">
                                <div class="col-4">
                                    <p class="table-header">Bulan</p>
                                </div>
                                <div class="col-8">
                                    <p class="table-header">Nominal</p>
                                </div>
                            </div>

                            <div class="row table-row">
                                <div class="col-4">
                                    <div class="col-3">
                                        <p class="table-row-text text-center">Angsuran pertama</p>
                                    </div>
                                    <div class="col-9">
                                        <input type="date" value="{{ old('angsuran_1', (!empty($ppdb->angsuran_1) ? $ppdb->angsuran_1 : \App\Helpers\Helper::tanggalCicilan($startDateAngsuran) )) }}" name="angsuran_1" class="form-control" onchange="handler(value,0)" id="0">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <input type="number" value="{{ old('cicilan_1', @$ppdb->cicilan_1) ?: $angsuran }}" name="cicilan_1" class="form-control" placeholder=""
                                               onchange="checkCicilan(value,0)" readonly>
                                        <div class="wrapper-content-mobile">
                                            <span id="info_angsuran_1"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row table-row">
                                <div class="col-4">
                                    <div class="col-3">
                                        <p class="table-row-text text-center">Angsuran kedua</p>
                                    </div>
                                    <div class="col-9">
                                        <input type="date" value="{{ old('angsuran_2', @$ppdb->angsuran_2) }}" name="angsuran_2" class="form-control"  onchange="handler(value,1)" id="1">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <input type="text" value="{{ old('cicilan_2', @$ppdb->cicilan_2) ?: $angsuran }}" name="cicilan_2" class="form-control" placeholder=""
                                               onchange="checkCicilan(value,1)" readonly>
                                        <div class="wrapper-content-mobile">
                                            <span id="info_angsuran_2"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row table-row">
                                <div class="col-4">
                                    <div class="col-3">
                                        <p class="table-row-text text-center">Angsuran ketiga</p>
                                    </div>
                                    <div class="col-9">
                                        <input type="date" value="{{ old('angsuran_3', @$ppdb->angsuran_3) }}" name="angsuran_3" class="form-control" onchange="handler(value,2)" id="2">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <input type="text" value="{{ old('cicilan_3', @$ppdb->cicilan_3) ?: $angsuran }}" name="cicilan_3" class="form-control" placeholder=""
                                               onchange="checkCicilan(value,2)" readonly>
                                        <div class="wrapper-content-mobile">
                                            <span id="info_angsuran_3"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row table-row">
                                <div class="col-4">
                                    <div class="col-3">
                                        <p class="table-row-text text-center">Angsuran keempat</p>
                                    </div>
                                    <div class="col-9">
                                        <input type="date" value="{{ old('angsuran_4', @$ppdb->angsuran_4) }}" name="angsuran_4" class="form-control" onchange="handler(value,3)" id="3">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <input type="text" value="{{ old('cicilan_4', @$ppdb->cicilan_4) ?: $angsuran }}" name="cicilan_4" class="form-control" placeholder=""
                                               onchange="checkCicilan(value,3)" readonly>
                                        <div class="wrapper-content-mobile">
                                            <span id="info_angsuran_4"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row table-row">
                                <div class="col-4">
                                    <div class="col-3">
                                        <p class="table-row-text text-center">Angsuran kelima</p>
                                    </div>
                                    <div class="col-9">
                                        <input type="date" value="{{ old('angsuran_5', @$ppdb->angsuran_5) }}" name="angsuran_5" class="form-control" onchange="handler(value,4)" id="4">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <input type="text" value="{{ old('cicilan_5', @$ppdb->cicilan_5) ?: $angsuran }}" name="cicilan_5" class="form-control" placeholder=""
                                               onchange="checkCicilan(value,4)" readonly>
                                        <div class="wrapper-content-mobile">
                                            <span id="info_angsuran_5"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-2">
                            <button type="submit" name="simpan-cicilan" id="simpan-cicilan" class="btn btn-green"
                                    style="padding: 0.5rem 2rem; display: none" >Simpan</button>
                            <button type="submit" name="simpan-cicilan-temp" id="simpan-cicilan-temp" class="btn btn-green"
                                    style="padding: 0.5rem 2rem" onclick="clicked(event)" >Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <form id="form-development" {!! @$ppdb->isCanRepayDevelopmentFee ? NULL : 'style="display: none;"' !!}>
                <div class="row">
                    <div class="col">
                        <input type="hidden" name="development_fee_option" value="cicilan" />
                        <p class="text-body-title text-primary-green">Upload Surat Pernyataan</p>
                        <p class="text-body">Silahkan download form surat pernyataan terlebih dahulu <a href="{{ route('ppdb.download-biaya-pengembangan', ['type' => 'cicilan']) }}" target="_blank">disini</a></p>
{{--                        <p class="text-body">Silahkan lakukan upload form surat pernyataan sebelum tanggal <b>{{$deadline}}</b>, jika anda tidak melakukan upload form surat pernyataan sampai batas waktu yang ditentukan otomatis calon siswa dinyatakan <b>TIDAK LOLOS</b></p>--}}

                        <div class="row" style="margin-bottom: 1em">
                            @if(!empty(@$ppdb['development_statement']))
                                <div class="status-tab status-tab-green" style="margin-top: 15px;" id="message_development_statement" role="alert">
                                    <span class="d-flex align-items-center text-white">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                        <span>Dokumen Sudah Terupload</span>
                                        </a>
                                </div>
                                <br>
                            @endif
                        </div>

                        <div class="upload-image-desktop">
                            <div class="btn-upload">
                                <div class="row justify-content-center align-items-center flex-column">
                                    <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-upload.png') }}" class="mb-3" alt="">
                                    <span class="text-title-3 mb-2">Pilih file dari perangkat komputer Anda</span>
                                    <span class="text-description text-grey mb-3">Support: PDF</span>
                                    <span href="" class="btn btn-grey">
                                        <img src="{{ asset('frontend-ppdb-online/img/Icon/Tab/folder.png') }}" alt="">Browse
                                    </span>
                                    <input type="file" name="development_statement" accept="application/pdf" class="btn btn-grey" id="development_statement" style="width: 135px; z-index: 9999; margin-top: -40px; opacity: 0; cursor: pointer;" />
                                </div>
                            </div>
                        </div>

                        <div class="upload-image-mobile">
                            <div class="d-flex justify-content-center">
                                <span class="btn btn-green upload-image" style="padding: 0.5rem 2rem">Upload Surat Pernyataan</span>
                            </div>
                        </div>

                        <div class="d-flex flex-row">
                            @if(!empty(@$ppdb['development_statement']))
                                <div class="status-tab status-tab-green" style="margin-top: 15px;" id="message_development_statement" role="alert">
                                    <a target="_blank" class="d-flex align-items-center text-white" href="{{ route('ppdb.download-development-statement-letter') }}">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                        <span>Lihat File</span>
                                    </a>
                                </div>
                                <div style="margin-top: 15px;">
                                    <a target="_blank" href="{{ route('ppdb.reset-development-fee.post') }}" class="btn btn-red" style="margin-top: 5px;" id="reset_development_fee_button">Ganti Pembayaran</a>
                                </div>
                            @else
                                <div class="status-tab status-tab-red" id="message_development_statement">
                                    <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                    <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                    <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                    <span>Belum</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .swal-title {
            font-size: 18px;
            margin-left: 4px;
            margin-right: 4px;
        }
        .swal-text {
            text-align: center;
            margin-left: 4px;
            margin-right: 4px;
        }
        .swal-footer {
            text-align: center;
            padding: 17px;
        }
        .swal-button--confirm {
            background-color: #a3dd82;
        }
        .swal-button--cancel {
            background-color: #efefef;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            let angsuran_1 = '{{\App\Helpers\Helper::tanggalCicilan($startDateAngsuran)}}';
            console.log(angsuran_1);
            handler(angsuran_1, 0)

            info_label_angsuran();
        });
        var isEnough = '<div class="alert-success">Jumlah cicilan sudah cukup</div>';
        var isLess = '<div class="alert-danger">Jumlah cicilan masih kurang</div>';
        var isOver = '<div class="alert-yellow">Jumlah cicilan berlebih</div>';

        const biaya = {{ \App\Helpers\PriceHelper::development($ppdb) }};
        const pct = 0.5;
        const installment = biaya * pct;
        var installments = [];

        const startDateAngsuran = "{{ $startDateAngsuran }}";

        function checkCicilan(val, month) {
            installments[month] = parseInt(val);

            if (installments.length != 0) {
                var totalInstallments = installments.reduce((a, b) => a + b);

                $('#sisa-angsuran').html('Rp '+ rupiah(installment - totalInstallments));

                if (totalInstallments == installment) {
                    document.getElementById("alert").innerHTML = isEnough;
                    document.getElementById('simpan-cicilan').disabled = false;
                } else if (totalInstallments > installment) {
                    document.getElementById("alert").innerHTML = isOver;
                    // document.getElementById('simpan-cicilan').disabled = true;
                } else {
                    document.getElementById("alert").innerHTML = isLess;
                    // document.getElementById('simpan-cicilan').disabled = true;
                }
            }

        }

        function rupiah(angka) {
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }

        var isMonthLess = '<div class="alert-danger">Tanggal harus lebih besar dari sebelumnya</div>';
        var isMonthBeforeUndef = '<div class="alert-danger">Tanggal sebelumnya isi terlebih dahulu</div>';
        var isMonthOver5 = '<div class="alert-danger">Maksimal 5 Bulan setelah tanggal mulai angsur</div>';
        var isMonthBeforeStart = '<div class="alert-danger">Tanggal pembayaran tidak boleh kurang dari tanggal mulai angsur</div>';
        var isDefferentMonth = '<div class="alert-danger">Tanggal pembayaran harus beda bulan dari tanggal sebelumnya</div>';

        const startDate = new Date(startDateAngsuran);
        const endDate = new Date(startDateAngsuran);
        endDate.setMonth(startDate.getMonth() + (4 + 1), 0)
        var installmentsDate = [];

        function handler(value, month) {
            var date = new Date(value);

            if (date.getTime() < startDate.getTime()) {
                document.getElementById("alert").innerHTML = isMonthBeforeStart;
                document.getElementById(month).value = "";
                return;
            }else if(date.getTime() > endDate.getTime()){
                document.getElementById("alert").innerHTML = isMonthOver5;
                document.getElementById(month).value = "";
                return;
            }

            if(month > 0){
                var beforeMonth = installmentsDate[month -1];

                if(date.getMonth() == beforeMonth.getMonth()){
                    document.getElementById("alert").innerHTML = isDefferentMonth;
                    document.getElementById(month).value = "";
                    return;
                }
            }

            if (month === 0) {
                installmentsDate[month] = date;
                document.getElementById("alert").innerHTML = "";
            } else {
                if (installmentsDate[month - 1] !== undefined) {
                    if (date.getTime() > installmentsDate[month - 1].getTime()) {
                        installmentsDate[month] = date;
                        document.getElementById("alert").innerHTML = "";
                    } else {
                        document.getElementById("alert").innerHTML = isMonthLess;
                        document.getElementById(month).value = "";
                    }
                } else {
                    document.getElementById("alert").innerHTML = isMonthBeforeUndef;
                    document.getElementById(month).value = "";
                }
            }


            if(month == 0){
                $('#info_angsuran_1').html( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()))  + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) :  ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            }

            if(month == 1){
                $('#info_angsuran_2').html( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()))  + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) :  ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            }

            if(month == 2){
                $('#info_angsuran_3').html( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()))  + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) :  ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            }

            if(month == 3) {
                $('#info_angsuran_4').html( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()))  + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) :  ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            }

            if(month == 4) {
                $('#info_angsuran_5').html( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()))  + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) :  ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            }
        }
        $(document).on('click', '.upload-image', function() {
            $('input[name=development_statement]').trigger('click');
        });

        $(document).on('change',"#development_statement", function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#form-development')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-development-fee')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_development_statement').removeClass("status-tab-green");
                        $('#message_development_statement').removeClass("status-tab-red");
                        $('#message_development_statement').addClass("status-tab-yellow");
                        $('#message_development_statement').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_development_statement').removeClass("status-tab-green");
                        $('#message_development_statement').addClass("status-tab-red");
                        $('#message_development_statement').removeClass("status-tab-yellow");
                        $('#message_development_statement').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_development_statement').addClass("status-tab-green");
                        $('#message_development_statement').removeClass("status-tab-red");
                        $('#message_development_statement').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" class="d-flex align-items-center text-white" href=' + data.preview + '><img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt=""><img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt=""><span>Lihat File</span></a>';
                        $('#message_development_statement').html(html);
                        swal({
                            icon: 'success',
                            title:"Sukses!",
                            text: 'Upload Dokumen Berhasil!',
                        });
                        setTimeout(function() {
                            location.reload()
                        }, 2000);
                    }
                });
                return false;
            }
        });

        $(document).on('click', '#reset_development_fee_button', function (e) {
            e.preventDefault();

            swal({
                title: 'Apakah Anda yakin akan merubah cara pembayaran Anda?',
                text: 'Data sebelumnya akan tergantikan dengan pilihan pembayaran Anda yang baru',
                buttons: [
                    'Tidak',
                    'Ya'
                ],
                icon: "warning"
            })
                .then((value) => {
                    switch (value) {
                        case true:
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                type: "POST",
                                contentType: "JSON",
                                url: "{{ route('ppdb.reset-development-fee.post') }}",
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (data) {
                                    if (data.status === 'success') {
                                        window.location.href = "{{route('ppdb.welcome')}}";
                                    }
                                }
                            });
                            break;
                        default:
                    }
                });
        });

    function clicked(e){
        e.preventDefault();
        let angsuran_1 = $("input[name=angsuran_1]").val();
        let angsuran_2 = $("input[name=angsuran_2]").val();
        let angsuran_3 = $("input[name=angsuran_3]").val();
        let angsuran_4 = $("input[name=angsuran_4]").val();
        let angsuran_5 = $("input[name=angsuran_5]").val();

        if((angsuran_1 && angsuran_2 && angsuran_3 && angsuran_4 && angsuran_5) == ''){
            swal({
                icon: 'warning',
                title:"Gagal",
                text: 'Pastikan Angsuran terisi semua!',
            });
        }else{
            swal({
                title: 'Konfirmasi Pembayaran Cicilan',
                text: 'Skema pembayaran yang anda pilih adalah cicilan, silahkan konfirmasi tanggal angsuran anda dan unduh surat pernyataan bermaterai, unggah kembali melalui sistem dan tunggu proses validasi dari admin',
                buttons: [
                    'Tidak',
                    'Ya'
                ],
                icon: "warning"
            })
                .then((value) => {
                    switch (value) {
                        case true:
                            $('#simpan-cicilan').trigger('click');
                            break;
                        default:
                    }
                });
        }
    }

    function info_label_angsuran(){
        let angsuran_1 = $("input[name=angsuran_1]").val();
        let angsuran_2 = $("input[name=angsuran_2]").val();
        let angsuran_3 = $("input[name=angsuran_3]").val();
        let angsuran_4 = $("input[name=angsuran_4]").val();
        let angsuran_5 = $("input[name=angsuran_5]").val();

        $('#info_angsuran_1').html(angsuran_1);
        $('#info_angsuran_2').html(angsuran_2);
        $('#info_angsuran_3').html(angsuran_3);
        $('#info_angsuran_4').html(angsuran_4);
        $('#info_angsuran_5').html(angsuran_5);
    }
    </script>
@endpush
