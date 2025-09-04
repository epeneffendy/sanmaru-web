<?php

namespace App\Listeners\PPDB;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Helpers\PriceHelper;

class SyncFinanceForm
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

            $finance = $connection->table('finance_forms')
                ->where('register_number', $event->ppdb->register_number)
                ->first();

            $nominal = PriceHelper::registration($event->ppdb);
            $paymentMethod = PriceHelper::virtualAccountNumber($event->ppdb, false, $event->ppdb->unit->payment_option);

            if ($finance == null) {
                $connection->table('finance_forms')
                    ->insert([
                        'unit' => $event->ppdb->unit->name,
                        'student_name' => $event->ppdb->name,
                        'register_number' => $event->ppdb->register_number,
                        'nominal_form' => $nominal,
                        'payment_status' => 'paid',
                        'payment_method' => $paymentMethod,
                        'payment_date' => $event->paymentDate,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                $connection->table('finance_forms')
                    ->where('id', $finance->id)
                    ->update([
                        'unit' => $event->ppdb->unit->name,
                        'student_name' => $event->ppdb->name,
                        'nominal_form' => $nominal,
                        'payment_status' => 'paid',
                        'payment_method' => $paymentMethod,
                        'payment_date' => $event->paymentDate,
                        'updated_at' => now()
                    ]);
            }

        });
    }
}
