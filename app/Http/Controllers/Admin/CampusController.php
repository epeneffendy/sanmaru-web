<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampusRequest;

class CampusController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "campus"
    ];

    public function index()
    {
        $campuses = Campus::orderBy('id')->get();
        $data = [
            'nav' => $this->page,
            'campuses' => $campuses
        ];

        return view('administrator.campus.list', $data);
    }

    public function add()
    {
        $data = [
            'nav' => $this->page,
            'campus' => ''
        ];

        return view('administrator.campus.add', $data);
    }

    public function insert(CampusRequest $request)
    {
        Campus::create($request->validated());

        return redirect(route('admin.campus.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id)
    {
        $campus = Campus::where('id',$id)->firstOrFail();
        $data = [
            'nav' => $this->page,
            'campus' => $campus,
            'status' => 'edit'
        ];

        return view('administrator.campus.add', $data);
    }

    public function update(CampusRequest $request, $id)
    {
        $campus = Campus::where('id',$id)->firstOrFail();
        $campus->update($request->validated());
        return redirect(route('admin.campus.index'))->with('message', 'Berhasil diupdate');
    }

    public function delete($id)
    {
        $campus = Campus::where('id',$id)->firstOrFail();
        $campus->delete();
        return redirect(route('admin.campus.index'))->with('message', 'Berhasil dihapus');
    }

    public function select()
    {
        $campuses = Campus::withCount('campusUnits')->get();
        $data = [
            'nav' => $this->page,
            'campuses' => $campuses
        ];

        return view('administrator.campus.select', $data);
    }
}
