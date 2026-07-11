<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Helpers\InputCollectionHelper;
use App\Observers\PPDBUserObserver;
use Awobaz\Compoships\Compoships;
use App\Traits\ImageHandler;
use App\Observers\Observable;
use App\Services\VoucherService;
use Illuminate\Support\Str;
use Auth;
use ReflectionClass;

class PPDBUser extends Authenticatable
{
    use Compoships;
    use Notifiable;
    use SoftDeletes;
    use Observable;
    use ImageHandler;

    const STATUS_INCOMPLETE = 'incomplete'; // baru saja mendaftar, belum verifikasi email
    const STATUS_COMPLETE = 'complete'; // sudah verifikasi email, belum verifikasi pembayaran
    const STATUS_CONFIRMED = 'confirmed'; // sudah verifikasi pembayaran, belum submit data penunjang (data diri, parent, dll)
    const STATUS_SUBMITTED = 'submitted'; // sudah memenuhi semua persyaratan, menunggu validasi data
    const STATUS_REJECTED = 'rejected'; // setelah validasi data, ada data yang kurang
    const STATUS_ACCEPTED = 'accepted'; // peserta diterima
    const STATUS_NOT_SELECTED= 'not_selected'; // peserta tidak diterima
    const STATUS_CANCELED = 'canceled'; // pendaftaran dibatalkan

    const ORDER_STATUS_ORDERED = 'ordered';
    const ORDER_STATUS_NOT_ORDERED = 'not_ordered';

    const PERIOD_WAITING = 'waiting';
    const PERIOD_SWITCH = 'switch';
    const PERIOD_VERIFIED = 'verified';

    public $email, $mobilePhone;

    /**
     * @var string
     */
    protected $table = 'ppdb_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'nik',

        // https://aimsis.atlassian.net/browse/AIMSIS-10448
        'nik_siswa',
        'nik_ortu',

        'gender',
        'place_of_birth',
        'date_of_birth',
        'address',
        'city',
        'region',
        'country',
        'religion',
        'payment_form',
        'birth_certificate',
        'award_photo',
        'report_cards',
        'photo',
        'family_card',
        'baptismal_certificate',
        'status',
        'school_year',
        'user_id',
        'periode',
        'register_number',
        'unit_id',
        'origin_school',
        'statement_letter',
        'marriage_certificate',
        'parent_identity_card',
        'development_statement',
        'development_fee_option',
        'verification_development_statement',

        //not mandatory
        'additional_info',
        'nama_siswa',
        'nama_panggilan',
        'anak_ke',
        'jumlah_saudara_kandung',
        'jumlah_saudara_tiri',
        'status_orangtua', //value yatim, piatu, yp
        'bahasa',
        'alamat_sesuai_kk',
        'alamat_tempat_tinggal',
        'tinggal_dengan', //value orang tua, wali, saudara, asrama, kost, panti asuhan, lainnya
        'jarak_tempat_tinggal',
        'golongan_darah',
        'penyakit',
        'kelainan',
        'tinggi',
        'berat',
        'kesiapan_psikis_image',
        'bakat_istimewa_image',
        'potensi_kecerdasan_image',

        'nama_saudara_se_sekolah',
        'waktu_tempuh',
        // 'npwp',
        'no_akta_kelahiran',
        'penanggungjawab_biaya',
        'asal_sekolah',
        'alamat_asal_sekolah',
        'kota_asal_sekolah',
        'nomor_telepon_asal_sekolah',
        'nisn',
        'tahun_lulus',
        'nomor_seri_shun',
        'nomor_seri_ijazah',
        'nomor_ujian_nasional',
        'pernah_dirawat',
        'kapan_dirawat',
        'alergi',
        'kontak_darurat_keluarga',
        'prestasi_akademik',
        'prestasi_nonakademik',
        'prestasi_lainnya',
        'potensi_dan_bakat_sains',
        'potensi_dan_bakat_seni',
        'potensi_dan_bakat_olahraga',
        'potensi_dan_bakat_lainnya',

        'report_card',
        'angket_peminatan',
        'rekomendasi_bk',
        'class_option',

        'kabupaten_asal_sekolah',
        'kecamatan_asal_sekolah',
        'kelurahan_asal_sekolah',
        'provinsi_asal_sekolah',
        'transportasi_ke_sekolah',
        'nik_ayah',
        'nik_ibu',

        'angsuran_1',
        'angsuran_2',
        'angsuran_3',
        'angsuran_4',
        'angsuran_5',
        'cicilan_1',
        'cicilan_2',
        'cicilan_3',
        'cicilan_4',
        'cicilan_5',

        'kms',
        'kartu_golongan_darah',
        'is_cost',
        'period_verified',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at'];

    protected static $observer = PPDBUserObserver::class;

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotConfirm($query)
    {
        return $query->where('status', '!=', 'confirmed');
    }

    public function scopeNotAccepted($query)
    {
        return $query->where('status', '<>', self::STATUS_ACCEPTED);
    }

    public function scopeOngoingPeriod($query)
    {
        return $query->whereHas('period', function ($q) {
            $q->where('active', true)
                ->where('start_date', '<=', now()->toDateString())
                ->where('end_date', '>=', now()->toDateString());
        });
    }

    public function scopePersonalDataFilled($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->whereNotNull('name')
            ->whereNotNull('gender')
            ->whereNotNull('place_of_birth')
            ->whereNotNull('date_of_birth')
            ->whereNotNull('address')
            ->whereNotNull('city')
            ->whereNotNull('region')
            ->whereNotNull('country')
            ->whereNotNull('nik')
            ->whereNotNull('religion')->exists();
    }

    public function scopeStatusComplete($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->whereNotNull('name')
            ->whereNotNull('gender')
            ->whereNotNull('place_of_birth')
            ->whereNotNull('date_of_birth')
            ->whereNotNull('address')
            ->whereNotNull('city')
            ->whereNotNull('region')
            ->whereNotNull('country')
            ->whereNotNull('nik')
            ->whereNotNull('religion')
            ->whereNotNull('birth_certificate')
            ->whereNotNull('photo')
            ->whereNotNull('family_card')
            ->whereNotNull('payment_form')->exists();
    }

    public function scopeByUserRole($query)
    {
        $user = auth()->user();
        if (!$user || !isset($user->role_units) || (isset($user->role_units) && !$user->role_units) || (isset($user->role_units) && $user->role_units && !count($user->role_units))) {
            return $query;
        }

        return $query->whereIn('unit_id', $user->role_units);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function parents()
    {
        return $this->hasMany('App\Models\Parents', 'children_id', 'user_id');
    }

    public function period()
    {
        return $this->belongsTo('App\Models\Period', 'periode', 'id');
    }

    public function fathermother()
    {
        return $this->parents->filter(function ($value, $key) {
            return $value->type === 'father' || $value->type === 'mother';
        })->all();
    }

    public function father()
    {
        return $this->parents->filter(function ($value, $key) {
            return $value->type === 'father';
        })->first();
    }

    public function mother()
    {
        return $this->parents->filter(function ($value, $key) {
            return $value->type === 'mother';
        })->first();
    }

    public function wali()
    {
        return $this->parents->filter(function ($value, $key) {
            return $value->type === 'wali';
        })->first();
    }

    public function orders()
    {
        return $this->hasMany(ProductOrder::class, 'user_id', 'user_id')
            ->notCanceled();
    }

    public function ordersConfirmed()
    {
        return $this->hasMany(ProductOrder::class, 'user_id', 'user_id')->paymentConfirmed();
    }

    public function ppdbResignation()
    {
        return $this->hasOne(PPDBResignation::class, 'ppdb_user_id', 'id');
    }

    public function paymentRefunds()
    {
        return $this->hasMany(PaymentRefund::class, 'user_id', 'user_id');
    }

    public function paymentRefundUniform()
    {
        return $this->hasOne(PaymentRefund::class, 'user_id', 'user_id')->repaymentUniform();
    }

    public function paymentRefundDevelopment()
    {
        return $this->hasOne(PaymentRefund::class, 'user_id', 'user_id')->repaymentDevelopment();
    }

    public function order()
    {
        return $this->hasOne(ProductOrder::class, 'user_id', 'user_id')
            ->notCanceled()
            ->orderBy('id', 'DESC')
            ->orderBy('payment_image', 'DESC')
            ->orderByRaw("FIELD(status, '" . ProductOrder::STATUS_CONFIRMED . "', '" . ProductOrder::STATUS_NEW_ORDER . "', '" . ProductOrder::STATUS_CANCEL . "')");
    }


    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    // public function generateRegisterNumber()
    // {
    //     $this->register_number = rand(1000, 10000);
    // }

    public function getIsParentsCompleteAttribute()
    {
        if ($this->isWaliRequired) {
            return $this->wali();
        }

        return count($this->fathermother()) >= 2;
        //return $this->parents->count() >= 2;
    }

    public function getIsCanRepayDevelopmentFeeAttribute()
    {
        return $this->development_fee_option === 'cicilan' &&
            $this->cicilan_1 && $this->angsuran_1 &&
            $this->cicilan_2 && $this->angsuran_2 &&
            $this->cicilan_3 && $this->angsuran_3 &&
            $this->cicilan_4 && $this->angsuran_4 &&
            $this->cicilan_5 && $this->angsuran_5;
    }

    public function getIsStatusCompleteAttribute()
    {
        return $this->name != '' &&
            $this->gender != '' &&
            $this->place_of_birth != '' &&
            $this->date_of_birth != '' &&
            $this->address != '' &&
            $this->city != '' &&
            $this->region != '' &&
            $this->country != '' &&
            $this->religion != '' &&
            // $this->nik != '' &&
            $this->birth_certificate != '' &&
            $this->photo != '' &&
            $this->family_card != '' &&
            $this->payment_form != '';
    }

    public function getIsStatusCompleteWhitoutBcaAttribute()
    {
        return $this->name != '' &&
            $this->gender != '' &&
            $this->place_of_birth != '' &&
            $this->date_of_birth != '' &&
            $this->address != '' &&
            $this->city != '' &&
            $this->region != '' &&
            $this->country != '' &&
            $this->religion != '' &&
            // $this->nik != '' &&
            $this->birth_certificate != '' &&
            $this->photo != '' &&
            $this->family_card != '';
    }

    public function getIsPersonalDataFilledAttribute()
    {
        return $this->name != '' &&
            $this->gender != '' &&
            $this->place_of_birth != '' &&
            $this->date_of_birth != '' &&
            $this->address != '' &&
            $this->city != '' &&
            $this->region != '' &&
            $this->country != '' &&
            $this->religion != '' &&
            $this->nik_siswa != '' &&
            $this->nik_ortu != '' &&
            // $this->nik != '' &&
            $this->additionalDatasFilled();
    }

    public function getIsDataCompleteAttribute()
    {
        return $this->name != '' &&
            $this->gender != '' &&
            $this->place_of_birth != '' &&
            $this->date_of_birth != '' &&
            $this->address != '' &&
            $this->city != '' &&
            $this->region != '' &&
            $this->country != '' &&
            $this->religion != '' &&
            $this->nik_siswa != '' &&
            $this->nik_ortu != '' &&
            // $this->nik != '' &&
            $this->uploadsFilled() &&
            $this->additionalDatasFilled();
    }

    public function getIsDataCompleteWhitoutBcaAttribute()
    {

        return $this->name != '' &&
            $this->gender != '' &&
            $this->place_of_birth != '' &&
            $this->date_of_birth != '' &&
            $this->address != '' &&
            $this->city != '' &&
            $this->region != '' &&
            $this->country != '' &&
            $this->religion != '' &&
            $this->nik_siswa != '' &&
            $this->nik_ortu != '' &&
            // $this->nik != '' &&
            $this->uploadsFilledWhitoutBCA() &&
            $this->additionalDatasFilled();
    }

    public function getIsOrderedAttribute()
    {
        return count($this->orders) ? true : false;
    }

    public function getIsOrderConfirmedAttribute()
    {
        if ($this->order) {
            return $this->order->status == ProductOrder::STATUS_CONFIRMED;
        }
        return false;
    }

    public function getOrderStatusAttribute()
    {
        return $this->is_ordered ? $this::ORDER_STATUS_ORDERED : $this::ORDER_STATUS_NOT_ORDERED;
    }

    public function getTextOrderStatusAttribute()
    {
        return $this->is_ordered ? 'sudah melakukan pemesanan' : 'belum melakukan pemesanan';
    }

    public function getIconOrderStatusAttribute()
    {
        $attribute = [
            'class' => $this->is_ordered ? 'success' : 'danger',
            'icon' => $this->is_ordered ? 'fa fa-check' : 'fa fa-times',
            'title' => $this->text_order_status,
        ];

        return "<span class=\"btn btn-circle btn-sm btn-{$attribute['class']}\"><icon class=\"icon-plus\"><i class=\"{$attribute['icon']}\" title=\"{$attribute['title']}\"></i></icon></span>";
    }

    public function listOrderStatus()
    {
        $constantWithLabel = [];
        $constants = (new ReflectionClass($this))->getConstants();

        foreach ($constants as $key => $constant) {
            $is_status = (strtoupper(substr($key, 0, 5)) == 'ORDER');
            if ($constant != 'created_at' && $constant != 'updated_at' && $is_status)
                $constantWithLabel[] = [
                    'value' => $constant,
                    'name' => ucwords(str_replace("_", " ", $constant))
                ];
        }

        return $constantWithLabel;
    }

    public function additionalDatasFilled()
    {
        $datas = InputCollectionHelper::additionalData($this->unit)->filter(function ($value, $key) {
            return in_array('required', $value);
        })->keys()->all();

        foreach ($datas as $key) {
            if ($this->$key == '') {
                return false;
            }
        }

        return true;
    }

    public function uploadsFilled()
    {
        $uploads = InputCollectionHelper::uploads($this->unit)->filter(function ($value, $key) {
            return Str::contains($value['validation'], 'required');
        })->keys()->all();

        foreach ($uploads as $key) {
            if (($this->$key == '' && $key != 'report_cards') || ($key == 'report_cards' && !count($this->report_cards))) {
                return false;
            }
        }

        return true;
    }

    public function uploadsFilledWhitoutBCA()
    {
        $uploads = InputCollectionHelper::uploadsWithoutBCA($this->unit)->filter(function ($value, $key) {
            return Str::contains($value['validation'], 'required');
        })->keys()->all();

        foreach ($uploads as $key) {
            if (($this->$key == '' && $key != 'report_cards') || ($key == 'report_cards' && !count($this->report_cards))) {
                return false;
            }
        }

        return true;
    }

    public function getIsReadyToSubmitAttribute()
    {
        if($this->payment_option == 'BCA'){
            return $this->uploadsFilledWhitoutBCA() &&
                $this->isDataCompleteWhitoutBca &&
                $this->isParentsComplete;
        }else{
            return $this->uploadsFilled() &&
                $this->isPaymentStatusVerified &&
                $this->isDataComplete &&
                $this->isParentsComplete;
        }
    }

    public function getIsPaymentStatusCompleteAttribute()
    {
        return $this->payment_form != '' && $this->status == self::STATUS_COMPLETE;
    }

    public function getIsPaymentStatusVerifiedAttribute()
    {
        return $this->payment_form != '' && $this->status != self::STATUS_COMPLETE;
    }

    public function getIsPaymentStatusVerifiedBcaAttribute()
    {
        return $this->payment_date != '' && $this->status != self::STATUS_COMPLETE;
    }

    public function getIsEmailVerifiedAttribute()
    {
        return $this->status != self::STATUS_INCOMPLETE;
    }

    public function getIsAcceptedAttribute()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function getIsRejectedAttribute()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getIsSubmittedAttribute()
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function getIsWaliRequiredAttribute()
    {
        $data = $this->tinggal_dengan;

        return $data && $data === 'wali';
    }

    public function getIsStatementLetterUploadedAttribute()
    {
        return $this->development_statement;
    }

    public function getIsStatementLetterConfirmedAttribute()
    {
        return $this->stages()->filter(function ($stage) {
            return $stage->is_opening_development_feature && $stage->passed === 'LOLOS';
        })->count();
    }

    public function getPaymentFormImageUrl()
    {
        if ($this->payment_form == null) {
            return null;
        }

        return $this->getImageUrl($this->payment_form);
        // return route('show_image', ['file' => $this->payment_form]);
    }

    public function getBirtCertificateImageUrl()
    {
        if ($this->birth_certificate == null) {
            return null;
        }

        return $this->getImageUrl($this->birth_certificate);
        // return route('show_image', ['file' => $this->birth_certificate]);
    }

    public function getDevelopmentStatementUrl()
    {
        if ($this->development_statement == null) {
            return null;
        }

        return $this->getImageUrl($this->development_statement);
        // return route('show_image', ['file' => $this->birth_certificate]);
    }

    public function getDevelopmentStatementFile()
    {
        if ($this->development_statement == null) {
            return null;
        }

        return $this->getImageFile($this->development_statement);
        // return route('show_image', ['file' => $this->birth_certificate]);
    }

    public function getMarriageCertificateImageUrl()
    {
        if ($this->marriage_certificate == null) {
            return null;
        }

        return $this->getImageUrl($this->marriage_certificate);
        // return route('show_image', ['file' => $this->marriage_certificate]);
    }

    public function getRaportImageUrl($value)
    {
        return $this->getImageUrl($value);
        // return route('show_image', ['file' => $value]);
    }

    public function getPhotoImageUrl()
    {
        if ($this->photo == null) {
            return null;
        }

        return $this->getImageUrl($this->photo);
        // return route('show_image', ['file' => $this->photo]);
    }

    public function getFamilyCardImageUrl()
    {
        if ($this->family_card == null) {
            return null;
        }

        return $this->getImageUrl($this->family_card);
        // return route('show_image', ['file' => $this->family_card]);
    }

    public function getParentIdentityCardImageUrl()
    {
        if ($this->parent_identity_card == null) {
            return null;
        }

        return $this->getImageUrl($this->parent_identity_card);
        // return route('show_image', ['file' => $this->parent_identity_card]);
    }

    public function getAwardPhotoImageUrl()
    {
        if ($this->award_photo == null) {
            return null;
        }

        return $this->getImageUrl($this->award_photo);
        // return route('show_image', ['file' => $this->award_photo]);
    }

    public function getBaptismalCertificateImageUrl()
    {
        if ($this->baptismal_certificate == null) {
            return null;
        }

        return $this->getImageUrl($this->baptismal_certificate);
        // return route('show_image', ['file' => $this->baptismal_certificate]);
    }

    public function getStatementLetterFileUrl()
    {
        if ($this->statement_letter == null) {
            return null;
        }

        return $this->getImageUrl($this->statement_letter);
        // return route('show_file', ['file' => $this->statement_letter]);
    }

    public function getRekomendasiBkImageUrl()
    {
        if ($this->rekomendasi_bk == null) {
            return null;
        }

        return $this->getImageUrl($this->statement_letter);
        // return route('show_file', ['file' => $this->rekomendasi_bk]);
    }

    public function getPotensiKecerdasanImageUrl()
    {
        if ($this->potensi_kecerdasan_image == null) {
            return null;
        }

        return $this->getImageUrl($this->potensi_kecerdasan_image);
        // return route('show_file', ['file' => $this->potensi_kecerdasan_image]);
    }

    public function getAngketPeminatanFileUrl()
    {
        if ($this->angket_peminatan == null) {
            return null;
        }

        return $this->getImageUrl($this->angket_peminatan);
        // return route('show_file', ['file' => $this->angket_peminatan]);
    }

    public function getBakatistimewaImageUrl()
    {
        if ($this->bakat_istimewa_image == null) {
            return null;
        }

        return $this->getImageUrl($this->bakat_istimewa_image);
        // return route('show_file', ['file' => $this->bakat_istimewa_image]);
    }

    public function getKesiapanPsikisImageUrl()
    {
        if ($this->kesiapan_psikis_image == null) {
            return null;
        }

        return $this->getImageUrl($this->kesiapan_psikis_image);
        // return route('show_file', ['file' => $this->kesiapan_psikis_image]);
    }

    public function getKartuGolonganDarahImageUrl()
    {
        if ($this->kartu_golongan_darah == null) {
            return null;
        }

        return $this->getImageUrl($this->kartu_golongan_darah);
        // return route('show_file', ['file' => $this->kartu_golongan_darah]);
    }

    public function getKmsImageUrl()
    {
        if ($this->kms == null) {
            return null;
        }

        return $this->getImageUrl($this->kms);
        // return route('show_file', ['file' => $this->kms]);
    }

    public function student()
    {
        return $this->hasOne(__NAMESPACE__ . '\Student', 'user_id', 'user_id');
    }

    public function unit()
    {
        return $this->hasOne(__NAMESPACE__ . '\Unit', 'id', 'unit_id');
    }

    public function getRandomGenerateNumberAttribute()
    {
        return substr(strval($this->created_at->timestamp), -3);
    }

    public function getPhotoDirectory()
    {
        return url(\App\Services\ImageService::PATH_PRIVATE . $this->photo);
    }


    private function setAdditionalData($attribute, $value)
    {
        $additional_data = json_decode($this->additional_info, TRUE);
        if (!$additional_data) {
            $additional_data = [];
        }

        $additional_data[$attribute] = $value;

        if ($value === NULL) {
            unset($additional_data[$attribute]);
        }

        $this->attributes['additional_info'] = json_encode($additional_data);
    }

    public function resetDevelopmentPaymentMethod()
    {
        $this->setAdditionalData('angsuran_1', null);
        $this->setAdditionalData('angsuran_2', null);
        $this->setAdditionalData('angsuran_3', null);
        $this->setAdditionalData('angsuran_4', null);
        $this->setAdditionalData('angsuran_5', null);
        $this->setAdditionalData('angsuran_5', null);
        $this->setAdditionalData('cicilan_1', null);
        $this->setAdditionalData('cicilan_2', null);
        $this->setAdditionalData('cicilan_3', null);
        $this->setAdditionalData('cicilan_4', null);
        $this->setAdditionalData('cicilan_5', null);
        $this->setAdditionalData('total_angsuran', null);
        $this->setAdditionalData('development_fee_option', null);
        $this->attributes['development_statement'] = null;
        $this->attributes['verification_development_statement'] = null;

        if(!empty($this->period_verified)){
            $dispensation = PaymentDispensations::where([
                'ppdb_user_id' => $this->id,
                'dispensation_type'=>PaymentDispensations::DISPENSATION_TYPE_DEVELOPMENT,
                'status'=> PaymentDispensations::STATUS_ACTIVE
            ])->first();

            if($dispensation){
                $dispensation->status = PaymentDispensations::STATUS_INACTIVE;
                $dispensation->save();
            }

            $virtualAccounts = PaymentVirtualAccounts::where('ppdb_user_id', $this->id)
                ->where('status', PaymentVirtualAccounts::STATUS_UNPAID)
                ->where('type', PaymentVirtualAccounts::PAYMENT_TYPE_DEVELOPMENT)
                ->get();
            
            foreach($virtualAccounts as $va) {
                $va->status = PaymentVirtualAccounts::STATUS_CANCELED;
                $va->save();
            }
        }

        $stage = $this->stages()->filter(function ($stage) {
            return $stage->is_opening_development_feature;
        })->first();
        if ($stage) {
            $userStage = PPDBUserStage::where('ppdb_user_id', $this->id)->where('stage_id', $stage->id)->first();
            if ($userStage) {
                $userStage->passed = 3;
                $userStage->save();

            }
        }
        (new VoucherService)->removeGeneratedFreeVouchersForOlahRagaProduct($this);

        return $this->save();
    }

    private function getAdditionalData($attribute)
    {
        $additional_data = json_decode($this->additional_info, TRUE);

        if (isset($additional_data[$attribute])) {
            return $additional_data[$attribute];
        }

        return NULL;
    }

    //mutator
    public function setNamaSiswaAttribute($value)
    {
        $this->setAdditionalData('nama_siswa', $value);
    }

    public function setNamaPanggilanAttribute($value)
    {
        $this->setAdditionalData('nama_panggilan', $value);
    }

    public function setAnakKeAttribute($value)
    {
        $this->setAdditionalData('anak_ke', $value);
    }

    public function setJumlahSaudaraKandungAttribute($value)
    {
        $this->setAdditionalData('jumlah_saudara_kandung', $value);
    }

    public function setJumlahSaudaraTiriAttribute($value)
    {
        $this->setAdditionalData('jumlah_saudara_tiri', $value);
    }

    public function setStatusOrangTuaAttribute($value)
    {
        $this->setAdditionalData('status_orangtua', $value);
    }

    public function setBahasaAttribute($value)
    {
        $this->setAdditionalData('bahasa', $value);
    }

    public function setAlamatSesuaiKkAttribute($value)
    {
        $this->setAdditionalData('alamat_sesuai_kk', $value);
    }

    public function setAlamatTempatTinggalAttribute($value)
    {
        $this->setAdditionalData('alamat_tempat_tinggal', $value);
    }

    public function setTinggalDenganAttribute($value)
    {
        $this->setAdditionalData('tinggal_dengan', $value);
    }

    public function setJarakTempatTinggalAttribute($value)
    {
        $this->setAdditionalData('jarak_tempat_tinggal', $value);
    }

    public function setGolonganDarahAttribute($value)
    {
        $this->setAdditionalData('golongan_darah', $value);
    }

    public function setPenyakitAttribute($value)
    {
        $this->setAdditionalData('penyakit', $value);
    }

    public function setKelainanAttribute($value)
    {
        $this->setAdditionalData('kelainan', $value);
    }

    public function setTinggiAttribute($value)
    {
        $this->setAdditionalData('tinggi', $value);
    }

    public function setBeratAttribute($value)
    {
        $this->setAdditionalData('berat', $value);
    }

    public function setPotensiKecerdasanImageAttribute($value)
    {
        $this->setAdditionalData('potensi_kecerdasan_image', $value);
    }

    public function setBakatIstimewaImageAttribute($value)
    {
        $this->setAdditionalData('bakat_istimewa_image', $value);
    }

    public function setKesiapanPsikisImageAttribute($value)
    {
        $this->setAdditionalData('kesiapan_psikis_image', $value);
    }

    public function setAngketPeminatanAttribute($value)
    {
        $this->setAdditionalData('angket_peminatan', $value);
    }

    public function setRekomendasiBkAttribute($value)
    {
        $this->setAdditionalData('rekomendasi_bk', $value);
    }

    public function setNamaSaudaraSeSekolahAttribute($value)
    {
        $this->setAdditionalData('nama_saudara_se_sekolah', $value);
    }

    public function setWaktuTempuhAttribute($value)
    {
        $this->setAdditionalData('waktu_tempuh', $value);
    }

    // public function setNPWPAttribute($value)
    // {
    //     $this->setAdditionalData('npwp', $value);
    // }

    public function setNoAktaKelahiranAttribute($value)
    {
        $this->setAdditionalData('no_akta_kelahiran', $value);
    }

    public function setPenanggungjawabBiayaAttribute($value)
    {
        $this->setAdditionalData('penanggungjawab_biaya', $value);
    }

    public function setAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('asal_sekolah', $value);
    }

    public function setAlamatAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('alamat_asal_sekolah', $value);
    }

    public function setKotaAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('kota_asal_sekolah', $value);
    }

    public function setNomorTeleponAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('nomor_telepon_asal_sekolah', $value);
    }

    public function setNisnAttribute($value)
    {
        $this->setAdditionalData('nisn', $value);
    }

    public function setTahunLulusAttribute($value)
    {
        $this->setAdditionalData('tahun_lulus', $value);
    }

    public function setNomorSeriShunAttribute($value)
    {
        $this->setAdditionalData('nomor_seri_shun', $value);
    }

    public function setNomorSeriIjazahAttribute($value)
    {
        $this->setAdditionalData('nomor_seri_ijazah', $value);
    }

    public function setNomorUjianNasionalAttribute($value)
    {
        $this->setAdditionalData('nomor_ujian_nasional', $value);
    }

    public function setPernahDirawatAttribute($value)
    {
        $this->setAdditionalData('pernah_dirawat', $value);
    }

    public function setKapanDirawatAttribute($value)
    {
        $this->setAdditionalData('kapan_dirawat', $value);
    }

    public function setAlergiAttribute($value)
    {
        $this->setAdditionalData('alergi', $value);
    }

    public function setKontakDaruratKeluargaAttribute($value)
    {
        $this->setAdditionalData('kontak_darurat_keluarga', $value);
    }

    public function setPrestasiAkademikAttribute($value)
    {
        $this->setAdditionalData('prestasi_akademik', $value);
    }

    public function setPrestasiNonakademikAttribute($value)
    {
        $this->setAdditionalData('prestasi_nonakademik', $value);
    }

    public function setPrestasiLainnyaAttribute($value)
    {
        $this->setAdditionalData('prestasi_lainnya', $value);
    }

    public function setPotensiDanBakatSainsAttribute($value)
    {
        $this->setAdditionalData('potensi_dan_bakat_sains', $value);
    }

    public function setPotensiDanBakatSeniAttribute($value)
    {
        $this->setAdditionalData('potensi_dan_bakat_seni', $value);
    }

    public function setPotensiDanBakatOlahragaAttribute($value)
    {
        $this->setAdditionalData('potensi_dan_bakat_olahraga', $value);
    }

    public function setPotensiDanBakatLainnyaAttribute($value)
    {
        $this->setAdditionalData('potensi_dan_bakat_lainnya', $value);
    }

    public function setKartuGolonganDarahAttribute($value)
    {
        $this->setAdditionalData('kartu_golongan_darah', $value);
    }

    public function setKmsAttribute($value)
    {
        $this->setAdditionalData('kms', $value);
    }

    public function setClassOptionAttribute($value)
    {
        $this->setAdditionalData('class_option', $value);
    }

    public function setKabupatenAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('kabupaten_asal_sekolah', $value);
    }

    public function setKecamatanAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('kecamatan_asal_sekolah', $value);
    }

    public function setKelurahanAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('kelurahan_asal_sekolah', $value);
    }

    public function setProvinsiAsalSekolahAttribute($value)
    {
        $this->setAdditionalData('provinsi_asal_sekolah', $value);
    }

    public function setTransportasiKeSekolahAttribute($value)
    {
        $this->setAdditionalData('transportasi_ke_sekolah', $value);
    }

    public function setNikAyahAttribute($value)
    {
        $this->setAdditionalData('nik_ayah', $value);
    }

    public function setNikIbuAttribute($value)
    {
        $this->setAdditionalData('nik_ibu', $value);
    }

    public function setCicilan1Attribute($value)
    {
        $this->setAdditionalData('cicilan_1', $value);
    }

    public function setCicilan2Attribute($value)
    {
        $this->setAdditionalData('cicilan_2', $value);
    }

    public function setCicilan3Attribute($value)
    {
        $this->setAdditionalData('cicilan_3', $value);
    }

    public function setCicilan4Attribute($value)
    {
        $this->setAdditionalData('cicilan_4', $value);
    }

    public function setCicilan5Attribute($value)
    {
        $this->setAdditionalData('cicilan_5', $value);
    }

    public function setAngsuran1Attribute($value)
    {
        $this->setAdditionalData('angsuran_1', $value);
    }

    public function setAngsuran2Attribute($value)
    {
        $this->setAdditionalData('angsuran_2', $value);
    }

    public function setAngsuran3Attribute($value)
    {
        $this->setAdditionalData('angsuran_3', $value);
    }

    public function setAngsuran4Attribute($value)
    {
        $this->setAdditionalData('angsuran_4', $value);
    }

    public function setAngsuran5Attribute($value)
    {
        $this->setAdditionalData('angsuran_5', $value);
    }

    public function setDevelopmentFeeOptionAttribute($value)
    {
        $this->setAdditionalData('development_fee_option', $value);
    }

    public function setReportCardAttribute($value)
    {
        $report_cards = json_decode($this->attributes['report_cards'], TRUE);
        if (!$report_cards) {
            $report_cards = [];
        }

        $report_cards[] = $value;

        $this->attributes['report_cards'] = json_encode($report_cards);
    }

    //ancestor additional data
    public function getNamaSiswaAttribute()
    {
        return $this->getAdditionalData('nama_siswa');
    }

    public function getNamaPanggilanAttribute()
    {
        return $this->getAdditionalData('nama_panggilan');
    }

    public function getAnakKeAttribute()
    {
        return $this->getAdditionalData('anak_ke');
    }

    public function getJumlahSaudaraKandungAttribute()
    {
        return $this->getAdditionalData('jumlah_saudara_kandung');
    }

    public function getJumlahSaudaraTiriAttribute()
    {
        return $this->getAdditionalData('jumlah_saudara_tiri');
    }

    public function getStatusOrangtuaAttribute()
    {
        return $this->getAdditionalData('status_orangtua');
    }

    public function getBahasaAttribute()
    {
        return $this->getAdditionalData('bahasa');
    }

    public function getAlamatSesuaiKkAttribute()
    {
        return $this->getAdditionalData('alamat_sesuai_kk');
    }

    public function getAlamatTempatTinggalAttribute()
    {
        return $this->getAdditionalData('alamat_tempat_tinggal');
    }

    public function getTinggalDenganAttribute()
    {
        return $this->getAdditionalData('tinggal_dengan');
    }

    public function getJarakTempatTinggalAttribute()
    {
        return $this->getAdditionalData('jarak_tempat_tinggal');
    }

    public function getGolonganDarahAttribute()
    {
        return $this->getAdditionalData('golongan_darah');
    }

    public function getPenyakitAttribute()
    {
        return $this->getAdditionalData('penyakit');
    }

    public function getKelainanAttribute()
    {
        return $this->getAdditionalData('kelainan');
    }

    public function getTinggiAttribute()
    {
        return $this->getAdditionalData('tinggi');
    }

    public function getBeratAttribute()
    {
        return $this->getAdditionalData('berat');
    }

    public function getPotensiKecerdasanImageAttribute()
    {
        return $this->getAdditionalData('potensi_kecerdasan_image');
    }

    public function getBakatIstimewaImageAttribute()
    {
        return $this->getAdditionalData('bakat_istimewa_image');
    }

    public function getKesiapanPsikisImageAttribute()
    {
        return $this->getAdditionalData('kesiapan_psikis_image');
    }

    public function getAngketPeminatanAttribute()
    {
        return $this->getAdditionalData('angket_peminatan');
    }

    public function getRekomendasiBkAttribute()
    {
        return $this->getAdditionalData('rekomendasi_bk');
    }

    public function getNamaSaudaraSeSekolahAttribute()
    {
        return $this->getAdditionalData('nama_saudara_se_sekolah');
    }

    public function getWaktuTempuhAttribute()
    {
        return $this->getAdditionalData('waktu_tempuh');
    }

    // public function getNPWPAttribute()
    // {
    //     return $this->getAdditionalData('npwp');
    // }

    public function getNoAktaKelahiranAttribute()
    {
        return $this->getAdditionalData('no_akta_kelahiran');
    }

    public function getPenanggungjawabBiayaAttribute()
    {
        return $this->getAdditionalData('penanggungjawab_biaya');
    }

    public function getAsalSekolahAttribute()
    {
        return $this->getAdditionalData('asal_sekolah');
    }

    public function getAlamatAsalSekolahAttribute()
    {
        return $this->getAdditionalData('alamat_asal_sekolah');
    }

    public function getKotaAsalSekolahAttribute()
    {
        return $this->getAdditionalData('kota_asal_sekolah');
    }

    public function getNomorTeleponAsalSekolahAttribute()
    {
        return $this->getAdditionalData('nomor_telepon_asal_sekolah');
    }

    public function getNisnAttribute()
    {
        return $this->getAdditionalData('nisn');
    }

    public function getClassOptionAttribute()
    {
        return $this->getAdditionalData('class_option');
    }

    public function getTahunLulusAttribute()
    {
        return $this->getAdditionalData('tahun_lulus');
    }

    public function getNomorSeriShunAttribute()
    {
        return $this->getAdditionalData('nomor_seri_shun');
    }

    public function getNomorSeriIjazahAttribute()
    {
        return $this->getAdditionalData('nomor_seri_ijazah');
    }

    public function getNomorUjianNasionalAttribute()
    {
        return $this->getAdditionalData('nomor_ujian_nasional');
    }

    public function getPernahDirawatAttribute()
    {
        return $this->getAdditionalData('pernah_dirawat');
    }

    public function getKapanDirawatAttribute()
    {
        return $this->getAdditionalData('kapan_dirawat');
    }

    public function getAlergiAttribute()
    {
        return $this->getAdditionalData('alergi');
    }

    public function getKontakDaruratKeluargaAttribute()
    {
        return $this->getAdditionalData('kontak_darurat_keluarga');
    }

    public function getPrestasiAkademikAttribute()
    {
        return $this->getAdditionalData('prestasi_akademik');
    }

    public function getPrestasiNonakademikAttribute()
    {
        return $this->getAdditionalData('prestasi_nonakademik');
    }

    public function getPrestasiLainnyaAttribute()
    {
        return $this->getAdditionalData('prestasi_lainnya');
    }

    public function getPotensiDanBakatSainsAttribute()
    {
        return $this->getAdditionalData('potensi_dan_bakat_sains');
    }

    public function getPotensiDanBakatSeniAttribute()
    {
        return $this->getAdditionalData('potensi_dan_bakat_seni');
    }

    public function getPotensiDanBakatOlahragaAttribute()
    {
        return $this->getAdditionalData('potensi_dan_bakat_olahraga');
    }

    public function getPotensiDanBakatLainnyaAttribute()
    {
        return $this->getAdditionalData('potensi_dan_bakat_lainnya');
    }

    public function getKartuGolonganDarahAttribute()
    {
        return $this->getAdditionalData('kartu_golongan_darah');
    }

    public function getKmsAttribute()
    {
        return $this->getAdditionalData('kms');
    }

    public function getKabupatenAsalSekolahAttribute()
    {
        return $this->getAdditionalData('kabupaten_asal_sekolah');
    }

    public function getKecamatanAsalSekolahAttribute()
    {
        return $this->getAdditionalData('kecamatan_asal_sekolah');
    }

    public function getKelurahanAsalSekolahAttribute()
    {
        return $this->getAdditionalData('kelurahan_asal_sekolah');
    }

    public function getProvinsiAsalSekolahAttribute()
    {
        return $this->getAdditionalData('provinsi_asal_sekolah');
    }

    public function getTransportasiKeSekolahAttribute()
    {
        return $this->getAdditionalData('transportasi_ke_sekolah');
    }

    public function getNikAyahAttribute()
    {
        return $this->getAdditionalData('nik_ayah');
    }

    public function getNikIbuAttribute()
    {
        return $this->getAdditionalData('nik_ibu');
    }

    public function getDevelopmentFeeOptionAttribute()
    {
        return $this->getAdditionalData('development_fee_option');
    }

    public function getAngsuran1Attribute()
    {
        return $this->getAdditionalData('angsuran_1');
    }

    public function getAngsuran2Attribute()
    {
        return $this->getAdditionalData('angsuran_2');
    }

    public function getAngsuran3Attribute()
    {
        return $this->getAdditionalData('angsuran_3');
    }

    public function getAngsuran4Attribute()
    {
        return $this->getAdditionalData('angsuran_4');
    }

    public function getAngsuran5Attribute()
    {
        return $this->getAdditionalData('angsuran_5');
    }

    public function getCicilan1Attribute()
    {
        return $this->getAdditionalData('cicilan_1');
    }

    public function getCicilan2Attribute()
    {
        return $this->getAdditionalData('cicilan_2');
    }

    public function getCicilan3Attribute()
    {
        return $this->getAdditionalData('cicilan_3');
    }

    public function getCicilan4Attribute()
    {
        return $this->getAdditionalData('cicilan_4');
    }

    public function getCicilan5Attribute()
    {
        return $this->getAdditionalData('cicilan_5');
    }

    public function getReportCardsAttribute()
    {
        $reports = json_decode($this->attributes['report_cards'], TRUE);
        if (!$reports) {
            return [];
        }

        return $reports;
    }

    public function stages()
    {
        return $this->join('stages', function ($join) {
            return $join->on('ppdb_users.unit_id', '=', 'stages.unit_id')
                ->on('ppdb_users.periode', '=', 'stages.periode');
        })->leftJoin('ppdb_user_stages', function ($join) {
            return $join->on('ppdb_users.id', '=', 'ppdb_user_stages.ppdb_user_id')
                ->on('ppdb_user_stages.stage_id', '=', 'stages.id');
        })->where('stages.active', true)
            ->where('ppdb_users.id', $this->id)
            ->select('stages.*', 'ppdb_user_stages.passed', 'ppdb_user_stages.note', 'ppdb_user_stages.id as ppdb_user_stage_id')
            ->get()->each(function ($stage) {

                if ($stage->passed == 1) {
                    return $stage->passed = 'LOLOS';
                }

                if ($stage->passed == 2) {
                    return $stage->passed = 'PENDING';
                }

                if ($stage->passed === 0) {
                    return $stage->passed = 'TIDAK LOLOS';
                }

                if ($stage->passed === 3) {
                    return $stage->passed = 'ULANGI PROSES';
                }

                return $stage->passed = '-';
            });
    }

    public function stageById($id)
    {
        $stages = $this->stages;
        return $stages->filter(function ($value) use ($id) {
            return $value->stage_id == $id;
        })->first();
    }

    public function stageResults()
    {
        return $this->unit->activeStages
            ->each(function ($stage) {
                $findSelected = $this->stages->where('stage_id', $stage->id)
                    ->first();

                return $stage->passed = $findSelected
                    ? $findSelected->passed_text
                    : "-";
            });
    }

    public function isStageDone()
    {
        return $this->stages->count() >= $this->unit->activeStages->count();
    }

    public function isStatusStageDone()
    {
        return $this->stages()->filter(function ($stage) {
                return $stage->passed == 'LOLOS';
            })->count() >= $this->unit->activeStages->count();
    }

    public function isNewStatusStageDone()
    {
        return $this->stages()->filter(function ($stage) {
                return $stage->passed == 'LOLOS';
            })->count();
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getIsWaitingPaymentAttribute()
    {
        return $this->payment_form == '' && $this->payment_date == '';
    }

    public function getIsPaymentFormVerifiedAttribute()
    {
        return $this->payment_form == '' && $this->payment_date != '';
    }

    public function getIsPaymentFormWithVirtualAccountAttribute()
    {
        return $this->payment_option == 'BCA';
    }
}
