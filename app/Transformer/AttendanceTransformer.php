<?php

namespace App\Transformer;

use App\Models\Attendance;
use League\Fractal\TransformerAbstract;

class AttendanceTransformer extends TransformerAbstract
{
    public function transform(Attendance $attendance)
    {
        return array(
            'date'   => $attendance->date,
            'type'   => $attendance->type,
            'reason' => $attendance->reason,
        );
    }
}
