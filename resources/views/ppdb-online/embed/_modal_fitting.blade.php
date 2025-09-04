    <!-- Modal -->
    <div class="modal fade" id="fittingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">List Jadwal Fitting</h4>
            </div>
            <div class="modal-body">
                <div class="modal-fitting-content">
                @if (!$user_fittings->isEmpty())
                    <b>Anda telah memilih sesi fitting, berikut jadwalnya:</b>
                    @foreach ($user_fittings as $user_fitting)
                    <div class="form-group">
                        <label class="form-control">{{ \App\Helpers\Helper::tanggal($user_fitting->date)}} ({{ \App\Helpers\Helper::jam($user_fitting->hour_start) }} - {{ \App\Helpers\Helper::jam($user_fitting->hour_end) }})</label>
                    </div>
                    @endforeach
                @else
                    <b>Silahkan pilih sesi fitting yang tersedia berikut</b>
                    @forelse ($fittings as $fitting)
                    <div class="form-group">
                        <label class="form-control {{ $fitting->is_not_available ? 'disabled' : NULL }}"><input type="radio" {{ $fitting->is_not_available ? 'disabled' : NULL }} name="id" value="{{ $fitting->id }}"> {{ \App\Helpers\Helper::tanggal($fitting->date)}} ({{ \App\Helpers\Helper::jam($fitting->hour_start) }} - {{ \App\Helpers\Helper::jam($fitting->hour_end) }})</label>
                    </div>
                    @empty
                        tidak ada jadwal yang tersedia
                    @endforelse
                @endif
                </div>
            </div>
            @if ($user_fittings->isEmpty())
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="pilih-jadwal">Pilih Jadwal</button>
            </div>
            @endif
            </div>
        </div>
    </div>

@push('scripts')
<script>
    $(document).on('click', '#pilih-jadwal', function(e) {
        e.preventDefault();
        return;
    
        if (!$('input[type=radio]:checked').length) {
            alert('harap pilih sesi fitting terlebih dahulu');
            return;
        }

        swal({
            title: 'Perhatian !',
            text: "Apakah anda yakin akan memilih jadwal sesi fitting ini !",
            icon: "info",
            buttons: true,
            dangerMode: false,
        }).then((result) => {
            if (result) {
                $.post('{{ route('ppdb.embed-product.post-fitting') }}', {
                    _token: '{{ csrf_token() }}',
                    id: $('input[type=radio]:checked').val()
                }, function(data, status) {
                    if (data.status === 'success') {
                        swal(
                            'Sukses!',
                            'Jadwal fitting berhasil dipilih.',
                            'success'
                        ).then((value) => {
                            window.location.reload();
                        });
                    } else {
                        swal(
                            'Gagal!',
                            'Jadwal fitting gagal dipilih. Silahkan coba kembali',
                            'error'
                        );
                    }
                })
            }
        });
    });
</script>
@endpush

@push('styles')
    <style>

        body#ppdb .modal {
            padding-right: 0 !important;
        }

        .modal-open {
            overflow: hidden;
            position:fixed;
            width: 100%;
            height: 100%;
        }

        body#ppdb .modal-header .modal-title {
            text-align: center;
            margin: 0 auto;
            font-family: Roboto;
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 21px;
            /* color: #06270A; */
        }

        body#ppdb .modal-header button {
            float: left;
            font-size: 22px;
        }

        body#ppdb .modal-footer {
            border-top: none;
        }

        body#ppdb .modal-footer button {
            background: linear-gradient(225deg, #489F59 0%, #266C34 100%);
            border-radius: 20px;
            font-family: Roboto;
            font-style: normal;
            font-weight: bold;
            font-size: 12px;
            line-height: 14px;
            text-align: center;
            padding: 13px 30px;
        }

        body#ppdb .modal-body .form-control {
            height: auto;
            cursor: pointer;
        } 

        body#ppdb .modal-body .form-control.disabled {
            background-color: #CECECE;
            cursor: auto;
        }
    </style>
@endpush
