<?php
namespace App\Http\Controllers\WebKantin;

use App\Enums\ProductOrderPaymentTypeEnum AS PaymentTypeEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use App\Traits\ImageHandler;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderHistoryController extends Controller
{
    use ImageHandler;

    function __construct()
    {
        $this->middleware('siswa');
    }

    public function index(Request $request)
    {
        $user = auth('siswa')->user();

        $hisoryProductOrders = ProductOrder::with([
            'productOrderDetails',
            'productOrderDetails.product',
            'productOrderDetails.productDetail',
        ])
            ->where('payment_type', PaymentTypeEnum::KANTIN)
            ->where(function ($q) {
                $q->whereIn('status', [
                    ProductOrder::STATUS_DONE,
                    ProductOrder::STATUS_CANCEL,
                ])->orWhere(function ($q2) {
                    $q2->pickup();
                });
            })
            ->orderBy('created_at', 'desc')
            ->whereUserId($user->id);

        $productOrders = ProductOrder::with([
            'productOrderDetails',
            'productOrderDetails.product',
            'productOrderDetails.productDetail',
        ])
            ->where('payment_type', PaymentTypeEnum::KANTIN)
            ->where(function ($q) {
                $q->whereNull('payment_image');
                $q->orWhere(function ($q2) {
                    $q2->where('status', '!=', ProductOrder::STATUS_CONFIRMED)
                        ->whereNotNull('payment_image');
                });
                $q->orWhere(function ($q2) {
                    $q2->where('status', '==', ProductOrder::STATUS_CONFIRMED)
                        ->whereNull('pickup_date')
                        ->whereNull('pickup_date_schedule');
                });
                $q->orWhere(function ($q2) {
                    $q2->where('status', '==', ProductOrder::STATUS_CONFIRMED)
                        ->whereNull('pickup_date')
                        ->whereNotNull('pickup_date_schedule');
                });
                $q->orWhere(function ($q2) {
                    $q2->where('pickup_status', '!=', ProductOrder::PICKUP_STATUS_PICKUP);
                });
            })
            ->orderBy('created_at', 'desc')
            ->whereUserId($user->id);

        if ($request->get('keyword')) {
            $keyword = $request->get('keyword');
            $hisoryProductOrders->where(function ($q) use ($keyword) {
                $q->where('invoice_no', 'like', '%'. $keyword .'%');
            });

            $productOrders->where(function ($q) use ($keyword) {
                $q->where('invoice_no', 'like', '%'. $keyword .'%');
            });
        }

        $hisoryProductOrders = $hisoryProductOrders->get();

        if ($hisoryProductOrders->count() > 0) {
            $productOrders->whereNotIn('id', $hisoryProductOrders->pluck('id')->toArray());
        }
        $productOrders = $productOrders->get();

        return view('webkantin.history', [
            'hisoryProductOrders' => $hisoryProductOrders,
            'productOrders' => $productOrders,
        ]);
    }

    public function show(Request $request, $id = '')
    {
        try {
            $user = auth('siswa')->user();

            $productOrder = ProductOrder::where([
                'id' => $id,
                'user_id' => $user->id
            ])->firstOrFail();
            if ($productOrder) {
                $qris = QrCode::size(200)->generate(route('admin.product-order-pickup.qr-result', $productOrder->id));
                return response()->json([
                    'payment_image' => $productOrder->getPaymentImageUrl(),
                    'qris' => $qris->toHtml(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([], Response::HTTP_NOT_FOUND);
    }

    public function upload_file(Request $request)
    {
        $data = [];
        try {
            if ($request->hasFile('payment_image') && $upload = $this->doUploadImage($request->file('payment_image'), 'payment_image')) {
                ProductOrder::whereId($request->get('id'))->update([
                    'payment_image' => $upload['path_upload']
                ]);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json($data, 200);
    }

    public function showPdf(Request $request, $id)
    {
        $user = auth('siswa')->user();

        $productOrder = ProductOrder::where([
            'id' => $id,
            'user_id' => $user->id
        ])->with('user', 'productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $data = [
            'productOrder' => $productOrder,
        ];

        $pdf = \PDF::loadView('webkantin.order_pdf', $data);
        return $pdf->download("detail-transaksi-$productOrder->invoice_no.pdf");
    }
}
