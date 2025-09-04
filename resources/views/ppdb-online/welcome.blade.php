@extends('layouts.ppdb-online.main')
@section('content')
@php
    $uploadsForm = \App\Helpers\InputCollectionHelper::uploads($user->unit, null, $user);
@endphp


{{-- <div class="wrapper-content-mobile"> --}}
    <div class="row row-height container-desktop">
        <div class="col content-top" id="start">
            <div id="wizard_container">
                <div class="wrapper-content-desktop">
                    <h2>Data Administrasi</h2>
                </div>
                {{-- <div id="top-wizard"></div> --}}
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- <div class="header-form">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam-top">
                </div> --}}
                <br>
                <form id="wrapped" method="POST" enctype="multipart/form-data"
                      action="{{route('ppdb.welcome.submit')}}">
                    <div>
                        {{-- <p>Selamat Datang,<br><span class="span-name">{{@$user['name']}}</span></p>

                        <div class="icon-step">
                            <img src="{{asset('frontend-ppdb-online/img/icon-step-2.png')}}">
                        </div> --}}

                        <p class="text-welcome">
                            Lengkapi data identitas dan dokumen administrasi dibawah ini untuk memudahkan proses penerimaan siswa baru.
                        </p>

                        <div class="container data-siswa-table">

                            <div class="row table-header-row">
                                <div class="col-5"><p class="table-header">Kelengkapan</p></div>
                                <div class="col-3"><p class="table-header">Action</p></div>
                                <div class="col-4"><p class="table-header">Status</p></div>
                            </div>


                            <div class="row table-row">
                                <div class="col-5">Identitas Calon Siswa</div>
                                <div class="col-3">
                                    <a href="{{ route('ppdb.form-student')  }}">
                                        <div class="status-tab status-tab-grey">
                                            <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                            <span>Edit</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    @if(!@$user->isPersonalDataFilled)
                                    <div class="status-tab status-tab-red">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Belum</span>
                                    </div>
                                    @else
                                    <div class="status-tab status-tab-green">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                        <span>Lengkap</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row table-row">
                                <div class="col-5">Identitas Orang Tua</div>
                                <div class="col-3">
                                    <a href="{{ route('ppdb.form-parent') }}">
                                        <div class="status-tab status-tab-grey">
                                            <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                            <span>Edit</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-4">
                                    @if(!$user->isParentsComplete)
                                    <div class="status-tab status-tab-red">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Belum</span>
                                    </div>
                                    @else
                                    <div class="status-tab status-tab-green">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                        <span>Lengkap</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($user['payment_option'] == 'BCA')
                                <div class="row table-row">
                                    <div class="col-5">Bukti Biaya Formulir</div>
                                    <div class="col-3">
                                            <div class="input-group mb-3">
                                                <small>
                                                    Sudah terbayarkan dengan Virtual Account <b> {{@$user['payment_option']}} </b> pada <b> {{ date('d-m-Y H:i:s', strtotime($user['payment_date'])) }} </b>
                                                </small>
                                            </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="status-tab status-tab-green" id="message_payment_form" role="alert">
                                            <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                            <span>Lunas</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($input = $uploadsForm->get('payment_form'))
                                    <div class="row table-row">
                                        <div class="col-5">{!! $input['nama'] !!}</div>
                                        <div class="col-3">
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    {{-- <input type="text" name="payment_form" accept="image/x-png,image/jpeg"
                                                        id="payment_form"
                                                        aria-describedby="inputGroupFileAddon01"> --}}
                                                    <label class="custom-file-label" for="payment_form"
                                                        style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['payment_form']))?str_replace('payment_form/','',$user['payment_form']):"Choose file"}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            @if(!empty(@$user['payment_form']))
                                                <div class="status-tab status-tab-green" id="message_payment_form" role="alert">
                                                    <a target="_blank" class="d-flex align-items-center" href="{{ $user->getPaymentFormImageUrl() }}">
                                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                        <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                        <span>Lihat File</span>
                                                    </a>
                                                </div>
                                            @else
                                            <div class="status-tab status-tab-red" id="message_payment_form">
                                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                                <span>Belum</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif


                            @if ($input = $uploadsForm->get('birth_certificate'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }}</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="birth_certificate" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="birth_certificate"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="birth_certificate"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['birth_certificate']))?str_replace('birth_certificate/','',$user['birth_certificate']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['birth_certificate']))
                                        <div class="status-tab status-tab-green" id="message_birth_certificate" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getBirtCertificateImageUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                    <div class="status-tab status-tab-red" id="message_birth_certificate">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                        <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <span>Belum</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('photo'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }}</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" class="custom-file-input" name="photo"
                                                   accept="image/x-png,image/jpeg" id="photo"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="photo"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['photo']))?str_replace('photo/','',$user['photo']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['photo']))
                                        <div class="status-tab status-tab-green" id="message_photo" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getPhotoImageUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                    <div class="status-tab status-tab-red" id="message_photo">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Belum</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('family_card'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }}</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="family_card" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="family_card"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="family_card"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['family_card']))?str_replace('family_card/','',$user['family_card']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['family_card']))
                                    <div class="status-tab status-tab-green" id="message_family_card" role="alert">
                                        <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getFamilyCardImageUrl() }}">
                                            <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                            <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                            <span>Lihat File</span>
                                        </a>
                                    </div>
                                    @else
                                    <div class="status-tab status-tab-red" id="message_family_card">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Belum</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('parent_identity_card'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }}</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="parent_identity_card" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="parent_identity_card"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="parent_identity_card"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['parent_identity_card']))?str_replace('parent_identity_card/','',$user['parent_identity_card']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['parent_identity_card']))
                                    <div class="status-tab status-tab-green" id="message_parent_identity_card" role="alert">
                                        <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getParentIdentityCardImageUrl() }}">
                                            <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                            <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                            <span>Lihat File</span>
                                        </a>
                                    </div>
                                    @else
                                    <div class="status-tab status-tab-red" id="message_parent_identity_card">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Belum</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('marriage_certificate'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }}</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="marriage_certificate" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="marriage_certificate"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="marriage_certificate"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['marriage_certificate']))?str_replace('marriage_certificate/','',$user['marriage_certificate']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['marriage_certificate']))
                                    <div class="status-tab status-tab-green" id="message_marriage_certificate" role="alert">
                                        <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getMarriageCertificateImageUrl() }}">
                                            <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                            <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                            <span>Lihat File</span>
                                        </a>
                                    </div>
                                    @else
                                    <div class="status-tab status-tab-red" id="message_marriage_certificate">
                                        <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Belum</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('report_cards'))
                            <div class="row table-row" id="table-row-raport-cards">
                                <div class="col-5">{{ $input['nama'] }}</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="report_card" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="report_card"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="report_card"
                                                   style="white-space: nowrap;overflow: auto;">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if (!count($user['report_cards']))
                                        <div class="status-tab status-tab-red" id="message_raport">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @else
                                        <div class="status-tab status-tab-green" id="message_raport">
                                            <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                            <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                            <span>Lengkap</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @forelse (@$user['report_cards'] as $report)
                                <div class="row table-row">
                                    <div class="col-5">
                                        <div class="input-group mb-3">
                                            <label class="" style="white-space: nowrap;overflow: auto;">{{ str_replace('report_card/', '', $report) }}</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <a class="btn-green btn-sm" target="_blank" href="{{ \App\Helpers\ImageHelper::imageUrl($report) }}">Lihat File</a>
                                        <button class="btn-red delete-report-image btn-sm" data-filename="{{ str_replace('report_card/', '', $report) }}">Hapus</button>
                                    </div>
                                </div>
                            @empty

                            @endforelse
                            @endif

                            @if ($input = $uploadsForm->get('award_photo'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }} (Optional)</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="award_photo" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="award_photo"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="award_photo"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['award_photo']))?str_replace('award_photo/','',$user['award_photo']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['award_photo']))
                                        <div class="status-tab status-tab-green" id="message_award_photo" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getAwardPhotoImageUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="status-tab status-tab-red" id="message_award_photo">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('kartu_golongan_darah'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }} (Optional)</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="kartu_golongan_darah" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="kartu_golongan_darah"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="award_photo"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['kartu_golongan_darah']))?str_replace('kartu_golongan_darah/','',$user['kartu_golongan_darah']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['kartu_golongan_darah']))
                                        <div class="status-tab status-tab-green" id="message_kartu_golongan_darah" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getKartuGolonganDarahImageUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" id="message_kartu_golongan_darah" role="alert">
                                            Belum Lengkap
                                        </div>
                                        <div class="status-tab status-tab-red" id="message_kartu_golongan_darah">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('kms'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }} (Optional)</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" name="kms" class="custom-file-input"
                                                   accept="image/x-png,image/jpeg" id="kms"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="kms"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['kms']))?str_replace('kms/','',$user['kms']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['kms']))
                                        <div class="status-tab status-tab-green" id="message_kms" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getKmsImageUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="status-tab status-tab-red" id="message_kms">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif


                            @if ($input = $uploadsForm->get('baptismal_certificate'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }} (Optional)</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" class="custom-file-input" name="baptismal_certificate"
                                                   accept="image/x-png,image/jpeg" id="baptismal_certificate"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="baptismal_certificate"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['baptismal_certificate']))?str_replace('baptismal_certificate/','',$user['baptismal_certificate']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['baptismal_certificate']))
                                        <div class="status-tab status-tab-green" id="message_baptismal_certificate" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getBaptismalCertificateImageUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="status-tab status-tab-red" id="message_baptismal_certificate">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('rekomendasi_bk'))
                            <div class="row table-row">
                                <div class="col-5">{{ $input['nama'] }} (Optional)</div>
                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <div class="status-tab status-tab-grey status-tab-edit">
                                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                <span>Browse</span>
                                            </div>
                                            <input type="file" class="custom-file-input" name="rekomendasi_bk"
                                                   accept="image/x-png,image/jpeg" id="rekomendasi_bk"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="rekomendasi_bk"
                                                   style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['rekomendasi_bk']))?str_replace('rekomendasi_bk/','',$user['rekomendasi_bk']):"Choose file"}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['rekomendasi_bk']))
                                        <div class="status-tab status-tab-green" id="message_rekomendasi_bk" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getRekomendasiBkImageUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="status-tab status-tab-red" id="message_rekomendasi_bk">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('nilai_raport'))
                            <div class="row table-row">
                                <div class="col-5">Input Nilai Raport</div>
                                <div class="col-7">
                                    <!-- <a href="https://docs.google.com/forms/d/e/1FAIpQLSdpT4S61GS_ahLKU_kV9y3SvzCTlFHhbuW0KWil2p-4_6OwUg/viewform" class="btn btn-success google-form" target="_blank">Google Form</a> -->
                                    <!-- <a href="https://docs.google.com/forms/d/e/1FAIpQLSfEapauQ93Az8Mma7GsvrEWmqLsKuOckX156mxUyp6H_cp6Xg/viewform" class="btn btn-success google-form" target="_blank">Google Form</a> -->
                                    {{-- <a href="{{ config('form') }}" class="btn-green google-form" target="_blank" style="margin: 0">Google Form</a> --}}
                                    @isset($customForm)
                                        <a href="{{ $customForm->show_url }}" class="btn-green google-form" style="margin: 0">Form</a>
                                    @endisset
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('custom_form') && isset($customForms))
                            @foreach ($customForms as $customForm)
                            <div class="row table-row">
                                <div class="col-5">{{ $customForm->name }}</div>
                                <div class="col-7">
                                    <a href="{{ $customForm->show_url }}" class="btn-green google-form" style="margin: 0">Form</a>
                                </div>
                            </div>
                            @endforeach
                            @endif

                            @if ($input = $uploadsForm->get('angket_peminatan'))
                            <div class="row table-row">
                                <div class="col-5" style="width: 30%">
                                    {{ $input['nama'] }}
                                    <br>
                                    <small>
                                        @if (@$input['deskripsi'])
                                        {{ $input['deskripsi'] }}
                                        @else
                                        Silakan unduh Formulir Angket Peminatan berikut dan unggah
                                        kembali jika sudah dilengkapi
                                        @endif
                                    </small>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{ route('ppdb.download-angket-peminatan') }}" target="_blank" class="btn btn-download">Unduh</a>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <div class="status-tab status-tab-grey status-tab-edit">
                                                        <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                        <span>Browse</span>
                                                    </div>
                                                    <input type="file" class="custom-file-input" name="angket_peminatan"
                                                        accept="image/x-png,image/jpeg,application/pdf" id="angket_peminatan"
                                                        aria-describedby="inputGroupFileAddon01">
                                                    <label class="custom-file-label" for="angket_peminatan"
                                                        style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['angket_peminatan']))?str_replace('angket_peminatan/','',$user['angket_peminatan']):"Choose file"}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['angket_peminatan']))
                                        <div class="status-tab status-tab-green" id="message_angket_peminatan" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getAngketPeminatanFileUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="status-tab status-tab-red" id="message_angket_peminatan">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if ($input = $uploadsForm->get('statement_letter'))
                            <div class="row table-row">
                                <div class="col-5" style="width: 30%">
                                    {{ $input['nama'] }}
                                    <br>
                                    <small>
                                        Silakan unduh Surat Pernyataan berikut dan unggah
                                        kembali jika sudah dilengkapi dengan tdiv bermaterai
                                    </small>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{ route('ppdb.download-statement-letter') }}" target="_blank" class="btn btn-download">Unduh</a>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <div class="status-tab status-tab-grey status-tab-edit">
                                                        <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/folder.png')}}" alt="">
                                                        <span>Browse</span>
                                                    </div>
                                                    <input type="file" class="custom-file-input" name="statement_letter"
                                                        accept="image/x-png,image/jpeg,application/pdf" id="statement_letter"
                                                        aria-describedby="inputGroupFileAddon01">
                                                    <label class="custom-file-label" for="statement_letter"
                                                        style="white-space: nowrap;overflow: auto;">{{(!empty(@$user['statement_letter']))?str_replace('statement_letter/','',$user['statement_letter']):"Choose file"}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(!empty(@$user['statement_letter']))
                                        <div class="status-tab status-tab-green" id="message_statement_letter" role="alert">
                                            <a target="_blank" class="d-flex align-items-center text-white" href="{{ $user->getStatementLetterFileUrl() }}">
                                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                                <img class="check-green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check-green.png')}}" alt="">
                                                <span>Lihat File</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="status-tab status-tab-red" id="message_statement_letter">
                                            <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                            <span>Belum</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            </tbody>
                        </div>

                        <div class="my-3">
                            {{-- <b>Catatan:  FC KK, FC Akta Kelahiran, FC Surat Baptis, {{ $user->unit->name === 'SD-SURABAYA' ? 'FC KTP Orang Tua, FC Akta Pernikahan, ' : NULL }} Surat Pernyataan Asli wajib disimpan dan dikirimkan setelah pengumuman penerimaan.</b> --}}
                        </div>

                        <ul class="btn-below">
                            @if(@$user->isReadyToSubmit)
                                <li>
                                    <button type="button" name="register" class="btn btn-register" onclick="popupRegister()">Simpan & Lanjut</button>
                                </li>
                            @else
                                <li>
                                    <button type="button" name="register" class="btn btn-register btn-disabled">Simpan & Lanjut</button>
                                </li>
                            @endif
                        </ul>


                    </div>
                    @csrf
                </form>
                <div class="clear-50"></div>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
{{-- </div> --}}
@endsection

@push('styles')
    <style>
        .btn{
            min-width: 150px;
        }
        .btn-register{
            padding: 0 1rem;
            font-size: 14px !important;
            color: white !important;
            width: auto !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script src="{{asset('frontend-ppdb-online/js/registration_func.js')}}"></script>
    <script>
        function changeBtnRegister(value)
        {
            if (value) {
                $('.btn-register').removeClass('btn-disabled');
                $('.btn-register').bind('click', popupRegister);
            } else {
                $('.btn-register').addClass('btn-disabled');
                $('.btn-register').unbind('click', popupRegister);
            }
        }

        $(document).on('change', "#report_card", function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-report-card')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_raport').removeClass("status-tab-green");
                        $('#message_raport').removeClass("status-tab-red");
                        $('#message_raport').addClass("status-tab-yellow");
                        $('#message_raport').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_raport').removeClass("status-tab-green");
                        $('#message_raport').addClass("status-tab-red");
                        $('#message_raport').removeClass("status-tab-yellow");
                        $('#message_raport').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_raport').addClass("status-tab-green");
                        $('#message_raport').removeClass("status-tab-red");
                        $('#message_raport').removeClass("status-tab-yellow");
                        $('#message_raport').text("Lengkap");
                        var html = `
                        <div class="row table-row">
                            <div class="col-5">
                                <div class="input-group mb-3">
                                    <label class="" style="white-space: nowrap;overflow: auto;">${data.filename}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <a class="btn-green btn-sm" target="_blank" href="${data.path}">Lihat File</a>
                                <button class="btn-red delete-report-image btn-sm" data-filename="${data.filename}">Hapus</button>
                            </div>
                        <div>
                        `;
                        $('#table-row-raport-cards').after(html);
                        self.val('');
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $(document).on('click', '.delete-report-image', function(e) {
            e.preventDefault();
            var self = $(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                type: "POST",
                url: "{{route('ppdb.delete-report-card')}}",
                data: {
                    id: {{ $user['id'] }},
                    filename: self.data('filename')
                },
                dataType: "JSON",
                cache: false,
                success: function (data) {
                    self.parent().parent().remove();
                    if ($('button.delete-report-image').length == 0) {
                        $('#message_raport').removeClass("status-tab-green");
                        $('#message_raport').addClass("status-tab-red");
                        $('#message_raport').removeClass("status-tab-yellow");
                        $('#message_raport').text("Belum Lengkap");
                        $('#message_raport').show();
                    }
                    changeBtnRegister(data.is_ready_to_submit);
                }
            });
            return false;
        });

        $(document).on('change',"#birth_certificate", function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-birth-certificate')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_birth_certificate').removeClass("status-tab-green");
                        $('#message_birth_certificate').removeClass("status-tab-red");
                        $('#message_birth_certificate').addClass("status-tab-yellow");
                        $('#message_birth_certificate').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_birth_certificate').removeClass("status-tab-green");
                        $('#message_birth_certificate').addClass("status-tab-red");
                        $('#message_birth_certificate').removeClass("status-tab-yellow");
                        $('#message_birth_certificate').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_birth_certificate').addClass("status-tab-green");
                        $('#message_birth_certificate').removeClass("status-tab-red");
                        $('#message_birth_certificate').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_birth_certificate').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $(document).on('change',"#rekomendasi_bk", function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-rekomendasi-bk')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_rekomendasi_bk').removeClass("status-tab-green");
                        $('#message_rekomendasi_bk').removeClass("status-tab-red");
                        $('#message_rekomendasi_bk').addClass("status-tab-yellow");
                        $('#message_rekomendasi_bk').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_rekomendasi_bk').removeClass("status-tab-green");
                        $('#message_rekomendasi_bk').addClass("status-tab-red");
                        $('#message_rekomendasi_bk').removeClass("status-tab-yellow");
                        $('#message_rekomendasi_bk').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_rekomendasi_bk').addClass("status-tab-green");
                        $('#message_rekomendasi_bk').removeClass("status-tab-red");
                        $('#message_rekomendasi_bk').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_rekomendasi_bk').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }

        });

        $(document).on('change',"#marriage_certificate", function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-marriage-certificate')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_marriage_certificate').removeClass("status-tab-green");
                        $('#message_marriage_certificate').removeClass("status-tab-red");
                        $('#message_marriage_certificate').addClass("status-tab-yellow");
                        $('#message_marriage_certificate').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_marriage_certificate').removeClass("status-tab-green");
                        $('#message_marriage_certificate').addClass("status-tab-red");
                        $('#message_marriage_certificate').removeClass("status-tab-yellow");
                        $('#message_marriage_certificate').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_marriage_certificate').addClass("status-tab-green");
                        $('#message_marriage_certificate').removeClass("status-tab-red");
                        $('#message_marriage_certificate').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_marriage_certificate').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }

        });

        $(document).on('change',"#parent_identity_card", function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-parent-identity-card')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_parent_identity_card').removeClass("status-tab-green");
                        $('#message_parent_identity_card').removeClass("status-tab-red");
                        $('#message_parent_identity_card').addClass("status-tab-yellow");
                        $('#message_parent_identity_card').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_parent_identity_card').removeClass("status-tab-green");
                        $('#message_parent_identity_card').addClass("status-tab-red");
                        $('#message_parent_identity_card').removeClass("status-tab-yellow");
                        $('#message_parent_identity_card').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_parent_identity_card').addClass("status-tab-green");
                        $('#message_parent_identity_card').removeClass("status-tab-red");
                        $('#message_parent_identity_card').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_parent_identity_card').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }

        });

        $(document).on('change',"#photo", function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-photo')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_photo').removeClass("status-tab-green");
                        $('#message_photo').removeClass("status-tab-red");
                        $('#message_photo').addClass("status-tab-yellow");
                        $('#message_photo').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_photo').removeClass("status-tab-green");
                        $('#message_photo').addClass("status-tab-red");
                        $('#message_photo').removeClass("status-tab-yellow");
                        $('#message_photo').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_photo').addClass("status-tab-green");
                        $('#message_photo').removeClass("status-tab-red");
                        $('#message_photo').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_photo').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }

        });

        $(document).on('change',"#family_card",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-family-card')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_family_card').removeClass("status-tab-green");
                        $('#message_family_card').removeClass("status-tab-red");
                        $('#message_family_card').addClass("status-tab-yellow");
                        $('#message_family_card').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_family_card').removeClass("status-tab-green");
                        $('#message_family_card').addClass("status-tab-red");
                        $('#message_family_card').removeClass("status-tab-yellow");
                        $('#message_family_card').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_family_card').addClass("status-tab-green");
                        $('#message_family_card').removeClass("status-tab-red");
                        $('#message_family_card').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_family_card').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $(document).on('change',"#award_photo",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-award-photo')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_award_photo').removeClass("status-tab-green");
                        $('#message_award_photo').removeClass("status-tab-red");
                        $('#message_award_photo').addClass("status-tab-yellow");
                        $('#message_award_photo').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_award_photo').removeClass("status-tab-green");
                        $('#message_award_photo').addClass("status-tab-red");
                        $('#message_award_photo').removeClass("status-tab-yellow");
                        $('#message_award_photo').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_award_photo').addClass("status-tab-green");
                        $('#message_award_photo').removeClass("status-tab-red");
                        $('#message_award_photo').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_award_photo').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $(document).on('change',"#baptismal_certificate",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-baptismal-certificate')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_baptismal_certificate').removeClass("status-tab-green");
                        $('#message_baptismal_certificate').removeClass("status-tab-red");
                        $('#message_baptismal_certificate').addClass("status-tab-yellow");
                        $('#message_baptismal_certificate').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_baptismal_certificate').removeClass("status-tab-green");
                        $('#message_baptismal_certificate').addClass("status-tab-red");
                        $('#message_baptismal_certificate').removeClass("status-tab-yellow");
                        $('#message_baptismal_certificate').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_baptismal_certificate').addClass("status-tab-green");
                        $('#message_baptismal_certificate').removeClass("status-tab-red");
                        $('#message_baptismal_certificate').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_baptismal_certificate').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $(document).on('change',"#kartu_golongan_darah",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-kartu-golongan-darah')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_kartu_golongan_darah').removeClass("status-tab-green");
                        $('#message_kartu_golongan_darah').removeClass("status-tab-red");
                        $('#message_kartu_golongan_darah').addClass("status-tab-yellow");
                        $('#message_kartu_golongan_darahr').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_kartu_golongan_darah').removeClass("status-tab-green");
                        $('#message_kartu_golongan_darahr').addClass("status-tab-red");
                        $('#message_kartu_golongan_darah').removeClass("status-tab-yellow");
                        $('#message_kartu_golongan_darahr').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_kartu_golongan_darahv').addClass("status-tab-green");
                        $('#message_kartu_golongan_darah').removeClass("status-tab-red");
                        $('#message_kartu_golongan_darahr').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_kartu_golongan_darah').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $(document).on('change',"#kms",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-kms')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_kms').removeClass("status-tab-green");
                        $('#message_kms').removeClass("status-tab-red");
                        $('#message_kms').addClass("status-tab-yellow");
                        $('#message_kms').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_kms').removeClass("status-tab-green");
                        $('#message_kms').addClass("status-tab-red");
                        $('#message_kms').removeClass("status-tab-yellow");
                        $('#message_kms').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_kms').addClass("status-tab-green");
                        $('#message_kms').removeClass("status-tab-red");
                        $('#message_kms').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_kms').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $(document).on('change',"#statement_letter",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-statement-letter')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_statement_letter').removeClass("status-tab-green");
                        $('#message_statement_letter').removeClass("status-tab-red");
                        $('#message_statement_letter').addClass("status-tab-yellow");
                        $('#message_statement_letter').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_statement_letter').removeClass("status-tab-green");
                        $('#message_statement_letter').addClass("status-tab-red");
                        $('#message_statement_letter').removeClass("status-tab-yellow");
                        $('#message_statement_letter').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_statement_letter').addClass("status-tab-green");
                        $('#message_statement_letter').removeClass("status-tab-red");
                        $('#message_statement_letter').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_statement_letter').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        $('.google-form').click(function (e) {
            e.preventDefault();
            $(this).parent().append('<iframe width="100%" height="500px" style="overflow-y: auto;" frameborder="0" src="'+ $(this).attr('href') +'"></iframe>');
            $(this).remove();
        });

        $(document).on('change',"#angket_peminatan",function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.upload-angket-peminatan')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#message_angket_peminatan').removeClass("status-tab-green");
                        $('#message_angket_peminatan').removeClass("status-tab-red");
                        $('#message_angket_peminatan').addClass("status-tab-yellow");
                        $('#message_angket_peminatan').text("Uploading...");
                    },
                    error: function (data) {
                        $('#message_angket_peminatan').removeClass("status-tab-green");
                        $('#message_angket_peminatan').addClass("status-tab-red");
                        $('#message_angket_peminatan').removeClass("status-tab-yellow");
                        $('#message_angket_peminatan').text("Belum Lengkap");
                    },
                    success: function (data) {
                        $('#message_angket_peminatan').addClass("status-tab-green");
                        $('#message_angket_peminatan').removeClass("status-tab-red");
                        $('#message_angket_peminatan').removeClass("status-tab-yellow");
                        var html = '<a target="_blank" href=' + data.path + '> Lihat File </a>';
                        $('#message_angket_peminatan').html(html);
                        self.siblings(".custom-file-label").addClass("selected").html(data.filename);
                        changeBtnRegister(data.is_ready_to_submit);
                    }
                });
                return false;
            }
        });

        function popupRegister() {
            swal("Apakah anda yakin akan mendaftar? ", {
                buttons: {
                    cancel: true,
                    confirm: true,
                },
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
                            url: "{{route('ppdb.welcome.submit')}}",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                if (data.status === 'success') {
                                    swal("Terima kasih, semua dokumen akan diproses oleh admin.", {
                                        icon: "success"
                                    }).then((value) => {
                                        window.location.reload();
                                        return false;
                                    });
                                }
                            }
                        });
                    break;
                    default:
                }
            });
        }
    </script>
@endpush
