<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFormColumn extends Model
{
    const TYPE_TEXT = 0;
    const TYPE_NUMBER = 1;

    protected $table = 'custom_form_columns';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function custom_form()
    {
        return $this->belongsTo(CustomForm::class);
    }

    public function getTypeHtmlAttribute()
    {
        switch ($this->type) {
            case self::TYPE_TEXT:
                return 'text';
                break;

            case self::TYPE_NUMBER:
                return 'number';
                break;

            default:
                return 'text';
                break;
        }
    }
}
