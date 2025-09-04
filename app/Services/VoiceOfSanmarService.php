<?php
namespace App\Services;

use App\Models\VoiceOfSanmar;

class VoiceOfSanmarService
{
    private function params($params)
    {
        if (isset($params['content_url']) && $params['content_url']) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $params['content_url'], $match)) {
                $video_id = $match[1];
                $params['content_url'] = $video_id;
            }
        }

        return $params;
    }

    public function generateIndexData($request, $nav)
    {
        $voiceOfSanmars = new VoiceOfSanmar();

        if ($request->input('title')) {
            $voiceOfSanmars = $voiceOfSanmars->where('title', 'like', '%' . $request->input('title') . '%');
        }

        $voiceOfSanmars = $voiceOfSanmars->orderBy('created_at', 'desc')->paginate(5);

        return [
            'nav' => $nav,
            'data' => $voiceOfSanmars
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $voiceOfSanmar = VoiceOfSanmar::where('id', $id)->firstOrFail();
        return [
            'status' => 'edit',
            'voiceOfSanmar' => $voiceOfSanmar,
            'nav' => $nav
        ];
    }

    public function create($params)
    {
        $params = $this->params($params);
        $voiceOfSanmar = VoiceOfSanmar::create($params);

        return $voiceOfSanmar;
    }

    public function update($id, $params)
    {
        $voiceOfSanmar = VoiceOfSanmar::where('id', $id)->firstOrFail();
        $params = $this->params($params);

        $voiceOfSanmar->fill($params);
        return $voiceOfSanmar->save();
    }

    public function delete($id)
    {
        $voiceOfSanmar = VoiceOfSanmar::where('id', $id)->firstOrFail();
        return $voiceOfSanmar->delete();
    }
}