<?php

namespace App\Services;

use Request;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\PPDBUser;
use App\Models\ProductOrder;
use App\Traits\ImageHandler;
use FontLib\Table\Type\name;
use App\Enums\ProductTypeEnum;
use App\Mail\NotificationEmail;
use App\Mail\OrderPickupConfirmed;
use App\Mail\OrderPickupNotification;
use App\Notifications\PPDBNotification;
use App\Enums\ProductOrderPaymentTypeEnum;
use App\Notifications\StudentNotification;

class ProductOrderPickupService
{
	use ImageHandler;

    public function filter(array $params, int $paginate_limit = null, array $related = null)
    {
        $collections = collect();
        $productOrders = ProductOrder::query();
        if (array_key_exists('product_order', $params) && $params['product_order']) {
            $productOrders->where('id', $params['product_order']);
        }
        if (array_key_exists('student_name', $params) && $params['student_name']) {
            $productOrders->whereHas('user.student', function ($query) use ($params) {
                $query->where('name', 'like', "%$params[student_name]%");
            })->orWhereHas('user.ppdb', function ($query) use ($params) {
                $query->where('name', 'like', "%$params[student_name]%");
            });
        }
        if (array_key_exists('payment_mail_confirmation', $params) && $params['payment_mail_confirmation']) {
            switch ($params['payment_mail_confirmation']) {
                case 'sent':
                    $productOrders->where('payment_confirmed_mail_sent', true);
                    break;
                case 'unsent':
                    $productOrders->where('payment_confirmed_mail_sent', false)->orWhereNull('payment_confirmed_mail_sent');
                    break;
                default:
                    break;
            }
        }
        if (array_key_exists('pickup_status', $params) && $params['pickup_status']) {
            switch ($params['pickup_status']) {
                case 'not_scheduled':
                    $productOrders->whereNull('pickup_date_schedule')->where('pickup_status', ProductOrder::PICKUP_STATUS_NOT_PICKUP);
                    break;
                case 'scheduled':
                    $productOrders->whereNotNull('pickup_date_schedule');
                    break;
                case 'not_picked_up':
                    $productOrders->where('pickup_status', ProductOrder::PICKUP_STATUS_NOT_PICKUP);
                    break;
                case 'picked_up':
                    $productOrders->pickup();
                    break;
                default:
                    break;
            }
        }
        if (array_key_exists('year', $params) && $params['year']) {
            $year = substr($params['year'], 2, 2);
            $productOrders->where('invoice_no', 'like', $year . '%');
        }
        if (array_key_exists('period', $params) && $params['period']) {
            $productOrders->whereHas('user.ppdb', function ($query) use ($params) {
                $query->where('periode', $params['period']);
            });
        } elseif (array_key_exists('periods', $params) && $params['periods']) {
            $productOrders->whereHas('user.ppdb', function ($query) use ($params) {
                $query->whereIn('periode', $params['periods']);
            });
        }
        if (array_key_exists('unit', $params) && $params['unit']) {
            $productOrders->whereHas('user.ppdb', function ($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            })->orWhereHas('user.student.class', function ($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            });
        }
        if ($related) {
            $productOrders->with($related);
        }
        if (array_key_exists('type', $params) && $params['type']) {
            if ($params['type'] == ProductTypeEnum::KANTIN) {
                $productOrders->where('payment_type', ProductOrderPaymentTypeEnum::KANTIN);
            } elseif ($params['type'] == ProductTypeEnum::SERAGAM) {
                $productOrders->where('payment_type', ProductOrderPaymentTypeEnum::SERAGAM)->orWhereNull('payment_type'); //some data payment type is null
            }
        }

        // $productOrders->orderBy('updated_at', 'desc');

        if ($paginate_limit) {
            return $productOrders->latest()->paymentConfirmed()->paginate($paginate_limit);
        } else {
            return $productOrders->paymentConfirmed()->get();
        }

        return $collections;

    }

    public function schedule(array $params)
    {
        // Add pickup status as not picked up, so it's not rescheduling picked up orders
        $params['pickup_status'] = 'not_picked_up';
        $productOrders = $this->filter($params);

        $value = [
            'pickup_date_schedule' => $params['pickup_date_schedule'],
            'alt_pickup_date_schedule' => $params['alt_pickup_date_schedule'],
            'pickup_start_time' => $params['pickup_start_time'],
            'pickup_end_time' => $params['pickup_end_time'],
            'pickup_location' => $params['pickup_location'],
            'pickup_notes' => $params['pickup_notes'] ?? null,
        ];
        ProductOrder::whereIn('id', $productOrders->pluck('id'))->update($value);
        if (array_key_exists('send_email', $params) && $params['send_email']) {
            $emailService = new EmailService();
            foreach ($productOrders as $productOrder) {
                $template = (new OrderPickupNotification($productOrder));
                $emailService->sendMail($template, $productOrder->user->email);
            }
        }
        // Blind success count, it suppossed to count the success rows
        return $productOrders->count();
    }

    public function resetSchedule($id, $params)
    {
        $productOrder = ProductOrder::where(function ($q) {
            return $q->whereHas('user.ppdb.unit', function ($query) {
                $query->byUserRole();
            })->orWhereHas('user.student.class.unit', function ($query) {
                $query->byUserRole();
            });
        })->where('id', $id)
        ->firstOrFail();

        $value = [
            'pickup_date_schedule' => null,
            'alt_pickup_date_schedule' => null,
            'pickup_start_time' => null,
            'pickup_end_time' => null,
            'pickup_location' => null,
            'pickup_notes' => null,
        ];

        $productOrder->update($value);

        // Create notification
        $user = $productOrder->user;
        $notification = NULL;
        $params['title'] = "[RESET] Jadwal Pengambilan Seragam " . $user->name;
        if ($user->type == User::STUDENT) {
            $user->student->notify(new StudentNotification($params));
            $notification = $user->student->unreadNotifications->first();
        } elseif ($user->type == User::PPDB) {
            $user->ppdb->notify(new PPDBNotification($params));
            $notification = $user->ppdb->unreadNotifications->first();
        } else {
            // notify user
        }

        if (array_key_exists('send_email', $params) && $params['send_email']) {
            $emailService = new EmailService();
            $template = (new NotificationEmail($user, $notification));
            $emailService->sendMail($template, $user->email);

            $notification->sended_email = Carbon::now();
            $notification->save();
        }

        return true;
    }

    public function getAvailableYears()
    {
        return ProductOrder::paymentConfirmed()->distinct()->selectRaw('CONCAT("20", SUBSTRING(invoice_no, 1, 2)) as year')->orderBy('year')->get();
    }

	public function generateIndexData($nav)
	{
		$orders = ProductOrder::where(function ($query) {
            $query->paymentConfirmed()->orWhere(function ($q) {
                $q->pickup();
            });
        })->with([
            'user',
            'user.student',
            'user.student.class',
            'user.student.class.unit',
            'user.ppdb',
            'user.ppdb.unit',
            'productOrderDetails',
            'productOrderDetails.productDetail',
        ]);

        if (Request::input('name')) {
            $orders = $orders->where(function($query) {
                return $query->whereHas('user.student', function($q) {
                    return $q->where('name', 'like', '%'. Request::input('student_name') . '%');
                })->orWhereHas('user.ppdb', function($q) {
                    return $q->where('name', 'like', '%'. Request::input('student_name') . '%');
                });
            });
        }

        if (Request::input('payment_mail_confirmation') == 'unsent') {
            $orders = $orders->where(function($query) {
                $query->where('payment_confirmed_mail_sent', false)->orWhereNull('payment_confirmed_mail_sent');
            });
        }

        if (Request::input('payment_mail_confirmation') == 'sent') {
            $orders = $orders->where('payment_confirmed_mail_sent', true);
        }

        $orders = $orders->where(function ($query) {
            $query->whereHas('user.ppdb.unit', function($q) {
                $q->byUserRole();
                if (Request::input('unit')) {
                    $q->where('id', Request::input('unit'));
                }
            })->orWhereHas('user.student.class.unit', function ($q) {
                if (Request::input('unit')) {
                    $q->where('id', Request::input('unit'));
                }
            });
        })->orderBy('created_at', 'desc')->paginate();

        return [
            'nav' => $nav,
            'units' => Unit::byUserRole()->get(),
            'product_orders' => $orders,
            'params' => Request::only(['name', 'unit', 'page', 'payment_mail_confirmation'])
        ];
	}

	public function generateShowingData($id, $nav)
	{
		$productOrder = ProductOrder::where('id', $id)->with('productOrderDetails', 'productOrderDetails.product', 'productOrderDetails.productDetail')->firstOrFail();
        return [
            'productOrder' => $productOrder,
            'nav' => $nav
        ];
	}

	public function uploadPickupImage($id, $params)
	{
		$productOrder = ProductOrder::where(function ($query){
								$query->paymentConfirmed()
									->orWhere(function ($q){
										$q->pickup();
									});
							})
							->where('id', $id)
							->firstOrFail();

		$params['current_pickup_image'] = $productOrder->pickup_image;
		$params = $this->params($params);
		$productOrder->fill($params);
		return $productOrder->save();
	}

	public function params($params)
	{
		if (isset($params['pickup_image'])) {
            if ($params['pickup_image'] && $image = $this->uploadImage(request(), $params)) {
                if (isset($params['current_pickup_image']) && $this->imageExists($params['current_pickup_image'])) {
                    $this->deleteImage($params['current_pickup_image']);
                }
                $params['pickup_image'] = $image;
            }
        } else {
            if (isset($params['current_pickup_image']) && $this->imageExists($params['current_pickup_image'])) {
                $params['pickup_image'] = $params['current_pickup_image'];
            }
        }

        return $params;
	}

	private function uploadImage($request, $params)
    {
        if ($request->hasFile('pickup_image')) {
            $type = 'pickup_image';
            if ($upload = $this->doUploadImage($request->file('pickup_image'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    public function sendPickupConfirmedEmail($id)
    {
        $order = ProductOrder::pickup()->where('id', $id)
                ->with('productOrderDetails')
                ->first();

        if (!$order) {
            return false;
        }

        $user = $order->user;

        $emailService = new EmailService();
        $template = (new OrderPickupConfirmed($order));

        if (isset($emailService)) {
            $emailService->sendMail($template, $user['email']);
        }
    }

    public function cancelPickup($id)
    {
        $productOrder = ProductOrder::pickup()->where('id', $id)->firstOrFail();
        $productOrder->fill([
            'pickup_status' => ProductOrder::PICKUP_STATUS_NOT_PICKUP,
            'pickup_date' => null
        ]);

        return $productOrder->save();
    }
}
