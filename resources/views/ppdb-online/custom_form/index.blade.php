@extends('layouts.ppdb-online.main')

@section('content')
<div class="row row-height container-desktop">
    <div class="col content-top" id="start">
        <div id="wizard_container">
            <form method="POST" action="{{ route('ppdb.custom-form.input.post', $customForm->slug) }}">
                @csrf
                <div class="wrapper-content-desktop">
                    <h2>Form {{ $customForm->name }}</h2>

                    <br>
                </div>

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                @foreach ($customForm->columns as $column)
                <div class="form-group">
                    <label class="control-label">{{ $column->label }}</label>
                    <input type="{{ $column->type_html }}" name="{{ $column->id }}"
                        value="{{ old($column->id, $customForm->columnInputs->firstWhere('custom_form_column_id', $column->id)->value ?? '') }}"
                        class="form-control required" placeholder="{{ $column->label }}">
                </div>
                @endforeach
                <div class="clear-50"></div>
                <ul class="btn-below">
                    <li>
                        <a href="{{ route('ppdb.data-siswa-ppdb') }}">
                            <button type="button" class="btn-back btn-disabled">Kembali</button>
                        </a>
                    </li>
                    <li>
                        <button type="submit" class="btn-save">Simpan</button>
                    </li>
                </ul>
            </form>
        </div>
        <!-- /Wizard container -->
    </div>
    <!-- /content-right-->
</div>
@endsection

@push('styles')
    <style>
        .btn{
            min-width: 150px;
        }
        .btn-register{
            padding: 0 1rem;
            font-size: 14px !important;
            color: white !important;
            width: auto !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
@endpush
