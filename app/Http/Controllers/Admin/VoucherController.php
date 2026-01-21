<?php

namespace App\Http\Controllers\Admin;

use App\Exports\VoucherNewUsageExport;
use App\Exports\VoucherUsageExport;
use App\Exports\VoucherUsageMissExport;
use App\Http\Requests\VoucherStoreRequest;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\PPDBUser;
use App\Models\User;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Unit;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

    public function addNewVoucher(VoucherService $voucherService)
    {
        $this->authorize('create', Voucher::class);
        return view('administrator.voucher.add-new-voucher', $voucherService->generateNewAddingData($this->page));
    }

    public function addVoucher(VoucherService $voucherService)
    {
        $this->authorize('create', Voucher::class);
        // dd($voucherService->generateNewAddingData($this->page));
        return view('administrator.voucher.add-voucher', $voucherService->generateNewAddingData($this->page));
    }

    public function insert(VoucherStoreRequest $voucherStoreRequest, VoucherService $voucherService)
    {
        $this->authorize('create', Voucher::class);
        $input = $voucherStoreRequest->validated();
        $voucherService->create($input);
        return redirect()->route('admin.voucher.index')->with('message', 'Berhasil ditambahkan');
    }

    public function newInsert(VoucherStoreRequest $voucherStoreRequest, VoucherService $voucherService)
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
        $data = $voucherService->generateNewEditableData($id, $this->page);

        if($data['voucher']->unit_id){
            unset($data['arr_student']);
            $data['arr_student'] = '';
        }


        return view('administrator.voucher.add-voucher', $data);
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
            case 'school-year' :
                $period = Period::where('id', request()->input('select', false))->first();
                $data = [
                    'year' => $period->school_year,
                    'unit_id' => $period->unit_id
                ];
                break;
        }
        return Response::json($data);
    }

    public function usage(Request $request, VoucherService $voucherService)
    {
        $data = $voucherService->generateVoucherClaimsData($this->page, $request->input());

        foreach($data['datas'] as $item){
            foreach($item['voucher'] as $detail){
                // dd($detail);
            }
        }
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

        if(!empty($productOrders)){
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
        }


        return view('administrator.voucher-usage-miss.list', [
            'nav' => $this->page,
            'search_scopes' => $searchScopes,
            'units' => Unit::byUserRole()->get(),
            'products' => Product::whereIn('id', $allMissProductIds)->get()->keyBy('id')->all(),
            'params' => $request->only(['status', 'page']),
            'orders' => $productOrders
        ]);

    }

    public function fetchStudent(Request $request)
    {
        // dd($request->all());
        if ($request->development == 1) {
            $users = PPDBUser::where('school_year', $request->year)->get();
            $collections = collect();
            foreach ($users as $user) {
                $collections->put($user->user_id, '[' . $user->register_number . '] ' . $user->name);
            }
        } else {
            if ($request->target_student == 'ppdb') {
                $users = PpdbUser::with('user')
                    ->where('school_year', $request->year)
                    ->where('unit_id', $request->unit_student)
//                    ->where('periode',$request->period_id)
                    ->whereHas('user', function($query) {
                        $query->where('type', 'ppdb');
                    })
                    ->get();
                $collections = collect();
                foreach ($users as $user) {
                    $collections->put($user->user_id, '[' . $user->register_number . '] ' . $user->name);
                }
            } else {
                $users = PpdbUser::with('user')
                    ->where('school_year', $request->year)
                    ->where('unit_id', $request->unit_student)
                    ->whereHas('user', function($query) {
                        $query->where('type', 'siswa');
                    })
                    ->get();

                $collections = collect();
                foreach ($users as $user) {
                    $collections->put($user->user_id, '[' . $user->user->student->nis . '] ' . $user->name);
                }
            }
        }

        return $collections;
    }

    public function modalGenerateVoucherDevelopment(Request $request, VoucherService $voucherService)
    {
        $data = [
            'units' => Unit::all(),
            'years' => $voucherService->yearsOption()
        ];
        return view('administrator.voucher.modal-generate-voucher', $data);
    }

    public function generateVoucherDevelopment(Request $request, VoucherService $voucherService)
    {
        $next_year = $request->year_voucher + 1;
        $voucher = $request->unit_voucher . substr($request->year_voucher, 2) . substr($next_year, 2) . $request->student_voucher;
        return $voucher;
    }

    public function usageVoucher(Request $request, VoucherService $voucherService)
    {
        $usage = [];
        $input = $request->all();

        if(!empty($input)){
            $vouchers = Voucher::where(['type'=>$input['type_voucher']])->get();

            $usage = [];
            $type = $free = '';
            foreach ($vouchers as $ind => $voucher) {

                if (!empty($voucher->user_id)) {
                    foreach ($voucher->user_id as $user) {
                        $user_id = $user;
                        $total_used = $voucher->usages->filter(function ($usage) use ($user_id) {
                            return $usage->orders->filter(function ($order) use ($user_id) {
                                return $user_id == $order->user_id && $order->status !== 'cancel';
                            })->first() ? true : false;
                        })->count();

                        if ($voucher->usage_type === 'per_user') {
                            $usage_remaining = $voucher->usage_limit - $total_used;
                        }

                        if ($voucher->usage_type === 'cumulative') {
                            $usage_remaining = $voucher->usage_remaining;
                        }

                        $ppdb = PPDBUser::where([
                            'user_id' => $user
                        ]);
                        if (isset($request->unit)) {
                            $ppdb->where(['unit_id' => $request->unit]);
                        }

                        if (isset($request->name)) {
                            $ppdb->where('name', 'like', '%' . $request->name . '%');
                        }


                        if (isset($request->school_year)) {
                            $ppdb->where(['school_year' => $request->school_year]);
                        } else {
                            $year = date('Y') + 1;
                            $ppdb->where(['school_year' => $year]);
                        }

                        $filter_status = false;
                        if (isset($request->status)) {
                            if ($request->status != 0) {
                                $filter_status = true;
                            }
                        }

                        $ppdb = $ppdb->first();


                        if ($ppdb) {
                            if ($voucher->type == 'free_product') {
                                $product_free = '';
                                if (!empty($voucher->rule)) {
                                    foreach (json_decode($voucher->rule) as $item) {
                                        $product = Product::where('id', $item)->first();
                                        $product_free .= $product->name . ', ';
                                    };
                                    $product_free = substr($product_free, 0, -2);
                                }
                                $type = 'Free Product';
                                $free = $product_free;
                            }

                            if ($voucher->type == 'discount_fixed') {
                                $type = 'Discount Fixed';
                                $free = 'Rp' . number_format($voucher->rule);
                            }

                            if ($voucher->type == 'discount_percent') {
                                $type = 'Discount Percent';
                                $free = $voucher->rule . '%';
                            }

                            if (!$filter_status) {
                                $usage[$voucher->code . '-' . $ppdb->id]['unit'] = $ppdb->unit->name;
                                $usage[$voucher->code . '-' . $ppdb->id]['name'] = $ppdb->name;
                                $usage[$voucher->code . '-' . $ppdb->id]['code'] = $voucher->code;
                                $usage[$voucher->code . '-' . $ppdb->id]['type'] = $type;
                                $usage[$voucher->code . '-' . $ppdb->id]['free'] = $free;
                                $usage[$voucher->code . '-' . $ppdb->id]['limit'] = $voucher->usage_limit;
                                $usage[$voucher->code . '-' . $ppdb->id]['usage_remining'] = $usage_remaining;
                                $usage[$voucher->code . '-' . $ppdb->id]['total_usage'] = $total_used;
                                $usage[$voucher->code . '-' . $ppdb->id]['status'] = $total_used ? 'claimed' : 'available';
                                $usage[$voucher->code . '-' . $ppdb->id]['label_color'] = $total_used ? 'danger' : 'success';
                            } else {
                                $status_voucher = ($total_used) ? 'claimed' : 'available';
                                if ($request->status == $status_voucher) {
                                    $usage[$voucher->code . '-' . $ppdb->id]['unit'] = $ppdb->unit->name;
                                    $usage[$voucher->code . '-' . $ppdb->id]['name'] = $ppdb->name;
                                    $usage[$voucher->code . '-' . $ppdb->id]['code'] = $voucher->code;
                                    $usage[$voucher->code . '-' . $ppdb->id]['type'] = $type;
                                    $usage[$voucher->code . '-' . $ppdb->id]['free'] = $free;
                                    $usage[$voucher->code . '-' . $ppdb->id]['limit'] = $voucher->usage_limit;
                                    $usage[$voucher->code . '-' . $ppdb->id]['usage_remining'] = $usage_remaining;
                                    $usage[$voucher->code . '-' . $ppdb->id]['total_usage'] = $total_used;
                                    $usage[$voucher->code . '-' . $ppdb->id]['status'] = $total_used ? 'claimed' : 'available';
                                    $usage[$voucher->code . '-' . $ppdb->id]['label_color'] = $total_used ? 'danger' : 'success';
                                }

                            }

                        }
                    }
                }


            }
        }

        return view('administrator.voucher-usage.list-usage', [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'years' => $voucherService->yearsOption(),
            'params' => $request->only(['name', 'unit', 'school_year', 'status', 'type_voucher']),
            'datas' => $usage
        ]);
    }

    public function exportUsage(Request $request)
    {
        $vendorsExport = new VoucherUsageExport($request->all());

        $title = "Laporan Klaim Voucher " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $vendorsExport->setTemplate(true);
            $title = "Laporan Klaim Voucher.xlsx";
        }

        return $vendorsExport->download($title);
    }

    public function exportNewUsage(Request $request)
    {
        $vendorsExport = new VoucherNewUsageExport($request->all());

        $title = "Laporan Klaim Voucher " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $vendorsExport->setTemplate(true);
            $title = "Laporan Klaim Voucher.xlsx";
        }

        return $vendorsExport->download($title);
    }

    public function exportUsageMiss(Request $request)
    {
        $vendorsExportMiss = new VoucherUsageMissExport($request->all());

        $title = "Laporan Penggunaan Voucher " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $vendorsExportMiss->setTemplate(true);
            $title = "Laporan Penggunaan Voucher.xlsx";
        }

        return $vendorsExportMiss->download($title);
    }

    public function detailReceiveVoucher(Request $request, $id)
    {
        $html = '';

        $voucher = Voucher::where('id', $id)->first();
        $users = $voucher->user_id;

        $html = '<div class="fixed-table-head period"><table id="datatables-uniform-deadline" class="table display">
                        <thead>
                            <tr>
                                <td>Nama</td>
                                <td>Date</td>
                                <td>Verificator</td>
                            </tr>
                        </thead>';
        foreach ($users as $ind => $item) {
            $ppdb = PPDBUser::where('user_id', $item)->first();

            $verificator = json_decode($ppdb->verification_development_statement);

            $html .= '<tr>
                        <td>' . $ppdb->name . '</td>
                        <td>' .( ($verificator != null) ?  date('d-m-Y H:i:s', strtotime($verificator->verification_time)) : '-').'</td>
                        <td>' .( ($verificator != null) ?  $verificator->username : '-').'</td>
                    </tr>';
        }
        $html .= '</table></div>';

        return $html;
    }
}
