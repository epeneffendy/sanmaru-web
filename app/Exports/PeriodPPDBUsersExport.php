<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Helpers\PriceHelper;
use App\Models\Period;
use App\Models\PPDBUser;

class PeriodPPDBUsersExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $period;

    public function __construct($params)
    {
        $period = Period::with('unit')->where('id', $params['id'])->first();
        $this->period = $period;
    }

    public function failed(Exception $e)
    {
        Log::error($e->getMessage());
    }

    public function view(): View
    {
        return view('exports.period_ppdb_users', [
            'collections' => $this->collection(),
            'headings' => $this->headings(),
            'period' => $this->period,
        ]);

    }

    public function collection()
    {
        $ppdbUsers = PPDBUser::whereIn('status', [PPDBUser::STATUS_SUBMITTED, PPDBUser::STATUS_CONFIRMED])
                        ->where('periode', $this->period->id)
                        ->orderBy('status')
                        ->with('user', 'parents');

        $ppdbUsers = $ppdbUsers->get();

        $collect = collect();

        foreach ($ppdbUsers as $ppdbUser) {
            $father = $ppdbUser->father();
            $mother = $ppdbUser->mother();
            $additional_info   = json_decode($ppdbUser->additional_info);

            $gender = null;
            if ($ppdbUser->gender == 'male') $gender = 'L';
            if ($ppdbUser->gender == 'female') $gender = 'P';
            $status = $ppdbUser->development_statement ? 'Sudah upload surat pernyataan' : 'Belum upload surat pernyataan';
            // switch ($ppdbUser->status) {
            //     case PPDBUser::STATUS_CONFIRMED:
            //         $status = 'Belum upload surat pernyataan';
            //         break;
            //     case PPDBUser::STATUS_SUBMITTED:
            //         $status = 'Sudah upload surat pernyataan';
            //         break;
            //     default:
            //         $status = 'Lainnya';
            //         break;
            // }

            $collect->push([
                'register_number' => $ppdbUser->register_number,
                'name' => $ppdbUser->name,
                'nik' => $ppdbUser->nik,
                'address' => $ppdbUser->address,
                'city' => $ppdbUser->city,
                'mobile_phone' => $ppdbUser->user->mobile_phone,
                'gender' => $gender,
                // 'npwp' => $ppdbUser->npwp,
                'father_name' => $father ? $father->name : null,
                'mother_name' => $mother ? $mother->name : null,
                'development' => PriceHelper::development($ppdbUser),
                'activity' => PriceHelper::activity($ppdbUser),
                'tuition' => PriceHelper::tuition($ppdbUser),
                'school_year_period' => $this->period->school_year_period,
                'period_name' => $this->period->name,
                'status' => $status,
            ]);
        }

        return $collect;
    }

    public function headings() : array
    {
        return [
            'No Pendaftar',
            'Nama',
            'NIK',
            'Alamat',
            'Kota',
            'Telp HP',
            'L/P',
            // 'NPWP Orangtua/Wali',
            'Nama Ayah',
            'Nama Ibu',
            'UGedung',
            'UKegiatan',
            'USekolah',
            'Tapel',
            'Jalur',
            'Status',
        ];
    }
}
