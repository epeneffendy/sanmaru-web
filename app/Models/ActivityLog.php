<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App;
use App\Contracts\ActivityLog\ModelMetadata;
use App\Helpers\Helper;

class ActivityLog extends Model
{
    const LANG_PREFIX = 'activity-log';

    protected $fillable = [
        'user_id', 
        'model_type', 
        'model_id', 
        'data', 
        'action', 
        'origin',
        'model_metadata',
    ];

    public static function getUserId()
    {
        $auth = App::make('auth');
        $actor = $auth->user();
        if ($actor) {
            return $actor->id;
        } else {
            $session_user = session()->get('user');
            return $session_user['id'] ?? null;
        }
    }

    protected static function getClass($model)
    {
        return str_replace(__NAMESPACE__.'\\', '', (get_class($model)));
    }

    public static function createLogFor($model, $action)
    {   
        switch($action) {
            case 'Create':
                list($data, $origin, $model_metadata) = static::generateCreateData($model);
                break;
            case 'Update':
                list($data, $origin, $model_metadata) = static::generateUpdateData($model);
                break;
            case 'Delete':
                list($data, $origin, $model_metadata) = static::generateDeleteData($model);
                break;
            case 'Publish':
                list($data, $origin, $model_metadata) = static::generatePublishData($model);
                break;
            case 'Unpublish':
                list($data, $origin, $model_metadata) = static::generateUnpublishData($model);
                break;
            default:
                return;
                break;
        }
        
        static::create([
            'user_id'    => static::getUserId(),
            'model_type' => static::getClass($model),
            'model_id'   => $model->id,
            'data'       => isset($data) ? json_encode($data) : null,
            'origin'     => isset($origin) ? json_encode($origin) : null,
            'model_metadata' => isset($model_metadata) ? json_encode($model_metadata) : null,
            'action'     => $action,
        ]);
    }

    public static function createModel($model)
    {
        static::createLogFor($model, 'Create');
    }

    public static function updateModel($model)
    {
        static::createLogFor($model, 'Update');
    }

    public static function deleteModel($model)
    {
        static::createLogFor($model, 'Delete');
    }

    public function pretty()
    {
        return json_encode(json_decode($this->data), JSON_PRETTY_PRINT);
    }

    public static function publishModel($model)
    {
        static::createLogFor($model, 'Publish');
    }

    public static function unpublishModel($model)
    {
        static::createLogFor($model, 'Unpublish');
    }

    public static function generateCreateData($model)
    {
        $new = $model->getAttributes();
        $metadata = [];
        if ($model instanceof ModelMetadata) {
            $metadata = $model->getModelMetadata();
        }

        return [
            $new,
            [],
            $metadata,
        ];
    }

    public static function generateUpdateData($model)
    {
        $original = $model->getOriginal();
        $new = $model->getDirty();
        $old = [];
        $metadata = [];

        foreach ($new as $attribute => $value) {
            $old[$attribute] = Arr::get($original, $attribute);
        }

        if ($model instanceof ModelMetadata) {
            $metadata = $model->getModelMetadata();
        }

        return [
            $new,
            $old,
            $metadata,
        ];
    }

    public static function generateDeleteData($model)
    {
        $old = $model->getAttributes();
        $metadata = [];

        if ($model instanceof ModelMetadata) {
            $metadata = $model->getModelMetadata();
        }

        return [
            [],
            $old,
            $metadata,
        ];
    }

    public static function generatePublishData($model)
    {
        $metadata = [];

        if ($model instanceof ModelMetadata) {
            $metadata = $model->getModelMetadata();
        }

        return [
            [],
            [],
            $metadata,
        ];
    }

    public static function generateUnpublishData($model)
    {
        $metadata = [];

        if ($model instanceof ModelMetadata) {
            $metadata = $model->getModelMetadata();
        }

        return [
            [],
            [],
            $metadata,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getModelNameAttribute()
    {
        if (! $this->model_type) {
            return null;
        }
        return Str::title(__(self::LANG_PREFIX.'.'.Str::snake($this->model_type)));
    }

    public function isShopRoleUser()
    {
        if ($this->isProductModel()) {
            if ($this->user && Helper::isShopRole($this->user)) {
                return true;
            } 
        }

        return false;
    }

    public function getActivityDescriptionAttribute()
    {
        if ($this->isContentModel()) {
            return $this->contentDescription();
        }

        if ($this->isProductModel()) {
            return $this->productDescription();
        }

        // biar menampilkan apa adanya / data mentah
        return json_encode(json_decode($this->data), JSON_PRETTY_PRINT);
    }

    private function contentDescription()
    {
        if (! $this->action || ! $this->model_name) {
            return null;
        }

        $data = $this->data ? json_decode($this->data, true) : null;
        $metadata = $this->model_metadata ? json_decode($this->model_metadata, true) : null;

        $title = $data['title'] ?? null;
        $title = $data['name'] ?? $title;
        $title = $data['original_title'] ?? $title;
        $title = $data['original_name'] ?? $title;
        $title = $metadata['title'] ?? $title;
        $title = $metadata['name'] ?? $title;

        $description = __(self::LANG_PREFIX.'.'.strtolower($this->action)) ." ". __(self::LANG_PREFIX.'.content') . " " . $this->model_name;
        
        if ($title) {
            $description .= " \"{$title}\"";
        }

        return  $description;
    }

    private function productDescription()
    {
        if (! $this->action || ! $this->model_name) {
            return null;
        }

        $data = $this->data ? json_decode($this->data, true) : null;
        $origin = $this->origin ? json_decode($this->origin, true) : null;
        $metadata = $this->model_metadata ? json_decode($this->model_metadata, true) : null;

        $title = $data['title'] ?? null;
        $title = $data['name'] ?? $title;
        $title = $data['original_title'] ?? $title;
        $title = $data['original_name'] ?? $title;

        $description = "<p>" .__(self::LANG_PREFIX.'.'.strtolower($this->action)) . " data " . $this->model_name;

        if ($title) {
            $description .= " \"{$title}\"";
        }

        if ($metadata) {
            foreach ($metadata as $key => $value) {
                $description .= "<br>{$key}: {$value}";
            }
        }

        $description .= "</p>";

        $data = $this->cleanData($data);
        $origin = $this->cleanData($origin);

        if ($origin) {
            foreach ($origin as $key => $value) {
                if (isset($data[$key]) && $this->action == 'Update') {
                    if ($key == 'stock' && ! $this->isShopRoleUser()) {
                        $action = (intval($data[$key]) < intval($value)) ? 'make_order' : 'return_order';
                        $action = __(self::LANG_PREFIX.'.'.$action);
                        $description = str_replace(__(self::LANG_PREFIX.'.'.strtolower($this->action)), $action, $description);
                    }
                    $description .= "<br>{$key}: {$value} " . __(self::LANG_PREFIX . '.to') . " {$data[$key]}";
                } else {
                    $description .= "<br>{$key}: {$value}";
                }
            }
        } else {    
            foreach ($data as $key => $value) {
                $description .= "<br>{$key}: {$value}";
            }
        }

        return $description;
    }

    public function isContentModel()
    {
        if (! $this->model_type) {
            return false;
        }
        return in_array($this->model_type, static::listContentModel());
    }

    public function isProductModel()
    {
        if (! $this->model_type) {
            return false;
        }
        return in_array($this->model_type, static::listProductModel());
    }

    public static function listContentModel()
    {
        return [
            'About',
            'AboutCategory',
            'Blog', 
            'BlogCategory',
            'Campus',
            'CampusUnit',
            'Faq',
            'Gallery',
            'Headline',
            'Scholarship',
            'SchoolLife',
            'SchoolLifeCategory',
            'Testimonial',
            'VoiceOfSanmar',
            'Popup',
        ];
    }

    public static function listProductModel()
    {
        return [
            'Product',
            'ProductDetail',
        ];
    }

    private function cleanData($data=null)
    {
        if ($data && is_array($data)) {
            return Arr::except($data, [
                'created_at', 
                'updated_at', 
                'deleted_at', 
                'original_name', 
                'original_title'
            ]);
        }

        return $data;
    }

}