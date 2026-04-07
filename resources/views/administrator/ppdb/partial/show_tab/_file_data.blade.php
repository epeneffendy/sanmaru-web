@php($uploadsForm = \App\Helpers\InputCollectionHelper::uploads($data->unit, null, $data))

<div class="panel-group modern-accordion" id="accordion" role="tablist" aria-multiselectable="true">
    @if ($uploadsForm->get('payment_form'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="true" aria-controls="collapseOne">
                        <span class="acc-icon"><i class="fa fa-credit-card"></i></span>
                        <span class="flex-grow-1">Bukti Bayar</span>
                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->payment_date != '')
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Melakukan Pembayaran
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    @include('administrator.ppdb.partial.show_collapse._registration_receipt')
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('birth_certificate'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Akta Kelahiran</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->birth_certificate !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Melakukan Pembayaran
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body">
                    @if ($data->birth_certificate !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getBirtCertificateImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getBirtCertificateImageUrl() }}"
                                     alt="Akta Kelahiran"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                        <span class="status-pill danger">
                            <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                        </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('photo'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Foto 3x4</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->photo !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    @if ($data->photo !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getPhotoImageUrl() }}" target="_blank">
                                <img src="{{ $data->getPhotoImageUrl() }}"
                                     alt="Foto 3x4"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                        <span class="status-pill danger">
                            <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                        </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('parent_identity_card'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">KTP Orang Tua</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->parent_identity_card !== null)
                                <span class="badge-status success">
                        <i class="fa fa-check-circle"></i> Tersedia
                    </span>
                            @else
                                <span class="badge-status danger">
                        <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                    </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    @if ($data->parent_identity_card !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getParentIdentityCardImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getParentIdentityCardImageUrl() }}"
                                     alt="KTP Orang Tua"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('marriage_certificate'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Akte Pernikahan Orang Tua</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->marriage_certificate !== null)
                                <span class="badge-status success">
                                    <i class="fa fa-check-circle"></i> Tersedia
                                </span>
                            @else
                                <span class="badge-status danger">
                                    <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                                </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                    @if ($data->marriage_certificate !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getMarriageCertificateImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getMarriageCertificateImageUrl() }}"
                                     alt="Akta Pernikahan Orangtua"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                            <span class="status-pill danger">
                                <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('family_card'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSix">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Kartu Keluarga</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->family_card !== null)
                                <span class="badge-status success">
                                <i class="fa fa-check-circle"></i> Tersedia
                            </span>
                            @else
                                <span class="badge-status danger">
                                <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                            </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                <div class="panel-body">
                    @if ($data->family_card !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getFamilyCardImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getFamilyCardImageUrl() }}"
                                     alt="Kartu Keluarga"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                        <span class="status-pill danger">
                            <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                        </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('report_cards'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeven">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Rapot</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->report_cards !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                <div class="panel-body">
                    @if ($data->family_card !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getFamilyCardImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getFamilyCardImageUrl() }}"
                                     alt="Kartu Keluarga"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('award_photo'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingEight">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Piagam Penghargaan</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->award_photo !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                <div class="panel-body">
                    @if ($data->award_photo !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getAwardPhotoImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getAwardPhotoImageUrl() }}"
                                     alt="Piagam Penghargaan"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('baptismal_certificate'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingNine">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Kartu Baptismal</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->baptismal_certificate !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine">
                <div class="panel-body">
                    @if ($data->baptismal_certificate !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getBaptismalCertificateImageUrl() }}"
                               target="_blank">
                                <img
                                    src="{{ $data->getBaptismalCertificateImageUrl() }}"
                                    alt="Kartu Baptismal"
                                    style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('angket_peminatan'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTen">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Angket Peminatan</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->angket_peminatan !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseTen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTen">
                <div class="panel-body">
                    @if ($data->angket_peminatan !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getAngketPeminatanFileUrl() }}"
                               target="_blank">
                                <img
                                    src="{{ $data->getAngketPeminatanFileUrl() }}"
                                    alt="Angket Peminatan"
                                    style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('rekomendasi_bk'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingEleven">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Rekomendasi BK</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->rekomendasi_bk !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEleven">
                <div class="panel-body">
                    @if ($data->rekomendasi_bk !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getRekomendasiBkImageUrl() }}"
                               target="_blank">
                                <img
                                    src="{{ $data->getRekomendasiBkImageUrl() }}"
                                    alt="Rekomendasi BK"
                                    style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('statement_letter'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwelve">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Surat Penyataan</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->statement_letter !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseTwelve" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwelve">
                <div class="panel-body">
                    @if ($data->statement_letter !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getStatementLetterFileUrl() }}"
                               target="_blank">
                                <img
                                    src="{{ $data->getStatementLetterFileUrl() }}"
                                    alt="Surat Pernyataan"
                                    style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('potensi_kecerdasan_image'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThreeten">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseThreeten" aria-expanded="false" aria-controls="collapseThreeten">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Potensi Kecerdasan</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->potensi_kecerdasan_image !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseThreeten" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingThreeten">
                <div class="panel-body">
                    @if ($data->potensi_kecerdasan_image !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getPotensiKecerdasanImageUrl() }}"
                               target="_blank">
                                <img
                                    src="{{ $data->getPotensiKecerdasanImageUrl() }}"
                                    alt="Potensi Kecerdasan"
                                    style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('bakat_istimewa_image'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFourteen">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Bakat Istimewa</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->bakat_istimewa_image !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseFourteen" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingFourteen">
                <div class="panel-body">
                    @if ($data->bakat_istimewa_image !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getBakatIstimewaImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getBakatIstimewaImageUrl() }}"
                                     alt="Bakat Istimewa"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('kesiapan_psikis_image'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFiveteen">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseFiveteen" aria-expanded="false" aria-controls="collapseFiveteen">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Kesiapan Psikis</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->kesiapan_psikis_image !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseFiveteen" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingFiveteen">
                <div class="panel-body">
                    @if ($data->kesiapan_psikis_image !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getKesiapanPsikisImageUrl() }}"
                               target="_blank">
                                <img src="{{ $data->getKesiapanPsikisImageUrl() }}"
                                     alt="Kesiapan Psikis"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('kartu_golongan_darah'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSixteen">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Kartu Golongan Darah</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->kartu_golongan_darah !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseSixteen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSixteen">
                <div class="panel-body">
                    @if ($data->kartu_golongan_darah !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getKartuGolonganDarahImageUrl() }}"
                               target="_blank">
                                <img
                                    src="{{ $data->getKartuGolonganDarahImageUrl() }}"
                                    alt="Kartu Golongan Darah"
                                    style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($uploadsForm->get('kms'))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeventeen">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                       href="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                        <span class="acc-icon"><i class="fa fa-file-image-o"></i></span>
                        <span class="flex-grow-1">Kartu Menuju Sehat (KMS)</span>

                        <div class="status-container" style="margin-left: 20px; margin-right: 10px;">
                            @if($data->kms !== null)
                                <span class="badge-status success">
                            <i class="fa fa-check-circle"></i> Tersedia
                        </span>
                            @else
                                <span class="badge-status danger">
                            <i class="fa fa-exclamation-circle"></i> Belum Tersedia
                        </span>
                            @endif
                        </div>
                        <i class="fa fa-chevron-down arrow-status"></i>
                    </a>
                </h4>
            </div>
            <div id="collapseSeventeen" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="headingSeventeen">
                <div class="panel-body">
                    @if ($data->kms !== null)
                        <div style="text-align: center">
                            <a href="{{ $data->getKmsImageUrl() }}" target="_blank">
                                <img src="{{ $data->getKmsImageUrl() }}"
                                     alt="Kartu Menuju Sehat"
                                     style="max-width: 300px; height: auto;">
                            </a>
                        </div>
                    @else
                        <div class="status-badge-wrapper" style="text-align: center">
                    <span class="status-pill danger">
                        <i class="fa fa-times"></i> Berkas Tidak Ditemukan
                    </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif


</div>
