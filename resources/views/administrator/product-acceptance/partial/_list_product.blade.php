<div class="form-group">
    <div class="col-sm-12">
        <fieldset>
            <legend>Product Details</legend>

            @foreach(@$products->details as $item)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group col-sm-1" style="margin-right: 1em">
                            <label class="form-label small">Size</label>
                            <input type="hidden" name="id[{{$item->id}}][]" id="id" class="form-control form-control-line text-center"
                                   value="{{$item->id}}" readonly>
                            <input type="text" name="size[{{$item->id}}][]" id="size"
                                   class="form-control form-control-line text-center" value="{{$item->size}}"
                                   readonly>
                        </div>

                        <div class="form-group col-sm-1" style="margin-right: 1em">
                            <label class="form-label small">Stock</label>
                            <input type="number" min="0" name="stock[{{$item->id}}][]" id="stock" value="0"
                                   class="form-control form-control-line text-right">
                        </div>

                        <div class="form-group col-sm-2" style="margin-right: 1em">
                            <label class="form-label small">Vendor Price Siswa</label>
                            <div class="input-group">
                                <div class="input-group-addon rupiah">
                                    Rp
                                </div>
                                <input type="number" min="1" name="price_vendor_regular[{{$item->id}}][]" id="price_vendor_regular"
                                       class="form-control text-right"
                                       value="{{number_format(@$item->price_vendor_regular, 0, '', '')}}" readonly>
                            </div>
                        </div>

                        <div class="form-group col-sm-2" style="margin-right: 1em">
                            <label class="form-label small">Price Siswa</label>
                            <div class="input-group">
                                <div class="input-group-addon rupiah">
                                    Rp
                                </div>
                                <input type="number" min="1" name="price_siswa[{{$item->id}}][]" id="price_siswa"
                                       class="form-control text-right"
                                       value="{{number_format(@$item->price_siswa, 0, '', '')}}" readonly>
                            </div>
                        </div>

                        <div class="form-group col-sm-2" style="margin-right: 1em">
                            <label class="form-label small">Vendor Price PPDB</label>
                            <div class="input-group">
                                <div class="input-group-addon rupiah">
                                    Rp
                                </div>
                                <input type="number" min="1" name="price_vendor_ppdb[{{$item->id}}][]" id="price_vendor_ppdb"
                                       class="form-control text-right"
                                       value="{{ number_format(@$price_vendor_ppdb, 0, '', '') }}" readonly>
                            </div>
                        </div>

                        <div class="form-group col-sm-2" style="margin-right: 1em">
                            <label class="form-label small">Price PPDB</label>
                            <div class="input-group">
                                <div class="input-group-addon rupiah">
                                    Rp
                                </div>
                                <input type="number" min="1" name="price_ppdb[{{$item->id}}][]" id="price_ppdb"
                                       class="form-control text-right"
                                       value="{{ number_format(@$price_ppdb, 0, '', '') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </fieldset>
        <div class="form-group pull-right" style="margin-right: 1em">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Save</button>
            </div>
        </div>
    </div>
</div>

