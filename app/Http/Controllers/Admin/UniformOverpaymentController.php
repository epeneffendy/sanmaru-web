<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UniformOverpaymentStoreRequest;
use App\Services\UniformOverpaymentService;

class UniformOverpaymentController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "uniform-overpayment"
    ];

    public function index(UniformOverpaymentService $uniformOverpaymentService)
    {
    	$data = $uniformOverpaymentService->generateIndexData($this->page);
    	return view('administrator.uniform-overpayment.list', $data);
    }

    public function add(UniformOverpaymentService $uniformOverpaymentService)
    {
    	$data = $uniformOverpaymentService->generateAddingData($this->page);
    	return view('administrator.uniform-overpayment.add', $data);
    }

    public function insert(UniformOverpaymentStoreRequest $request, UniformOverpaymentService $uniformOverpaymentService)
    {
    	$data = $uniformOverpaymentService->create($request->validated());
    	return redirect(route('admin.uniform-overpayment.index'))->withMessage('berhasil ditambahkan');
    }

    public function edit($id, UniformOverpaymentService $uniformOverpaymentService)
    {
    	$data = $uniformOverpaymentService->generateEditableData($id, $this->page);
    	return view('administrator.uniform-overpayment.add',$data);
    }

    public function update($id, UniformOverpaymentStoreRequest $request, UniformOverpaymentService $uniformOverpaymentService)
    {
    	$data = $uniformOverpaymentService->update($id, $request->validated());
    	return redirect(route('admin.uniform-overpayment.index'))->withMessage('berhasil diedit');
    }

    public function show($id, UniformOverpaymentService $uniformOverpaymentService)
    {
    	$data = $uniformOverpaymentService->generateShowingData($id, $this->page);
    	return view('administrator.uniform-overpayment.show', $data);
    }

    public function studentData(Request $request, UniformOverpaymentService $uniformOverpaymentService)
    {
    	$data = $uniformOverpaymentService->getStudentData($request->input());
    	if (is_null($data)) {
    		return response()->json(null, 404);
    	} else {
    		return response()->json($data, 200);
    	}
    }
}
