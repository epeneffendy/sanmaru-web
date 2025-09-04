<?php

namespace App\Transformer;

use League\Fractal\TransformerAbstract;
use App\Traits\ImageHandler;
use App\Models\Event;

class EventTransformer extends TransformerAbstract
{
    use ImageHandler;

    public function events($datas)
    {
        $events = collect();
        foreach ($datas as $event) {
            $events->push($this->event($event));
        }

        return $events;
    }

    public function event(Event $event)
    {
        $event->image_puth = $this->getImageUrl($event->image);
    	return $event->makeHidden(['deleted_at','created_at','updated_at', 'created_by', 'last_updated_by']);
    }


}
