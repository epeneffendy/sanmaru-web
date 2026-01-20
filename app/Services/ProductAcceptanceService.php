<?php

namespace App\Services;

use App\Models\ProductAcceptance;
use App\Models\ProductAcceptanceDetail;
use App\Models\ProductDetail;

class ProductAcceptanceService
{
    public function get()
    {
        $data = ProductAcceptance::get();
        return $data;
    }

    public function insert($params)
    {

        $payload = [
            'product_id' => $params['product_id'],
            'vendor_id' => $params['vendor_id'],
            'date' => (empty($params['date'])) ? date('Y-m-d') : $params['date'],
            'description' => $params['description'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id
        ];
        $productAcceptance = ProductAcceptance::create($payload);

        foreach ($params['id'] as $ind => $item) {
            $payloadDetail = [
                'product_acceptance_id' => $productAcceptance->id,
                'product_detail_id'=>$ind,
                'size' => $params['size'][$ind][0],
                'stock' => $params['stock'][$ind][0],
                'price_siswa' => $params['price_siswa'][$ind][0],
                'price_ppdb' => $params['price_ppdb'][$ind][0],
                'price_vendor_regular' => $params['price_vendor_regular'][$ind][0],
                'price_vendor_ppdb' => $params['price_vendor_ppdb'][$ind][0]
            ];
            $productAcceptanceDetail = ProductAcceptanceDetail::create($payloadDetail);

            $productDetail = ProductDetail::where('id',$ind)->first();

            $productDetail->stock = $productDetail->stock + $payloadDetail['stock'];
            $productDetail->save();
        }


    }

    public function getById($id)
    {
        $data = ProductAcceptance::whereId($id)->first();
        return $data;
    }
}
