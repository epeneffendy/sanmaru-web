<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Carbon\Carbon;

class ExportJob extends Model
{
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_PROCESSING = 'processing';
    const STATUS_FINISHED = 'finished';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'params',
        'path', 
        'status',
        'show'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStartDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y/m/d');
    }

    public function getStartHourAttribute()
    {
        return Carbon::parse($this->created_at)->format('H:i');
    } 

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case $this::STATUS_NOT_STARTED:
                return '<label class="label label-warning">Not Started</label>';
                break;
            case $this::STATUS_PROCESSING:
                return '<label class="label label-primary">Processing</label>';
                break;
            case $this::STATUS_FINISHED:
                return '<label class="label label-success">Finished</label>';
                break;
            case $this::STATUS_FAILED:
                return '<label class="label label-danger">Failed</label>';
                break;
        }
    }

    public function getDownloadLinkAttribute()
    {
        if ($this->status === $this::STATUS_FINISHED) 
            return '<a href="'. route('show_export', ['file' => $this->path]) .'" download target="_blank">download file</a>';

        return "";
    }
}