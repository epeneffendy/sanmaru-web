<div class="product-detail row" style="margin: 0;" data-product-details-id="{{ @$product_details_id }}">
    <input type="hidden" name="product_details_ids[]" value="{{ @$product_details_id }}" />
    <div class="form-group col-sm-1">
        <label class="form-label">{{ @$size }}</label>
        <input name="sizes[]" type="hidden" value="{{ @$size }}" class="form-control form-control-line" />
    </div>
    <div class="form-group col-sm-1">
        <label class="form-label">{{ @$stock }}</label>
        <input name="stocks[]" min="0" data-validation="number" type="hidden" value="{{ @$stock }}" class="form-control form-control-line" />
    </div>
    <div class="form-group col-sm-2">
        <label class="form-label">{{ number_format(@$price_vendor_regular, 0, '', '') }}</label>
        <div class="input-group" style="display: none;">
            <div class="input-group-addon rupiah">
                Rp
            </div>
            <input name="prices_vendor_regular[]" data-validation="number" type="hidden" min="1" value="{{ number_format(@$price_vendor_regular, 0, '', '') }}" class="form-control form-control-line" />
        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="form-label">{{ number_format(@$price_siswa, 0, '', '') }}</label>
        <div class="input-group" style="display: none;">
            <div class="input-group-addon rupiah">
                Rp
            </div>
            <input name="prices_siswa[]" data-validation="number" type="hidden" min="1" value="{{ number_format(@$price_siswa, 0, '', '') }}" class="form-control form-control-line" />
        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="form-label">{{ number_format(@$price_vendor_ppdb, 0, '', '') }}</label>
        <div class="input-group" style="display: none;">
            <div class="input-group-addon rupiah">
                Rp
            </div>
            <input name="prices_vendor_ppdb[]" data-validation="number" type="hidden" min="1" value="{{ number_format(@$price_vendor_ppdb, 0, '', '') }}" class="form-control form-control-line" />
        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="form-label">{{ number_format(@$price_ppdb, 0, '', '') }}</label>
        <div class="input-group" style="display: none;">
            <div class="input-group-addon rupiah">
                Rp
            </div>
            <input name="prices_ppdb[]" data-validation="number" type="hidden" min="1" value="{{ number_format(@$price_ppdb, 0, '', '') }}" class="form-control form-control-line" />
        </div>
    </div>
    <div class="form-group col-sm-2">
        <button class="btn btn-xs button-edit-details"><i class="fa fa-pencil"></i></button>
        <button class="btn btn-xs button-delete-details btn-danger"><i class="fa fa-trash"></i></button>
        <button class="btn btn-xs button-save-details btn-success" style="display: none;"><i class="fa fa-check"></i></button>
    </div>
</div>
