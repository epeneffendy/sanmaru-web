<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductFitting as Fitting;
use App\Http\Requests\FittingRequest;
use App\Http\Controllers\Controller;
use App\Models\Unit;

class FittingController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "fitting"
    ];
  
    public function index()
    {
        $data = [
            'nav' => $this->page,
            'fittings' => Fitting::byUserRole()->with('unit')->get()
        ];

        return view('administrator.fitting.list', $data);
    }

    public function add()
    {
        $data = [
            'unitOption' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'nav' => $this->page,
        ];

        return view('administrator.fitting.add', $data);
    }

    public function insert(FittingRequest $request)
    {
        Fitting::create($request->validated());

        return redirect(route('admin.fitting.index'))->with('message', 'Jadwal Fitting berhasil ditambahkan');
    }

    public function edit($id)
    {
        $fitting = Fitting::byUserRole()->findOrFail($id);

        $data = [
            'status' => 'edit',
            'fitting' => $fitting,
            'unitOption' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'nav' => $this->page
        ];

        return view('administrator.fitting.add', $data);
    }

    public function update(FittingRequest $request, $id)
    {
        $fitting = Fitting::byUserRole()->findOrFail($id);
        $fitting->update($request->validated());

        return redirect(route('admin.fitting.index'))->with('message', 'Jadwal fitting berhasil diedit');
    }

    public function delete($id)
    {
        $fitting = Fitting::byUserRole()->findOrFail($id);
        $fitting->delete();

        return redirect(route('admin.fitting.index'))->with('message', 'Jadwal Fitting berhasil dihapus');
    }
}
