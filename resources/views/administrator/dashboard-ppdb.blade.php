@extends('layouts.admin.main')
@section('content')
    <style>
        .dashboard-modern {
            padding-top: 15px;
        }

        .widget-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .widget-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .widget-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #ffffff;
        }

        /* Modern Gradients */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4F46E5 0%, #3B82F6 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #06B6D4 0%, #3B82F6 100%);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #8B5CF6 0%, #D946EF 100%);
        }

        .bg-gradient-teal {
            background: linear-gradient(135deg, #14B8A6 0%, #10B981 100%);
        }

        .widget-info h4 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #64748B;
        }

        .widget-info h2 {
            margin: 8px 0 0 0;
            font-size: 28px;
            font-weight: 800;
            color: #0F172A;
        }

        .panel-modern {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
            border: none;
        }

        .panel-modern .panel-title {
            font-size: 16px;
            font-weight: 700;
            color: #1E293B;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #F1F5F9;
        }

        .recent-list-item {
            border: none;
            padding: 12px 0;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recent-list-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .recent-list-item h5 {
            margin: 0 0 4px 0;
            font-weight: 600;
            font-size: 14px;
            color: #334155;
        }

        .table-modern {
            width: 100%;
            margin-bottom: 0;
        }

        .table-modern th {
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border-top: none !important;
            border-bottom: 2px solid #F1F5F9 !important;
            padding: 12px 15px;
        }

        .table-modern td {
            vertical-align: middle !important;
            color: #334155;
            font-weight: 500;
            padding: 15px;
            border-top: 1px solid #F1F5F9;
        }

        /* Loader Styles */
        .dashboard-loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            /* Latar belakang agak transparan */
            backdrop-filter: blur(4px);
            /* Efek blur bergaya modern */
            z-index: 999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease, visibility 0.5s;
            border-radius: 14px;
        }

        .dashboard-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .spinner {
            border: 4px solid #E2E8F0;
            border-top: 4px solid #3B82F6;
            /* Warna biru */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="page-header">
        <h1 class="title">Dashboard PPDB</h1>
        <ol class="breadcrumb">
            <li class="active">Berikut adalah gambaran umum dari statistik pendaftar PPDB beserta tren penerimaan.</li>
        </ol>
    </div>

    <div class="container-default dashboard-modern" style="position: relative; min-height: 400px;">
        <!-- Loading Overlay -->
        <div id="dashboardLoader" class="dashboard-loader">
            <div class="spinner"></div>
        </div>

        <!-- Summary Widgets Row -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="widget-card">
                    <div class="widget-info">
                        <h4>Total Pendaftar</h4>
                        <h2>{{ number_format($data['totalRegistered']) }}</h2>
                    </div>
                    <div class="widget-icon bg-gradient-primary"><i class="fa fa-users"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="widget-card">
                    <div class="widget-info">
                        <h4>Melengkapi Administrasi</h4>
                        <h2>{{ $data['verifEmail'] }}</h2>
                    </div>
                    <div class="widget-icon bg-gradient-warning"><i class="fa fa-clock-o"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="widget-card">
                    <div class="widget-info">
                        <h4>Upload Surat Pernyataan</h4>
                        <h2>{{ $data['statementLetter'] }}</h2>
                    </div>
                    <div class="widget-icon bg-gradient-danger"><i class="fa fa-file-text-o"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="widget-card">
                    <div class="widget-info">
                        <h4>Siswa Diterima</h4>
                        <h2>{{ $data['studentAccepted'] }}</h2>
                    </div>
                    <div class="widget-icon bg-gradient-success"><i class="fa fa-check-circle"></i></div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity Row -->
        <div class="row">
            <div class="col-md-8">
                <div class="panel-modern">
                    <h3 class="panel-title">Statistik Pendaftar per Unit (Tahun Ini)</h3>
                    <canvas id="ppdbChart" height="110"></canvas>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel-modern">
                    <h3 class="panel-title">Pendaftar Terbaru</h3>
                    <div class="recent-list">
                        @foreach ($data['latestRegistered'] as $item)
                            <div class="recent-list-item">
                                <div>
                                    <h5>{{ $item['name'] }}</h5>
                                    <small class="text-muted"><i class="fa fa-building-o"></i> {{ $item['unit'] }}</small>
                                </div>
                                <span class="badge badge-warning"
                                    style="border-radius: 12px; padding: 5px 10px;">{{ $item['status'] }}</span>
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Statistics by Unit Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel-modern">
                    <h3 class="panel-title">Statistik Pembayaran Uang Pengembangan per Unit (Tahun Ini)</h3>
                    <div class="table-responsive">
                        <table class="table table-modern table-hover">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th class="text-center">Lunas</th>
                                    <th class="text-center">Cicilan</th>
                                    <th class="text-center">Dispensasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($data['totalRegisteredBillPerUnit']))
                                    @foreach ($data['totalRegisteredBillPerUnit'] as $unit => $stats)
                                        <tr>
                                            <td>{{ $stats['name'] }}</td>
                                            <td class="text-center"><span class="badge bg-gradient-info"
                                                    style="padding: 6px 12px; border-radius: 12px;">{{ number_format($stats['total_full'] ?? 0) }}</span>
                                            </td>
                                            <td class="text-center"><span class="badge bg-gradient-purple"
                                                    style="padding: 6px 12px; border-radius: 12px;">{{ number_format($stats['total_installment'] ?? 0) }}</span>
                                            </td>
                                            <td class="text-center"><span class="badge bg-gradient-teal"
                                                    style="padding: 6px 12px; border-radius: 12px;">{{ number_format($stats['total_dispensation'] ?? 0) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demografi Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel-modern">
                    <h3 class="panel-title">Top 5 Asal Sekolah</h3>
                    <div style="position: relative; height: 280px; width: 100%; display: flex; justify-content: center;">
                        <canvas id="originSchoolChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel-modern">
                    <h3 class="panel-title">Rasio Jenis Kelamin</h3>
                    <div style="position: relative; height: 280px; width: 100%; display: flex; justify-content: center;">
                        <canvas id="genderRatioChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Load Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('ppdbChart');

            var registeredPerUnitData = @json($data['totalRegisteredPerUnit'] ?? []);
            var chartLabels = [];
            var chartData = [];

            // Pisahkan data json menjadi labels dan hitungan
            for (var key in registeredPerUnitData) {
                if (registeredPerUnitData.hasOwnProperty(key)) {
                    chartLabels.push(registeredPerUnitData[key].name);
                    chartData.push(registeredPerUnitData[key].total);
                }
            }

            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartLabels.length > 0 ? chartLabels : ['Data Kosong'],
                        datasets: [{
                            label: 'Jumlah Pendaftar',
                            data: chartData.length > 0 ? chartData : [0],
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 0,
                            borderRadius: 6,
                            /* Border Radius for modern chart look */
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F1F5F9',
                                    drawBorder: false,
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                }
                            }
                        }
                    }
                });
            }

            // Inisialisasi Chart Pie Top 5 Asal Sekolah
            var ctxOrigin = document.getElementById('originSchoolChart');
            var originData = @json($data['topOriginSchools'] ?? []);

            var originLabels = [];
            var originCounts = [];

            if (Array.isArray(originData)) {
                originData.forEach(function(item) {
                    originLabels.push(item.origin_school || 'Tidak Diketahui');
                    originCounts.push(item.total || 0);
                });
            }

            if (ctxOrigin && originLabels.length > 0) {
                new Chart(ctxOrigin, {
                    type: 'pie', // Bisa diganti 'doughnut' untuk tampilan berlubang di tengah
                    data: {
                        labels: originLabels,
                        datasets: [{
                            data: originCounts,
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444',
                                '#8B5CF6'
                            ],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 14
                                }
                            }
                        }
                    }
                });
            }

            // Inisialisasi Chart Donat Rasio Jenis Kelamin
            var ctxGender = document.getElementById('genderRatioChart');
            var genderData = @json($data['genderRatio'] ?? []);

            var genderLabels = [];
            var genderCounts = [];

            if (Array.isArray(genderData)) {
                genderData.forEach(function(item) {
                    // Format label sesuai value (male/female)
                    var label = item.gender === 'male' ? 'Laki-laki' : (item.gender === 'female' ?
                        'Perempuan' : item.gender);
                    genderLabels.push(label || 'Tidak Diketahui');
                    genderCounts.push(item.total || 0);
                });
            } else if (typeof genderData === 'object' && genderData !== null) {
                // Handle jika struktur datanya berupa objek {male: X, female: Y}
                for (var key in genderData) {
                    if (genderData.hasOwnProperty(key)) {
                        var label = key === 'male' ? 'Laki-laki' : (key === 'female' ? 'Perempuan' : key);
                        genderLabels.push(label);
                        genderCounts.push(genderData[key]);
                    }
                }
            }

            if (ctxGender && genderLabels.length > 0) {
                new Chart(ctxGender, {
                    type: 'doughnut',
                    data: {
                        labels: genderLabels,
                        datasets: [{
                            data: genderCounts,
                            backgroundColor: ['#3B82F6', '#EC4899',
                                '#9CA3AF'
                            ], // Biru (Laki-laki), Pink (Perempuan), Abu (Lainnya)
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 14
                                }
                            }
                        }
                    }
                });
            }

            // Sembunyikan animasi loading setelah semua chart selesai digambar
            setTimeout(function() {
                var loader = document.getElementById('dashboardLoader');
                if (loader) {
                    loader.classList.add('hidden');
                }
            }, 600); // Penundaan 600ms agar transisinya terlihat halus
        });
    </script>
@endpush
