<div class="table-responsive">
    <table id="datatables-product-orders" class="table display table-responsive">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Register Number</th>
                <th>Unit Sekolah</th>
                <th>Dispensasi</th>
                <th>Nominal Pembayaran</th>
                <th>Sisa Pembayaran</th>
                <th>Tgl Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp

            @foreach ($data as $item)
                <tr style="font-weight: bold;">
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['register_number'] }}</td>
                    <td>{{ $item['unit'] }}</td>
                    <td>{{ $item['is_dispensation'] }}</td>
                    <td>{{ number_format($item['total_final_fee'], 0, '.', ',') }}</td>
                    <td>{{ number_format($item['remaining_balance'], 0, '.', ',') }}</td>
                    <td>{{ $item['created_at'] }}</td>
                </tr>
                @if (count($item['detail']) > 0)
                    <tr id="detail-{{ $no }}" style="background-color: #f9fafb;">
                        <td></td>
                        <td colspan="10" style="padding: 15px;">
                            <div style="border-left: 3px solid #399BFF; padding-left: 15px;">
                                <h5 style="margin-top: 0; font-weight: bold; color: #58666E;">Detail
                                    Pembayaran</h5>
                                <table class="table table-bordered table-condensed"
                                    style="background-color: #fff; margin-bottom: 0;">
                                    <thead>
                                        <tr style="background-color: #f1f5f9;">
                                            <th>Keterangan</th>
                                            <th>Virtual Account</th>
                                            <th>Tgl Bayar</th>
                                            <th>Tagihan</th>
                                            <th>Tagihan Dibayar</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item['detail'] as $detail)
                                            <tr>
                                                <td>{{ $detail['installment_number'] }}</td>
                                                <td>{{ $detail['virtual_account'] }}</td>
                                                <td>{{ $detail['date'] }}</td>
                                                <td>{{ number_format($detail['nominal'], 0, '.', ',') }}
                                                </td>
                                                <td>{{ number_format($detail['amount_paid'], 0, '.', ',') }}
                                                </td>
                                                <td>{{ $detail['status'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach

        </tbody>
    </table>
</div>
