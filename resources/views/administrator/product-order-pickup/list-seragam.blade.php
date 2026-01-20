<div class="button-collection" style="margin: 15px 0">
    <a href="{{ route('admin.product-order-pickup.export', request()->except('page')) }}"
        class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
    <a href="{{ route('admin.product-order.send-confirmed') }}" class="btn btn-success btn-sm"><i
            class="fa fa-envelope"></i> Kirim email konfirmasi pembayaran</a>
    <a href="{{ route('admin.product-order-pickup.create-schedule') }}" class="btn btn-success btn-sm"><i
            class="fa fa-calendar"></i> Buat Jadwal</a>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Filter</h3>
    </div>
    <div class="panel-body">
        <form role="form" autocomplete="off" method="GET" action="{{ route('admin.product-order-pickup.index') }}"
            autocomplete="false">
            <div class="row">
                <input type="hidden" name="apply_filter" value="1">
                <div class="form-group col-md-3">
                    <label for="student_name" class="form-label">Nama Siswa</label>
                    <input type="text" name="student_name" placeholder="Nama siswa"
                        value="{{ @$params['student_name'] }}" class="form-control input-sm" />
                </div>
                <div class="form-group col-md-3">
                    <label for="date_range_seragam" class="form-label">rentang waktu</label>
                    <input type="text" id="date_range_seragam" name="date_range" placeholder="rentang waktu" value="{{ @$params['date_range'] }}" class="form-control input-sm date-range-field" />
                </div>
                <div class="form-group col-md-3">
                    <label for="pickup_status" class="form-label">Status Pengambilan</label>
                    <select name="pickup_status" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        <option value="not_scheduled" {{ @$params['pickup_status']=='not_scheduled' ? 'selected' : NULL
                            }}>Belum dijadwalkan</option>
                        <option value="scheduled" {{ @$params['pickup_status']=='scheduled' ? 'selected' : NULL }}>Sudah
                            dijadwalkan</option>
                        <option value="not_picked_up" {{ @$params['pickup_status']=='not_picked_up' ? 'selected' : NULL
                            }}>Belum diambil</option>
                        <option value="picked_up" {{ @$params['pickup_status']=='picked_up' ? 'selected' : NULL }}>Sudah
                            diambil</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="payment_mail_confirmation" class="form-label">Status Konfirmasi</label>
                    <select name="payment_mail_confirmation" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        <option value="unsent" {{ @$params['payment_mail_confirmation']=='unsent' ? 'selected' : NULL
                            }}>Email konfirmasi belum dikirim</option>
                        <option value="sent" {{ @$params['payment_mail_confirmation']=='sent' ? 'selected' : NULL }}>
                            Email konfirmasi sudah dikirim</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="unit" class="form-label">Unit</label>
                    <select name="unit" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        @foreach (@$units as $unit)
                        <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' :
                            null }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="year" class="form-label">Tahun Ajaran</label>
                    <select name="year" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        @foreach ($years as $year)
                        <option value="{{ $year->year }}" {{ $year->year == @$params['year'] ?
                            'selected' : null }}>{{ $year->year }} - {{ $year->year + 1 }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="type_user" class="form-label">Siswa</label>
                    <select name="type_user" id="type_user" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        <option value="siswa" {{ @$params['type_user'] == 'siswa' ? 'selected' : NULL }} >Siswa Reguler</option>
                        <option value="ppdb" {{ @$params['type_user'] == 'ppdb' ? 'selected' : NULL }}>Siswa PPDB</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.product-order-pickup.index') }}" class="pull-right btn btn-sm btn-warning">
                        <i class="fa fa-refresh"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="fixed-table-head">
    <table id="datatables-product-orders-pickup" class="table display table-responsive">
        <thead>
            <tr>
                <th>No Tagihan</th>
                <th>Unit</th>
                <th>Pesanan</th>
                <th>Waktu Pengambilan</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Email Konfirmasi</th>
                <th>Sudah diambil</th>
                <th width="110px">Option</th>
            </tr>
        </thead>
        <tbody>
            @foreach($values as $key => $value)
            <tr>
                <td>{{$value->invoice_no}}<br>
                    <small class="d-block text-muted">siswa:</small>
                    <b class="d-block">{{ @$value->user->name }}</b>
                    <small class="d-block text-muted">email: {{ @$value->user->email }}</small>
                </td>
                <td>{{ @$value->user->unit_name }}</td>
                <td>{{ $value->productOrderDetails->count() }} pesanan<br /><label class="label label-info">{{
                        \App\Helpers\PriceHelper::rupiah($value->grand_total) }}</label></td>
                <td>
                    @if($value->pickup_date_schedule)
                    {{ \App\Helpers\Helper::tanggal($value->pickup_date_schedule) .
                    ($value->alt_pickup_date_schedule ? " atau " .
                    \App\Helpers\Helper::tanggal($value->alt_pickup_date_schedule) : null) . " / " .
                    \Carbon\Carbon::parse($value->pickup_start_time)->format('H:i') . " - " .
                    \Carbon\Carbon::parse($value->pickup_end_time)->format('H:i') }}
                    @if (!$value->isPickup())
                    <button data-toggle="modal" data-target="#resetPickupScheduleSeragamModal" class="btn btn-sm btn-warning" style="margin-top: 5px">Reset</button>
                    <!-- Modal -->
                    <div id="resetPickupScheduleSeragamModal" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Nofitication for student</h4>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.product-order-pickup.reset-schedule', $value->id) }}" method="POST">
                                        @csrf
                                        <p style="text-align: left;">Silahkan isi pesan reset jadwal.</p>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <textarea class="form-control" name="body" id="body" rows="3" placeholder="Masukkan" required>{!! old('body') !!}</textarea>

                                                @error('body')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="checkbox checkbox-success">
                                                    <input type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email', 1) ? 'checked' : '' }}>
                                                    <label for="send_email">Kirim email pemberitahuan</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0" style="text-align: right; padding-right:10px">
                                                <button type="submit" class="btn btn-warning">Reset</button>

                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @else
                    <a href="{{ route('admin.product-order-pickup.create-schedule', ['product_order' => $value->id]) }}"
                        class="btn btn-sm btn-success">Jadwalkan</a>
                    @endif
                </td>
                <td>{!!$value->status_label!!}</td>
                <td style="text-align: center;">{!! $value->icon_konfirmasi_pembayaran !!}</td>
                <td style="text-align: center">
                    {!! $value->icon_email_konfirmasi_pembayaran !!}
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" data-orderid="{{$value->id}}" class="pickupchecker" {{$value->isPickup() ?
                    'checked disabled' : NULL }}>
                    <div id="pickup-message-{{$value->id}}"></div>
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.product-order-pickup.qr-result', $value->id) }}" title="Halaman QR"
                        class="btn btn-xs btn-success">
                        <i class="fa fa-qrcode"></i>
                    </a>
                    <a href="{{ route('admin.product-order-pickup.show', $value->id) }}" title="Show"
                        class="btn btn-xs btn-success">
                        <i class="fa fa-eye"></i>
                    </a>
                    @if (!$value->payment_confirmed_mail_sent)
                    <a href="{{ route('admin.product-order-pickup.send-confirmation', $value->id) }}"
                        class="btn btn-xs btn-primary"><i class="fa fa-envelope"></i></button>
                        @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $product_orders['seragam']->appends(request()->except('page'))->links() }}
