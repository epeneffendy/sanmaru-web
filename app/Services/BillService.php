<?php

namespace App\Services;

use App\Models\BillCategory;
use App\Models\BillUser;

class BillService
{
    public function listCategories()
    {
        return BillCategory::select('id', 'name')->get();
    }

    public function countCategories()
    {
        return BillCategory::count();
    }

    public function getBillUser(int $userId, int $billId)
    {
        return BillUser::where('user_id', $userId)->where('bill_id', $billId)
            ->select(
                'bill_id as id',
                'bill_name as name',
                'bill_due_date as due_date',
                'bill_amount as amount',
                'status'
            )->firstOrFail();
    }

    public function listUsersBills(int $userId, int $categoryId, $offset, $limit)
    {
        $usersbills = BillUser::withUserAndCategory($userId, $categoryId);
        if (isset($offset)) {
            $usersbills->offset($offset);
        }
        if (isset($limit)) {
            $usersbills->limit($limit);
        }
        return $usersbills
            ->select(
                'id',
                'bill_id',
                'bill_name as name',
                'bill_due_date as due_date',
                'bill_amount as amount',
                'status'
            )
            ->get();
    }

    public function countUsersBills(int $userId, int $categoryId)
    {
        return BillUser::withUserAndCategory($userId, $categoryId)->count();
    }
}
