<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Filter</h3>
    </div>
    <div class="panel-body">
        <form role="form" autocomplete="off" method="GET" action="{{ route('admin.product.index') }}">
            <div class="row">
                <input type="hidden" name="apply_filter" value="1">
                <div class="form-group col-md-4">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}" class="form-control input-sm" />
                </div>
                <div class="form-group col-md-4">
                    <label for="unit" class="form-label">Unit</label>
                    <select name="unit" class="form-control input-sm">
                        <option value="0">== SEMUA ==</option>
                        @foreach (@$units as $unit)
                            <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="category" class="form-label">Kategori</label>
                    <select name="category" class="form-control input-sm">
                        <option value="0">== SEMUA ==</option>
                        @foreach (@$categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == @$params['category'] ? 'selected' : NULL }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.product.index') }}" class="pull-right btn btn-sm btn-warning">
                        <i class="fa fa-refresh"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="button-collection">
    <a href="{{ route('admin.product.export', request()->except('page')) }}" download class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
    <a href="{{ route('admin.product.export', ['template-only']) }}" download class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Download Template .xls</a>
    <button class="btn btn-default btn-upload-modal btn-sm"><i class="fa fa-upload"></i> Import .xls</button>
    <a href="{{ route('admin.product.history-stock') }}" class="btn btn-primary btn-sm"><i class="fa fa-history"></i> History Stock</a>
</div>

<div class="fixed-table-head">
    <table id="datatables-master-product" class="table display table-responsive">
        <thead>
        <tr>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">No</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;min-width: 150px;">Name</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Category</th>
            <th colspan="6" style="text-align: center;vertical-align: middle;">Stock</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;" width="100px">Publish</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;" width="120px">Option</th>
        </tr>
        <tr>
            <th style="text-align: center;">Size</th>
            <th style="text-align: center;">Initial</th>
            <th style="text-align: center;">Sold</th>
            <!-- <th style="text-align: center;">Canceled</th> -->
            <th style="text-align: center;">Available</th>
            <th style="text-align: center;">Price Siswa</th>
            <th style="text-align: center;">Price PPDB</th>
        </tr>
        </thead>
        <tbody>
        @php
            $rowId = 0;
            $rowspan = 0;
            $number = ($products->currentPage() - 1) * $products->perPage();
        @endphp
        @foreach ($products as $value)
            @foreach($value->details as $key => $detail)
                @php
                    $rowId += 1
                @endphp
            <tr>
                @if ($key == 0 || $rowspan == $rowId)
                    @php
                        $rowId = 0;
                        $rowspan = $value->details_count;
                    @endphp
                    <td rowspan="{{$rowspan}}">{{++$number}}</td>
                    <td rowspan="{{$rowspan}}">
                        <p>
                            <b>{{ $value->name }}</b><br/>
                            <label class="label label-info">merk: {{ $value->merk }}</label><br/>
                            <label class="label label-default">slug: {{ $value->slug }}</label><br/>
                            <label class="label label-warning" style="white-space: inherit">type: {{ $value->type->name }}</label><br/>
                            <label class="label label-default">level: {{ $value->level }}</label><br/>
                            <label class="label label-success">weight: {{ $value->weight }}gram</label><br/>
                            @foreach ($value->units->pluck('name')->all() as $unit)
                                <label class="label label-danger">{{ $unit }}</label>
                            @endforeach
                        </p>
                    </td>
                    <td rowspan="{{$rowspan}}">{{ $value->category->name }}</td>
                @endif


                <td style="text-align: center;"><label class="label label-default label-sm">{{ $detail->size }}</label></td>
                <td style="text-align: center;"><label class="label label-primary label-sm">{{ $detail->initial_stock }}</label></td>
                <td style="text-align: center;"><label class="label label-warning label-sm">{{ $detail->stock_sold }}</label></td>
                <!-- <td style="text-align: center;"><label class="label label-danger label-sm">{{ $detail->stock_canceled }}</label></td> -->
                <td style="text-align: center;"><label class="label label-success label-sm">{{ $detail->available_stock }}</label></td>
                <td style="text-align: center;"><label class="label label-default label-sm">{{ \App\Helpers\PriceHelper::rupiah($detail->price_siswa) }}</label></td>
                <td style="text-align: center;"><label class="label label-default label-sm">{{ \App\Helpers\PriceHelper::rupiah($detail->price_ppdb) }}</label></td>


                @if ($rowId == 0)
                    <td><span class="label {{ $value->isPublished() ? 'label-success' : 'label-danger'}}">{{ $value->status }}</label></td>
                    <td>
                        <a href="{{ route('admin.product.show',$value->id) }}" title="Edit" class="btn btn-xs btn-primary button-action">
                            <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                        </a>
                        <a href="{{ route('admin.product.edit',$value->id) }}" title="Edit" class="btn btn-xs btn-default button-action">
                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                        </a>
                        <a href="{{ route('admin.product.toggle',$value->id) }}" class="btn btn-xs button-action {{ $value->isPublished() ? 'btn-warning' : 'btn-info'}}">
                            <icon class="icon-plus">
                                {!! $value->isPublished() ? '<i class="fa fa-toggle-off" alt="Unpublish" title="Unpublish"></i>' : '<i class="fa fa-toggle-on" title="Publish"></i>' !!}
                            </icon>
                        </a>
                        <a href="{{ route('admin.product.delete',$value->id) }}" title="Delete" class="btn btn-xs btn-danger button-action"
                            onclick="return confirm('Are you sure you want to delete this item?');">
                            <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                        </a>
                    </td>
                @endif
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>

{{ $products->appends(request()->except('page'))->fragment($fragment)->links() }}
<div class="btn-group padding-t-10 pull-right">
    <a href="{{ route('admin.product.add') }}" class="btn btn-sm btn-success">
        <i class="fa fa-plus"></i> Tambah Data
    </a>
</div>
