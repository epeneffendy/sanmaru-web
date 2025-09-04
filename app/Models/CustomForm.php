<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomForm extends Model
{
    protected $table = 'custom_forms';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'custom_form_periods', 'custom_form_id', 'period_id');
    }

    public function columns()
    {
        return $this->hasMany(CustomFormColumn::class, 'custom_form_id');
    }

    /**
     * Get the user's inputs.
     */
    public function columnInputs()
    {
        return $this->hasManyThrough(
            CustomFormInput::class,
            CustomFormColumn::class,
            'custom_form_id', // Foreign key on custom_form_column table...
            'custom_form_column_id', // Foreign key on custom_form_input table...
            'id', // Local key on custom_form table...
            'id' // Local key on custom_form_column table...
        );
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function(CustomForm $customForm) {
            $customForm->slug = Str::slug($customForm->name);
        });
    }

    public function getShowUrlAttribute()
    {
        return route('ppdb.custom-form.input', $this->slug);
    }
}
