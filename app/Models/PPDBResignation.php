<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageHandler;

class PPDBResignation extends Model
{
	protected $table = 'ppdb_resignations';
    protected $fillable = ['school_year', 'unit_id', 'ppdb_user_id', 'reason','attachment','status','user_id'];

    use ImageHandler;

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

   public function ppdb()
    {
    	return $this->belongsTo(PPDBUser::class, 'ppdb_user_id', 'id');
    }

     public function getAttachmentImageUrl()
    {
        if ($this->attachment == null) {
            return null;
        }

        return $this->getImageUrl($this->attachment);
    }
}
