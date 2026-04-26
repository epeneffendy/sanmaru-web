<style>
    .table-modern thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
        padding: 15px;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f1f4f9;
        transform: scale(1.002);
    }

    .student-info-box {
        display: flex;
        flex-direction: column;
    }

    .badge-id {
        background: #e9ecef;
        color: #495057;
        font-weight: 600;
    }

    .badge-school {
        background: #007bff20;
        color: #007bff;
    }

    .status-check {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .check-success {
        background-color: #d4edda;
        color: #28a745;
    }

    .check-pending {
        background-color: #fff3cd;
        color: #ffc107;
    }
</style>


<div class="card shadow-sm border-0">
    <div class="selection-header">
        <ul class="nav nav-pills custom-pills" id="selection">
            <li class="active">
                <a href="#seleksi" data-toggle="pill">
                    <i class="fa fa-users mr-2"></i> List Siswa
                </a>
            </li>
            <li>
                <a href="#manual" data-toggle="pill">
                    <i class="fa fa-upload mr-2"></i> Import Data
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content selection-body">
        <div id="seleksi" class="tab-pane fade in active">
            <div class="card-body p-0">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->get('errors') as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                        <tr>
                            <th style="width: 5%">NO</th>
                            <th style="width: 30%">DETAIL SISWA</th>
                            <th style="width: 20%">IDENTITAS ORANG TUA</th>
                            <th style="width: 20%">ALAMAT DOMISILI</th>
                            <th style="width: 20%">KELAS</th>
                            <th style="width: 20%">NISN</th>
                        </tr>
                        </tr>
                        </thead>
                        <tbody>
                        @php($number = 1)
                        @foreach($data as $item)
                            <tr>
                                <td class="text-center fw-bold">{{$number++}}</td>
                                <td>
                                    <div class="fw-bold text-uppercase"> {{ $item['name'] }} </div>
                                    <div class="d-flex flex-wrap gap-1 my-1">
                                        <span class="badge bg-secondary text-white">NIK: {{ $item['nik_siswa'] }}</span>
                                        <span class="badge bg-info text-dark">Register Number: {{ $item['register_number'] }} </span>
                                    </div>
                                    <div class="small text-muted" style="margin-top: 1em">
                                        <i class="fa fa-envelope me-1"></i> {{ $item['email'] }} <br>
                                        <i class="fa fa-phone"></i> {{ $item['mobile_phone'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold" style="font-size: 12px;"> {{ $item['father_name'] }}
                                                / {{ $item['mother_name'] }} </div>
                                            <div class="text-primary small fw-bold">NIK: {{ $item['nik_ortu'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-truncate" style="max-width: 200px;"
                                         title="{{ $item['address'] }}">
                                        {{ $item['address'] }}<br>
                                        {{ $item['city'] }}<br>
                                        {{ $item['region'] }}
                                    </div>
                                </td>
                                <td>
                                    @if(empty($item['class']))
                                        <span class="badge bg-secondary text-white">Belum di setting</span>
                                    @else
                                        <span class="badge bg-primary">{{ $item['class'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(empty($item['nisn']))
                                        <span class="badge bg-secondary text-white">Belum di setting</span>
                                    @else
                                        <span class="badge bg-primary">{{ $item['nisn'] }}</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="manual" class="tab-pane fade">
            <div class="widget-header">
                <h3>Pengumuman Privasi</h3>
            </div> <!-- /widget-header -->
            <div class="widget-content">
                <form action="{{ route('admin.ppdb-monitoring.import-users-student', ['id' => @$period['id']]) }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="file" class="form-control form-control-radius"
                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                    </div>
                    <div class="form-group">
                        <div class="checkbox checkbox-circle checkbox-info">
                            <input id="overwrite" name="type" type="checkbox" checked="true" value="overwrite"/>
                            <label for="overwrite">Overwrite</label>
                        </div>
                    </div>
                    <div class="form-group">
                        download template dengan <a href="{{ route('admin.ppdb-monitoring.template-setting-class', [
                            'unit'=>@$period->unit->id,
                            'periode'=>@$period->id,
                         ]) }}" target="_blank" download>klik disini</a>
                    </div>

                    <div class="form-group">
                        <div class="alert alert-info border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-info-circle me-3 fa-lg"> Panduan Pengisian Template</i>
                                <div>
                                    <span>Silahkan cek data kelas di <strong>Sheet Data Kelas</strong>, pastikan penulisan kelas sesuai dengan data kelas yang ada di <strong>Sheet Data Kelas</strong> untuk menghindari kegagalan <strong>Import Data</strong> :
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-sm btn-insert">import</button>
                    </div>
            </div>
        </div>
    </div>


</div>