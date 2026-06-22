@extends('layouts.ppdb-online.main')
@section('content')
    <div class="row-height">

        <div class="wrapper-content-desktop">
            @include('layouts.ppdb-online.tab-bar')

            {{-- IF STATUS COMPLETE --}}
            @if ($user->status === \App\Models\PPDBUser::STATUS_COMPLETE)
                @if ($user->payment_option == 'BCA')
                    <div class="container">
                        <div class="" style="padding-bottom: 2rem">
                            <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 1.png') }}" alt=""
                                class="progress-bar-ppdb">
                        </div>
                        <div class="row row-content" style="padding-bottom: unset">
                            <h2 class="text-black">Selamat datang,</h2>
                            <p class="text-subtitle-3">
                                Terima kasih sudah melakukan registrasi PPDB, untuk melanjutkan silahkan selesaikan
                                pembayaran registrasi terlebih dahulu
                            </p>
                        </div>

                        <div class="row row-content" style="padding-top: unset;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="total">
                                        <h2 class="text-black">Total yang harus dibayarkan</h2>
                                        <div class="total-item">
                                            {{ \App\Helpers\PriceHelper::rupiah($user->total_payment_form) }}</div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row row-content" style="padding-top: unset; padding-bottom: unset">
                            <div style="width: 100%">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="text-black">Metode Pembayaran</h3>
                                    <a href="{{ route('ppdb.payment-registration', ['id' => $user->id]) }}"
                                        class="text-subtitle-3 text-grey">lihat detail</a>
                                </div>

                                <div class="pembayaran-item">
                                    <div class="pembayaran-item__title">
                                        @if (!empty($user->payment_option))
                                            Bank {{ $user->payment_option }} <span
                                                class="{{ $user->payment_option }}"></span>
                                        @else
                                            Bank BCA
                                            <span class="BCA"></span>
                                        @endif
                                    </div>
                                    @if ($currentDateTime > $user->expired_at)
                                        <div class="text-danger" style="margin-top: 10px">
                                            <b>**Notes:</b>
                                            Batas waktu pembayaran anda telah berakhir, segera lakukan pembayaran dengan
                                            Virtual Account BCA
                                        </div>
                                        <br />
                                        <button type="button" class="btn btn-green" id="button-bayar-sekarang"
                                            data-id="{{ $user->id }}">Bayar Sekarang
                                        </button>
                                    @else
                                        <div class="pembayaran-item__content">No. VA:
                                            <span id="virtual_account_number">
                                                @if (!empty($user->virtual_account_number))
                                                    {{ $user->virtual_account_number }}
                                                @endif
                                            </span>
                                            &nbsp;&nbsp;<img class="icon-normal"
                                                onclick="CopyToClipboard('virtual_account_number')" id="copy-va"
                                                src="{{ asset('frontend-ppdb-online/img/Icon/Data-Active.png') }}"
                                                alt="Copy" title="Copy">
                                            <br />
                                            <div class="text-danger" style="margin-top: 10px">
                                                <b>**Notes:</b>
                                                Segera lakukan pembayaran sebelum
                                                <b>{{ \App\Helpers\Helper::hariTanggalJam($user->expired_at) }}</b>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                            </div>
                        </div>

                    </div>
                @else
                    <div class="container">
                        <div class="" style="padding-bottom: 2rem">
                            <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 1.png') }}"
                                alt="" class="progress-bar-ppdb">
                        </div>
                        <div class="row row-content">
                            <h2 class="text-black">Selamat datang,</h2>
                            <p class="text-subtitle-3">
                                Terima kasih sudah melakukan registrasi PPDB, untuk melanjutkan silahkan upload file
                                4 </p>
                            <a href="#" class="btn-green" data-toggle="modal" data-target="#buktiModal"><img
                                    src="{{ asset('frontend-ppdb-online/img/Icon/upload.png') }}"
                                    alt=""><span>Upload</span></a>
                        </div>
                    </div>
                @endif

            @endif

            {{-- IF STATUS !( COMPLETE and INCOMPLETE ) --}}
            @if ($user->status != \App\Models\PPDBUser::STATUS_COMPLETE && $user->status != \App\Models\PPDBUser::STATUS_INCOMPLETE)

                <div class="container" style="padding: 3rem">
                    <div class="" style="padding-bottom: 2rem">
                        @if ($user->payment_option == 'BCA')
                            @if ($user->isAccepted)
                                <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 5.png') }}"
                                    alt="" class="progress-bar-ppdb">
                            @elseif ($user->isDataCompleteWhitoutBca && $user->isNewStatusStageDone())
                                <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 4.png') }}"
                                    alt="" class="progress-bar-ppdb">
                            @elseif ($user->isDataCompleteWhitoutBca)
                                <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 3.png') }}"
                                    alt="" class="progress-bar-ppdb">
                            @else
                                <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 2.png') }}"
                                    alt="" class="progress-bar-ppdb">
                            @endif
                        @else
                            @if ($user->isDataComplete && $user->isStatusStageDone())
                                <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 4.png') }}"
                                    alt="" class="progress-bar-ppdb">
                            @elseif ($user->isDataComplete)
                                <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 3.png') }}"
                                    alt="" class="progress-bar-ppdb">
                            @else
                                <img src="{{ asset('frontend-ppdb-online/img/progress-bar/Progress Bar 2.png') }}"
                                    alt="" class="progress-bar-ppdb">
                            @endif
                        @endif

                    </div>


                    @if (!$is_stage_show || $user->status == 'confirmed')
                        <div class="alert mt-2 p-3"
                            style="background-color: #e0f2fe; border: 1px solid #bae6fd; border-radius: 8px;">
                            <h6 class="fw-bold mb-2" style="color: #075985; font-size: 13px;">
                                <i class="fa-solid fa-gift me-1"></i>Informasi
                            </h6>
                            <ul class="mb-0 ps-3" style="color: #075985; font-size: 13px;">
                                <li>Silahkan lengkapi data administrasi calon siswa terlebih dahulu dan tunggu
                                    konfirmasi
                                    email dari kami agar dapat melanjutkan ke tahap berikutnya, terimakasih.
                                </li>
                            </ul>
                        </div>
                    @endif

                    <div class="status-ppdb">
                        <div class="col">
                            <div class="status-container">
                                <div class="status-indicator">
                                    <div class="">
                                        @if (!$user->isAccepted)
                                            <div class="status-circle status-circle-green"></div>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        @if ($user->isAccepted)
                                            <span class="text-subtitle-1 status-detail">
                                                <div class="status-tab status-tab-green-full">
                                                    Pendaftaran anda telah berhasil diselesaikan, silahkan pantau
                                                    informasi
                                                    selanjutnya melalui dashboard
                                                </div>

                                            </span>
                                            <br>
                                        @else
                                            <span class="text-subtitle-1 status-detail">Pendaftaran sudah kami terima,
                                                silahkan melanjutkan pengisian kelengkapan data pada tahap
                                                berikutnya</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="status-indicator">
                                    <div class="">
                                        @if ($user['payment_option'] == 'BCA')
                                            <div
                                                class="status-circle status-circle-{{ @$user->isStatusCompleteWhitoutBca ? 'green' : 'red' }}">
                                            </div>
                                        @else
                                            <div
                                                class="status-circle status-circle-{{ @$user->isStatusComplete ? 'green' : 'red' }}">
                                            </div>
                                        @endif

                                    </div>
                                    <div class="col-5">
                                        <span class="text-subtitle-1 status-detail">Seleksi Administrasi</span>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        @if ($user['payment_option'] == 'BCA')
                                            <div
                                                class="status-tab status-tab-{{ @$user->isDataCompleteWhitoutBca ? 'green' : 'red' }}-full">
                                                {{--                                                class="status-tab status-tab-{{ @$user->isStatusCompleteWhitoutBca ? 'green' : 'red'}}-full"> --}}
                                                <img class="green"
                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                                    alt="">
                                                <img class="red"
                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                                    alt="">
                                                <span>{{ @$user->isDataCompleteWhitoutBca ? 'Lengkap' : 'Belum lengkap' }}</span>
                                                {{--                                                <span>{{ @$user->isStatusCompleteWhitoutBca ? 'Lengkap' : 'Belum lengkap' }}</span> --}}
                                            </div>
                                        @else
                                            <div
                                                class="status-tab status-tab-{{ @$user->isStatusComplete ? 'green' : 'red' }}-full">
                                                <img class="green"
                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                                    alt="">
                                                <img class="red"
                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                                    alt="">
                                                <span>{{ @$user->isStatusComplete ? 'Lengkap' : 'Belum lengkap' }}</span>
                                            </div>
                                        @endif


                                        <div class="status-tab status-tab-grey">
                                            <a href="{{ route('ppdb.data-siswa-ppdb') }}" class="btn-detail">Detail</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="status-line"></div>
                                <div class="status-description">
                                </div>
                            </div>
                            @if ($is_stage_show)
                                @if ($is_stage)
                                    @php
                                        $next_stage = false;
                                        $next_ind = '';
                                    @endphp
                                    @foreach ($stages as $ind => $stage)
                                        @php
                                            if ($ind == 0) {
                                                $next_ind = $ind;
                                                $next_stage = true;
                                            }

                                        @endphp

                                        @if ($next_ind == $ind && $next_stage)
                                            <div class="status-container">
                                                <div class="status-indicator">
                                                    <div class="">
                                                        @if ($stage->passed == '-')
                                                            <div class="status-circle status-circle-yellow"></div>
                                                        @else
                                                            <div
                                                                class="status-circle status-circle-{{ $stage->passed == 'LOLOS' ? 'green' : 'red' }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-5">
                                                        <span
                                                            class="text-subtitle-1 status-detail">{{ $stage->name }}</span>
                                                    </div>

                                                    <div class="col-6 d-flex align-items-center">

                                                        @if ($stage->passed == '-')
                                                            <div class="status-tab status-tab-yellow-full">
                                                                @if ($stage->is_opening_development_feature)
                                                                    @if ($ppdbUser->development_statement == null)
                                                                        <span style="color: #555555"><i
                                                                                class="fa fa-file-excel-o"></i>Info</span>
                                                                    @else
                                                                        <span style="color: #555555"><i
                                                                                class="fa fa-file-excel-o"></i>Menunggu
                                                                            Verifikasi</span>
                                                                    @endif
                                                                @else
                                                                    <span style="color: #555555"><i
                                                                            class="fa fa-file-excel-o"></i>Info</span>
                                                                @endif


                                                            </div>
                                                        @else
                                                            <div
                                                                class="status-tab status-tab-{{ $stage->passed == 'LOLOS' ? 'green' : 'red' }}-full">
                                                                <img class="green"
                                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                                                    alt="">
                                                                <img class="red"
                                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                                                    alt="">
                                                                @if ($stage->passed == 'LOLOS')
                                                                    <span>{{ $stage->passed == 'LOLOS' ? 'TAHAP TERPENUHI' : $stage->passed }}</span>
                                                                @else
                                                                    @if ($stage->is_opening_development_feature)
                                                                        @if ($stage->passed == 'TIDAK LOLOS')
                                                                            <span
                                                                                title="Calon siswa dinyatakan TIDAK LOLOS dikarenakan tidak melakukan pembayaran sampai batas waktu yang di tentukan">{{ $stage->passed }}</span>
                                                                        @else
                                                                            <span
                                                                                title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed }}</span>
                                                                        @endif
                                                                    @else
                                                                        <span
                                                                            title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed == 'TIDAK LOLOS' ? 'BELUM MEMENUHI KRITERIA' : $stage->passed }}</span>
                                                                    @endif
                                                                @endif

                                                            </div>
                                                        @endif
                                                        @if ($stage->passed == 'LOLOS')
                                                            <div class="status-tab status-tab-grey">
                                                                <a href="{{ route('ppdb.informasi-ppdb', ['id' => $stage->id]) }}"
                                                                    class="btn-detail"
                                                                    title="Silahkan klik tombol detail untuk melihat informasi lengkap mengenai tahap ini">Detail</a>
                                                            </div>
                                                        @elseif ($stage->is_opening_development_feature)
                                                            @if ($stage->passed != 'TIDAK LOLOS')
                                                                <div class="status-tab status-tab-grey">
                                                                    {{-- <a href="{{route('ppdb.biaya-pengembangan')}}"
                                                                        class="btn-detail"
                                                                        title="Ini adalah tahap akhir penerimaan murid baru. Silahkan memilih skema pembayaran (lunas/cicilan), unduh dan unggah surat pernyataan  pengembangan yang telah diberi materai.setelah diverifikasi oleh admin maka status akhir putra/putri anda dinyatakan Diterima">Finalisasi
                                                                            Penerimaan</a> --}}
                                                                    <a href="{{ route('ppdb.bills.choise-payment', ['type' => 'development']) }}"
                                                                        class="btn-detail"
                                                                        title="Ini adalah tahap akhir penerimaan murid baru. Silahkan memilih skema pembayaran (lunas/cicilan), unduh dan unggah surat pernyataan  pengembangan yang telah diberi materai.setelah diverifikasi oleh admin maka status akhir putra/putri anda dinyatakan Diterima">Finalisasi
                                                                        Penerimaan</a>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                @if (!$loop->last)
                                                    <div class="status-line"></div>
                                                @endif
                                                <div class="status-description">
                                                </div>
                                            </div>
                                        @elseif($stage->passed == 'LOLOS')
                                            <div class="status-container">
                                                <div class="status-indicator">
                                                    <div class="">
                                                        @if ($stage->passed == '-')
                                                            <div class="status-circle status-circle-yellow"></div>
                                                        @else
                                                            <div
                                                                class="status-circle status-circle-{{ $stage->passed == 'LOLOS' ? 'green' : 'red' }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-5">
                                                        <span
                                                            class="text-subtitle-1 status-detail">{{ $stage->name }}</span>
                                                    </div>

                                                    <div class="col-6 d-flex align-items-center">

                                                        @if ($stage->passed == '-')
                                                            <div class="status-tab status-tab-yellow-full">
                                                                @if ($stage->is_opening_development_feature)
                                                                    @if ($ppdbUser->development_statement == null)
                                                                        <span style="color: #555555"><i
                                                                                class="fa fa-file-excel-o"></i>Info</span>
                                                                    @else
                                                                        <span style="color: #555555"><i
                                                                                class="fa fa-file-excel-o"></i>Menunggu
                                                                            Verifikasi</span>
                                                                    @endif
                                                                @else
                                                                    <span style="color: #555555"><i
                                                                            class="fa fa-file-excel-o"></i>Info</span>
                                                                @endif


                                                            </div>
                                                        @else
                                                            <div
                                                                class="status-tab status-tab-{{ $stage->passed == 'LOLOS' ? 'green' : 'red' }}-full">
                                                                <img class="green"
                                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                                                    alt="">
                                                                <img class="red"
                                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                                                    alt="">
                                                                @if ($stage->passed == 'LOLOS')
                                                                    <span>{{ $stage->passed == 'LOLOS' ? 'TAHAP TERPENUHI' : $stage->passed }}</span>
                                                                @else
                                                                    @if ($stage->is_opening_development_feature)
                                                                        @if ($stage->passed == 'TIDAK LOLOS')
                                                                            <span
                                                                                title="Calon siswa dinyatakan TIDAK LOLOS dikarenakan tidak melakukan pembayaran sampai batas waktu yang di tentukan">{{ $stage->passed }}</span>
                                                                        @else
                                                                            <span
                                                                                title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed }}</span>
                                                                        @endif
                                                                    @else
                                                                        <span
                                                                            title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed == 'TIDAK LOLOS' ? 'BELUM MEMENUHI KRITERIA' : $stage->passed }}</span>
                                                                    @endif
                                                                @endif

                                                            </div>
                                                        @endif
                                                        @if ($stage->passed == 'LOLOS')
                                                            <div class="status-tab status-tab-grey">
                                                                <a href="{{ route('ppdb.informasi-ppdb', ['id' => $stage->id]) }}"
                                                                    class="btn-detail"
                                                                    title="Silahkan klik tombol detail untuk melihat informasi lengkap mengenai tahap ini">Detail</a>
                                                            </div>
                                                        @elseif ($stage->is_opening_development_feature)
                                                            @if ($stage->passed != 'TIDAK LOLOS')
                                                                <div class="status-tab status-tab-grey">
                                                                    <a href="{{ route('ppdb.biaya-pengembangan') }}"
                                                                        class="btn-detail"
                                                                        title="Ini adalah tahap akhir penerimaan murid baru. Silahkan memilih skema pembayaran (lunas/cicilan), unduh dan unggah surat pernyataan  pengembangan yang telah diberi materai.setelah diverifikasi oleh admin maka status akhir putra/putri anda dinyatakan Diterima">Finalisasi
                                                                        Penerimaan</a>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                @if (!$loop->last)
                                                    <div class="status-line"></div>
                                                @endif
                                                <div class="status-description">
                                                </div>
                                            </div>
                                        @endif

                                        @php
                                            if ($stage->passed == 'LOLOS') {
                                                $next_ind = $ind + 1;
                                                $next_stage = true;
                                            } else {
                                                $next_ind = $ind + 1;
                                                $next_stage = false;
                                            }
                                        @endphp
                                    @endforeach
                                @endif
                            @endif
                        </div>
                        <br>
                        <div class="clear-50"></div>
                    </div>

                </div>
            @endif
        </div>

        <div class="wrapper-content-mobile">
            <div class="col-lg-7 content-top" id="start">
                <div id="wizard_container">
                    <div id="top-wizard"></div>
                    <div class="header-form">
                        <img src="{{ asset('frontend-ppdb-online/img/logo-serviam.png') }}" class="logo-serviam-top">
                        <p>Selamat Datang,<br><span class="span-name text-extra-bold">{{ @$user['name'] }}</span></p>
                        <b class="text-tanggal text-center">No registrasi: {{ $user->register_number }} </b>
                        <p class="text-grey text-tanggal">Tanggal
                            Mendaftar: {{ date('d/m/Y', strtotime(@$user['created_at'])) }}</p>
                        <p>{{ @$user['period']['name'] }} <br /> {{ @$user['unit']['name'] }}</p>

                        <a href="{{ route('ppdb.faq-ppdb') }}" class="btn btn-green align-self-center">FAQ</a>
                        @if ($user->status === \App\Models\PPDBUser::STATUS_COMPLETE)
                            @if ($user->payment_option == 'BCA')
                                <br>
                                <p class="text-subtitle-3">
                                    Selamat Datang, Terima kasih sudah melakukan registrasi PPDB, untuk melanjutkan
                                    silahkan
                                    selesaikan
                                    pembayaran registrasi terlebih dahulu
                                </p>
                                <br>
                                <div class="row row-content" style="padding-top: unset;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="total">
                                                <h2 class="text-black">Total yang harus dibayarkan </h2>
                                                <div class="total-item">
                                                    {{ \App\Helpers\PriceHelper::rupiah($user->total_payment_form) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row-content" style="padding-top: unset; padding-bottom: unset">
                                    <div style="width: 100%">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h3 class="text-black">Metode Pembayaran</h3>
                                            {{--                                        <a href="{{route('ppdb.payment-registration',['id'=>$user->id])}}" class="text-subtitle-3 text-grey">lihat detail</a> --}}
                                        </div>

                                        <div class="pembayaran-item">
                                            <div class="pembayaran-item__title">
                                                @if (!empty($user->payment_option))
                                                    Bank {{ $user->payment_option }} <span
                                                        class="{{ $user->payment_option }}"></span>
                                                @else
                                                    Bank BCA
                                                    <span class="BCA"></span>
                                                @endif
                                            </div>
                                            @if ($currentDateTime > $user->expired_at)
                                                <div class="text-danger" style="margin-top: 10px">
                                                    <b>**Notes:</b>
                                                    Batas waktu pembayaran anda telah berakhir, segera lakukan
                                                    pembayaran
                                                    dengan Virtual Account BCA
                                                </div>
                                                <br />
                                                <button type="button" class="btn btn-green" id="button-bayar-sekarang"
                                                    data-id="{{ $user->id }}">Bayar Sekarang
                                                </button>
                                            @else
                                                <p style="text-align: left; font-weight: bold">Nomor Virtual Account</p>
                                                <span id="virtual_account_number_mobile"
                                                    style="font-size: 18px; color: #2b542c">
                                                    @if (!empty($user->virtual_account_number))
                                                        {{ $user->virtual_account_number }}
                                                    @endif
                                                </span>
                                                &nbsp;&nbsp;<img class="icon-normal"
                                                    onclick="CopyToClipboardMobile('virtual_account_number_mobile')"
                                                    id="copy-va"
                                                    src="{{ asset('frontend-ppdb-online/img/Icon/Data-Active.png') }}"
                                                    alt="Copy" title="Copy">
                                                <br />
                                                <br />
                                                <div class="text-danger" style="margin-top: 10px">
                                                    <b>**Notes:</b>
                                                    Segera lakukan pembayaran sebelum
                                                    <b>{{ \App\Helpers\Helper::hariTanggalJam($user->expired_at) }}</b>
                                                </div>
                                        </div>
                            @endif
                    </div>
                </div>
            </div>
        @else
            @if ($user->status === \App\Models\PPDBUser::STATUS_COMPLETE)
                <a href="#" class="btn btn-grey align-self-center" data-toggle="modal"
                    data-target="#buktiModal">Upload Bukti Daftar</a>
            @endif
            @endif
            @endif
        </div>

        @if ($user->status != \App\Models\PPDBUser::STATUS_COMPLETE && $user->status != \App\Models\PPDBUser::STATUS_INCOMPLETE)
            <div class="status-ppdb">
                <div class="col">
                    <div class="status-container">
                        <div class="status-indicator">
                            <div class="status-circle status-circle-green"></div>
                            <div class="status-line"></div>
                        </div>
                        <div class="status-description">
                            <p class="text-body status-detail">Seleksi Administrasi</p>
                            @if ($user->payment_option == 'BCA')
                                <div class="d-flex align-items-center">
                                    <div
                                        class="status-tab status-tab-{{ @$user->isDataCompleteWhitoutBca ? 'green' : 'red' }}">
                                        {{--                                            <div class="status-tab status-tab-{{ @$user->isStatusCompleteWhitoutBca ? 'green' : 'red' }}"> --}}
                                        <img class="green"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                            alt="">
                                        <img class="red"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                            alt="">
                                        <span>{{ @$user->isDataCompleteWhitoutBca ? 'Lengkap' : 'Belum lengkap' }}</span>
                                        {{--                                                <span>{{ @$user->isStatusCompleteWhitoutBca ? 'Lengkap' : 'Belum lengkap' }}</span> --}}
                                    </div>
                                    <div class="status-tab status-tab-grey">
                                        <a href="{{ route('ppdb.data-siswa-ppdb') }}" class="btn-detail">detail</a>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="status-tab status-tab-{{ @$user->isStatusComplete ? 'green' : 'red' }}">
                                        <img class="green"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                            alt="">
                                        <img class="red"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                            alt="">
                                        <span>{{ @$user->isStatusComplete ? 'Lengkap' : 'Belum lengkap' }}</span>
                                    </div>
                                    <div class="status-tab status-tab-grey">
                                        <a href="{{ route('ppdb.data-siswa-ppdb') }}" class="btn-detail">detail</a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                    @if ($is_stage)
                        @php
                            $next_stage = false;
                            $next_ind = '';
                        @endphp
                        @foreach ($stages as $ind => $stage)
                            @php
                                if ($ind == 0) {
                                    $next_ind = $ind;
                                    $next_stage = true;
                                }
                            @endphp

                            @if ($next_ind == $ind && $next_stage)
                                <div class="status-container">
                                    <div class="status-indicator">
                                        @if ($stage->passed == '-')
                                            <div class="status-circle status-circle-yellow"></div>
                                        @else
                                            <div
                                                class="status-circle status-circle-{{ $stage->passed == 'LOLOS' ? 'green' : 'red' }}">
                                            </div>
                                        @endif
                                        @if (!$loop->last)
                                            <div class="status-line"></div>
                                        @endif
                                    </div>
                                    <div class="status-description">
                                        <p class="text-body status-detail">{{ $stage->name }}</p>
                                        <div class="d-flex align-items-center">
                                            @if ($stage->passed == '-')
                                                <div class="status-tab status-tab-yellow-full">
                                                    @if ($stage->is_opening_development_feature)
                                                        @if ($ppdbUser->development_statement == null)
                                                            <span style="color: #555555"><i
                                                                    class="fa fa-file-excel-o"></i>Info</span>
                                                        @else
                                                            <span style="color: #555555"><i
                                                                    class="fa fa-file-excel-o"></i>Menunggu
                                                                Verifikasi</span>
                                                        @endif
                                                    @else
                                                        <span style="color: #555555"><i
                                                                class="fa fa-file-excel-o"></i>Info</span>
                                                    @endif
                                                </div>
                                            @else
                                                <div
                                                    class="status-tab status-tab-{{ @$stage->passed == 'LOLOS' ? 'green' : 'red' }}">
                                                    <img class="green"
                                                        src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                                        alt="">
                                                    <img class="red"
                                                        src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                                        alt="">

                                                    @if ($stage->passed == 'LOLOS')
                                                        <span>{{ $stage->passed == 'LOLOS' ? 'TAHAP TERPENUHI' : $stage->passed }}</span>
                                                    @else
                                                        @if ($stage->is_opening_development_feature)
                                                            @if ($stage->passed == 'TIDAK LOLOS')
                                                                <span
                                                                    title="Calon siswa dinyatakan TIDAK LOLOS dikarenakan tidak melakukan pembayaran sampai batas waktu yang di tentukan">{{ $stage->passed }}</span>
                                                            @else
                                                                <span
                                                                    title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed }}</span>
                                                            @endif
                                                        @else
                                                            <span
                                                                title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed == 'TIDAK LOLOS' ? 'BELUM MEMENUHI KRITERIA' : $stage->passed }}</span>
                                                        @endif
                                                    @endif

                                                </div>
                                            @endif

                                            @if ($stage->passed == 'LOLOS')
                                                <div class="status-tab status-tab-grey">
                                                    <a href="{{ route('ppdb.informasi-ppdb', ['id' => $stage->id]) }}"
                                                        class="btn-detail">Detail</a>
                                                </div>
                                            @elseif ($stage->is_opening_development_feature)
                                                @if ($stage->passed != 'TIDAK LOLOS')
                                                    <div class="status-tab status-tab-grey">
                                                        <a href="{{ route('ppdb.biaya-pengembangan') }}"
                                                            class="btn-detail">Detail</a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($stage->passed == 'LOLOS')
                                <div class="status-container">
                                    <div class="status-indicator">
                                        @if ($stage->passed == '-')
                                            <div class="status-circle status-circle-yellow"></div>
                                        @else
                                            <div
                                                class="status-circle status-circle-{{ $stage->passed == 'LOLOS' ? 'green' : 'red' }}">
                                            </div>
                                        @endif
                                        @if (!$loop->last)
                                            <div class="status-line"></div>
                                        @endif
                                    </div>
                                    <div class="status-description">
                                        <p class="text-body status-detail">{{ $stage->name }}</p>
                                        <div class="d-flex align-items-center">
                                            @if ($stage->passed == '-')
                                                <div class="status-tab status-tab-yellow-full">
                                                    @if ($stage->is_opening_development_feature)
                                                        @if ($ppdbUser->development_statement == null)
                                                            <span style="color: #555555"><i
                                                                    class="fa fa-file-excel-o"></i>Info</span>
                                                        @else
                                                            <span style="color: #555555"><i
                                                                    class="fa fa-file-excel-o"></i>Menunggu
                                                                Verifikasi</span>
                                                        @endif
                                                    @else
                                                        <span style="color: #555555"><i
                                                                class="fa fa-file-excel-o"></i>Info</span>
                                                    @endif
                                                </div>
                                            @else
                                                <div
                                                    class="status-tab status-tab-{{ @$stage->passed == 'LOLOS' ? 'green' : 'red' }}">
                                                    <img class="green"
                                                        src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                                        alt="">
                                                    <img class="red"
                                                        src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                                        alt="">

                                                    @if ($stage->passed == 'LOLOS')
                                                        <span>{{ $stage->passed == 'LOLOS' ? 'TAHAP TERPENUHI' : $stage->passed }}</span>
                                                    @else
                                                        @if ($stage->is_opening_development_feature)
                                                            @if ($stage->passed == 'TIDAK LOLOS')
                                                                <span
                                                                    title="Calon siswa dinyatakan TIDAK LOLOS dikarenakan tidak melakukan pembayaran sampai batas waktu yang di tentukan">{{ $stage->passed }}</span>
                                                            @else
                                                                <span
                                                                    title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed }}</span>
                                                            @endif
                                                        @else
                                                            <span
                                                                title="Finalisasi penerimaan murid baru belum lengkap. Anda masih bisa mengganti pilihan pembayaran (lunas/cicilan). Silahkan klik finalisasi penerimaan kembali dan unggah surat pernyataan kembali agar status dapat dinyatakan DITERIMA">{{ $stage->passed == 'TIDAK LOLOS' ? 'BELUM MEMENUHI KRITERIA' : $stage->passed }}</span>
                                                        @endif
                                                    @endif

                                                </div>
                                            @endif

                                            @if ($stage->passed == 'LOLOS')
                                                <div class="status-tab status-tab-grey">
                                                    <a href="{{ route('ppdb.informasi-ppdb', ['id' => $stage->id]) }}"
                                                        class="btn-detail">Detail</a>
                                                </div>
                                            @elseif ($stage->is_opening_development_feature)
                                                @if ($stage->passed != 'TIDAK LOLOS')
                                                    <div class="status-tab status-tab-grey">
                                                        <a href="{{ route('ppdb.biaya-pengembangan') }}"
                                                            class="btn-detail">Detail</a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php
                                if ($stage->passed == 'LOLOS') {
                                    $next_ind = $ind + 1;
                                    $next_stage = true;
                                } else {
                                    $next_ind = $ind + 1;
                                    $next_stage = false;
                                }
                            @endphp
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
        <br>
        <div class="clear-50"></div>
    </div>
    <!-- /Wizard container -->
    </div>
    </div>
    <!-- /content-right-->
    <div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-pembayaran">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="wrapped" method="POST" enctype="multipart/form-data"
                        action="{{ route('ppdb.welcome.submit') }}">
                        <div>

                            <div class="modal-container">
                                @if (empty(@$user['payment_form']))
                                    <label for="payment_form" class="custom-file-upload">
                                        <img src="{{ asset('frontend-ppdb-online/img/Icon/Folder.png') }}"
                                            alt="">
                                    </label>
                                    <input type="file" name="payment_form" accept="image/x-png,image/jpeg"
                                        class="custom-file-input" id="payment_form"
                                        aria-describedby="inputGroupFileAddon01">
                                    <p class="text-title-2">Pilih file dari perangkat komputer Anda.</p>
                                    <p class="text-title-2 text-grey">Supports: JPG, JPEG, PDF</p>
                                @endif

                                @if (!empty(@$user['payment_form']))
                                    <img src="{{ asset('frontend-ppdb-online/img/Icon/Wait.png') }}" alt="">
                                    <h1 class="text-orange">Menunggu Validasi</h1>
                                    <p class="text-title-2">
                                        Tunggu validasi dari sekolah ya
                                    </p>
                                @endif
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .total-item {
            /* font-family: Acumin Pro; */
            font-style: italic;
            font-weight: bold;
            font-size: 24px;
            line-height: 24px;
            color: #42B549;
        }
    </style>
@endpush
@push('scripts')
    <!-- Wizard script -->
    <script src="{{ asset('js/sweet-alert/sweet-alert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            @if (session('message'))
                swal({
                    title: 'Berhasil!',
                    text: "Data Anda berhasil disimpan",
                    icon: "success",
                });
                return;
            @endif
            @if (session('errors'))
                swal({
                    title: 'Gagal!',
                    text: "Data Anda gagal disimpan",
                    icon: "errors",
                });
                return;
            @endif
            @if (session('unread_notification') && count($notifications) > 0)
                swal({
                    title: 'Anda memiliki notifikasi',
                    text: "Mohon periksa tab notifikasi Anda.",
                    icon: "info",
                });

                @php(session()->forget('unread_notification'))
                return;
            @endif
        });
        $(document).on('change', "#payment_form", function() {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{ route('ppdb.upload-payment-form') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#message_payment_form').removeClass("alert-success");
                        $('#message_payment_form').removeClass("alert-danger");
                        $('#message_payment_form').addClass("alert-info");
                        $('#message_payment_form').text("Uploading...");
                        $('.input-group-notice').html('');
                    },
                    error: function(data) {
                        $('#message_payment_form').removeClass("alert-success");
                        $('#message_payment_form').addClass("alert-danger");
                        $('#message_payment_form').removeClass("alert-info");
                        $('#message_payment_form').text("Belum Lengkap");
                    },
                    success: function(data) {
                        $('#message_payment_form').addClass("alert-success");
                        $('#message_payment_form').removeClass("alert-danger");
                        $('#message_payment_form').removeClass("alert-info");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_payment_form').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        swal("Sukses upload Image\nbukti pembayaran akan diproses oleh admin. \nAnda akan mendapatkan konfirmasi via email.", {
                            icon: "success"
                        });
                    }
                });
                return false;
            }
        });

        $(document).on('click', "#button-bayar-sekarang", function() {
            var self = $(this);
            var id = $(this).data("id");

            $.post('{{ route('ppdb.repayment-registration') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
            }, function(data, status) {
                swal({
                    title: 'Berhasil!',
                    text: "Virtual Account anda telah di perbaruhi, segera lakukan pembayaran!",
                    icon: "success",
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2000);

            });
        });

        function CopyToClipboard(id) {
            var r = document.createRange();
            r.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(r);
            try {
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
                console.log('Successfully copy text: hello world ' + r);
                swal({
                    text: "Virtual Account berhasil dicopy!",
                });
            } catch (err) {
                console.log('Unable to copy!');
            }
        }

        function CopyToClipboardMobile(id) {
            var r = document.createRange();
            r.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(r);
            try {
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
                console.log('Successfully copy text: hello world ' + r);
                swal({

                    text: "Virtual Account berhasil dicopy!",
                });
            } catch (err) {
                console.log('Unable to copy!');
            }
        }
    </script>
@endpush
