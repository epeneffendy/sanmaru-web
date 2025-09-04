<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Requests\TagRequest;

class TagController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "tag"
    ];

    public function index()
    {
        $data = [
            'nav' => $this->page,
            'Tags' => Tag::get()
        ];

        return view('administrator.tag.list', $data);
    }

    public function add()
    {
        $data = [
            'Tag' => '',
            'nav' => $this->page
        ];

        return view('administrator.tag.add', $data);
    }

    public function insert(TagRequest $request)
    {
        FaqTag::create($request->validated());

        return redirect(route('admin.tag.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit(Tag $Tag)
    {
        $data = [
            'status' => 'edit',
            'Tag' => $Tag,
            'nav' => $this->page
        ];

        return view('administrator.tag.add', $data);
    }

    public function update(TagRequest $request, Tag $Tag)
    {
        $Tag->update($request->validated());

        return redirect(route('admin.tag.index'))->with('message', 'Berhasil diupdate');
    }

    public function delete(Tag $tag)
    {
        $tag->delete();

        return redirect(route('admin.tag.index'))->with('message', 'Berhasil dihapus');
    }
}
