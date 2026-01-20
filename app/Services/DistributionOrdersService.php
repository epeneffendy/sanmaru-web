<?php

namespace App\Services;

use App\Models\DistributionOrderDetail;
use App\Models\DistributionOrders;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DistributionOrdersService
{
    public function get()
    {
        $distribution = DistributionOrders::get();
        return $distribution;
    }

    public function getById($id)
    {
        $distribution = DistributionOrders::find($id);
        return $distribution;
    }

    public function findOrderUniform($params)
    {
        if ($params['student'] == 'ppdb') {
            $orders = DB::table('product_orders')
                ->select('ppdb_users.name', 'units.name as unit_name', 'products.name as product_name', 'product_details.size', 'product_orders.status as payment_status', 'product_orders.pickup_status', DB::raw('sum(product_order_details.quantity) AS qty'))
                ->join('ppdb_users', 'ppdb_users.user_id', '=', 'product_orders.user_id')
                ->join('units', 'units.id', '=', 'ppdb_users.unit_id')
                ->join('product_order_details', 'product_order_details.product_order_id', '=', 'product_orders.id')
                ->join('product_details', 'product_details.id', '=', 'product_order_details.product_detail_id')
                ->join('products', 'products.id', '=', 'product_details.product_id')
                ->where('product_orders.payment_type', '=', '08');

            if (array_key_exists('unit_id', $params) && $params['unit_id']) {
                $orders->where('ppdb_users.unit_id', '=', $params['unit_id']);
            }

            if (array_key_exists('date_range', $params) && $params['date_range']) {
                $dateStart = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
                $dateEnd = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();

                $orders->where('product_orders.created_at', '>=', $dateStart)->where('product_orders.created_at', '<=', $dateEnd);
            }

            $orders = $orders->groupBy(['ppdb_users.name', 'ppdb_users.unit_id', 'products.name', 'product_details.size', 'product_orders.status', 'product_orders.pickup_status'])->get();
        }else{
            $orders = DB::table('product_orders')
                ->select('ppdb_users.name', 'units.name as unit_name', 'products.name as product_name', 'product_details.size', 'product_orders.status as payment_status', 'product_orders.pickup_status', DB::raw('sum(product_order_details.quantity) AS qty'))
                ->join('ppdb_users', 'ppdb_users.user_id', '=', 'product_orders.user_id')
                ->join('units', 'units.id', '=', 'ppdb_users.unit_id')
                ->join('product_order_details', 'product_order_details.product_order_id', '=', 'product_orders.id')
                ->join('product_details', 'product_details.id', '=', 'product_order_details.product_detail_id')
                ->join('products', 'products.id', '=', 'product_details.product_id')
                ->join('users','users.id','=','ppdb_users.user_id')
                ->where('product_orders.payment_type', '=', '08')
                ->where('users.type','=','siswa');

                if (array_key_exists('unit_id', $params) && $params['unit_id']) {
                $orders->where('ppdb_users.unit_id', '=', $params['unit_id']);
            }

            if (array_key_exists('date_range', $params) && $params['date_range']) {
                $dateStart = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
                $dateEnd = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();

                $orders->where('product_orders.created_at', '>=', $dateStart)->where('product_orders.created_at', '<=', $dateEnd);
            }

            $orders = $orders->groupBy(['ppdb_users.name', 'ppdb_users.unit_id', 'products.name', 'product_details.size', 'product_orders.status', 'product_orders.pickup_status'])->get();
        }

        return $orders;

    }

    public function create($params)
    {
        $params['created_by'] = auth()->user()->id;

        $distribution =  DistributionOrders::create($params);
        $params['student'] = $params['type_student'];
        $details = $this->findOrderUniform($params);
        if (count($details) > 0){
            foreach ($details as $item){
                $detail = new DistributionOrderDetail;
                $detail->distribution_order_id = $distribution->id;
                $detail->name = $item->name;
                $detail->product_name = $item->product_name;
                $detail->size = $item->size;
                $detail->qty = $item->qty;
                $detail->save();
            }
        }
    }

    public function send($id){
        $data = DistributionOrders::find($id);
        $data->status = DistributionOrders::STATUS_SEND;
        $data->save();
    }

    public function delete($id){
        $data = DistributionOrders::find($id);
        $data->status = DistributionOrders::STATUS_REJECTED;
        $data->save();
    }

    public function confirm($id){
        $data = DistributionOrders::find($id);
        $data->status = DistributionOrders::STATUS_CONFIRMED;
        $data->save();
    }

    public function getListOrder($id){
        $detail = DistributionOrderDetail::where('distribution_order_id','=', $id)->get();
        return $detail;
    }

}
