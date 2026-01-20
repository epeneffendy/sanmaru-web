<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait HasActivityLogs
{
    public static function bootHasActivityLogs()
	{
		static::created(function (Model $model) {
            ActivityLog::createModel($model);
            if (isset($model->published)) {
                $model->published ? ActivityLog::publishModel($model)
                                : ActivityLog::unpublishModel($model);
            }
        });

        static::updating(function (Model $model) {
            if (isset($model->published) && ($model->published <> $model->getOriginal('published'))) {
                $model->published ? ActivityLog::publishModel($model)
                                : ActivityLog::unpublishModel($model);
            }
        });

        static::updated(function (Model $model) {
            ActivityLog::updateModel($model);
        });

        static::deleted(function (Model $model) {
            ActivityLog::deleteModel($model);
        });
	}
}
