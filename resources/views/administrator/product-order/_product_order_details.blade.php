<tr class="product-detail-row">
    <td>
        <label class="form-label size"></label>
        {{-- <input type="hidden" name="product_id[]" value="">
        <input type="hidden" name="product_detail_id[]" value="">
        <input type="hidden" class="form-control" name="size[]" value=""> --}}
        <input type="hidden" name="product_id" value="">
        <input type="hidden" name="product_detail_id" value="">
        <input type="hidden" class="form-control" name="size" value="">
    </td>
    <td>
        <label class="form-label stock"></label>
        {{-- <input type="hidden" class="form-control" name="stock[]" value=""> --}}
        <input type="hidden" class="form-control" name="stock" value="">
    </td>
    <td>
        <label class="form-label price_siswa"></label>
        {{-- <input type="hidden" class="form-control" name="price_siswa[]" value=""> --}}
        <input type="hidden" class="form-control" name="price_siswa" value="">
    </td>
    <td>
        <label class="form-label price_ppdb"></label>
        {{-- <input type="hidden" class="form-control" name="price_ppdb[]" value=""> --}}
        <input type="hidden" class="form-control" name="price_ppdb" value="">
    </td>
    <td>
        <input type="number" class="form-control" name="qty" class="form-control input-sm" style="width:15rem" value="">
        {{-- <input type="number" class="form-control" name="qty[]" class="form-control input-sm" style="width:15rem" value=""> --}}
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-success btn-add">
            <i class="fa fa-plus"></i> Add
        </button>
    </td>
</tr>
