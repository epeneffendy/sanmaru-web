<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VoucherStoreRequest;
use App\Http\Controllers\Controller;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Unit;
use Response;

class VoucherController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "voucher"
    ];

    public function index(Request $request, VoucherService $voucherService)
    {
        $this->authorize('viewAny', Voucher::class);
        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'years' => $voucherService->getAvailableYears(),
            'params' => $request->only(['code', 'name', 'unit', 'type', 'year', 'page']),
            'vouchers' => $voucherService->filter($request->all(), 10),
        ];
        return view('administrator.voucher.list', $data);
    }

    public function add(VoucherService $voucherService)
    {
        $this->authorize('create', Voucher::class);
        return view('administrator.voucher.add', $voucherService->generateAddingData($this->page));
    }

    public function insert(VoucherStoreRequest $voucherStoreRequest, VoucherService $voucherService)
    {
        $this->authorize('create', Voucher::class);
        $input = $voucherStoreRequest->validated();
        $voucherService->create($input);
        return redirect()->route('admin.voucher.index')->with('message', 'Berhasil ditambahkan');
    }

    public function update(VoucherStoreRequest $voucherStoreRequest, $id, VoucherService $voucherService)
    {
        $this->authorize('update', Voucher::find($id));
        $input = $voucherStoreRequest->validated();
        $voucherService->update($id, $input);
        return redirect()->route('admin.voucher.index')->with('message', 'Berhasil diedit');
    }

    public function edit($id, VoucherService $voucherService)
    {
        $this->authorize('update', Voucher::find($id));
        $data = $voucherService->generateEditableData($id, $this->page);
        return view('administrator.voucher.add', $data);
    }

    public function delete(Request $request, $id)
    {
        $this->authorize('delete', Voucher::find($id));
        try {
            Voucher::where('id', $id)->delete();
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.voucher.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.voucher.index')->with('message', 'Berhasil dihapus');
    }

    public function ajax(VoucherService $voucherService)
    {
        $data = [];
        $type = request()->input('type', false);

        switch ($type) {
            case 'generate-code':
                $data = [
                    'code' => $voucherService->generateCode()
                ];
            break;
        }

        return Response::json($data);
    }

    public function usage(Request $request, VoucherService $voucherService)
    {
        $data = $voucherService->generateVoucherClaimsData($this->page, $request->input());
        return view('administrator.voucher-usage.list', $data);
    }

    public function usageMiss(Request $request, VoucherService $VoucherService)
    {
        $related = [
            'user',
            'user.student',
            'user.student.class',
            'user.student.class.unit',
            'user.ppdb',
            'user.ppdb.unit',
            'productOrderDetails',
            'productOrderDetails.productDetail',
        ];
        $searchScopes = [
            'student_name' => 'Nama Siswa',
            'register_number' => 'Nomor Registrasi Siswa'
        ];
        
        $productOrders = $VoucherService->filterUsageMiss($request->all(), 0, $related);
        $allMissProductIds = [];

        $productOrders = $productOrders->filter(function ($item) use (&$allMissProductIds) {
            $missProductIds = [];
            $voucher = json_decode($item->voucher, TRUE);

            if ($voucher != null) {
                $productIds = $item->productOrderDetails->keyBy('product_id')->all();
                $rules = json_decode($voucher['rule'], TRUE);

                if (is_array($rules) || is_object($rules)) {
                    foreach ($rules as $rule) {
                        if (!array_key_exists($rule, $productIds)) {
                            $missProductIds[$rule] = $rule;
                        }
                    }

                    $allMissProductIds = array_merge($allMissProductIds, $missProductIds);
                    $item->missProductIds = $missProductIds;

                    return count($missProductIds);
                }
            }
        });

        return view('administrator.voucher-usage-miss.list', [
            'nav' => $this->page,
            'search_scopes' => $searchScopes,
            'units' => Unit::byUserRole()->get(),
            'products' => Product::whereIn('id', $allMissProductIds)->get()->keyBy('id')->all(),
            'params' => $request->only(['status', 'page']),
            'orders' => $productOrders
        ]);
        
    }
}
