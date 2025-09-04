<tr class="product-detail-row">
    @if (isset($no))
    <td>
        <label class="form-label">{{ @$no }}</label>
    </td>
    @endif
    @if (isset($product_name))
    <td>
        <label class="form-label">{{ @$product_name }}</label>
    </td>
    @endif
    <td>
        <label class="form-label size">{{ @$size }}</label>
        <input type="hidden" name="product_id[]" value="{{ @$product_id }}">
        <input type="hidden" name="product_detail_id[]" value="{{ @$product_detail_id }}">
        <input type="hidden" name="size[]" value="{{ @$size }}">
        <input type="hidden" name="product_order_detail_id[]" value="{{ @$product_order_detail_id }}" />
    </td>
    @if (isset($stock))
    <td>
        <label class="form-label stock">{{ @$stock }}</label>
        <input type="hidden" name="stock[]" value="{{ @$stock }}">
    </td>
    @endif
    <td>
        <label class="form-label price_siswa">{{ @$price_siswa }}</label>
        <input type="hidden" name="price_siswa[]" value="{{ @$price_siswa }}">
    </td>
    <td>
        <label class="form-label qty">{{ @$qty }}</label>
        <input type="hidden" class="form-control input-sm" name="qty[]" style="width:15rem" value="{{ @$qty }}">
    </td>
    <td>
        <label class="form-label total_price">{{ @$total_price }}</label>
        <input type="hidden" class="form-control input-sm" name="total_price[]" style="width:15rem" value="{{ @$total_price }}">
    </td>
    @if(! @$mode == 'show')
        <td>
            <button type="button" class="btn btn-sm btn-light btn-edit-selected-product"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-sm btn-danger btn-delete-selected-product"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-sm btn-success btn-update-selected-product" style="display: none;"><i class="fa fa-check"></i></button>
        </td>
    @endif
</tr>
