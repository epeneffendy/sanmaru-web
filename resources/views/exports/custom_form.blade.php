<table>
    @php ($colspan = (count($forms->columns) + count($headings)))
    <thead>
    <tr>
        <th colspan="2" style="font-weight: bold;">Nama Form</th>
        <th colspan="{{$colspan - 2}}" style="font-weight: bold;">: {{ strtoupper(@$forms->name) }}</th>
    </tr>
    <tr>
        <th colspan="2" style="font-weight: bold;">Unit</th>
        <th colspan="{{$colspan - 2}}" style="font-weight: bold;">: {{ strtoupper(@$forms->unit->name) }}</th>
    </tr>
    <tr>
        <th colspan="2" style="font-weight: bold;">Periode</th>
        <th colspan="{{$colspan - 2}}" style="font-weight: bold;">
            : {{ strtoupper(@$params['period'] ? @$forms->periods->where('id', $params['period'])->first()->name : @$forms->periods->pluck('name')->join(', ')) }}</th>
    </tr>
    <tr>
        <td colspan="{{$colspan}}"></td>
    </tr>
    <tr>
        @foreach ($headings as $heading)
            <th style="font-weight: bold;">{{ strtoupper($heading) }}</th>
        @endforeach
        @foreach ($forms->columns as $column)
            <th style="font-weight: bold;">{{ strtoupper($column->label) }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @php($no=1)
    @foreach ($forms->columnInputs->groupBy('ppdb_user_id') as $key => $details)
    @if (!@$params['period'] || (@$params['period'] && $details->first()->ppdb_user->periode == $params['period']))
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $details->first()->ppdb_user->register_number }}</td>
            <td>{{ $details->first()->ppdb_user->name }}</td>
            @foreach ($details as $detail)
                <td>{{ $detail->value }}</td>
            @endforeach
        </tr>
    @endif
    @endforeach
    </tbody>
</table>
