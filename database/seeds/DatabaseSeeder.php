<?php

use App\Models\Bill;
use App\Models\Unit;
use App\Models\User;
use App\Models\Event;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Classes;
use App\Models\Product;
use App\Models\Student;
use App\Models\BillUser;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\CourseUser;
use App\Models\ProductType;
use Illuminate\Support\Str;
use App\Models\BillCategory;
use App\Models\ProductDetail;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use App\Models\CourseAssignment;
use App\Models\PaymentAgreement;
use Illuminate\Support\Facades\Hash;
use App\Models\CourseAssignmentScore;
use App\Models\PPDBUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(CourseUsersTableSeeder::class);
        $this->call(CourseAssignmentTableSeeder::class);
        $this->call(CourseAssignmentScoreTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(CourseSchedulesSeeder::class);
        $this->call(StudentBilsSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(PaymentAgreementsTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(AttendanceSeeder::class);
        // $this->call(CampusSeeder::class);
    }
}

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        User::create(
            [
                'type' => 'admin',
                'username' => 'ywardhana',
                'email' => 'ywardhana@a.b.c',
                'mobile_phone' => 6282122329292,
                'password' => Hash::make('password'),
                'status' => 'active',
                'remember_token' => Str::random(10),
            ]
        );
        User::create(
            [
                'type' => 'admin',
                'username' => 'luluq',
                'email' => 'luluq@a.b.c',
                'mobile_phone' => 6282322121221,
                'password' => Hash::make('password'),
                'status' => 'active',
                'remember_token' => Str::random(10),
            ]
        );
        $user_1 = User::create(
            [
                'type' => 'siswa',
                'username' => 'student1',
                'email' => 'student1@a.b.c',
                'mobile_phone' => 6282122329293,
                'password' => Hash::make('password'),
                'status' => 'active',
                'remember_token' => Str::random(10),
            ]
        );
        User::create(
            [
                'type' => 'siswa',
                'username' => 'student2',
                'email' => 'student2@a.b.c',
                'mobile_phone' => 6282122329295,
                'password' => Hash::make('password'),
                'status' => 'active',
                'remember_token' => Str::random(10),
            ]
        );
        User::create(
            [
                'type' => 'admin',
                'username' => 'santoso',
                'email' => 'santoso@a.b.c',
                'mobile_phone' => 6281945751505,
                'password' => Hash::make('password'),
                'status' => 'active',
                'remember_token' => Str::random(10),
            ]
        );

        $class = Classes::create([
            'unit_id' => 5,
            'name' => 'XII IPA 1',
            'unit_class' => 'IPA 1',
        ]);

        $student_1 = Student::create([
            'nis' => '123456789',
            'user_id' => $user_1->id,
            'name' => 'Student One',
            'email' => $user_1->email,
            'mobile_phone' => $user_1->mobile_phone,
            'address' => 'New York',
            'school_year' => '20',
            'class_id' => $class->id,
        ]);

        PPDBUser::create([
            'name' => $student_1->name,
            'nik' => '1606160616061606',
            'gender' => 'male',
            'place_of_birth' => 'Jakarta',
            'date_of_birth' => '2000-01-01',
            'address' => $student_1->address,
            'city' => 'Jakarta',
            'region' => 'Jakarta',
            'country' => 'Indonesia',
            'religion' => 'Islam',
            'school_year' => $student_1->school_year,
            'user_id' => $user_1->id,
        ]);
    }
}

class CoursesTableSeeder extends Seeder
{

    public function run()
    {
        Course::create(
            [
                'name' => 'Matematika',
                'code' => 'MX120',
                // 'grade' => 'X',
                // 'year' => 2020,
                // 'semester' => 1,
                'status' => 'active',
            ]
        );
        Course::create(
            [
                'name' => 'Fisika',
                'code' => 'FX120',
                // 'grade' => 'X',
                // 'year' => 2020,
                // 'semester' => 1,
                'status' => 'active',
            ]
        );
        Course::create(
            [
                'name' => 'Biologi',
                'code' => 'BX120',
                // 'grade' => 'X',
                // 'year' => 2020,
                // 'semester' => 1,
                'status' => 'active',
            ]
        );
    }
}

class CourseUsersTableSeeder extends Seeder
{
    public function run()
    {
        CourseUser::create(
            [
                'course_id' => 1,
                'user_id' => 3,
                'uts_score' => 70,
                'year_taken' => 2020,
                'semester_taken' => 2,
            ]
        );
        CourseUser::create(
            [
                'course_id' => 2,
                'user_id' => 3,
                'uts_score' => 80,
                'uas_score' => 90,
                'year_taken' => 2020,
                'semester_taken' => 2,
            ]
        );
    }
}

class CourseAssignmentTableSeeder extends Seeder
{
    public function run()
    {
        CourseAssignment::create(
            [
                'name' => 'Tugas 1 Matematika',
                'course_id' => 1
            ]
        );
        CourseAssignment::create(
            [
                'name' => 'Tugas 1 Fisika',
                'course_id' => 2
            ]
        );
        CourseAssignment::create(
            [
                'name' => 'Tugas 2 Fisika',
                'course_id' => 2
            ]
        );
    }
}

class CourseAssignmentScoreTableSeeder extends Seeder
{
    public function run()
    {
        CourseAssignmentScore::create(
            [
                'course_assignment_id' => 1,
                'user_id' => 3,
                'score' => 80
            ]
        );
        CourseAssignmentScore::create(
            [
                'course_assignment_id' => 2,
                'user_id' => 3,
                'score' => 85
            ]
        );
        CourseAssignmentScore::create(
            [
                'course_assignment_id' => 3,
                'user_id' => 3,
                'score' => 70
            ]
        );
    }
}

class CourseSchedulesSeeder extends Seeder
{
    public function run()
    {
        Schedule::create(
            [
                'scheduleable_id' => 1,
                'scheduleable_type' => 'App\Models\Course',
                'day' => 'monday',
                'start_time' => "08:00:00",
            ]
        );
        Schedule::create(
            [
                'scheduleable_id' => 2,
                'scheduleable_type' => 'App\Models\Course',
                'day' => 'monday',
                'start_time' => "09:00:00",
            ]
        );
        Schedule::create(
            [
                'scheduleable_id' => 3,
                'scheduleable_type' => 'App\Models\Course',
                'day' => 'monday',
                'start_time' => "11:00:00",
            ]
        );
        Schedule::create(
            [
                'scheduleable_id' => 1,
                'scheduleable_type' => 'App\Models\Course',
                'day' => 'wednesday',
                'start_time' => "11:00:00",
            ]
        );
    }
}


class EventsTableSeeder extends Seeder
{
    public function run()
    {
        $datetime = new DateTime('tomorrow');
        Event::create(
            [
                'title' => 'event 1',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non pretium velit, eu sodales nibh. Ut nec tincidunt tellus. Quisque dictum auctor eros, in sagittis risus vulputate vel. Quisque sed neque ligula. Nam id congue quam. Suspendisse lobortis eros eu mi vulputate vestibulum. Nullam augue ligula, fringilla nec arcu non, iaculis porta massa. Mauris vestibulum arcu ac mauris tincidunt mollis at a tellus. Sed blandit ac magna a volutpat. Mauris eget urna lorem.

                Nulla ultrices, enim ut dignissim facilisis, libero sem aliquet augue, id convallis ligula augue in ante. Vestibulum lorem urna, ullamcorper faucibus tristique ut, imperdiet non mauris. In bibendum turpis a arcu vestibulum, ac dapibus quam sagittis. Donec nunc lorem, blandit id leo vitae, tempus blandit nisi. Aenean quis molestie nunc. Praesent eleifend sagittis quam in condimentum. Suspendisse in nisi suscipit, pretium est ut, tristique ante. Mauris quis dolor interdum, convallis velit sed, vehicula tortor. Maecenas eleifend commodo lectus, et luctus diam ullamcorper eget. Curabitur porta nunc et dui molestie, non imperdiet risus dictum.

                Morbi et diam sed turpis interdum maximus. Nulla mattis mi est, sit amet hendrerit leo fermentum et. Praesent eget odio et elit volutpat cursus vitae eget felis. Ut fringilla tellus a pulvinar rhoncus. Vivamus in sapien magna. Curabitur a consectetur tortor, eget tincidunt lacus. Suspendisse a lorem odio. Vivamus rutrum ornare tellus vitae gravida.

                Fusce sed velit vestibulum, tempus magna eget, convallis leo. Vivamus volutpat sed lectus vitae consectetur. Vivamus nibh lorem, ultrices eu orci sed, placerat luctus dolor. Vivamus tellus nunc, commodo in nunc vitae, fringilla tincidunt nisl. Suspendisse potenti. Nunc ut diam in dolor facilisis sollicitudin eget quis ligula. Ut ac iaculis ipsum. Praesent felis tellus, faucibus eget bibendum in, sagittis et felis. Donec eget elit lacus. Quisque eget metus consectetur, posuere libero sit amet, accumsan libero. Aliquam tempor urna sem, a ornare augue mollis ac. Donec eu elementum velit.

                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris accumsan commodo venenatis. Aenean ultrices vel magna non tristique. Ut odio est, ultricies et ullamcorper et, porttitor vitae augue. Mauris suscipit posuere lorem in congue. Suspendisse laoreet aliquam metus sed fringilla. Vestibulum pharetra rhoncus enim, ut ultrices elit commodo at. Nam maximus sapien non justo ullamcorper, non volutpat erat facilisis. Praesent venenatis enim quam, sit amet eleifend felis sodales at. Vivamus mollis consequat semper. Etiam finibus posuere tellus in imperdiet. Pellentesque elementum pellentesque nulla quis rutrum.',
                'location' => 'Sekolah',
                'event_time' => $datetime->format('Y-m-d H:i:s'),
                'created_by' => 1,
                'last_updated_by' => 1,
            ]
        );
        Event::create(
            [
                'title' => 'event 2',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non pretium velit, eu sodales nibh. Ut nec tincidunt tellus. Quisque dictum auctor eros, in sagittis risus vulputate vel. Quisque sed neque ligula. Nam id congue quam. Suspendisse lobortis eros eu mi vulputate vestibulum. Nullam augue ligula, fringilla nec arcu non, iaculis porta massa. Mauris vestibulum arcu ac mauris tincidunt mollis at a tellus. Sed blandit ac magna a volutpat. Mauris eget urna lorem.

                Nulla ultrices, enim ut dignissim facilisis, libero sem aliquet augue, id convallis ligula augue in ante. Vestibulum lorem urna, ullamcorper faucibus tristique ut, imperdiet non mauris. In bibendum turpis a arcu vestibulum, ac dapibus quam sagittis. Donec nunc lorem, blandit id leo vitae, tempus blandit nisi. Aenean quis molestie nunc. Praesent eleifend sagittis quam in condimentum. Suspendisse in nisi suscipit, pretium est ut, tristique ante. Mauris quis dolor interdum, convallis velit sed, vehicula tortor. Maecenas eleifend commodo lectus, et luctus diam ullamcorper eget. Curabitur porta nunc et dui molestie, non imperdiet risus dictum.

                Morbi et diam sed turpis interdum maximus. Nulla mattis mi est, sit amet hendrerit leo fermentum et. Praesent eget odio et elit volutpat cursus vitae eget felis. Ut fringilla tellus a pulvinar rhoncus. Vivamus in sapien magna. Curabitur a consectetur tortor, eget tincidunt lacus. Suspendisse a lorem odio. Vivamus rutrum ornare tellus vitae gravida.

                Fusce sed velit vestibulum, tempus magna eget, convallis leo. Vivamus volutpat sed lectus vitae consectetur. Vivamus nibh lorem, ultrices eu orci sed, placerat luctus dolor. Vivamus tellus nunc, commodo in nunc vitae, fringilla tincidunt nisl. Suspendisse potenti. Nunc ut diam in dolor facilisis sollicitudin eget quis ligula. Ut ac iaculis ipsum. Praesent felis tellus, faucibus eget bibendum in, sagittis et felis. Donec eget elit lacus. Quisque eget metus consectetur, posuere libero sit amet, accumsan libero. Aliquam tempor urna sem, a ornare augue mollis ac. Donec eu elementum velit.

                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris accumsan commodo venenatis. Aenean ultrices vel magna non tristique. Ut odio est, ultricies et ullamcorper et, porttitor vitae augue. Mauris suscipit posuere lorem in congue. Suspendisse laoreet aliquam metus sed fringilla. Vestibulum pharetra rhoncus enim, ut ultrices elit commodo at. Nam maximus sapien non justo ullamcorper, non volutpat erat facilisis. Praesent venenatis enim quam, sit amet eleifend felis sodales at. Vivamus mollis consequat semper. Etiam finibus posuere tellus in imperdiet. Pellentesque elementum pellentesque nulla quis rutrum.',
                'location' => 'Gedung',
                'event_time' => $datetime->format('Y-m-d H:i:s'),
                'created_by' => 1,
                'last_updated_by' => 1,
            ]
        );
    }
}

class StudentBilsSeeder extends Seeder
{
    public function run()
    {
        $billCategory = BillCategory::create(
            [
                'name' => 'Biaya Kelas 2 SMA',
            ]
        );
        $datetime = new DateTime('next month');
        $datetimePaid = new DateTime('yesterday');
        $bill = Bill::create(
            [
                'name' => 'Uang Bulanan',
                'due_date' => $datetime->format('Y-m-d'),
                'amount' => '500000',
                'category_id' => $billCategory->id
            ]
        );
        BillUser::create(
            [
                'user_id' => 3,
                'bill_id' => $bill->id,
                'bill_name' => $bill->name,
                'bill_amount' => $bill->amount,
                'bill_due_date' => $bill->due_date,
                'bill_category_id' => $bill->category_id,
                'status' => 'unpaid'
            ]
        );
        $bill = Bill::create(
            [
                'name' => 'Uang Gedung',
                'due_date' => $datetime->format('Y-m-d'),
                'amount' => '2000000',
                'category_id' => $billCategory->id
            ]
        );
        BillUser::create(
            [
                'user_id' => 3,
                'bill_id' => $bill->id,
                'bill_name' => $bill->name,
                'bill_amount' => $bill->amount,
                'bill_category_id' => $bill->category_id,
                'bill_due_date' => $bill->due_date,
                'status' => 'paid',
                'paid_date' => $datetimePaid->format('Y-m-d')
            ]
        );
        $bill = Bill::create(
            [
                'name' => 'Pendaftaran Sekolah',
                'due_date' => $datetime->format('Y-m-d'),
                'amount' => '2000000',
                'category_id' => $billCategory->id
            ]
        );
        BillUser::create(
            [
                'user_id' => 3,
                'bill_id' => $bill->id,
                'bill_name' => $bill->name,
                'bill_amount' => $bill->amount,
                'bill_category_id' => $bill->category_id,
                'bill_due_date' => $bill->due_date,
                'status' => 'paid',
                'paid_date' => $datetimePaid->format('Y-m-d')
            ]
        );
        BillUser::create(
            [
                'user_id' => 4,
                'bill_id' => $bill->id,
                'bill_name' => $bill->name,
                'bill_amount' => $bill->amount,
                'bill_category_id' => $bill->category_id,
                'bill_due_date' => $bill->due_date,
                'status' => 'unpaid'
            ]
        );
        $bill = Bill::create(
            [
                'name' => 'UKS',
                'due_date' => $datetime->format('Y-m-d'),
                'amount' => '2000000',
                'category_id' => $billCategory->id
            ]
        );
        BillUser::create(
            [
                'user_id' => 3,
                'bill_id' => $bill->id,
                'bill_name' => $bill->name,
                'bill_amount' => $bill->amount,
                'bill_category_id' => $bill->category_id,
                'bill_due_date' => $bill->due_date,
                'status' => 'paid',
                'paid_date' => $datetimePaid->format('Y-m-d')
            ]
        );
    }
}


class ProductsSeeder extends Seeder
{
    public function run()
    {
        $productCategory = ProductCategory::create(
            [
                'name' => 'Pakaian Pria',
                'description' => 'Ini barang-barang pakaian untuk pria',
                'slug' => 'pakaian-pria'
            ]
        );

        $productType = ProductType::create(
            [
                'name' => 'Kemeja SMP',
                'description' => 'Ini barang-barang kemeja untuk SMP',
                'slug' => 'kemeja-smp'
            ]
        );

        $productOne = Product::create(
            [
                'name' => 'Satu Set Kemeja Batik SMP',
                'slug' => 'satu-set-kemeja-batik-smp',
                'weight' => 300,
                'merk' => 'Javara',
                'status' => 'published',
                'type_id' => $productType->id,
                'type_name' => $productType->name,
                'category_id' => $productCategory->id,
                'category_name' => $productCategory->name
            ]
        );

        ProductDetail::create([
            'product_id' => $productOne->id,
            'stock' => 300,
            'price_siswa' => 150000,
            'price_ppdb' => 150000,
            'size' => 'L',
        ]);

        $produtTwo = Product::create(
            [
                'name' => 'Kemeja Coklat SMA',
                'slug' => 'kemeja-coklat-sma',
                'weight' => 300,
                'merk' => 'Purnama',
                'status' => 'published',
                'type_id' => $productType->id,
                'type_name' => $productType->name,
                'category_id' => $productCategory->id,
                'category_name' => $productCategory->name
            ]
        );

        ProductDetail::create([
            'product_id' => $produtTwo->id,
            'stock' => 150,
            'price_siswa' => 75000,
            'price_ppdb' => 75000,
            'size' => 'L',
        ]);
    }
}

class PaymentAgreementsTableSeeder extends Seeder
{
    public function run()
    {
        PaymentAgreement::create([
            'name' => 'Lunas',
            'desc' => 'membayar lunas'
        ], [
            'name' => 'Rutin',
            'desc' => 'Membayar SPP rutin setiap bulannya'
        ], [
            'name' => 'Sebagian',
            'desc' => 'Melunasi atau membayar secara tunai minimal 50% pada saat penyelesaian administrasi'
        ]);
    }
}

class UnitsTableSeeder extends Seeder
{
    public function run()
    {
        Unit::insert([[
            'name' => 'KB-SURABAYA',
            'city' => 'Surabaya',
            'unit_code' => '01',
            'keunggulan_path' => ''
        ], [
            'name' => 'TK-SURABAYA',
            'city' => 'Surabya',
            'unit_code' => '02',
            'keunggulan_path' => ''
        ], [
            'name' => 'SD-SURABAYA',
            'city' => 'Surabya',
            'unit_code' => '03',
            'keunggulan_path' => ''
        ], [
            'name' => 'SMP-SURABAYA',
            'city' => 'Surabya',
            'unit_code' => '04',
            'keunggulan_path' => ''
        ], [
            'name' => 'SMA-SURABAYA',
            'city' => 'Surabya',
            'unit_code' => '05',
            'keunggulan_path' => ''
        ], [
            'name' => 'KB-SIDOARJO',
            'city' => 'Sidoarjo',
            'unit_code' => '06',
            'keunggulan_path' => ''
        ], [
            'name' => 'TK-SIDOARJO',
            'city' => 'Sidoarjo',
            'unit_code' => '07',
            'keunggulan_path' => ''
        ], [
            'name' => 'SD-SIDOARJO',
            'city' => 'Sidoarjo',
            'unit_code' => '08',
            'keunggulan_path' => ''
        ], [
            'name' => 'SMP-SIDOARJO',
            'city' => 'Sidoarjo',
            'unit_code' => '09',
            'keunggulan_path' => ''
        ], [
            'name' => 'SMP-PACET',
            'city' => 'Pacet',
            'unit_code' => '10',
            'keunggulan_path' => ''
        ]]);
    }
}

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $userId = User::where('username', 'student1')->pluck('id')->first();
        for ($i = 0; $i <= 4; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            factory(Attendance::class)->create(['user_id' => $userId, 'date' => $date]);
        }
    }
}

class CampusSeeder extends Seeder
{
    public function run()
    {
        Campus::create([
            'name' => 'Kampus Santa Maria Surabaya',
        ]);
        Campus::create([
            'name' => 'Kampus Santa Maria Sidoarjo',
        ]);
        Campus::create([
            'name' => 'Kampus Santa Maria Pacet',
        ]);
    }
}
