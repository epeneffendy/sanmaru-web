@extends('layouts.welcome-page.main')

@section('content')
    @push('styles')
        <style>
            body {
                background-color: #e9ecef;
            }

            .receipt-container {
                max-width: 800px;
                margin: 40px auto;
                background-color: #fff;
                border: 1px solid #dee2e6;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
                font-family: 'Arial', sans-serif;
                color: #333;
            }

            .receipt-header {
                background-color: #1a4d2e;
                /* Warna hijau primer */
                color: white;
                padding: 30px;
                text-align: center;
                border-bottom: 5px solid #f0ad4e;
                /* Aksen kuning */
            }

            .receipt-header img {
                max-height: 70px;
                margin-bottom: 15px;
            }

            .receipt-header h1 {
                margin: 0;
                font-size: 2rem;
                font-weight: 700;
                letter-spacing: 1px;
            }

            .receipt-body {
                padding: 30px 40px;
                position: relative;
            }

            .receipt-meta {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
                font-size: 0.9rem;
            }

            .receipt-meta div {
                text-align: right;
            }

            .receipt-meta div:first-child {
                text-align: left;
            }

            .receipt-meta strong {
                display: block;
                color: #1a4d2e;
                margin-bottom: 3px;
            }

            .student-info {
                background-color: #f8f9fa;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 30px;
            }

            .student-info h3 {
                font-size: 1.2rem;
                color: #1a4d2e;
                margin-top: 0;
                margin-bottom: 15px;
                border-bottom: 2px solid #e9ecef;
                padding-bottom: 10px;
            }

            .student-info .info-grid {
                display: grid;
                grid-template-columns: 150px 1fr;
                gap: 8px;
            }

            .student-info .info-grid span:nth-child(odd) {
                font-weight: 600;
                color: #555;
            }

            .payment-details table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .payment-details th,
            .payment-details td {
                padding: 15px;
                border-bottom: 1px solid #e9ecef;
                text-align: left;
            }

            .payment-details thead th {
                background-color: #f8f9fa;
                font-weight: 700;
                color: #333;
                font-size: 0.9rem;
                text-transform: uppercase;
            }

            .payment-details .total-row td {
                font-weight: 700;
                font-size: 1.1rem;
            }

            .payment-details .total-row td:last-child {
                color: #1a4d2e;
            }

            .status-stamp {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-20deg);
                font-size: 7rem;
                font-weight: 900;
                color: #28a745;
                opacity: 0.15;
                border: 10px solid #28a745;
                padding: 10px 30px;
                border-radius: 10px;
                z-index: 0;
                pointer-events: none;
            }

            .receipt-footer {
                padding: 30px 40px;
                text-align: center;
                font-size: 0.85rem;
                color: #6c757d;
                background-color: #f8f9fa;
                border-top: 1px solid #e9ecef;
            }

            .print-button-container {
                text-align: center;
                margin-bottom: 40px;
            }

            @media print {
                body {
                    background-color: #fff;
                }

                .receipt-container {
                    margin: 0;
                    box-shadow: none;
                    border: none;
                }

                .print-button-container,
                .main-header,
                .main-sidebar,
                .main-footer,
                .breadcrumb-wrapper {
                    display: none;
                }
            }
        </style>
    @endpush

    <div class="receipt-container">
        <div class="receipt-header">
            <h1 class="text-white">BUKTI PEMBAYARAN</h1>
        </div>

        <div class="receipt-body">
            <div class="status-stamp">LUNAS</div>

            <div class="receipt-meta">
                <div>
                    <strong>Tanggal Lunas:</strong>
                    <span>{{ \Carbon\Carbon::parse($dispensation->updated_at)->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <div class="student-info">
                <h3>Informasi Siswa</h3>
                <div class="info-grid">
                    <span>Nama Lengkap</span>
                    <span>: {{ $ppdb->name ?? 'Nama Siswa' }}</span>
                    <span>No. Registrasi</span>
                    <span>: {{ $ppdb->register_number ?? '000000' }}</span>
                    <span>Unit / Sekolah</span>
                    <span>: {{ $ppdb->unit->name ?? 'Unit Sekolah' }}</span>
                    <span>Tahun Ajaran</span>
                    <span>: {{ $ppdb->school_year . '/' . ($ppdb->school_year + 1) }}</span>
                </div>
            </div>

            @php
                // Menghitung jumlah potongan
                $discount = $dispensation->actual_cost - $dispensation->total_final_fee;
            @endphp

            <div class="student-info" style="background-color: #fdfaf6; border-color: #f5e2c8;">
                <h3 style="color: #b87b2b; border-bottom-color: #f5e2c8;">Ringkasan Tagihan {{ $title }}</h3>
                <div class="info-grid">
                    <span>Tagihan Awal</span>
                    <span>: Rp {{ number_format($dispensation->actual_cost, 0, ',', '.') }}</span>

                    @if ($discount > 0)
                        <span>Dispensasi / Potongan</span>
                        <span style="color: #d9534f;">: - Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    @endif

                    <span style="font-weight: 700; color: #333; margin-top: 5px;">Total Kewajiban</span>
                    <span style="font-weight: 700; color: #1a4d2e; margin-top: 5px; font-size: 1.05rem;">
                        : Rp {{ number_format($dispensation->total_final_fee, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="payment-details">
                <h3>Rincian Pembayaran {{ $title }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th style="text-align: right;">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($dispensation->details) > 0)
                            @foreach ($dispensation->details as $item)
                                @if (count($dispensation->details) == 1)
                                    <tr>
                                        <td>Pembayaran Lunas</td>
                                        <td style="text-align: right;">Rp
                                            {{ number_format($item->amount_paid, 0, ',', '.') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $item->installment_number == 0 ? 'Pembayaran DP' : 'Cicilan ke- ' . $item->installment_number }}
                                        </td>
                                        <td style="text-align: right;">Rp
                                            {{ number_format($item->amount_paid, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach

                            <tr class="total-row" style="border-top: 2px solid #333; border-bottom: 2px solid #333;">
                                <td>Total Telah Dibayar</td>
                                <td style="text-align: right;">Rp
                                    {{ number_format($dispensation->total_final_fee, 0, ',', '.') }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2" style="font-style: italic; text-align: center;">Data Pembayaran Tidak
                                    Ditemukan</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Terima kasih telah menyelesaikan pembayaran {{ $title }}. Simpan bukti ini dengan baik.</p>
            <p>&copy; {{ date('Y') }} Yayasan Paratha Bhakti.</p>
        </div>
    </div>

    <div class="print-button-container">
        <a href="{{ route('finance-bills') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>
            Kembali</a>
        {{-- <button onclick="window.print()" class="btn btn-dark-green text-white"><i class="fa fa-print"></i> Cetak Bukti
            Pembayaran</button> --}}
    </div>
@endsection
