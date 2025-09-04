<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PopupRequest;
use App\Services\PopupService;

class PopupController extends Controller
{
    private $page = [
        'parent' => 'konten',
        'child' => 'popup'
    ];

    public function index(PopupService $popupService)
    {
        $data = $popupService->generateIndexData($this->page);
        return view('administrator.popup.list', $data);
    }

    public function add(PopupService $popupService)
    {
        $data = $popupService->generateAddingData($this->page);
        return view('administrator.popup.add', $data);
    }

    public function insert(PopupRequest $request, PopupService $popupService)
    {
        $popupService->create($request->validated());
        return redirect(route('admin.popup.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id, PopupService $popupService)
    {
        $data = $popupService->generateEditableData($id, $this->page);
        return view('administrator.popup.add', $data);
    }

    public function update(PopupRequest $request, $id, PopupService $popupService)
    {
        $popupService->update($id, $request->validated());
        return redirect(route('admin.popup.index'))->with('message', 'Berhasil diupdate');
    }

    public function delete($id, PopupService $popupService)
    {
        $popupService->delete($id);
        return redirect(route('admin.popup.index'))->with('message', 'Berhasil dihapus');
    }
}
