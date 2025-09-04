<?php

namespace App\Listeners\PPDB;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Helpers\PriceHelper;

class SyncFinanceActivity
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
    public function handle($event)
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
            }

            $activity = $connection->table('finance_activities')
                ->where('student_type', 'ppdb')
                ->where('student_id', $event->ppdb->id)
                ->first();

            $nominal = PriceHelper::activity($event->ppdb);

            if ($activity == null) {
                $insertId = $connection->table('finance_activities')
                    ->insertGetId([
                        'student_id' => $event->ppdb->id,
                        'student_type' => 'ppdb',
                        'amount' => $nominal,
                        'number' => $this->getLastCode($connection, 'KK01'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                $activity = $connection->table('finance_activities')
                    ->where('id', $insertId)->first();
            } else {
                if ($activity->amount <> $nominal) {
                    $connection->table('finance_activities')
                        ->where('id', $activity->id)
                        ->update([
                            'amount' => $nominal,
                            'updated_at' => now()
                        ]);
                }
            }

            $arr_details = [
                [
                    'finance_activity_id' => $activity->id,
                    'nominal' => $nominal,
                    'payment_date' => null,
                    'payment_method' => null,
                    'number' => $activity->number,
                    'debet' => $nominal,
                    'credit' => 0,
                    'description' => "Nominal keuangan kegiatan: " . $event->ppdb->register_number . " a/n " . $event->ppdb->name,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            $objDetails = $connection->table('finance_activity_details')
                ->where('finance_activity_id', $activity->id)
                ->where('number', 'like', 'KK01%')
                ->get();

            $objDetails = collect(json_decode(json_encode($objDetails), TRUE));
            $arr_details = collect($arr_details);
            $total_1 = $objDetails->sum('debet') - $objDetails->sum('credit');
            $total_2 = $arr_details->sum('debet') - $objDetails->sum('credit');

            if (count($objDetails) <> count($arr_details) || ($total_1 <> $total_2)) {
                $connection->table('finance_activity_details')
                ->where('finance_activity_id', $activity->id)
                ->where('number', 'like', 'KK01%')
                ->delete();

                foreach ($arr_details as $detail) {
                    $connection->table('finance_activity_details')
                        ->insert($detail);
                }
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
