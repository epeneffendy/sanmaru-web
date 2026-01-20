<?php

namespace App\Helpers;

use App\Enums\ProductTypeEnum;
use App\Models\PPDBUser;
use App\Models\Student;
use App\Models\Unit;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductHelper
{
    public static function suitableProducts($model, $type = ProductTypeEnum::SERAGAM, $filters = [])
    {
        if (!$model) {
            return null;
        }

        $types = null;
        $unit = null;
        $gender = null;

        if ($model instanceof PPDBUser) {
            $unit = $model->unit;
            $gender = $model->gender;
        }

        if ($model instanceof Student) {
            $unit = ($model->class) ? $model->class->unit : null;
            $gender = $model->user->ppdb->gender;
        }

        $products = Product::published()->with(['productUnits', 'details'])
                        ->byType($type)
                        ->select('id', 'name', 'level', 'slug', 'image_path');

        if ($unit) {
            $products = $products->whereHas('productUnits', function ($query) use ($unit) {
                return $query->where('unit_id', $unit->id);
            });
        }

        if ($gender) {
            $levels = self::collectProductLevels($model);
            $products = $products->whereIn('level', $levels->all());
        }

        if (isset($filters['q'])) {
            $products->where('name', 'like', '%'. $filters['q'] .'%');
        }

        $products = $products->get();

        return $products;
    }

    public static function collectProductLevels($model = null)
    {
        $collect = collect([
            'male' => 'PA',
            'female' => 'PI',
            'male_female' => 'PA/PI'
        ]);

        if ($model && $model instanceof PPDBUser) {
            switch($model->gender) {
                case 'male':
                    $collect->pull('female');
                break;
                case 'female':
                    $collect->pull('male');
                break;
                default:
                break;
            }
        }

        if ($model && $model instanceof Student) {
            switch($model->user->ppdb->gender) {
                case 'male':
                    $collect->pull('female');
                break;
                case 'female':
                    $collect->pull('male');
                break;
                default:
                break;
            }
        }

        return $collect;
    }
}
