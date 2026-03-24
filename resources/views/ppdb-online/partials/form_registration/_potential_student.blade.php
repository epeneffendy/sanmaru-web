@if ($inputs->get('potensi_dan_bakat_sains'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Potensi dan bakat Sains</label>
                <div class="input-group modern-input-group">
                    <textarea name="potensi_dan_bakat_sains" class="form-control uppercase-input required"
                              placeholder="Potensi dan bakat Sains">{{ old('potensi_dan_bakat_sains', @$ppdbUser->potensi_dan_bakat_sains) }}</textarea>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('potensi_dan_bakat_seni'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Potensi dan bakat Seni</label>
                <div class="input-group modern-input-group">
                    <textarea name="potensi_dan_bakat_seni" class="form-control uppercase-input required"
                              placeholder="Potensi dan bakat Seni">{{ old('potensi_dan_bakat_seni', @$ppdbUser->potensi_dan_bakat_seni) }}</textarea>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('potensi_dan_bakat_olahraga'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Potensi dan bakat Olahraga</label>
                <div class="input-group modern-input-group">
                    <textarea name="potensi_dan_bakat_seni" class="form-control uppercase-input required"
                              placeholder="Potensi dan bakat Seni">{{ old('potensi_dan_bakat_seni', @$ppdbUser->potensi_dan_bakat_seni) }}</textarea>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('potensi_dan_bakat_lainnya'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Potensi dan bakat Lainnya</label>
                <div class="input-group modern-input-group">
                    <textarea name="potensi_dan_bakat_lainnya" class="form-control uppercase-input required"
                              placeholder="Potensi dan bakat Lainnya">{{ old('potensi_dan_bakat_lainnya', @$ppdbUser->potensi_dan_bakat_lainnya) }}</textarea>
                </div>
            </div>
        </div>
    </div>
@endif
