<div class="table-responsive">
    <table id="datatables-product-orders" class="table display table-responsive">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Register Number</th>
                <th>Unit Sekolah</th>
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

                </tr>
                @if ($item['billing'])
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
                                            <th>Tagihan</th>
                                            <th>Cara Pembayaran</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item['bills'] as $detail)
                                            <tr>
                                                <td>
                                                    {{ $detail['desc'] }}
                                                    @if ($detail['is_dispensation'])
                                                        <br><span class="badge-modern badge-soft-info"
                                                            style="margin-top: 5px;"><i class="fa fa-info-circle"></i>
                                                            Penerima Dispensasi</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($detail['amount']) }}
                                                    @if ($detail['is_dispensation'])
                                                        <br><span class="badge-modern badge-soft-success"
                                                            style="margin-top: 5px;"><i class="fa fa-info-circle"></i>
                                                            Total Final Fee:
                                                            {{ number_format($detail['total_final_fee']) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $detail['payment_term'] }}</td>
                                                <td>
                                                    {{ $detail['payment_method'] }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr id="detail-{{ $no }}" style="background-color: #f9fafb;">
                        <td></td>
                        <td colspan="3" style="padding: 15px;">
                            <div style="border-left: 3px solid #F39C12; padding-left: 15px;">
                                <h5 style="margin-top: 0; font-weight: bold; color: #58666E;">Detail Pembayaran</h5>
                                <p class="text-muted" style="margin-bottom: 0;"><i class="fa fa-info-circle"></i>
                                    Tagihan Belum Tersedia!</p>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach

        </tbody>
    </table>
</div>
