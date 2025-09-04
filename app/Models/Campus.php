<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class Campus extends Model implements ModelMetadata
{   
    use HasActivityLogs;
    
    protected $table = 'campuses';

    protected $fillable = ['name', 'description'];

    public function campusUnits()
    {
        return $this->hasMany(CampusUnit::class);
    }

    public function campusUnitsWithUnit()
    {
        return $this->hasMany(CampusUnit::class)->with('unit');
    }

    public function getModelMetadata()
    {
        return [
            'name' => $this->name
        ];
    }
}
