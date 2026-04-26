@extends('layouts.ppdb-online.main')
@section('content')
    @push('styles')
        <style>
            /* Base Styles */
            .header-accent { width: 40px; height: 4px; background-color: #1a4d2e; border-radius: 2px; }
            .icon-square { width: 40px; height: 40px; background-color: rgba(26, 77, 46, 0.08); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
            .btn-detail { background-color: #f8f9fa; color: #555; border: 1px solid #dee2e6; font-size: 0.75rem; font-weight: 600; border-radius: 8px; }
            .summary-box { background-color: #f0f7f2; border: 1px solid rgba(26, 77, 46, 0.1); }
            .btn-pay { background-color: #1a4d2e; color: white; border: none; }
            .x-small { font-size: 0.7rem; line-height: 1.2; }
            .rounded-4 { border-radius: 1.2rem !important; }

            /* Responsive Adjustments */
            @media (max-width: 767.98px) {
                .h6-mobile { font-size: 0.95rem; }
                .small-mobile-title { font-size: 0.85rem; }
                .responsive-total { font-size: 1.5rem; }

                .border-top-mobile {
                    border-top: 1px dashed #eee;
                    padding-top: 10px;
                }

                /* Memastikan card tidak terlalu mepet ke pinggir layar */
                .container { padding-left: 15px; padding-right: 15px; }

                /* Tombol detail dibuat sedikit lebih besar agar mudah ditekan jari */
                .btn-detail { padding: 8px 20px; font-size: 0.8rem; }
            }

            /* Menyesuaikan dengan sidebar hijau (Kamandaka Madava Anwar style) */
            .header-accent {
                width: 40px;
                height: 4px;
                background-color: #1a4d2e;
                border-radius: 2px;
            }

            .bill-row {
                padding: 1rem 0;
                border-bottom: 1px solid #f1f1f1;
                transition: background 0.2s;
            }

            .bill-row:hover {
                background-color: #fcfcfc;
            }

            .icon-square {
                width: 40px;
                height: 40px;
                background-color: rgba(26, 77, 46, 0.08); /* Hijau transparan soft */
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
            }

            .text-success { color: #1a4d2e !important; }

            /* Button Detail Minimalis */
            .btn-detail {
                background-color: #f8f9fa;
                color: #555;
                border: 1px solid #dee2e6;
                font-size: 0.75rem;
                font-weight: 600;
                padding: 5px 15px;
                border-radius: 6px;
                transition: all 0.2s;
            }

            .btn-detail:hover {
                background-color: #1a4d2e;
                color: white;
                border-color: #1a4d2e;
            }

            /* Summary & Pay Button */
            .summary-box {
                background-color: #f0f7f2; /* Hijau sangat muda */
                border: 1px solid rgba(26, 77, 46, 0.1);
            }

            .btn-pay {
                background-color: #1a4d2e;
                color: white;
                border: none;
                box-shadow: 0 4px 10px rgba(26, 77, 46, 0.2);
            }

            .btn-pay:hover {
                background-color: #143d24;
                color: white;
                transform: translateY(-1px);
            }

            .x-small { font-size: 0.75rem; }
            .rounded-4 { border-radius: 1rem !important; }
        </style>
    @endpush
    <div class="row-height">

        <div class="container py-3 px-2 px-md-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold px-3" style="color: #1a4d2e; font-size: calc(1.1rem + 0.5vw);">Rincian Biaya Pendidikan</h4>
                        <p class="text-muted small">Tahun Ajaran 2025/2026</p>
                        <div class="header-accent mx-auto"></div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header border-0 p-3 p-md-4" style="background-color: #1a4d2e;">
                            <h5 class="mb-0 text-white fw-bold h6-mobile"><i class="fas fa-receipt me-2"></i> Ringkasan Tagihan</h5>
                        </div>

                        <div class="card-body p-3 p-md-4 bg-white">
                            @php
                                $items = [
                                    ['label' => 'Uang Pendaftaran', 'desc' => 'Biaya administrasi dan formulir', 'price' => '500.000', 'icon' => 'fa-file-invoice'],
                                    ['label' => 'Uang Pengembangan', 'desc' => 'Pembangunan dan fasilitas gedung', 'price' => '5.000.000', 'icon' => 'fa-building'],
                                    ['label' => 'Uang Seragam', 'desc' => '5 Setel seragam & atribut lengkap', 'price' => '1.500.000', 'icon' => 'fa-tshirt'],
                                    ['label' => 'Uang SPP', 'desc' => 'Biaya pendidikan bulan Juli 2025', 'price' => '1.000.000', 'icon' => 'fa-calendar-alt'],
                                ];
                            @endphp

                            @foreach($items as $item)
                                <div class="bill-row py-3 border-bottom">
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-square flex-shrink-0"><i class="fas {{ $item['icon'] }} text-success"></i></div>
                                            <div class="ms-3">
                                                <h6 class="mb-0 fw-bold text-dark small-mobile-title">{{ $item['label'] }}</h6>
                                                <span class="text-muted x-small d-block">{{ $item['desc'] }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between w-100 w-md-auto mt-2 mt-md-0 pt-2 pt-md-0 border-top-mobile">
                                            <span class="fw-bold text-dark me-md-3">Rp {{ $item['price'] }}</span>
                                            <button class="btn btn-detail px-3 py-1">Detail</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="summary-box mt-4 p-3 p-md-4 rounded-4">
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-7 mb-3 mb-md-0">
                                        <span class="text-uppercase x-small fw-bold text-muted">Total Kewajiban</span>
                                        <h2 class="fw-bolder mb-0 responsive-total" style="color: #1a4d2e;">Rp 8.000.000</h2>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <button class="btn btn-pay w-100 py-3 py-md-2 fw-bold rounded-3">
                                            Bayar Sekarang <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 py-3 text-center px-3">
                            <small class="text-muted x-small">* Pembayaran dapat dilakukan melalui Transfer Bank atau Virtual Account</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection