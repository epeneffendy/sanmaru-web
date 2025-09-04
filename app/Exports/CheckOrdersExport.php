<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\PPDBUser;
use App\Models\ProductOrder;

class CheckOrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $collections = null;

    public function __construct($params)
    {
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

        $ppdbUsers = $ppdbUsers->orderBy('id', 'desc');


        $this->collections = $ppdbUsers->get();
    }

    public function collection()
    {
        return $this->collections;
    }

    public function map($ppdbUser) : array
    {
        return [
            $ppdbUser->register_number,
            $ppdbUser->name,
            @$ppdbUser->unit->name,
            @$ppdbUser->text_order_status,
            @$ppdbUser->orders->last()->konfirmasi_pembayaran,
        ];
    }


    public function headings(): array
    {
        return [
            'NO PENDAFTARAN',
            'NAMA ANAK',
            'UNIT',
            'ORDER STATUS',
            'STATUS PESANAN',
        ];
    }
}
