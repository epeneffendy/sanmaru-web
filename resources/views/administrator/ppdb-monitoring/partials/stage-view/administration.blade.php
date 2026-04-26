<div class="fixed-table-head">
    <table id="datatables-master-ppdb" class="table table-responsive table-striped display"
           style="width: 100%; border-top-width: medium; border-top-style: solid;">
        <thead>
        <tr>
            <th rowspan="2" class="text-center">No</th>
            <th rowspan="2" class="text-center">Calon Siswa</th>
            <th rowspan="2" class="text-center">Status Pendaftaran</th>
            <th colspan="5" class="text-center">Status Administrasi</th>
            <th rowspan="2" class="text-center">Option</th>
        </tr>
        <tr>
            <th class="text-center">Verified</th>
            <th class="text-center">Biaya Formulir</th>
            <th class="text-center">Data</th>
            <th class="text-center">Parent</th>
            <th class="text-center">Surat Pernyataan</th>
        </tr>
        </thead>
        <tbody>
        @php($number = 1)
        @foreach($data as $item)
            <tr>
                <td class="text-center">{{$number++}}</td>
                <td class="text-center">
                    <b style="text-transform: uppercase">{{$item['name']}}</b><br/>
                    <u>{{$item['username']}}</u><br/>
                    <label class="label label-info">{{$item['email']}}</label><br/>
                    <label class="label label-warning label-sm">no
                        registrasi: {{$item['register_number']}}</label><br/>
                    <label
                            class="label label-danger label-sm">{{$item['unit_name']}}</label><br/>
                    <label class="label label-info label-xs">{{ $item['periode_name'] }}</label><br/>
                    <label class="label label-xs"
                           style="background-color: gray">{{ $item['origin_school'] }}</label><br/>
                    <small>phone: {{$item['mobile_phone']}}</small>
                    <br/>
                    {{$item['gender']}}
                </td>
                <td class="text-center">
                    {!! $item['status_confirm'] !!}<br>

                    {!! $item['status_stage'] !!}
                </td>
                <td class="text-center">
                    <span class="badge-status {{ $item['isEmailVerified'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                        <i class="fa {{ $item['isEmailVerified'] ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i> {{ $item['isEmailVerified'] ? ' Email Verified' : ' Email Belum Verified' }}
                    </span>
                    @if (!$item['isEmailVerified'])
                        <br/>
                        <br/>
                        <span class="btn btn-sm btn-default send-confirmation" data-id="{{ $item['id'] }}"><i
                                    class="fa fa-envelope"></i></span>
                    @endif
                </td>
                <td class="text-center">

                    @if($item['payment_date'] == '')
                        <span class="badge-status bg-soft-danger text-danger">
                            <i class="fa fa-times-circle me-1"></i> Pembayaran Melakukan Pembayaran
                        </span>
                    @else
                        <span class="badge-status bg-soft-success text-success">
                            <i class="fa fa-check-circle me-1"></i> Pembayaran Terkonfirmasi
                        </span>
                        {{--<span class="btn btn-circle btn-sm btn-success">--}}
                            {{--<icon class="icon-times"><i class="fa fa-check" title="Pembayaran Terkonfirmasi"></i></icon>--}}
                        {{--</span>--}}
                        <br/>
                        <br/>
                        <span
                                class="label label-info">Pembayaran Terkonfirmasi</span>
                        <br>
                        <span
                                class="label label-success">Rp.{{number_format($item['total_payment_form'])}}</span>
                    @endif
                </td>

                <td class="text-center">
                    <span class="badge-status {{ $item['isComplite'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                        <i class="fa {{ $item['isComplite'] ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i> {{ $item['isComplite'] ? ' Lengkap' : ' Belum Lengkap' }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="badge-status {{ $item['isParent'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                        <i class="fa {{ $item['isParent'] ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i> {{ $item['isParent'] ? ' Lengkap' : ' Belum Lengkap' }}
                    </span>
                </td>
                <td class="text-center">
                    <span
                            class="btn btn-circle btn-sm {{ $item['IsStatementLetterUploaded'] ? ($item['IsStatementLetterConfirmed'] ? "btn-success btn-modal-statement-letter-success" : "btn-warning btn-modal-statement-letter") : "btn-danger" }}"
                            data-id="{{$item['id']}}" data-name="{{$item['name']}}"
                            data-register_number="{{$item['register_number']}}"
                            data-unit_id="{{$item['unit_id']}}"
                            data-unit_name="{{$item['unit_name']}}">
                        <icon class="icon-plus">
                            @if ($item['IsStatementLetterConfirmed'])
                                <i class="fa fa-check"></i>
                            @elseif ($item['IsStatementLetterUploaded'])
                                <i class="fa fa-question"></i>
                            @else
                                <i class="fa fa-times"></i>
                            @endif
                        </icon>
                    </span>

                    {{-- ACTION MODAL--}}
                    @if ($item['development_fee_option'] && !$item['isOrderConfirmed'])
                        <button data-toggle="modal"
                                data-target="#reset-development-payment-modal"
                                class="btn btn-sm btn-warning" style="margin-top: 5px"
                                onclick="return confirm('Apakah anda yakin akan mereset tahapan ini? Surat pernyataan akan terhapus');">
                            Reset
                        </button>
                        <!-- Modal -->
                        <div id="reset-development-payment-modal" class="modal fade"
                             role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close"
                                                data-dismiss="modal">&times;
                                        </button>
                                        <h4 class="modal-title">Nofitication for student</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form
                                                action="{{ route('admin.ppdb.reset-development-payment-method', $item) }}"
                                                method="POST">
                                            @csrf
                                            <p style="text-align: left;">Silahkan isi alasan
                                                mereset surat pernyataan.</p>
                                            <input type="hidden" id="year" name="year"
                                                   value="{{ $item['school_year'] }}">
                                            <input type="hidden" id="unit" name="unit"
                                                   value="{{ $item['unit_id'] }}">
                                            <input type="hidden" id="periode" name="periode"
                                                   value="{{ $item['periode']}}">
                                            <input type="hidden" id="ppdb_user_id"
                                                   name="ppdb_user_id[]"
                                                   value="{{ $item['id']}}">
                                            <input type="hidden" id="title" name="title"
                                                   value="[RESET] Surat Pernyataan {{ $item['name'] }}">
                                            <div class="form-group row">
                                                <label for="body"
                                                       class="col-md-4 col-form-label text-md-right">Alasan
                                                    Reset</label>
                                                <div class="col-md-6">
                                                                            <textarea class="form-control" name="body"
                                                                                      id="body" rows="3"
                                                                                      placeholder="Enter Pesan">{!! old('body') !!}</textarea>

                                                    @error('body')
                                                    <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-10 col-sm-offset-2">
                                                    <div class="checkbox checkbox-success">
                                                        <input type="checkbox" name="send_email"
                                                               id="send_email"
                                                               value="1" {{ old('send_email', 1) ? 'checked' : '' }}>
                                                        <label for="send_email">Kirim email
                                                            pemberitahuan</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-0"
                                                 style="text-align: right; padding-right:10px">
                                                <button type="submit" class="btn btn-warning">
                                                    Reset
                                                </button>

                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <br>
                    <br>
                    @if($item['development_fee_option'] != null)
                        @if($item['development_fee_option'] == 'lunas')
                            <span class="label label-info">Pembayaran Lunas</span>
                            <br>
                            {!! $item['voucher'] !!}
                        @else
                            <span class="label label-warning">Pembayaran Cicilan</span>
                        @endif
                    @endif
                    <br>
                    @if($item['IsStageDevelopment'])
                        <button type="button" class="btn btn-sm ">
                            <i class="fa fa-sync me-2"></i> Sync Data
                        </button>
                    @endif

                </td>
                <td class="text-center">
                    <a href="{{ route('admin.ppdb.show', $item['id']) }}" title="Show"
                       class="btn btn-xs btn-success">
                        <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                    </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

<div id="modal-confirmation" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                <button type="button" id="btn-confirm-modal" class="btn btn-success">&nbsp;</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).on('click', '.btn-modal-statement-letter', function (e) {
            e.preventDefault();
            var id = $(this).data('id'),
                unitId = $(this).data('unit_id'),
                fileUrl = "{{ route('show_file') }}";
            var html = `<form role="form" action="{{ route("admin.ppdb.confirm-development-statement", ["id"=>null]) }}/` + id + `" method="POST" id="statement-letter-confirmation-form" class="form-horizontal">
                        @csrf
                <input type="hidden" name="id" value="` + id + `" />
                        <div><h4 class="text-primary modal-form-label">` + $(this).data('name') + `</h4></div>
                        <div>` + $(this).data('register_number') + `</div>
                        <div>` + $(this).data('unit_name') + `</div>
                        <div class="pull-right">
                            <a href="{{ route("admin.ppdb.get-development-file", ["id"=>null]) }}/` + $(this).data('id') + `" target="_blank">
                                open new tab
                            </a>
                        </div>
                        <div class="margin-t-5 text-center">
                        <iframe src="{{ route("admin.ppdb.get-development-file", ["id"=>null]) }}/` + $(this).data('id') + `" width="100%" height="300">
                        <div>
                    </form>
                `;
            $('#modal-confirmation .modal-title').html('Konfirmasi Surat Pernyataan Ini ?');
            $('#modal-confirmation .modal-body').html(html);
            $('#btn-confirm-modal').attr('data-id', id);
            $('#btn-confirm-modal').html('Setujui');
            $('#modal-confirmation').modal();
        });

        $(document).on('click', "#btn-confirm-modal", function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#statement-letter-confirmation-form').submit();
        });
    </script>
@endpush
