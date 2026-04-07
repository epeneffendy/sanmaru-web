@extends('layouts.admin.main')
@section('content')
    @push('styles')
        <style>

            /* Container Utility */
            .container-padding {
                padding: 25px !important;
                background-color: #f8f9fa; /* Latar belakang abu-abu sangat muda agar kartu lebih menonjol */
            }

            /* Profile Card Styling */
            .card.shadow-sm {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
                border: none !important;
                transition: transform 0.3s ease;
            }

            /* Header Gradient & Design */
            .bg-success {
                /* Menggunakan gradien agar lebih dinamis dibandingkan warna solid */
                background: linear-gradient(135deg, #50c36f 0%, #26703B 100%) !important;
                border-bottom: 4px solid rgba(0, 0, 0, 0.05);
                padding: 30px !important;
            }

            /* Avatar Styling */
            .bg-white.rounded-circle {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .bg-white.rounded-circle:hover {
                transform: scale(1.05);
            }

            .text-success {
                color: #26a69a !important;
            }

            /* Typography */
            h2.fw-bold {
                font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
                font-size: 24px;
                letter-spacing: -0.5px;
                margin-bottom: 4px !important;
            }

            .opacity-75 {
                font-size: 14px;
                font-weight: 400;
                letter-spacing: 0.2px;
                opacity: 0.9 !important; /* Sedikit lebih terang agar mudah dibaca */
            }

            /* Badge Modern Styling */
            .badge.rounded-pill {
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                padding: 10px 20px !important;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border: none;
            }

            /* Flex Gap Helper (Jika versi Bootstrap Anda belum mendukung utility gap) */
            .gap-3 {
                gap: 1.5rem !important;
            }

            /* Status Icon Animation */
            .fa-check-circle, .fa-exclamation-triangle {
                margin-right: 8px;
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .bg-success {
                    flex-direction: column;
                    text-align: center;
                }

                .d-flex.align-items-center {
                    flex-direction: column;
                    margin-bottom: 20px;
                }

                .text-end {
                    text-align: center !important;
                }
            }

            /* Container & Widget Styling */
            .widget {
                background: #ffffff;
                border-radius: 15px !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                border: none !important;
                margin-bottom: 30px;
            }

            /* Modern Tabs Styling */
            .nav-tabs-modern {
                border-bottom: 2px solid #f0f0f0 !important;
                display: flex;
                gap: 10px;
            }

            .nav-tabs-modern > li {
                margin-bottom: -2px; /* Menempel pada border bottom */
            }

            .nav-tabs-modern > li > a {
                border: none !important;
                background: transparent !important;
                color: #888 !important;
                font-weight: 600;
                padding: 12px 20px !important;
                transition: all 0.3s ease;
                position: relative;
                text-transform: uppercase;
                font-size: 13px;
                letter-spacing: 0.5px;
            }

            /* Hover Effect */
            .nav-tabs-modern > li > a:hover {
                color: #26a69a !important; /* Hijau sesuai tema */
            }

            /* Active State with Smooth Underline */
            .nav-tabs-modern > li.active > a,
            .nav-tabs-modern > li.active > a:focus,
            .nav-tabs-modern > li.active > a:hover {
                color: #26a69a !important;
                border: none !important;
            }

            .nav-tabs-modern > li.active > a::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 3px;
                background: #26a69a; /* Warna hijau identitas */
                border-radius: 3px 3px 0 0;
                animation: slideIn 0.3s ease;
            }

            /* Form Label & Value Styling */
            .text-muted.small.fw-bold {
                display: block;
                margin-bottom: 5px;
                color: #999 !important;
                letter-spacing: 0.3px;
            }

            .border-bottom.pb-2 {
                border-bottom: 1px solid #f5f5f5 !important;
                font-size: 15px;
                color: #333;
                margin-bottom: 20px;
                padding-bottom: 10px !important;
                font-weight: 500;
            }

            /* List Group Sidebar (Verification) */
            .list-group-item {
                padding: 12px 0 !important;
                font-size: 14px;
            }

            .list-group-item i {
                font-size: 18px;
            }

            /* Animations */
            @keyframes slideIn {
                from {
                    width: 0;
                    left: 50%;
                }
                to {
                    width: 100%;
                    left: 0;
                }
            }

            /* Responsive adjustment */
            @media (max-width: 768px) {
                .nav-tabs-modern {
                    flex-wrap: wrap;
                }
            }

            /* Container Utama */
            .modern-accordion {
                border: none;
                box-shadow: none;
            }

            /* Card Panel */
            .modern-accordion .panel-default {
                border: 1px solid #edf2f7 !important;
                border-radius: 12px !important;
                margin-bottom: 15px !important;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .modern-accordion .panel-default:hover {
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            }

            /* Header / Title */
            .modern-accordion .panel-heading {
                background: #ffffff !important;
                padding: 0 !important;
                border: none;
            }

            .modern-accordion .panel-title a {
                display: flex;
                align-items: center;
                padding: 18px 25px;
                text-decoration: none;
                color: #2d3748;
                font-weight: 700;
                font-size: 15px;
                position: relative;
                transition: background 0.3s ease;
            }

            /* Icon Bulat di Sebelah Kiri */
            .acc-icon {
                width: 35px;
                height: 35px;
                background: #f1f5f9;
                color: #26703B; /* Hijau Anda */
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                margin-right: 15px;
                font-size: 14px;
            }

            /* Arrow Status */
            .arrow-status {
                margin-left: auto;
                font-size: 12px;
                transition: transform 0.3s ease;
                color: #cbd5e0;
            }

            /* Efek Saat Terbuka (Active) */
            .modern-accordion .panel-title a[aria-expanded="true"] {
                background-color: #f8fafc;
                color: #26703B;
            }

            .modern-accordion .panel-title a[aria-expanded="true"] .acc-icon {
                background: #26703B;
                color: #ffffff;
            }

            .modern-accordion .panel-title a[aria-expanded="true"] .arrow-status {
                transform: rotate(180deg);
                color: #26703B;
            }

            /* Panel Body */
            .modern-accordion .panel-body {
                border-top: 1px solid #f1f5f9 !important;
                padding: 25px !important;
                background: #ffffff;
            }

            /* Custom Style untuk Label Data */
            .text-muted.small.fw-bold {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 5px;
                display: block;
            }

            .border-bottom.pb-2 {
                border-bottom: 1px solid #f1f5f9 !important;
                font-size: 14px;
                color: #4a5568;
                margin-bottom: 15px;
            }

            /* Container Utama */
            .payment-card {
                background: #fff;
                border-radius: 16px;
                border: 1px solid #e0e6ed;
                overflow: hidden;
                max-width: 650px;
                margin: 10px 0;
                font-family: 'Inter', 'Segoe UI', sans-serif;
            }

            /* Header Section */
            .payment-header {
                background: #f8f9fa;
                padding: 20px 25px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #f0f0f0;
            }

            .bca-logo {
                height: 25px;
                margin-right: 12px;
            }

            .method-label {
                font-weight: 700;
                color: #555;
                font-size: 14px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* Status Pill */
            .status-pill.success {
                background-color: #eaf6ed;
                color: #26703B; /* Menggunakan Warna Hijau Anda */
                padding: 8px 16px;
                border-radius: 50px;
                font-weight: 600;
                font-size: 13px;
                border: 1px solid rgba(38, 112, 59, 0.2);
            }

            /* Body Section */
            .payment-body {
                padding: 30px 25px;
            }

            .info-row {
                margin-bottom: 15px;
            }

            .info-row label {
                display: block;
                font-size: 12px;
                color: #888;
                text-transform: uppercase;
                font-weight: 600;
                margin-bottom: 4px;
            }

            .info-row .value {
                font-size: 15px;
                color: #333;
            }

            /* Grid layout untuk detail */
            .payment-details-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-top: 25px;
                padding-top: 20px;
                border-top: 1px dashed #eee;
            }

            /* Styling Khusus Nilai */
            .va-number {
                font-family: 'Courier New', Courier, monospace;
                font-weight: 700;
                letter-spacing: 1px;
                font-size: 18px !important;
                color: #26703B !important; /* Hijau Anda */
            }

            .text-success {
                color: #26703B !important;
                font-weight: 800;
            }

            .fw-bold {
                font-weight: 700;
            }

            /* Footer */
            .payment-footer {
                background: #fff;
                padding: 15px 25px;
                border-top: 1px solid #f8f9fa;
            }

            .payment-footer p {
                font-size: 12px;
                color: #999;
                margin: 0;
                font-style: italic;
            }

            /* Container Timeline */
            .modern-timeline {
                position: relative;
                padding-left: 10px;
            }

            /* Garis Penghubung Vertikal */
            .modern-timeline::before {
                content: '';
                position: absolute;
                left: 24px;
                top: 5px;
                bottom: 5px;
                width: 2px;
                background: #f1f5f9; /* Warna garis default */
                z-index: 1;
            }

            /* Item Timeline */
            .timeline-item {
                position: relative;
                display: flex;
                align-items: flex-start;
                margin-bottom: 25px;
                z-index: 2;
            }

            .timeline-item:last-child {
                margin-bottom: 0;
            }

            /* Ikon Lingkaran */
            .timeline-icon {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                background: #fff;
                border: 2px solid #e2e8f0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                color: #94a3b8;
                margin-right: 15px;
                transition: all 0.3s ease;
                flex-shrink: 0;
            }

            /* Konten Teks */
            .timeline-content h5 {
                font-size: 14px;
                font-weight: 700;
                color: #334155;
                margin-bottom: 2px;
            }

            .timeline-content p {
                font-size: 11px;
                letter-spacing: 0.3px;
            }

            /* --- STATE: COMPLETED (HIJAU) --- */
            .timeline-item.completed .timeline-icon {
                background: #26703B; /* Hijau Anda */
                border-color: #26703B;
                color: #fff;
                box-shadow: 0 0 0 4px rgba(38, 112, 59, 0.1);
            }

            .timeline-item.completed .timeline-content h5 {
                color: #26703B;
            }

            /* --- STATE: ACTIVE/PENDING (ORANGE/BLUE) --- */
            .timeline-item.active .timeline-icon {
                border-color: #f59e0b;
                color: #f59e0b;
                background: #fff;
                animation: pulse-orange 2s infinite;
            }

            .timeline-item.active .timeline-content h5 {
                color: #f59e0b;
            }

            /* Animasi Pulse untuk Tahap yang Sedang Aktif */
            @keyframes pulse-orange {
                0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
                70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
                100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
            }

            /* Utility tambahan */
            .rounded-pill { border-radius: 50px !important; }
            .fw-bold { font-weight: 700 !important; }
        </style>
    @endpush
    <div class="page-header">
        <h1 class="title">Data Master Peserta PPDB</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.ppdb.index')}}">PPDB</a></li>
            <li class="active">Show</li>
        </ol>
    </div>

    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0 rounded-lg mb-4"
                     style="background: #fff; border-radius: 15px; overflow: hidden;">
                    <div class="p-4 bg-success text-white d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="profile-info-text">
                                <h2 class="m-0 fw-bold">{{ $data->name }}</h2>
                                <p class="m-0 opacity-75 info-sub-text">
                                    <span class="info-item">No. Registrasi: <strong>{{ $data->register_number }}</strong></span>
                                    <span class="info-divider">|</span>
                                    <span class="info-item">Unit: <strong>{{ $data->unit->name }}</strong></span>
                                    <span class="info-divider">|</span>
                                    <span class="info-item">Tahun Masuk: <strong>{{@$data->school_year}}</strong></span>
                                </p>
                            </div>
                        <div class="text-end">
                            <span class="badge bg-white  px-3 py-2 rounded-pill">
                                <i class="fa fa-check-circle me-1"></i>
                                {{$data->period->name}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="widget shadow-sm" style="border-radius: 15px; border: none;">
                    <div class="widget-header p-3 border-bottom">
                        <ul class="nav nav-tabs nav-tabs-modern" role="tablist">
                            <li class="active"><a href="#tab-personal" data-toggle="tab">Data Personal</a></li>
                            <li><a href="#tab-family" data-toggle="tab">Orang Tua/Wali</a></li>
                            <li><a href="#tab-additional" data-toggle="tab">Data Tambahan</a></li>
                            <li><a href="#tab-file" data-toggle="tab">Berkas</a></li>
                        </ul>
                    </div>

                    <div class="widget-content p-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-personal">
                                @include('administrator.ppdb.partial.show_tab._personal_data')
                            </div>

                            <div class="tab-pane" id="tab-family">
                                @if (!$data->isWalIRequired)
                                    @include('administrator.ppdb.partial.show_tab._parent_data')
                                @else
                                    @include('administrator.ppdb.partial.show_tab._guardian_data')
                                @endif
                            </div>

                            <div class="tab-pane" id="tab-additional">
                                @include('administrator.ppdb.partial.show_tab._additional_data')
                            </div>

                            <div class="tab-pane" id="tab-file">
                                @include('administrator.ppdb.partial.show_tab._file_data')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="widget shadow-sm p-4" style="border-radius: 15px; border: none; background: #fff;">
                    <h4 class="fw-bold mb-4" style="color: #333; font-size: 18px;">Proggres Verifikasi</h4>

                    <div class="modern-timeline">
                        <div class="timeline-item {{ ($data->payment_date != '') ? 'completed' : 'pending' }}">
                            <div class="timeline-icon">
                                <i class="fa {{ ($data->payment_date != '') ? 'fa-check' : 'fa-credit-card' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="m-0">Pembayaran Formulir</h5>
                                <p class="text-muted small m-0">{{ ($data->payment_date != '') ? 'Telah Dikonfirmasi' : 'Menunggu Bukti Bayar' }}</p>
                            </div>
                        </div>

                        {{-- 2. Data Administrasi --}}
                        <div class="timeline-item {{ $data->is_data_complete_whitout_bca ? 'completed' : 'pending' }}">
                            <div class="timeline-icon">
                                <i class="fa {{ $data->is_data_complete_whitout_bca ? 'fa-check' : 'fa-file-text' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="m-0">Data Administrasi</h5>
                                <p class="text-muted small m-0">{{ $data->is_data_complete_whitout_bca ? 'Data Lengkap' : 'Data Belum Lengkap' }}</p>
                            </div>
                        </div>

                        {{-- 3. Surat Pernyataan --}}
                        <div class="timeline-item {{ ($data->IsStatementLetterUploaded) ? (($data->IsStatementLetterConfirmed) ? 'completed' : 'pending') :'pending' }}">
                            <div class="timeline-icon">
                                <i class="fa {{ ($data->IsStatementLetterUploaded) ? (($data->IsStatementLetterConfirmed) ? 'fa-check' : 'fa-clock-o') :'fa-file-text' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="m-0">Surat Pernyataan</h5>
                                <p class="text-muted small m-0">{{ ($data->IsStatementLetterUploaded) ? (($data->IsStatementLetterConfirmed) ? 'Sudah Terverifikasi' : 'Sudah Diunggah') :'Belum Diunggah' }} </p>
                            </div>
                        </div>

                        <div class="timeline-item {{ ($data->isOrderConfirmed) ? 'completed' :'pending' }}">
                            <div class="timeline-icon">
                                <i class="fa {{ ($data->isOrderConfirmed) ?  'fa-check'  :'fa-cart-plus' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="m-0">Pembelian Seragam</h5>
                                <p class="text-muted small m-0">{{ ($data->isOrderConfirmed) ? 'Sudah Melakukan Pembelian Seragam' :'Belum Melakukan Pembelian Seragam' }} </p>
                            </div>
                        </div>

                        <div class="timeline-item {{ ($data->status == \App\Models\PPDBUser::STATUS_ACCEPTED) ? 'completed' :'pending' }}">
                            <div class="timeline-icon">
                                <i class="fa {{ ($data->status == \App\Models\PPDBUser::STATUS_ACCEPTED) ?  'fa-check'  :'fa-question' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="m-0">Penerimaan Akhir</h5>
                                <p class="text-muted small m-0">{{ ($data->status == \App\Models\PPDBUser::STATUS_ACCEPTED) ? 'Telah Diterima Sebegai Siswa' :'Belum Diterima Sebagai Siswa' }} </p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="opacity: 0.1;">

{{--                    <div class="d-grid gap-2">--}}
{{--                        <a href="{{ route('admin.ppdb.index') }}"--}}
{{--                           class="btn btn-block btn-warning rounded-pill fw-bold py-2 shadow-sm">--}}
{{--                            <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
