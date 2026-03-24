<div class="{{ isset($full) ? 'col-12' : 'col-md-6' }}">
    <div class="p-3 rounded-3 bg-light border-start border-4 border-success bg-opacity-10 h-100">
        <label class="d-block text-muted small text-uppercase fw-bold mb-1">{{ $label }}</label>
        <div class="fw-semibold text-dark">{{ $value ?? '-' }}</div>
    </div>
</div>
