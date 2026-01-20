

<form role="form" action="" method="POST" id="statement-letter-confirmation-form" class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-4" for="code">Siswa:</label>
        <div class="col-sm-8">
            <select name="student_voucher" id="student_voucher"
                    class="form-control selectpicker show-tick" data-style="btn-info">
                <option data-hidden="true" value="">-- Pilih Siswa --</option>
                <option value="PA"> PUTRA </option>
                <option value="PI"> PUTRI </option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-4" for="code">Unit:</label>
        <div class="col-sm-8">
            <select name="unit_voucher" id="unit_voucher"
                    class="form-control selectpicker show-tick" data-style="btn-info">
                <option data-hidden="true" value="">-- Pilih Unit --</option>
                @foreach($units as $unit)
                    <option value="{{$unit->unit_code}}"> {{$unit->name}} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-4" for="code">Tahun Ajaran:</label>
        <div class="col-sm-8">
            <select name="year_voucher" id="year_voucher"
                    class="form-control selectpicker show-tick" data-style="btn-info">
                <option data-hidden="true" value="">-- Pilih Tahun Ajaran --</option>
                @foreach($years as $ind => $year)
                    <option value="{{$ind}}"> {{$year}} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-4" for="code"></label>
        <div class="col-sm-8">
            <span style="color: red; display: none" id="error_generate">Lengkapi filter terlebih dahulu!</span>
        </div>
    </div>

    <span style="color: red; margin-bottom: 1em">**Voucher ini diperuntukan untuk pembayran uang pengembangan</span>
</form>
