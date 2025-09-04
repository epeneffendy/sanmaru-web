<?php

namespace App\Services;

use App\Transformer\EventTransformer;
use App\Traits\ImageHandler;
use App\Models\Event;

class EventService
{
    use ImageHandler;

    public function listEvents($offset, $limit) {
        $events = Event::onGoing()->select('id', 'title', 'image_path', 'event_time');
        if (isset($offset)) {
            $events->offset($offset);
        }
        if (isset($limit)) {
            $events->limit($limit);
        }

        $events = $events->get();
        return (new EventTransformer())->events($events);
    }

    public function create($params, $userId)
    {
        $params['created_by'] = $userId;
        $params['last_updated_by'] = $userId;
        if (isset($params['image'])) {
            $params['image_path'] = $this->uploadFile($params['image']);
        }
        return Event::create($params);
    }

    public function generateEditableData($id, $nav)
    {
        $event = Event::findOrFail($id);
        return array(
            'event' => $event,
            'nav' => $nav,
            'method' => 'edit'
        );
    }

    public function update($id, $params, $userId)
    {
        $params['last_updated_by'] = $userId;
        $event = Event::findOrFail($id);
        if (isset($params['image'])) {
            if ($this->imageExists($event->image_path)) {
                $this->deleteImage($event->image_path);
            }

            $params['image_path'] = $this->uploadFile($params['image']);
        }
        $event->fill($params);
        return $event->save();
    }

    public function toggleStatus($id, $userId)
    {
        $params['last_updated_by'] = $userId;

        $event = Event::findOrFail($id);
        $event->status = $event->isPublished() ? Event::STATUS_UNPUBLISHED : Event::STATUS_PUBLISHED;
        return $event->save();
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);
        return $event->delete();
    }

    public function uploadFile($file)
    {
        $type = 'event';
        if ($upload = $this->doUploadImage($file, $type)) {
            return $upload['path_upload'];
        }

        return false;
    }
}
