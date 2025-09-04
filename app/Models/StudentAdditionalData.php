<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAdditionalData extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'student_additional_datas';

    protected $fillable = [
        'student_id',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'city',
        'region',
        'country',
        'religion',
        'nik',
        'nama_siswa',
        'nama_panggilan',
        'anak_ke',
        'jumlah_saudara_kandung',
        'nama_saudara_se_sekolah',
        'jumlah_saudara_tiri',
        'bahasa',
        'tinggal_dengan',
        'jarak_tempat_tinggal',
        'waktu_tempuh',
        // 'npwp',
        'status_orangtua',
        'no_akta_kelahiran',
        'penanggungjawab_biaya',
        'asal_sekolah',
        'alamat_asal_sekolah',
        'nisn',
        'tahun_lulus',
        'nomor_seri_shun',
        'nomor_seri_ijazah',
        'nomor_ujian_nasional',
        'tinggi',
        'berat',
        'golongan_darah',
        'pernah_dirawat',
        'kapan_dirawat',
        'alergi',
        'kontak_darurat_keluarga',
        'prestasi_akademik',
        'prestasi_nonakademik',
        'alamat_sesuai_kk',
        'alamat_tempat_tinggal',
        'penyakit',
        'kelainan',
        'potensi_dan_bakat_sains',
        'potensi_dan_bakat_olahraga',

        'payment_form',
        'birth_certificate',
        'family_card',
        'baptismal_certificate',
        'report_cards',
        // 'photo',
        'angket_peminatan',
        'rekomendasi_bk',
        'statement_letter',
        'nilai_raport',
        'award_photo',
        'parent_identity_card',
        'marriage_certificate',
        'kartu_golongan_darah',
        'kms',

    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
