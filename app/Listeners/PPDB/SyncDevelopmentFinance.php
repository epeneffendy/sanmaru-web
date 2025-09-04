<?php

namespace App\Listeners\PPDB;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Events\PPDB\DevelopmentStatementConfirmed;
use App\Helpers\PriceHelper;
use Carbon\Carbon;

class SyncDevelopmentFinance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DevelopmentStatementConfirmed $event)
    {
        DB::connection('mysql_erp')->transaction(function ($connection) use ($event) {
            $data = $connection->table('ppdb_users')
                ->where('id', $event->ppdb->id)
                ->first();

            if ($data == null) {
                $connection->table('ppdb_users')
                    ->insert([
                        'id' => $event->ppdb->id,
                        'name' => $event->ppdb->name,
                        'register_number' => $event->ppdb->register_number,
                        'unit' => $event->ppdb->unit->name,
                        'development_statement' => $event->ppdb->development_statement,
                        'school_year' => $event->ppdb->school_year,
                        'periode' => $event->ppdb->periode,
                        'user_id' => $event->ppdb->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            } else {
                $connection->table('ppdb_users')
                    ->where('id', $event->ppdb->id)
                    ->update([
                        'name' => $event->ppdb->name,
                        'register_number' => $event->ppdb->register_number,
                        'unit' => $event->ppdb->unit->name,
                        'development_statement' => $event->ppdb->development_statement,
                        'school_year' => $event->ppdb->school_year,
                        'periode' => $event->ppdb->periode,
                        'user_id' => $event->ppdb->user_id,
                        'updated_at' => now(),
                    ]);
            }

            $development = $connection->table('ppdb_finance_developments')
                ->where('ppdb_user_id', $event->ppdb->id)
                ->first();

            if ($development == null || ($development && $development->number == null)) {
                $number = $this->getLastCode($connection, 'KG01');
            } else {
                $number = $development->number;
            }

            $nominal = PriceHelper::development($event->ppdb);

            $arr = [
                'ppdb_user_id' => $event->ppdb->id,
                'nominal' => $nominal,
                'number' => $number,
            ];

            if ($event->ppdb->development_fee_option == 'cicilan') {
                $arr['period_start'] = $event->ppdb->angsuran_1;
                $arr['period_closed'] = $event->ppdb->angsuran_5;
                $arr['installment_period'] = 5;
            }

            if ($development == null) {
                $arr['created_at'] = now();
                $arr['updated_at'] = now();
                $insertId = $connection->table('ppdb_finance_developments')
                    ->insertGetId($arr);

                $development = $connection->table('ppdb_finance_developments')
                    ->where('id', $insertId)->first();
                    
            } else {
                $arr['updated_at'] = now();
                $connection->table('ppdb_finance_developments')
                    ->where('id', $development->id)
                    ->update($arr);
            }

            $arr_details = [
                [
                    'ppdb_finance_development_id' => $development->id,
                    'nominal' => $nominal,
                    'debet' => $nominal,
                    'credit' => 0,
                    'payment_date' => null,
                    'payment_method' => null,
                    'number' => $number,
                    'description' => "Nominal keuangan gedung registrasi: " . $event->ppdb->register_number . " a/n " . $event->ppdb->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            if (PriceHelper::getDevelopmentDiscountStatus($event->ppdb) && $event->ppdb->development_fee_option === 'lunas') {
                $arr_details[] = [
                    'ppdb_finance_development_id' => $development->id,
                    'nominal' => 0.05 * $nominal,
                    'debet' => 0,
                    'credit' => 0.05 * $nominal,
                    'payment_date' => null,
                    'payment_method' => null,
                    'number' => $number,
                    'description' => "Diskon keuangan gedung pembayaran lunas 5% registrasi: " . $event->ppdb->register_number." a/n " . $event->ppdb->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // sync detail register
            $objDetails = $connection->table('ppdb_finance_development_details')
                ->where('ppdb_finance_development_id', $development->id)
                ->where('number', 'like', 'KG01%')
                ->get();

            $objDetails = collect(json_decode(json_encode($objDetails), TRUE));
            $arr_details = collect($arr_details);
            $total_1 = $objDetails->sum('debet') - $objDetails->sum('credit');
            $total_2 = $arr_details->sum('debet') - $arr_details->sum('credit');

            if (count($objDetails) <> count($arr_details) || ($total_1 <> $total_2)) {
                $connection->table('ppdb_finance_development_details')
                ->where('ppdb_finance_development_id', $development->id)
                ->where('number', 'like', 'KG01%')
                ->delete();

                foreach ($arr_details as $detail) {
                    $connection->table('ppdb_finance_development_details')
                        ->insert($detail);
                }
            } 

            // sync detail payment
            $objDetails = $connection->table('ppdb_finance_development_details')
                ->where('ppdb_finance_development_id', $development->id)
                ->where(function ($query) {
                    return $query->whereNull('number')
                        ->orWhere('number', 'NOT LIKE', 'KG01%');
                })
                ->where('credit', '<=', 0)
                ->where('debet', '<=', 0)
                ->get();

            foreach ($objDetails as $detail) {
                $number = $this->getLastCode($connection, 'KG02');

                $connection->table('ppdb_finance_development_details')
                    ->insert([
                        'ppdb_finance_development_id' => $detail->ppdb_finance_development_id,
                        'nominal' => $detail->nominal,
                        'payment_date' => $detail->payment_date,
                        'payment_method' => $detail->payment_method,
                        'created_at' => $detail->created_at,
                        'updated_at' => $detail->updated_at,
                        'number' => $number,
                        'credit' => $detail->nominal,
                        'description' => 'Pembayaran keuangan gedung register ' . $event->ppdb->register_number . ' a/n ' . $event->ppdb->name,
                    ]);

                $connection->table('ppdb_finance_development_details')
                    ->where('id', $detail->id)
                    ->delete();
            }

        });
    }

    private function getLastCode($connection, $key)
    {
        $key = $key . now()->format('Ymd');
        $objNumber = $connection->table('code_number')
            ->select('code', 'id')->where('code', '=', $key)->first();

        if ($objNumber) {
            $id = $objNumber->id + 1;
        } else {
            $id = 1;
        }

        $connection->table('code_number')->insert(['code' => $key, 'id' => $id]);
        $connection->table('code_number')->where('code', '=', $key)
            ->where('id', '<', $id)->delete();

        $seq = str_pad($id, 6, "0", STR_PAD_LEFT);
        $number = $key . $seq;

        return $number;
    }
}
