@extends('layouts.admin.main')
@section('content')
    @push('styles')
        <style>
            .modern-timeline {
                padding-left: 10px;
            }

            .timeline-item {
                display: flex;
                position: relative;
                padding-bottom: 2.5rem; /* Jarak antar tahap agar tidak berhimpitan */
            }

            /* Menghilangkan padding pada item terakhir agar garis tidak kebablasan */
            .timeline-item:last-child {
                padding-bottom: 0;
            }

            /* Garis Vertikal yang menyatu dengan lingkaran */
            .timeline-line {
                position: absolute;
                left: 15px; /* Setengah dari lebar marker */
                top: 30px; /* Mulai dari bawah lingkaran */
                bottom: 0;
                width: 2px;
                background-color: #e9ecef; /* Warna garis yang soft */
                z-index: 1;
            }

            .timeline-marker {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                z-index: 2;
                background-color: #198754; /* Warna hijau konsisten */
                box-shadow: 0 0 0 4px #fff; /* Memberikan efek putih agar garis terlihat menyatu */
            }

            /* Padding Text agar tidak berhimpitan */
            .timeline-content {
                padding-left: 25px; /* Jarak aman antara lingkaran dan teks */
                padding-top: 2px;
            }

            /* Font lebih besar dan Bold untuk Title */
            .timeline-content h3 {
                font-size: 1.75rem !important; /* Font lebih besar */
                font-weight: 700 !important; /* Bold maksimal */
                color: #2d3436;
                margin-top: 2px;
            }

            /* Penyesuaian ikon check */
            .timeline-marker i {
                font-size: 16px;
            }

            /* Container untuk Badge di pojok kanan atas */
            .date-badge-container {
                position: absolute;
                top: 15px;
                right: 15px;
                background: #ffffff;
                border: 1px solid #e9ecef;
                padding: 6px 14px;
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                align-items: center;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
                min-width: 100px;
            }

            /* Label kecil di atas tanggal */
            .badge-date-label {
                font-size: 10px;
                text-transform: uppercase;
                font-weight: 700;
                color: #adb5bd;
                letter-spacing: 1px;
                margin-bottom: 2px;
            }

            /* Nilai tanggal utama */
            .badge-date-value {
                font-size: 13px;
                font-weight: 700;
                color: #198754; /* Hijau konsisten dengan tema pendaftaran */
            }

            /* Tambahan pemanis teks */
            .letter-spacing-1 {
                letter-spacing: 1px;
            }

            /* Hover effect agar interaktif */
            .timeline-content:hover .date-badge-container {
                border-color: #198754;
                transition: all 0.3s ease;
            }

            .progress {
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .progress-bar {
                transition: width 1.5s ease-in-out; /* Efek loading saat halaman dibuka */
            }

            .bg-success.bg-opacity-10 {
                background-color: rgba(25, 135, 84, 0.1) !important;
            }

            /* Membuat huruf miring untuk info */
            .italic {
                font-style: italic;
            }

            /* Agar progres bar tidak terpengaruh panjang timeline-item */
            .progress-container-wrapper {
                margin-top: 10px;
                margin-bottom: 15px;
            }

            /* Memastikan progres bar di dalam partials tetap konsisten */
            .progress-container-wrapper .progress {
                height: 8px !important; /* Buat lebih tipis agar elegan */
                border-radius: 10px;
                background-color: #f0f0f0;
            }

            /* Responsif: Di layar kecil (HP) biarkan full width, di layar besar batasi */
            @media (min-width: 768px) {
                .progress-container-wrapper {
                    max-width: 300px; /* Ukuran ideal agar tidak terlalu panjang */
                }
            }
        </style>
    @endpush

    <div class="page-header">
        <h1 class="title">Monitoring PPDB</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li>Monitoring PPDB</li>
            <li class="active">{{isset($period) ? $period->name : ''}}</li>
        </ol>
    </div>

    <div class="container-padding">

        <div class="panel panel-default">
            <div class="panel-title">
                Tahapan Pendaftaran
            </div>

            <div class="timeline-content card border-0 bg-light p-4 rounded-4 shadow-sm position-relative">
                <div class="date-badge-container">
                    <span class="badge-date-label">Periode</span>
                    <span
                        class="badge-date-value"> {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($period->start_end)->format('d M Y') }}  </span>
                </div>

                <div class="pe-5">
                    <h1 class="fw-bold text-dark fs-4 mb-0">{{ $period->name }}</h1>
                    <h4 class="fw-bold text-dark fs-4 mb-0">Unit : {{ $period->unit->name }}</h4>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="modern-timeline">
                    <div class="timeline-item">
                        <div class="timeline-line"></div>
                        <div class="timeline-marker bg-success">
                            <i class="bi bi-check2 text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="text-muted large mb-0">Tahap 1</p>
                            <h3 class="fw-bold text-dark fs-5 mb-1">Seleksi Administrasi</h3>
                            <p class="text-secondary small">Tahap pengisian data administrasi calon siswa</p>
                            <label class="label label-info label-xs">{{ $stageAdministrasi['confirm'] }} Siswa
                                Terkonfirmasi</label><br>
                            <label class="label label-danger label-xs">{{ $stageAdministrasi['not_confirm'] }} Siswa
                                Belum Terkonfirmasi</label>
                            <h3 class="fw-bold text-dark fs-5 mb-1"></h3>
                            <div class="d-flex gap-6 mt-2">
                                <a href="{{ route('admin.ppdb-monitoring.show-detail-stage', [$period['id'], 'administration', 'xx']) }}"
                                   title="Detail Seleksi Administrasi"
                                   class="btn btn-ld btn-success">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>

                    @php($tahap = 2)
                    @foreach($stages as $stage)
                        <div class="timeline-item">
                            <div class="timeline-line"></div>
                            <div class="timeline-marker bg-success">
                                <i class="bi bi-check2 text-white"></i>
                            </div>
                            <div class="timeline-content">

                                <p class="text-muted large mb-0">Tahap {{$tahap++}}</p>
                                <h3 class="fw-bold text-dark fs-5 mb-1">{{$stage->name. ' -- '. $stage->id .' -- '}}</h3>
                                @if(isset($detailStages[$stage->id]))

                                    <p class="text-secondary small">Total Siswa di tahap ini
                                        : {{$detailStages[$stage->id]['total']}}</p>
                                    <label
                                        class="label label-info label-xs">{{ $detailStages[$stage->id]['passed'] }}
                                        Siswa
                                        Lolos</label><br>
                                    <label
                                        class="label label-primary label-xs">{{ $detailStages[$stage->id]['not_passed'] }}
                                        Siswa
                                        Tidak Lolos</label><br>
                                    <label
                                        class="label label-warning label-xs">{{ $detailStages[$stage->id]['pending'] }}
                                        Pending</label><br>
                                    <label
                                        class="label label-danger label-xs">{{ $detailStages[$stage->id]['not_confirm'] }}
                                        Belum Terkonfirmasi</label>

                                    <div class="progress-container-wrapper" style="max-width: 350px;">
                                        @include('administrator.ppdb-monitoring.partials.timeline-progress-bar')
                                    </div>

                                    <div class="d-flex gap-6 mt-2">
                                        @if($stage->is_opening_development_feature)
                                            <a href="{{ route('admin.ppdb-monitoring.show-detail-stage', [$period['id'], 'development-statement',$stage->id ]) }}"
                                               title="Detail Seleksi Administrasi"
                                               class="btn btn-ld btn-success">
                                                Detail
                                            </a>
                                        @else
                                            <a href="{{ route('admin.ppdb-monitoring.show-detail-stage', [$period['id'], 'stage',$stage->id]) }}"
                                               title="Detail Seleksi Tahap"
                                               class="btn btn-ld btn-success">
                                                Detail
                                            </a>
                                        @endif

                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>
@endsection
