<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Carbon\Carbon;

class ImportJob extends Model
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
        'show',
        'total_success',
        'total_errors',
        'success',
        'errors'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFilenameAttribute()
    {
        $va = explode('/', $this->path);
        $last = max(count($va) - 1, 0);
        return $va[$last];
    }
}