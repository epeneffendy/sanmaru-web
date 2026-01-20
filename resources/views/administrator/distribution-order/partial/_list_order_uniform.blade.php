
<div class="table-responsive">
    <table id="datatables-product-orders" class="table display table-responsive">
        <thead>
        <tr>
            <th>No</th>
            <th>Unit Sekolah</th>
            <th>Nama Siswa</th>
            <th>Nama PRoduct</th>
            <th>Ukuran Seragam</th>
            <th>Jumlah</th>
        </tr>
        </thead>
        <tbody>
        @php
            $no = 1;
        @endphp
        @foreach($data as $item)

            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->unit_name }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->size }}</td>
                <td>{{ $item->qty }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="form-group pull-right" style="margin-right: 1em">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Save</button>
    </div>
</div>
