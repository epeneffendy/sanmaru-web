<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoiceOfSanmarRequest;
use App\Services\VoiceOfSanmarService;

class VoiceOfSanmarController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "voice-of-sanmar"
    ];

    public function index(Request $request, VoiceOfSanmarService $voiceOfSanmarService)
    {
        $data = $voiceOfSanmarService->generateIndexData($request, $this->page);
        return view('administrator.voice-of-sanmar.list', $data);
    }

    public function add()
    {
        return view('administrator.voice-of-sanmar.add', [
            'voiceOfSanmar' => '',
            'nav' => $this->page
        ]);
    }

    public function insert(VoiceOfSanmarRequest $request, VoiceOfSanmarService $voiceOfSanmarService)
    {
        $voiceOfSanmarService->create($request->validated());
        return redirect(route('admin.voice-of-sanmar.index'))->with('message', 'berhasil ditambahkan');
    }

    public function edit($id, VoiceOfSanmarService $voiceOfSanmarService)
    {
        $data = $voiceOfSanmarService->generateEditableData($id, $this->page);
        return view('administrator.voice-of-sanmar.add', $data);
    }

    public function update(VoiceOfSanmarRequest $request, $id, VoiceOfSanmarService $voiceOfSanmarService)
    {
        $voiceOfSanmarService->update($id, $request->validated());
        return redirect(route('admin.voice-of-sanmar.index'))->with('message', 'berhasil diupdate');
    }

    public function delete($id, VoiceOfSanmarService $voiceOfSanmarService)
    {
        $voiceOfSanmarService->delete($id);
        return redirect(route('admin.voice-of-sanmar.index'))->with('message', 'berhasil dihapus');
    }
}
