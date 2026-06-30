<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Progress Seleksi Siswa</h5>
                <p class="text-muted small mb-0">
                    <span class="text-success fw-bold">{{$detailStages[$stage->id]['stageUsed']}}</span> dari
                    <span class="fw-bold">{{ $detailStages[$stage->id]['total'] }}</span> siswa telah selesai diverifikasi
                </p>
            </div>
            <div class="text-end">
                <h3 class="fw-bold text-success mb-0">{{ round($detailStages[$stage->id]['overallProgress']) }}%</h3>
            </div>
        </div>

        <div class="progress rounded-pill" style="height: 12px; background-color: #e9ecef;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success rounded-pill"
                 role="progressbar"
                 style="width: {{ round($detailStages[$stage->id]['overallProgress']) }}%"
                 aria-valuenow="{{ round($detailStages[$stage->id]['overallProgress']) }}"
                 aria-valuemin="0"
                 aria-valuemax="100">
            </div>
        </div>
    </div>

    <div class="bg-light px-4 py-2 border-top border-light">
        <small class="text-secondary italic">
            <i class="bi bi-info-circle me-1"></i>
            Tahap dianggap selesai jika seluruh siswa pendaftar telah terkonfirmasi semua.
        </small>
    </div>
</div>
