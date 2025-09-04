<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Popup;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use App\Traits\ImageHandler;

class PopupService
{
    use ImageHandler;

    public function generateIndexData($nav) 
    {
        $popups = Popup::orderBy('publish_date', 'DESC')->with('unit');
        if (request()->unit) {
            $popups = $popups->whereHas('unit', function ($query) {
                $query->byUserRole()->where('id', request()->unit);
            });
        }

        if (request()->search) {
            $popups = $popups->where('title', 'like', '%'.request()->search.'%');
        }

        $popups = $popups->paginate();

        return [            
            'popups' => $popups,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'params' => request()->except(['page']),
            'nav' => $nav
        ];
    }

    public function generateAddingData($nav)
    {
        $popup = new Popup();

        return [
            'popup' => $popup,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $popup = Popup::where('id',$id)->firstOrFail();
        return [
            'status' => 'edit',
            'popup' => $popup,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav
        ];
    }

    private function params($params)
    {
        if (isset($params['content'])) {
            $imageService = new ImageService();
            $params['content'] = $imageService->filterUploadHTML($params['content'], 'content_image');
        }

        if (isset($params['current_content'])) {
            $imageService = new ImageService();
            $imageService->filterUpdateHTML($params['current_content'], $params['content']);
        }

        if (isset($params['publish_date'])) {
            $params['publish_date'] = Carbon::parse($params['publish_date'])->toDateTimeString();
        }

        if (!Helper::canPublishArticle() && isset($params['published'])) {
            unset($params['published']);
        }

        return $params;
    }

    public function create($params)
    {
        $params = $this->params($params);
        $popup = Popup::create($params);

        return $popup;
    }

    public function update($id, $params)
    {
        $popup = Popup::findOrFail($id);

        $params['current_content'] = $popup->content;
        $params = $this->params($params);

        $popup->fill($params);
        return $popup->save();
    }

    public function delete($id)
    {
        $popup = Popup::where('id', $id)->firstOrFail();
        $imageService = new ImageService();
        $imageService->filterDeleteHTML($popup->content, 'content_image');
        return $popup->delete();
    }
}
