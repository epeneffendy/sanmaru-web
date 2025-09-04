<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PPDBResignation extends Model
{
	protected $table = 'ppdb_resignations';
    protected $fillable = ['unit_id', 'ppdb_user_id', 'register_number'];

    public function ppdbUser()
    {
    	return $this->belongsTo(PPDBUser::class, 'ppdb_user_id', 'id')
    			->onlyTrashed()
    			->notAccepted()
                ->byUserRole();
    }

    public function unit()
    {
    	return $this->belongsTo(Unit::class);
    }
}
