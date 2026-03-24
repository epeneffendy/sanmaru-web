@extends('layouts.admin.main')
@section('content')
    @push('styles')
        <style>
            body {
                background-color: #f3f6f9;
            }

            .card {
                transition: transform 0.2s;
            }

            .nav-tabs .nav-link {
                color: #adb5bd;
                border: none;
                border-bottom: 3px solid transparent;
                transition: all 0.3s ease;
            }

            .nav-tabs .nav-link.active {
                color: #198754 !important;
                background: none;
                border-bottom: 3px solid #198754;
            }

            .border-start.border-4 {
                border-width: 4px !important;
            }

            .bg-light {
                background-color: #f8f9fa !important;
            }


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
                <div class="widget ">

                    <div class="widget-content">
                        <div class="container-fluid py-4">
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                                        <h5 class="fw-bold mb-1">{{ $data->name }}</h5>
                                        <p class="text-muted small mb-3">Register
                                            Number: {{ $data->register_number }}</p>

                                        @if($data->status == \App\Models\PPDBUser::STATUS_INCOMPLETE)
                                            <label class="label label-info label-xs">Status : Menunggu Verifikasi
                                                Email</label>
                                        @elseif($data->status == \App\Models\PPDBUser::STATUS_COMPLETE)
                                            <label class="label label-info label-xs">Status : Menunggu
                                                Pembayaran</label>
                                        @elseif($data->status == \App\Models\PPDBUser::STATUS_CONFIRMED)
                                            <label class="label label-info label-xs">Status : Proses Penerimaan</label>
                                        @elseif($data->status == \App\Models\PPDBUser::STATUS_SUBMITTED)
                                            <label class="label label-info label-xs">Status : Menunggu
                                                Validasi Data</label>
                                        @elseif($data->status == \App\Models\PPDBUser::STATUS_ACCEPTED)
                                            <label class="label label-info label-xs">Status : Siswa Diterima</label>
                                        @else
                                            <label class="label label-danger label-xs">Status : Ditolak</label>
                                        @endif


                                        <hr class="my-4 opacity-50">

                                        <div class="text-start">
                                            <h6 class="fw-bold small text-uppercase mb-3" style="font-weight: bold">Informasi Periode
                                                Pendaftaran</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="small">{{ $data->period->name }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-whatsapp text-success me-3"></i>
                                                <span class="small">{{ $data->unit->name }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="small">{{ $data->school_year }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-header bg-white border-0 pt-4">
                                            <ul class="nav nav-tabs custom-modern-tabs border-0" id="registrationTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="diri-tab" data-bs-toggle="tab" data-bs-target="#diri" type="button" role="tab">
                                                        <i class="bi bi-person-fill me-2"></i>Data Diri
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="ortu-tab" data-bs-toggle="tab" data-bs-target="#ortu" type="button" role="tab">
                                                        <i class="bi bi-people-fill me-2"></i>Data Orang Tua
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="tab-content" id="registrationTabContent">
                                                <div class="tab-pane fade show active" id="diri" role="tabpanel">
                                                </div>
                                                <div class="tab-pane fade" id="ortu" role="tabpanel">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
