<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\User;

class CreateUserFromStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:students-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user data from student';

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
        $students = Student::with('user')->get();

        foreach ($students as $student) {
            if (!$student->user) {
                $user = User::where('email', $student->email)->orWhere('username', Str::slug($student->name, '.'))->orWhere('mobile_phone', $student->mobile_phone)->first();

                if (!$user) {
                    $user = new User();
                    $user->fill([
                        'email' => $student->email,
                        'mobile_phone' => $student->mobile_phone,
                        'username' => Str::slug($student->name, '.'),
                        'password' => Hash::make(str_random(8)),
                        'type' => User::STUDENT,
                        'status' => 'active',
                    ]);

                    $user->save();
                }

                $student->user_id = $user->id;
                $student->save();

                $this->info('created user for '. $student->name);
            }

        }
    }
}
