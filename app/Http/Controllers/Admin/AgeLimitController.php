<?php

namespace App\Http\Controllers\Admin;

use App\Models\AgeLimit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgeLimitRequest;

class AgeLimitController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "age-limit"
    ];
  
    public function index()
    {
        $data = [
            'nav' => $this->page,
            'ageLimits' => AgeLimit::get()
        ];

        return view('administrator.age-limit.list', $data);
    }

    public function add()
    {
        $data = [
            'ageLimit' => '',
            'nav' => $this->page,
        ];

        return view('administrator.age-limit.add', $data);
    }

    public function insert(AgeLimitRequest $request)
    {
        AgeLimit::create($request->validated());

        return redirect(route('admin.age-limit.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit(AgeLimit $ageLimit)
    {
        $data = [
            'status' => 'edit',
            'ageLimit' => $ageLimit,
            'nav' => $this->page
        ];

        return view('administrator.age-limit.add', $data);
    }

    public function update(AgeLimitRequest $request, AgeLimit $ageLimit)
    {
        $ageLimit->update($request->validated());

        return redirect(route('admin.age-limit.index'))->with('message', 'Berhasil diedit');
    }

    public function delete(AgeLimit $ageLimit)
    {
        $ageLimit->delete();

        return redirect(route('admin.age-limit.index'))->with('message', 'Berhasil dihapus');
    }
}
