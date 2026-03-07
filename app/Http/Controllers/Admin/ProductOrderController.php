<?php

namespace App\Http\Controllers\Admin;

use App\Models\PPDBUser;
use App\Models\User;
use App\Services\OpenApi\v1\PaymentBCAService;
use Auth;
use App\Models\Unit;
use App\Lib\ExportJob;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductOrder;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Services\ProductService;
use App\Models\ProductOrderDetail;
use App\Models\ExportJob as Export;
use App\Exports\ProductOrdersExport;
use App\Http\Controllers\Controller;
use App\Services\ProductOrderService;
use Illuminate\Support\Facades\Redirect;
use App\Exports\KantinProductOrdersExport;
use App\Http\Requests\ProductOrderRequest;
use App\Services\ProductOrderPickupService;
use App\Http\Requests\DateRangeDifferenceRequest;

class ProductOrderController extends Controller
{
    use ImageHandler;

    private $page = [
        "parent" => "shop",
        "child" => "product-order"
    ];

    public function index(Request $request, ProductOrderService $productOrderService)
    {
        $data = $productOrderService->generateListData($this->page, $request, $productOrderService);

//        $related = [
//            'user',
//            'user.student',
//            'user.student.class',
//            'user.student.class.unit',
//            'user.ppdb',
//            'user.ppdb.unit',
//            'productOrderDetails',
//            'productOrderDetails.productDetail',
//        ];
//        $searchScopes = [
//            'student_name' => 'Nama Siswa',
//            'register_number' => 'Nomor Registrasi Siswa'
//        ];
//        $data = [
//            'nav' => $this->page,
//            'units' => Unit::byUserRole()->get(),
//            'product_orders' => $productOrderService->filter($request->all(), 20, $related),
//            'search_scopes' => $searchScopes,
//            'years' => $productOrderService->getAvailableYears(),
//            'params' => $request->only(['page', 'search', 'scope', 'status', 'unit', 'year', 'date_range', 'pickup_status', 'type_voucher'])
//        ];
        return view('administrator.product-order.list', $data);
    }

    public function add(ProductOrderService $productOrderService)
    {
        $data = $productOrderService->generateAddingData($this->page);

        return view('administrator.product-order.add', $data);
    }

    public function insert(ProductOrderRequest $request, ProductOrderService $productOrderService)
    {
        $productOrderService->createByAdmin($request->validated());
        return redirect(route('admin.product-order.index'));
    }

    public function show($id)
    {
        $productOrder = ProductOrder::where('id', $id)->with('productOrderDetails', 'productOrderDetails.product', 'productOrderDetails.productDetail')->firstOrFail();
        $user = PPDBUser::where('user_id', $productOrder->user_id)->firstOrFail();

        $data = [
            'productOrder' => $productOrder,
            'nav' => $this->page,
            'user' => $user
        ];

        return view('administrator.product-order.show', $data);
    }

    public function edit($id, ProductOrderService $productOrderService)
    {
        $data = $productOrderService->generateEditableData($id, $this->page);

        return view('administrator.product-order.add', $data);
    }

    public function update(ProductOrderRequest $request, $id, ProductOrderService $productOrderService)
    {
        $productOrderService->update($id, $request->validated());

        return redirect(route('admin.product-order.index'));
    }

    public function delete($id, ProductOrderService $productOrderService)
    {
        $productOrderService->delete($id);

        return back();
    }

    public function productDetail(Product $product)
    {
        return $product->details;
    }

    public function uploadPayment(Request $request)
    {
        $data = [];
        $type = 'payment_image';
        $user = $request->session()->get('user');
        try {
            if ($request->hasFile($type)) {
                $upload = $this->doUploadImage($request->file($type), $type);

                $update = array(
                    $type => $upload['path_upload'],
                );

                $order = ProductOrder::where('id', $request->input('id'))->firstOrFail();
                $order->update($update);

                $data = [
                    'path' => url('images/' . $upload['path_upload']),
                    'filename' => $upload['filename'],
                ];
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json($data, 200);
    }

    public function sendConfirmed(ProductOrderService $productOrderService)
    {
        $productOrders = $productOrderService->massSendPaymentConfirmedMails();

        return redirect()->route('admin.product-order-pickup.index')->with('message', $productOrders . ' email diproses');
    }

    public function export(DateRangeDifferenceRequest $request)
    {
        // $validParams = $request->validated();

        // $productOrdersExport = new productOrdersExport($request->all(), Auth::user());
        // $title = Str::slug("Exports Data Pemesanan Product " . date('Y-m-d H:i:s'), '_') . ".xlsx";

        // // $productOrdersExport->queue('exports/'. $title, 'private')->allOnQueue('exports');
        // (new ExportJob())->export($productOrdersExport, array_merge($request->only('unit'), ['page' => 'product-order']), Auth::user(), $title);
        // return back()->withSuccess('Export started!');

        // //return $productOrdersExport->download($title);

        $validParams = $request->validated();

        $productOrdersExport = new productOrdersExport($request->all(), Auth::user());
        $title = "Exports Data PPDB Users " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $productOrdersExport->setTemplate(true);
            $title = "Template Import PPDB Users.xlsx";
        }

        return $productOrdersExport->download($title);
    }

    public function exportKantin(DateRangeDifferenceRequest $request)
    {
        $kantinProductOrdersExport = new KantinProductOrdersExport($request->all(), Auth::user());
        $title = Str::slug("Exports Data Pemesanan Product " . date('Y-m-d H:i:s'), '_') . ".xlsx";

        return $kantinProductOrdersExport->download($title);
    }

    public function confirmPayment(Request $request, $id, ProductOrderService $productOrderService)
    {
        $user = $request->session()->get('user');
        if ($productOrderService->confirmPayment($id, $user))
            return redirect()->route('admin.product-order.index')->with('message', 'Pembayaran Berhasil dikonfirmasi');
        return redirect()->route('admin.product-order.index')->with('errors', 'Gagal dikonfirmasi');
    }

    public function rejectPayment(Request $request, $id, ProductOrderService $productOrderService)
    {
        $input = $request->all();
        if (!isset($input['send_email'])) {
            $input['send_email'] = '0';
        }
        $user = $request->session()->get('user');
        if ($productOrderService->rejectPayment($id, $input))
            return redirect()->route('admin.product-order.index')->with('message', 'Pembayaran telah ditolak');
        return redirect()->route('admin.product-order.index')->with('errors', 'Gagal dikonfirmasi');
    }

    public function exportList()
    {
        $exports = Export::with('user')->orderBy('created_at', 'DESC')->get();
        return view('administrator.export-job.list', [
            'nav' => $this->page,
            'exports' => $exports
        ]);
    }

    public function unitStudent($unitId, ProductOrderService $productOrderService)
    {
        $data = $productOrderService->getStudentList($unitId);
        return response()->json($data, 200);
    }

    public function studentData($userId, $type, ProductOrderService $productOrderService)
    {
        $data = $productOrderService->generateStudentData($userId, $type);
        return response()->json($data, 200);
    }

    public function checkStatusPayment($id, PaymentBCAService $paymentBCAService, ProductOrderService $productOrderService)
    {
        $productOrder = ProductOrder::where('id', $id)->firstOrFail();
        if (isset($productOrder->ppdbUser)) {
            $customerNumber = '0508' . $productOrder->ppdbUser->register_number;
            $params = [
                'CompanyCode' => env('PAYMENT_BCA_API_CORP_ID', '54321'),
                'CustomerNumber' => $customerNumber,
            ];
            $validator = validator($params, [
                'CompanyCode' => ['string'],
                'CustomerNumber' => ['required_without:RequestID', 'string', 'max:18'],
            ], [], [
                'CompanyCode' => 'Company Code',
                'CustomerNumber' => 'Customer Number',
            ]);
            try {
                $validator->validate();
                $token = $paymentBCAService->getAuthToken();
                $result = $paymentBCAService->inquiryStatus($token->token, $params);
                if (isset($result->ErrorMessage)) {
                    return redirect()
                        ->back()
                        ->with(
                            'errors',
                            collect([
                                'Error cek status pembayaran : [' . $result->ErrorCode . '] ' . $result->ErrorMessage->Indonesian
                            ])
                        );
                } else {
                    $transactionData = $result->TransactionData;
                    if (count($transactionData) > 0) {
                        foreach ($transactionData as $transaction) {
                            foreach ($transaction->DetailBills as $bill) {
                                $status = $transaction->PaymentFlagStatus;
                                if ($bill->BillNumber == $productOrder->invoice_no && $status == 'Success') {
                                    $confirmed = $paymentBCAService->getDebug() || $productOrderService->confirmPayment($id, $productOrder->user);
                                    if ($confirmed) {
                                        return redirect()
                                            ->back()
                                            ->with('message', 'Cek status berhasil, pembelian sudah dibayar dan terkonfirmasi');
                                    }
                                }
                            }
                        }
                    }
                    return redirect()
                        ->back()
                        ->with('message', 'Cek status berhasil, belum ada pembayaran untuk invoice ' . $productOrder->invoice_no);
                }
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('errors', [
                        $e->getMessage()
                    ]);
            }
        } else {
            return redirect()
                ->back()
                ->with('errors', [
                    'Data PPDB tidak ditemukan'
                ]);
        }
    }

    public function cancelPickup($id, ProductOrderPickupService $productOrderPickupService)
    {
        $data = $productOrderPickupService->cancelPickup($id);
        return redirect(route('admin.product-order.index'))->withMessage('berhasil dibatalkan');
    }

    public function checkInquiryStatus($id, PaymentBCAService $paymentBCAService, ProductOrderService $productOrderService)
    {
        $productOrder = ProductOrder::where('id', $id)->firstOrFail();
        if (isset($productOrder->ppdbUser)) {

            $virtualAccountNo = \App\Helpers\PriceHelper::virtualAccountNumber($productOrder->ppdbUser, true, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL);
            $customerNo = substr($virtualAccountNo, 5, 16);
            $lengthString = 8 - (strlen(env('PAYMENT_BCA_API_CORP_ID')));
            $lengthVA = strlen($virtualAccountNo);

            $params = [
                'partnerServiceId' => str_pad(env('PAYMENT_BCA_API_CORP_ID', '13977'), 8, " ", STR_PAD_LEFT),
                'customerNo' => $customerNo,
                'virtualAccountNo' => str_pad($virtualAccountNo, $lengthVA + $lengthString, " ", STR_PAD_LEFT),
                'inquiryRequestId' => !empty($productOrder->payment_inquiry_id) ? $productOrder->payment_inquiry_id : '202212150953591397700040992856',
                'paymentRequestId' => !empty($productOrder->payment_inquiry_id) ? $productOrder->payment_inquiry_id : '202212150953591397700040992856',
                'additionalInfo' => (object)array(),
            ];
            $validator = validator($params, [
                'partnerServiceId' => ['string'],
                'customerNo' => ['required', 'string', 'max:18'],
                'virtualAccountNo' => ['required', 'string', 'max:18'],
            ], [], [
                'partnerServiceId' => 'Company Code',
                'customerNo' => 'Customer Number',
                'virtualAccountNo' => 'Customer Number',
            ]);

            try {
                $isoTime = date('o-m-d') . 'T' . date('H:i:s') . date('P');
                $token = $paymentBCAService->getAccessToken($isoTime);
                $result = $paymentBCAService->inquiryStatus($params, $token, $isoTime);
                if ($result->responseCode == '2002600') {
                    if ($result->virtualAccountData->paymentFlagStatus == '00') {
                        $billDetails = $result->virtualAccountData->billDetails;
                        $status = $result->virtualAccountData->paymentFlagReason->english;
                        foreach ($billDetails as $bill) {
                            if ($result->virtualAccountData->inquiryRequestId == $productOrder->payment_inquiry_id && $status == 'Success') {
                                $confirmed = $paymentBCAService->getDebug() || $productOrderService->confirmPayment($id, $productOrder->user);
                                if ($confirmed) {
                                    return redirect()
                                        ->back()
                                        ->with('message', 'Cek status berhasil, pembelian sudah dibayar dan terkonfirmasi');
                                }
                            }
                        }
                    } else {
                        $billDetails = $result->virtualAccountData->billDetails;
                        foreach ($billDetails as $bill) {
                            if ($bill->status == '01') {
                                return redirect()
                                    ->back()
                                    ->with(
                                        'errors',
                                        collect([
                                            'Error cek status pembayaran : ' . $bill->reason->indonesia
                                        ])
                                    );
                            }

                        }
                    }
                    return redirect()
                        ->back()
                        ->with('message', 'Cek status berhasil, belum ada pembayaran untuk invoice ' . $productOrder->invoice_no);
                } else {
                    return redirect()
                        ->back()
                        ->with(
                            'errors',
                            collect([
                                'Error cek status pembayaran : [' . $result->responseCode . '] ' . $result->responseMessage
                            ])
                        );
                }
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with(
                        'errors',
                        collect([
                            $e->getMessage()
                        ])
                    );
            }
        } else {
            return redirect()
                ->back()
                ->with('errors', [
                    'Data PPDB tidak ditemukan'
                ]);
        }
    }

}
