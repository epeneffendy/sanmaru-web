<table class="table table-bordered table-sm">
    <thead>
        <th width="15%"></th>
        @foreach($weekDays as $day)
            <th>{{ \App\Helpers\Helper::hari($day) }}</th>
        @endforeach
    </thead>
    <tbody>
        @foreach($calendarData as $time => $days)
            <tr>
                <td>
                    {{ $time }}
                </td>
                @foreach($days as $value)
                    @if (is_array($value))
                        <td rowspan="{{ $value['rowspan'] }}" class="align-middle text-center success">
                            {{ $value['class_name'] }}<br>
                            Mata Pelajaran: {{ $value['course_name'] }}
                        </td>
                    @elseif ($value === 1)
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>