<div class="stepper-wrapper mb-5 px-md-5">
    <div class="stepper-progress">
        <div id="formProgressBar" class="stepper-progress-bar" style="width: 0%;"></div>
    </div>

    <div class="d-flex justify-content-between position-relative">
        @php
            $steps = [
                ['icon' => 'bi-person-vcard', 'label' => 'Identitas'],
                ['icon' => 'bi-file-earmark-text', 'label' => 'Dokumen'],
                ['icon' => 'bi-trophy', 'label' => 'Prestasi'],
                ['icon' => 'bi-bank', 'label' => 'Sekolah']
            ];
        @endphp

        @foreach($steps as $index => $step)
            <div class="step-item text-center">
                <button type="button"
                        class="step-circle {{ $index == 0 ? 'active' : '' }}"
                        id="step-btn-{{ $index }}"
                        data-index="{{ $index }}">
                    <i class="bi {{ $step['icon'] }}"></i>
                </button>
                <div class="step-label-container mt-2">
                    <span class="step-label {{ $index == 0 ? 'active' : '' }} d-none d-md-block">
                        {{ $step['label'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
