@extends('layouts.ppdb-landing-page.main')
@section('content')
    <div class="row row-height">
        @include('ppdb-online.step-left-section')

        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
          <a class="navbar-brand" href="{{ route('ppdb.index') }}"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">KEMBALI KE HALAMAN UTAMA</button></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

            </ul>
            <form class="form-inline my-2 my-lg-0">
              <a href="{{ route('ppdb.login') }}"><button class="btn btn-outline-success my-2 my-sm-0" type="button">LOGIN</button></a>
            </form>
          </div>
        </nav>

        <div class="col-lg-6 content-right" id="start" style="height: unset">
            @if ($unit->ongoingPeriods->isEmpty())
                <div class="row">
                    <div class="alert alert-danger">
                        Mohon maaf periode PPDB belum dibuka.
                        <a href="{{ url()->previous() }}" style="text-decoration: underline;">back</a>
                    </div>
                </div>
            @else
            <div id="wizard_container">
                <div id="top-wizard">
                </div>
                <div class="header-form text-center">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam">
                    <div class="row">
                        <div class="col-lg-5 offset-lg-1 text-center">
                            <div class="title"><a href="{{ route('ppdb.index') }}">Register</a></div>
                        </div>
                        <div class="col-lg-5 text-center">
                            <div class="title-gray"><a href="{{ route('ppdb.login') }}">Log In</a></div>
                        </div>
                    </div>
                </div>

                <!-- /top-wizard -->
                <form id="wrapped" method="POST" action="{{route('ppdb.insert')}}" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="show_fieldset" value="false" />
                    <input type="hidden" name="unit_id" value="{{ $unit->id }}" />
                    <input type="hidden" name="periode" value="{{ $unit->ongoingPeriods->first()->id }}" />
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <!-- Leave for security protection, read docs for details -->
                    <div class="text-center">
                        <p class="text-center">Selamat datang di Sistem Penerimaan Murid Baru (SPMB) Kampus Santa Maria{{ $unit->ongoingPeriods->first()->school_year ? ' Tahun Ajaran ' . $unit->ongoingPeriods->first()->school_year . '/' . ($unit->ongoingPeriods->first()->school_year + 1) : '' }} <br>
                            <b>{{$unit->name}}</b>
                            <br/>Silakan lengkapi form berikut terlebih dahulu untuk mendapatkan form
                            pendaftaran.</p>
                            <div class="alert alert-danger" {!! count(@$errors) > 0 ? NULL : "style='display: none'" !!}>
                                <ul style="margin: 0 0 0 0;">
                                    @foreach (@$errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        <div class="form-group">
                            <input type="text" name="name" class="form-control required" placeholder="Nama Siswa Sesuai Akta kelahiran"
                                   value="{{ old('name') }}" onchange="getVals(this, 'name');">
                        </div>
                        @if (!\Illuminate\Support\Str::startsWith($unit->name, ['KB', 'TK']))
                        @if($unit->ongoingPeriods->first()->is_feeder_school)
                            <div class="form-group">
                                <select name="origin_school" class="form-control required">
                                    @foreach($unit->ongoingPeriods->first()->origin_school_options as $value)
                                    <option value="{{ $value }}" {{ old('origin_school') == $value ? 'selected' : NULL }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="form-group">
                                <input type="text" name="origin_school" class="form-control required" placeholder="Sekolah Asal" value="{{ old('origin_school') }}" onchange="getVals(this, 'origin_school');">
                            </div>
                        @endif

                        @endif

                        @if ($unit->isAgeLimitApplied)
                        <div class="form-group">
                            <input type="text" readonly="readonly" name="date_of_birth" class="form-control required" placeholder="Tanggal Lahir"
                                   value="{{ old('date_of_birth') }}" onchange="getVals(this, 'date_of_birth');">
                        </div>
                        @endif
                        <div class="form-group">
                            <input type="email" name="email" class="form-control required" placeholder="Email Orang Tua"
                                   value="{{ old('email') }}" onchange="getVals(this, 'email');">
                        </div>
                        <div class="form-group">
                            <input type="number" name="mobile_phone" class="form-control required"
                                   placeholder="Nomor HP / Whatsapp Orang Tua" value="{{ old('mobile_phone') }}"
                                   onchange="getVals(this, 'mobile_phone');">
                        </div>

                        <!-- https://aimsis.atlassian.net/browse/AIMSIS-10448 -->
                        <div class="form-group">
                            <input type="number" name="nik_siswa" class="form-control required"
                                   placeholder="NIK Siswa" value="{{ old('nik_siswa') }}"
                                   onchange="getVals(this, 'nik_siswa');">
                        </div>
                        <div class="form-group">
                            <input type="number" name="nik_ortu" class="form-control required"
                                   placeholder="NIK Orang Tua" value="{{ old('nik_ortu') }}"
                                   onchange="getVals(this, 'nik_ortu');">
                        </div>

                        @if (in_array($unit->name, ['KB-SURABAYA', 'TK-SURABAYA', 'TK-SIDOARJO']))
                        <div class="form-group">
                            <select name="class_option" class="form-control">
                                <option value="" hidden selected disabled>PILIH KELAS</option>
                                @if ($unit->name === 'KB-SURABAYA')
                                <option value="KB A">KB A</option>
                                <option value="KB B">KB B</option>
                                @else
                                <option value="TK A">TK A</option>
                                <option value="TK B">TK B</option>
                                @endif
                            </select>
                        </div>
                        @endif

                        <div class="form-group">
                            <input type="password" name="password" class="form-control required" placeholder="Password"
                                   value="{{ old('password') }}" onchange="getVals(this, 'password');">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control required"
                                   placeholder="Ulangi Password" value="{{ old('password_confirmation') }}"
                                   onchange="getVals(this, 'retype-password');">
                        </div>

                        <fieldset {!! old('show_fieldset', 'false') == 'false' ? 'style="display: none"' : NULL !!}>
                            <div class="alert alert-info">
                                Usia anak Anda dibawah batas usia namun masih dapat mendaftar dengan melampirkan bukti potensi kecerdasan dan/atau bakat istimewa dan kesiapan psikis dari psikolog profesional. Informasi selengkapnya silakan menghubungi admin kami di nomor: {{ \App\Helpers\Helper::phoneWithLeadingZero($unit->phone) }}
                            </div>
                        </fieldset>
                        <br>
                        <button type="submit" name="register" class="btn btn-register">Daftar</button>
                    </div>
                    @csrf
                </form>
            </div>
            @endif
            <!-- Button trigger modal -->
            <!-- Modal -->
            <div class="modal fade" id="popup_modal" tabindex="-1" role="dialog" aria-labelledby="popup_modal_title" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-warning" id="popup_modal_title">Perhatian</h5>
                  </div>
                  <div class="modal-body">
                    {!! $unit->ongoingPeriods->first() ? $unit->ongoingPeriods->first()->popup_content : NULL !!}
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
@endsection
@push('scripts')
    <!-- Wizard script -->
    <script>
        var ageLimitByMonths = {{ $ageLimit ? $ageLimit->months : 0}};
    </script>
    <script src="{{asset('frontend-ppdb-online/js/registration_func.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    @if ($unit->ongoingPeriods->first() && $unit->ongoingPeriods->first()->show_registration_popup)
    <script>
        $(document).ready(function() {
            $('#popup_modal').modal('show');
        })
    </script>
    @endif
    <script>
        $('input[name=date_of_birth]').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            maxDate: new Date()
        });

        $('input[type=file]').change(function () {
            var parent = this;
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(parent).parent().parent().find('.alert-success a').attr('href', e.target.result).parent().show();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        $('.alert-success a').click(function(e) {
            let data = $(this).attr('href');
            let w = window.open('about:blank');
            let image = new Image();
            image.src = data;
            setTimeout(function(){
                w.document.write(image.outerHTML);
            }, 0);
        });

        $('xinput[name=date_of_birth]').change(function(e) {
            e.preventDefault();
            $('.alert-danger').hide();
            $('input[name=show_fieldset]').val('false');
            $('fieldset').hide();
            if ($(this).val()) {
                var userInput = new Date($(this).val());
                var today = new Date("2021-07-01");
                var difMonths = monthDiff(userInput, today);
                var systemLimit = ageLimitByMonths;

                if (systemLimit > 0 && difMonths >= (systemLimit - 2) && difMonths < systemLimit) {
                    $('fieldset').show();
                    $('input[name=show_fieldset]').val('true');
                    document.querySelector('fieldset').scrollIntoView({behavior: 'smooth'});
                } else if (systemLimit > 0 && difMonths < systemLimit) {
                    $('.alert-danger ul').html('<li>Mohon maaf usia Anak Anda masih dibawah batas usia yang ditetapkan.</li>').parent().show();
                    document.querySelector('.alert-danger').scrollIntoView({behavior: 'smooth'});
                }
            }
            return;
        });

        function monthDiff(d1, d2) {
            var months;
            months = (d2.getFullYear() - d1.getFullYear()) * 12;
            months -= d1.getMonth();
            months += d2.getMonth();
            return months <= 0 ? 0 : months;
        }
    </script>
@endpush
