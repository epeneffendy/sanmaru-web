<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TaylorNetwork\UsernameGenerator\Generator;
use Config;

class SyncUserEmployeeFromERP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:user-employee-from-erp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize employee user data from sanmaru-erp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Config::get('database.connections.mysql_erp')) {
            $employees = DB::connection('mysql_erp')->table('employees')
                ->select('employees.fullname', 'employees.phone', 'employees.email', 'units.sanmaru_unit_id')
                ->whereNull('employees.deleted_at')
                ->whereNull('employees.user_id')
                ->whereNotNull('email')
                ->whereNotNull('phone')
                ->leftJoin('units', 'units.id', '=', 'employees.unit_id')
                ->get();

            foreach ($employees as $employee) {
                $register = $this->createUser($employee);
                $this->info($register);
            }
        }
    }

    private function createUser($employee)
    {
        $message = "";

        try {
            DB::beginTransaction();
            $data = $this->generateUserData($employee);
            $user = new User($data);
            $user->save();
            $user->refresh();
            DB::commit();
            $message = "success create user {$user->username}";
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $message = "error creating user {$employee->fullname}";
        }

        return $message;
    }

    private function generateUserData($employee)
    {
        return array(
            'email' => $employee->email,
            'username' => $this->username($employee),
            'type' => 'pegawai',
            'mobile_phone' => app('phoneNormalizerService')->normalize($employee->phone),
            'password' => Hash::make('sanmar123'),
            'status' => 'active',
            'role_units' => $employee->sanmaru_unit_id
                            ? [strval($employee->sanmaru_unit_id)]
                            : null,
        );
    }

    private function username($employee)
    {
        $generator = new Generator(['separator' => '.']);
        $names = explode(" ", $employee->fullname);
        $name = $names[0];
        if (count($names) > 1) {
            $name = $name . " " . $names[1];
        }
        $username = $_username = $generator->generate($name);
        while (User::where('username', $username)->first()) {
            $username = $_username.'.'.rand(1, 99);
        }

        return $username;
    }
}
