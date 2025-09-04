<?php

namespace App\Listeners\PPDB;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Helpers\PriceHelper;

class SyncFinanceUniform
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

            $finance = $connection->table('finance_uniforms')
                    ->where('register_number', $event->ppdb->register_number)
                    ->where('student_id', $event->ppdb->id)
                    ->where('student_type', 'ppdb')
                    ->first();

            if ($finance == null) {
                $finance = $connection->table('finance_uniforms')
                    ->where('register_number', $event->ppdb->register_number)
                    ->whereNull('student_id')
                    ->whereNull('student_type')
                    ->first();
            }
            
            $paymentMethod = PriceHelper::virtualAccountNumber($event->ppdb, true, $event->ppdb->unit->payment_option);
            
            if ($finance == null) {
                $connection->table('finance_uniforms')
                    ->insert([
                        'unit' => $event->ppdb->unit->name,
                        'student_id' => $event->ppdb->id,
                        'student_type' => 'ppdb',
                        'student_name' => $event->ppdb->name,
                        'register_number' => $event->ppdb->register_number,
                        'nominal' => $event->order->grand_total,
                        'payment_status' => 'paid',
                        'payment_method' => $paymentMethod,
                        'payment_date' => $event->paymentDate,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                $connection->table('finance_uniforms')
                    ->where('id', $finance->id)
                    ->update([
                        'unit' => $event->ppdb->unit->name,
                        'student_id' => $event->ppdb->id,
                        'student_type' => 'ppdb',
                        'student_name' => $event->ppdb->name,
                        'nominal' => $event->order->grand_total,
                        'payment_status' => 'paid',
                        'payment_method' => $paymentMethod,
                        'payment_date' => $event->paymentDate,
                        'updated_at' => now()
                    ]);
            }

        });
    }
}
