<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAdditionalDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_additional_datas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('student_id');
            $table->string('gender')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('religion')->nullable();
            $table->string('nama_siswa')->nullable();
            $table->string('nama_panggilan')->nullable();
            $table->string('anak_ke')->nullable();
            $table->string('jumlah_saudara_kandung')->nullable();
            $table->string('nama_saudara_se_sekolah')->nullable();
            $table->string('jumlah_saudara_tiri')->nullable();
            $table->string('bahasa')->nullable();
            $table->string('tinggal_dengan')->nullable();
            $table->string('jarak_tempat_tinggal')->nullable();
            $table->string('waktu_tempuh')->nullable();
            $table->string('nik')->nullable();
            $table->string('status_orangtua')->nullable();
            $table->string('no_akta_kelahiran')->nullable();
            $table->string('penanggungjawab_biaya')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->text('alamat_asal_sekolah')->nullable();
            $table->string('nisn')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->string('nomor_seri_shun')->nullable();
            $table->string('nomor_seri_ijazah')->nullable();
            $table->string('nomor_ujian_nasional')->nullable();
            $table->string('tinggi')->nullable();
            $table->string('berat')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('pernah_dirawat')->nullable();
            $table->string('kapan_dirawat')->nullable();
            $table->string('alergi')->nullable();
            $table->string('kontak_darurat_keluarga')->nullable();
            $table->string('prestasi_akademik')->nullable();
            $table->string('prestasi_nonakademik')->nullable();
            $table->text('alamat_sesuai_kk')->nullable();
            $table->text('alamat_tempat_tinggal')->nullable();
            $table->string('penyakit')->nullable();
            $table->string('kelainan')->nullable();
            $table->string('potensi_dan_bakat_sains')->nullable();
            $table->string('potensi_dan_bakat_olahraga')->nullable();

            $table->string('payment_form')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('family_card')->nullable();
            $table->string('baptismal_certificate')->nullable();
            $table->string('report_cards')->nullable();
            // $table->string('photo')->nullable();
            $table->string('angket_peminatan')->nullable();
            $table->string('rekomendasi_bk')->nullable();
            $table->string('statement_letter')->nullable();
            $table->string('nilai_raport')->nullable();
            $table->string('award_photo')->nullable();
            $table->string('parent_identity_card')->nullable();
            $table->string('marriage_certificate')->nullable();
            $table->string('kartu_golongan_darah')->nullable();
            $table->string('kms')->nullable();

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_additional_datas');
    }
}
