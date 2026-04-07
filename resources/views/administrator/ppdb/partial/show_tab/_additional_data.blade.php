@php($additionalData = \App\Helpers\InputCollectionHelper::additionalData($data->unit))

<div class="row">
    @foreach ($additionalData as $k => $v)
    <div class="col-md-6 mb-3">
        @if ($k == 'npwp')
            <label class="text-muted small fw-bold text-uppercase">{{ strtoupper($k) }} Orangua/Wali</label>
        @else
            <label class="text-muted small fw-bold text-uppercase">{{ ucwords(str_replace('_', ' ', $k)) }}</label>
        @endif

        <p class="border-bottom pb-2">{{ @$data[$k] }}</p>
    </div>
    @endforeach
</div>
