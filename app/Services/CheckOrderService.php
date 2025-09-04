<?php

namespace App\Services;

use App\Models\PPDBUser;
use App\Models\ProductOrder;
use App\Models\Unit;

class CheckOrderService 
{

    public function generateDashboardData($nav)
    {
        $units = Unit::select('id', 'name', 'unit_code')
                        ->with('userOrders')
                        ->orderBy('unit_code', 'ASC')
                        ->get();

        return [
            'nav' => $nav,
            'data' => $units
        ];
        
    }

	public function generateIndexData($params, $nav)
	{
        $listOrder = $this->getListOrder();

        $ppdbUsers = PPDBUser::notAccepted();

        if (isset($params['name']) && $params['name']) {
        	$name = trim($params['name']);
            $ppdbUsers = $ppdbUsers->where('name', 'like', '%' . $name . '%');
        }
        if (isset($params['unit']) && $params['unit']) {
        	$unit = trim($params['unit']);
            $ppdbUsers = $ppdbUsers->where('unit_id', $unit);
        }
        if (isset($params['order_status']) && $params['order_status'] && is_array($params['order_status'])) {

            $orderStatusLength = count($params['order_status']);

            if ($orderStatusLength <= 1) {
                switch ($params['order_status'][0]) {
                    case PPDBUser::ORDER_STATUS_ORDERED :
                        $ppdbUsers = $ppdbUsers->whereHas('orders')->with(['unit','orders', 'orders.user']);
                        break;
                    case PPDBUser::ORDER_STATUS_NOT_ORDERED :
                        $ppdbUsers = $ppdbUsers->doesntHave('orders')->with(['unit','orders', 'orders.user']);
                        break;
                    case ProductOrder::PAYMENT_STATUS_NOT_CONFIRMED :
                        $ppdbUsers = $ppdbUsers->withAndWhereHas('orders', function ($query) {
                            return $query->paymentNotConfirmed();
                        })->with(['unit']);
                        break;
                    case ProductOrder::PAYMENT_STATUS_UPLOADED :
                        $ppdbUsers = $ppdbUsers->withAndWhereHas('orders', function ($query) {
                            return $query->paymentUploaded();
                        })->with(['unit']);
                        break;
                    case ProductOrder::PAYMENT_STATUS_CONFIRMED : 
                        $ppdbUsers = $ppdbUsers->withAndWhereHas('orders', function ($query) {
                            return $query->paymentConfirmed();
                        })->with(['unit']);
                        break;
                    default :
                        $ppdbUsers = $ppdbUsers->with(['unit', 'orders', 'orders.user']);
                        break;
    
                }
            } else {
                if (in_array(PPDBUser::ORDER_STATUS_NOT_ORDERED, $params['order_status'])) {
                    $ppdbUsers = $ppdbUsers->with(['orders' => function ($query) use ($params) {
                        if (in_array(ProductOrder::PAYMENT_STATUS_NOT_CONFIRMED, $params['order_status'])) {
                            if ($params['order_status'][1] === ProductOrder::PAYMENT_STATUS_NOT_CONFIRMED) {
                                $query = $query->where(function ($q) {
                                    $q->paymentNotConfirmed();
                                });
                            } else {
                                $query = $query->orWhere(function ($q) {
                                    $q->paymentNotConfirmed();
                                });
                            }
                        }
                        if (in_array(ProductOrder::PAYMENT_STATUS_UPLOADED, $params['order_status'])) {
                            if ($params['order_status'][1] === ProductOrder::PAYMENT_STATUS_UPLOADED) {
                                $query = $query->where(function ($q) {
                                    $q->paymentUploaded();
                                });
                            } else {
                                $query = $query->orWhere(function ($q) {
                                    $q->paymentUploaded();
                                });
                            }
                        }
                        if (in_array(ProductOrder::PAYMENT_STATUS_CONFIRMED, $params['order_status'])) {
                            if ($params['order_status'][1] === ProductOrder::PAYMENT_STATUS_CONFIRMED) {
                                $query = $query->where(function ($q) {
                                    $q->paymentConfirmed();
                                });
                            } else {
                                $query = $query->orWhere(function ($q) {
                                    $q->paymentConfirmed();
                                });
                            }
                        }
                        return $query;
                    }, 'unit', 'orders.user']);
                } else {
                    $ppdbUsers = $ppdbUsers->withAndWhereHas('orders', function ($query) use ($params) {
                        if (in_array(PPDBUser::ORDER_STATUS_ORDERED, $params['order_status'])) {
                            if ($params['order_status'][0] === PPDBUser::ORDER_STATUS_ORDERED) {
                                $query = $query->where(function ($q) {
                                    $q->notCanceled();
                                });
                            } else {
                                $query = $query->orWhere(function ($q) {
                                    $q->notCanceled();
                                });
                            }
                        } 
                        if (in_array(ProductOrder::PAYMENT_STATUS_NOT_CONFIRMED, $params['order_status'])) {
                            if ($params['order_status'][0] === ProductOrder::PAYMENT_STATUS_NOT_CONFIRMED) {
                                $query = $query->where(function ($q) {
                                    $q->paymentNotConfirmed();
                                });
                            } else {
                                $query = $query->orWhere(function ($q) {
                                    $q->paymentNotConfirmed();
                                });
                            }
                        } 
                        if (in_array(ProductOrder::PAYMENT_STATUS_UPLOADED, $params['order_status'])) {
                            if ($params['order_status'][0] === ProductOrder::PAYMENT_STATUS_UPLOADED) {
                                $query = $query->where(function ($q) {
                                    $q->paymentUploaded();
                                });
                            } else {
                                $query = $query->orWhere(function ($q) {
                                    $q->paymentUploaded();
                                });
                            }
                        }
                        if (in_array(ProductOrder::PAYMENT_STATUS_CONFIRMED, $params['order_status'])) {
                            if ($params['order_status'][0] === ProductOrder::PAYMENT_STATUS_CONFIRMED) {
                                $query = $query->where(function ($q) {
                                    $q->paymentUploaded();
                                });
                            } else {
                                $query = $query->orWhere(function ($q) {
                                    $q->paymentConfirmed();
                                });
                            }
                        }
                        return $query;
                    })->with(['unit']);
                }
            }
        } else {
            $ppdbUsers = $ppdbUsers->with(['unit','orders']);
        }

        $ppdbUsers = $ppdbUsers->byUserRole()->orderBy('id', 'desc')->paginate();        

        return [
            'nav' => $nav,
			'units' => Unit::byUserRole()->get(),
            'data' => $ppdbUsers,
            'orderStatus' => $listOrder,
            'params' => $params,
        ];
	}

	public function getListOrder()
	{
		$listOrderStatus = (new PPDBUser())->listOrderStatus();
        $listPaymentStatus = (new ProductOrder())->listPaymentStatus();

        $listOrder = array_merge($listOrderStatus, $listPaymentStatus);
        /*
			Belum pesan
			Sudah pesan
			Belum bayar
			Sudah bayar, belum konfirmasi
			Sudah bayar, terkonfirmasi
        */

		$listOrder = array_map(function ($item) {
			if ($item['value'] === PPDBUser::ORDER_STATUS_ORDERED) {
				$item['name'] = 'Sudah pesan';
			} else if ($item['value'] === PPDBUser::ORDER_STATUS_NOT_ORDERED) {
				$item['name'] = 'Belum pesan';
			} else if ($item['value'] === ProductOrder::PAYMENT_STATUS_CONFIRMED) {
				$item['name'] = 'Sudah bayar, terkonfirmasi';
			} else if ($item['value'] === ProductOrder::PAYMENT_STATUS_UPLOADED) {
				$item['name'] = 'Sudah bayar, belum konfirmasi';
			} else if ($item['value'] === ProductOrder::PAYMENT_STATUS_NOT_CONFIRMED) {
				$item['name'] = 'Belum bayar';
			}
			return $item;
		}, $listOrder);

		return $listOrder;
	}
}