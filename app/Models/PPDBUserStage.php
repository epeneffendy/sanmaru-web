<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PPDBUserStage extends Model
{
    const TEXT_LOLOS = 'LOLOS';
    const TEXT_TIDAK_LOLOS = 'TIDAK_LOLOS';
    const TEXT_PENDING = 'PENDING';

    protected $table = 'ppdb_user_stages';

    protected $fillable = [
        'ppdb_user_id',
        'stage_id',
        'note',
        'passed'
    ];

    public $appends = [
        'passed_text'
    ];

    public function getPassedTextAttribute()
    {
        $isPassed = ! $this->passed ? "TIDAK " : "";

        return $isPassed .  "LOLOS";
    }

    public function stage()
    {
        return $this->belongsTo('App\Models\Stage', 'stage_id', 'id');
    }
}
