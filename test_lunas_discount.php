<?php

use App\Models\PPDBUser;
use App\Helpers\PriceHelper;

// 5537 is yuliantimuda@gmail.com
$ppdb = PPDBUser::find(5537);

if (!$ppdb) {
    echo "Siswa tidak ditemukan!\n";
    exit;
}

echo "=== Data Siswa ===\n";
echo "Nama: " . $ppdb->name . "\n";
echo "Register Number: " . $ppdb->register_number . "\n\n";

echo "=== Menjalankan checkDevelopmentLunasDiscount ===\n";
$result = PriceHelper::checkDevelopmentLunasDiscount($ppdb);

echo "Hasil Array yang dikembalikan:\n";
print_r($result);

echo "\n";
echo "=== Kesimpulan ===\n";
if ($result['is_eligible_discount']) {
    echo "[!] Siswa MENDAPATKAN diskon sebesar " . $result['discount_percentage'] . "%\n";
} else {
    echo "[!] Siswa TIDAK MENDAPATKAN diskon (Karena terdaftar di finance_user & is_insider = 1)\n";
}

if ($result['is_eligible_free_voucher']) {
    echo "[!] Siswa MENDAPATKAN free voucher (Selalu berlaku untuk Lunas)\n";
}
