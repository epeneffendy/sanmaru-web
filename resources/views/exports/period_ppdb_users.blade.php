<table>
    @php ($colspan = count($headings) + 1)
    <thead>
    <tr>
        <th colspan={{$colspan}} style="font-weight: bold;">CALON PESERTA DIDIK {{ strtoupper($period->unit->name_santa_maria) }}</th>
    </tr>
    <tr>
        <th colspan={{$colspan}} style="font-weight: bold;">TAHUN PELAJARAN {{ $period->school_year_period }}</th>
    </tr>
    <tr>
        <th style="font-weight: bold;">No</th>
        @foreach ($headings as $heading)
            <th style="font-weight: bold;">{{ $heading }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @php($no=1)
    @foreach($collections as $key => $collection)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $collection['register_number'] }}</td>
            <td>{{ $collection['name'] }}</td>
            <td>{{ $collection['nik'] }}</td>
            <td>{{ $collection['address'] }}</td>
            <td>{{ $collection['city'] }}</td>
            <td>{{ $collection['mobile_phone'] }}</td>
            <td>{{ $collection['gender'] }}</td>
            {{-- <td>{{ $collection['npwp'] }}</td> --}}
            <td>{{ $collection['father_name'] }}</td>
            <td>{{ $collection['mother_name'] }}</td>
            <td>{{ $collection['development'] }}</td>
            <td>{{ $collection['activity'] }}</td>
            <td>{{ $collection['tuition'] }}</td>
            <td>{{ $collection['school_year_period'] }}</td>
            <td>{{ $collection['period_name'] }}</td>
            <td>{{ $collection['status'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
