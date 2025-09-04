@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Shop / Dashboard</h1>
        <ol class="breadcrumb">
            <li class="active">Rekapitulasi umum terkait data penjualan atribut sekolah</li>
        </ol>
    </div>

    <div class="container-padding">
      <div class="panel panel-default">
        <div class="panel-title">
          <b>Transaction Summary</b>
          <div class="pull-right">
            Sort By 
            <select id="sort_by" name="unit_id">
              <option value="">All</option>
              @foreach ($units as $unit)
                <option value="{{ $unit->id }}" {{ Request::input('unit_id') == $unit->id ? 'selected' : NULL }}>{{ $unit->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="panel-body" style="padding-top: 30px">
          <div class="row">
            <div class="col-md-10 col-md-offset-1" style="text-align: center;">
              <div class="row">
                <div class="col-md-4">
                  <div class="panel panel-primary">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ $soldProducts }}
                    </div>
                    <div class="panel-footer">
                      <b>Produk terjual</b>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="panel panel-success">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ $orders->filter(function($data){return $data->status!==\App\Models\ProductOrder::STATUS_NEW_ORDER;})->count() }}
                    </div>
                    <div class="panel-footer">
                      <b>Sudah bayar</b>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="panel panel-warning">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ $orders->filter(function($data){return $data->needPickup();})->count() }}
                    </div>
                    <div class="panel-footer">
                      <b>Perlu diambil/dikirim</b>
                    </div>
                  </div>
                </div>


                <div class="col-md-4">
                  <div class="panel panel-danger">
                    <div class="panel-body" style="text-align: center; font-size: 20px; font-weight: 700; padding: 10px 0;">
                      {{ \App\Helpers\PriceHelper::rupiah($orders->filter(function($data){return $data->status!==\App\Models\ProductOrder::STATUS_NEW_ORDER;})->sum('grand_total')) }}
                    </div>
                    <div class="panel-footer">
                      <b>Total pemasukan</b>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="panel panel-dark">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ $orders->filter(function($data){return $data->status===\App\Models\ProductOrder::STATUS_NEW_ORDER;})->count() }}
                    </div>
                    <div class="panel-footer">
                      <b>Belum bayar</b>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="panel panel-info">
                    <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                      {{ $outOfStockProducts }}
                    </div>
                    <div class="panel-footer">
                      <b>Stok habis</b>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-title">
          <b>Inventory Summary</b>
          <div class="pull-right">
            Sort By 
            <select id="sort_by_category" name="category_id">
              <option value="all">All</option>
              @foreach ($productCategories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="panel-body" style="padding-top: 30px">
          <div class="row">
            @foreach ($products as $key=>$product)
              <div class="col-md-4 inventory-summary category-{{ $product->category_id }}">
                <a href="{{ route('admin.product.show', $product->id) }}">
                <div class="panel panel-{{(!$product->productUnits->isEmpty()) ? $product->productUnits[0]->unit->present_color : 'info'}}">
                  <div class="panel-heading" style="text-align: center;">
                    @if($product->details->min('stock') < 10)
                      <label class="label" style="background-color:white; font-size: 14px; color:red;">{{'ada stock yang kurang dari 10'}}</label>
                    @else 
                      &nbsp;
                    @endif
                  </div>
                  <div class="panel-body" style="text-align: center; font-size: 28px; font-weight: 700; padding: 10px 0;">
                    {{ $product->details->sum('stock') }}
                  </div>
                  <div class="panel-footer">
                    <b>{{ $product->name }}</b>
                  </div>
                </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>

    </div>
@endsection

@push('scripts')
  <script>
    $('#sort_by_category').on('change', function(e) {
      e.preventDefault();
      var selected = $('#sort_by_category :selected').val();
      if (selected.trim().toLowerCase() === 'all') {
        $('.inventory-summary').show();
      } else {
        $('.inventory-summary:not(.category-'+ selected +')').hide();
        $('.inventory-summary.category-'+ selected).show();
      }
    });

    $('#sort_by').on('change', function(e) {
      e.preventDefault();
      var selected = $('#sort_by :selected').val();
      if (selected) {
        selected = '?unit_id='+selected;
      }

      window.location.href = '{{ route('admin.dashboard-order.index') }}'+selected;
    });
  </script>
@endpush