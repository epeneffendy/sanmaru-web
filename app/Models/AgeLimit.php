<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeLimit extends Model
{
    protected $fillable = [
        'name', 'description', 'year', 'month', 'active', 'max_year', 'max_month'
    ];

    public function getAgeAttribute()
    {
        return "{$this->year} Year {$this->getMonthIfExistsAttribute()}";
    }

    public function getMonthIfExistsAttribute()
    {
        if ($this->month)
            return "{$this->month} Month";

        return;
    }


    public function getActiveLabelAttribute()
    {
        $attributes = [
            'class' => 'danger',
            'label' => 'Inactive'
        ];

        if ($this->active)
            $attributes = [
                'class' => 'success',
                'label' => 'Active'
            ];

        return "<span class='label label-{$attributes['class']}'>{$attributes['label']}</span>";
    }

    public function getDaysAttribute()
    {
        return ($this->year * 12 * 30) + ( $this->month ? ($this->month * 30): 0);
    }

    public function getMonthsAttribute()
    {
        return ($this->year * 12) + ( $this->month ? $this->month: 0);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeActiveUsed($query, $class = null, $forced = null, $level_of_education = null)
    {
        $level_of_education = $level_of_education ?: '';
        if ($forced) {
            $query = $query->where('name', $forced);
        } elseif ($class) {
            $query = $query->where('name', 'like', '%'. $class .'%');
        } else {
            $query = $query->where('name', 'Batas Umur '.$level_of_education);
        }
        return $query->where('active', 1);
    }

    public function getMaxAgeAttribute()
    {
        $year = $this->year;
        if (!is_null($this->max_year) && !is_null($this->max_month)) {
            $year = $this->max_year;
        } 
        
        return "{$year} Year {$this->getMaxMonthIfExistsAttribute()}";
    }

    public function getMaxMonthIfExistsAttribute()
    {
        $month = $this->month;
        if (!is_null($this->max_year) && !is_null($this->max_month)) {
            $month = $this->max_month;
        } 

        if ($month) {
            return "{$month} Month";
        }

        return;
    }

    public function getMaxMonthsAttribute()
    {
        $year = $this->year;
        $month = $this->month;
        if (!is_null($this->max_year) && !is_null($this->max_month)) {
            $year = $this->max_year;
            $month = $this->max_month;
        }
        return ($year * 12) + ($month ?: 0);
    }
}
