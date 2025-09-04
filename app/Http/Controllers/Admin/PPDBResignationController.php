<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PPDBResignationStoreRequest;
use App\Http\Requests\PPDBResignationUpdateRequest;
use App\Http\Requests\PaymentRefundStoreRequest;
use App\Models\PPDBResignation;
use App\Services\PPDBResignationService;
use App\Services\PaymentRefundService;

class PPDBResignationController extends Controller
{
    private $page = [
        'parent' => 'ppdb',
        'child' => 'ppdb-resignation'
    ];

    public function index(Request $request, PPDBResignationService $resignationService)
    {
        $data = $resignationService->generateIndexData($request, $this->page);
        return view('administrator.ppdb-resignation.list', $data);
    }

    public function add(PPDBResignationService $resignationService)
    {
        $data = $resignationService->generateAddingData($this->page);
        return view('administrator.ppdb-resignation.add', $data);
    }

    public function insert(PPDBResignationStoreRequest $request, PPDBResignationService $resignationService)
    {
        $input = $request->validated();
        $data = $resignationService->create($input);
        return redirect(route('admin.ppdb-resignation.index'))->with('message', 'berhasil ditambahkan');
    }

    public function show(Request $request, $id, PPDBResignationService $resignationService)
    {
        $data = $resignationService->generateEditableData($id, $this->page);
        return view('administrator.ppdb-resignation.show', $data);
    }

    public function edit(Request $request, $id, PPDBResignationService $resignationService)
    {
        $data = $resignationService->generateEditableData($id, $this->page);
        return view('administrator.ppdb-resignation.edit', $data);
    }

    public function update(PPDBResignationUpdateRequest $request, $id, PPDBResignationService $resignationService)
    {
        $input = $request->validated();
        $data = $resignationService->update($id, $input);
        return redirect(route('admin.ppdb-resignation.index'))->with('message', 'berhasil diedit');
    }

    public function ajax(Request $request, PPDBResignationService $resignationService)
    {
        return $resignationService->getStudent($request->input());
    }

    public function addRefund($id, PaymentRefundService $paymentRefundService)
    {
        $ppdbResignation = PPDBResignation::with('ppdbUser')
                            ->where('id', $id)
                            ->firstOrFail();

        $data = $paymentRefundService->generateAddingData($ppdbResignation->ppdbUser->id, $this->page);
        $data['ppdbResignation'] = $ppdbResignation;
        return view('administrator.ppdb-resignation.add-refund', $data);
    }

    public function insertRefund($id, PaymentRefundStoreRequest $request, PaymentRefundService $paymentRefundService)
    {
        $ppdbResignation = PPDBResignation::with('ppdbUser')
                            ->where('id', $id)
                            ->firstOrFail();

        $input = $request->validated();
        $data = $paymentRefundService->create($input);
        return redirect()->route('admin.ppdb-resignation.edit', $ppdbResignation->id)->withMessage('berhasil ditambahkan');
    }

    public function showRefund($id, $paymentRefundId, PaymentRefundService $paymentRefundService)
    {
        $ppdbResignation = PPDBResignation::with('ppdbUser', 'ppdbUser.paymentRefunds')
                            ->whereHas('ppdbUser.paymentRefunds', function ($query) use ($paymentRefundId) {
                                $query->where('id', $paymentRefundId);
                            })
                            ->where('id', $id)
                            ->firstOrFail();
        $data = $paymentRefundService->generateShowData($paymentRefundId, $this->page);
        return view('administrator.ppdb-resignation.show-refund', $data);
    }

}
