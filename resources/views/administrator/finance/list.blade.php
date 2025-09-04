@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Data Master Keuangan</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">Keuangan</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-title">
                        Data Master Keuangan
                    </div>
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Filter</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.finance.index') }}">
                                    <div class="row">
                                        <input type="hidden" name="apply_filter" value="1">
                                        <div class="form-group col-md-3">
                                            <label for="search" class="form-label">Pencarian</label>
                                            <input type="text" name="search" id="search" placeholder="Search" value="{{ @$params['search'] }}" class="form-control input-sm" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="scope" class="form-label">berdasarkan</label>
                                            <select name="scope" id="scope" class="form-control input-sm">
                                                @foreach ($scopes as $key => $name)
                                                <option value="{{ $key }}" {{ @$params['scope'] == $key ? 'selected' : NULL }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="type" class="form-label">Tipe</label>
                                            <select name="type" id="type" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach ($types as $type)
                                                <option value="{{ $type }}" {{ $type == @$params['type'] ? 'selected' : null }}>{{ ucwords($type) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="unit" class="form-label">Unit</label>
                                            <select name="unit" id="unit" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : null}}>{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="period" class="form-label">Tahun Ajaran</label>
                                            <select name="period" id="period" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach ($periods as $period)
                                                <option value="{{ $period->year }}" {{ $period->year == @$params['period'] ? 'selected' : null }}>{{ $period->year }} - {{ $period->year + 1 }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.finance.index') }}" class="pull-right btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="320px">Nama</th>
                                    <th>Tipe</th>
                                    <th>Detail</th>
                                    <th>Nominal Default</th>
                                    <th>Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($finances->currentPage() - 1) * $finances->perPage();
                                @endphp
                                @foreach($finances as $key => $finance)
                                    <tr>
                                        <td>{{++$number}}</td>
                                        <td>{{$finance['name']}}</td>
                                        <td>{{$finance['type']}}</td>
                                        <td>
                                            @if ($finance->unit)
                                            {{$finance->unit->name}}<br/>
                                            @endif
                                            @if ($finance->period)
                                            <small><label class="label label-info">{{ $finance->period->name }}</label></small><br/>
                                            @endif
                                            @if ($finance->user)
                                            <small><label class="label label-warning">{{ $finance->user->username }}</label></small><br/>
                                            @endif
                                            @if($finance->users)
                                            @foreach($finance->users as $user)
                                            <small><label class="label label-warning">{{ $user->username }}</label></small><br />
                                            @endforeach
                                            @endif
                                            @if ($finance->year)
                                            <small><label class="label label-danger">{{ $finance->year }}</label></small>
                                            @endif
                                        </td>
                                        <td>{{ \App\Helpers\PriceHelper::rupiah($finance->nominal_default) }}</td>
                                        <td>
                                            <a href="{{ route('admin.finance.edit',$finance['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$finance['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$finance['id']}}" action="{{ route('admin.finance.delete',$finance['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $finances->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.finance.add') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
    <!-- Modal -->
    <div id="import-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Import Unit</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form class="fieldset-form" method="POST" enctype="multipart/form-data" action={{ route('admin.finance.import')}}>
                            @csrf
                            <fieldset>
                                <legend>Import menggunakan template .xls</legend>
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" style="margin-top: -7px;" value="add" name="type" checked=""> Tambah Data</label>
                                    <label class="radio-inline"><input type="radio" style="margin-top: -7px;" value="overwrite" name="type" checked=""> Perbaharui Data</label>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-default btn-upload-import" type="submit"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="result">
                    </div>
                    <div class="loadings" style="display:none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal -->
@endsection
@push('styles')
@endpush
@push('scripts')
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }

        $(document).ready(function() {
            $('select.select-autocomplete').selectpicker({
                style: "input-sm",
                liveSearch: true
            });
        });
    </script>
@endpush
