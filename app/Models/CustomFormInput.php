<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFormInput extends Model
{
    protected $table = 'custom_form_inputs';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function custom_form_column()
    {
        return $this->belongsTo(CustomFormColumn::class);
    }

    public function ppdb_user()
    {
        return $this->belongsTo(PPDBUser::class, 'ppdb_user_id');
    }
}
