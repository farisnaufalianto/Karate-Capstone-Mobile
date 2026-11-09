<?php
// Jalankan SEKALI di browser (http://localhost/karate-absensi/setup.php), lalu HAPUS file ini.
require 'config.php';
$cek = $pdo->prepare('SELECT 1 FROM pengguna WHERE username = ?');
$cek->execute(['faris']);
if ($cek->fetch()) { exit('Akun sudah dibuat. Hapus file setup.php.'); }

$pdo->prepare('INSERT INTO pengguna (username, password_hash, nama, role) VALUES (?,?,?,?)')
    ->execute(['faris', password_hash('karate123', PASSWORD_DEFAULT), 'Faris', 'peserta']);
$uid = $pdo->lastInsertId();

// Riwayat contoh: tanggal => [status, alasan, jam]
$contoh = [
  '2026-11-06' => ['Hadir', null, '16:02'],
  '2026-11-04' => ['Izin', 'Ada keperluan keluarga', '10:15'],
  '2026-11-02' => ['Hadir', null, '16:01'],
  '2026-10-30' => ['Tidak Hadir', null, '18:00'],
];
foreach ($contoh as $tanggal => [$status, $alasan, $waktu]) {
    $j = $pdo->prepare('SELECT id_jadwal FROM jadwal_latihan WHERE tanggal = ?');
    $j->execute([$tanggal]);
    $id = $j->fetchColumn();
    $pdo->prepare('INSERT INTO kehadiran (id_jadwal,id_pengguna,waktu_absen,metode,status,alasan_izin) VALUES (?,?,?,?,?,?)')
        ->execute([$id, $uid, "$tanggal $waktu:00", 'Manual', $status, $alasan]);
}
echo 'Selesai. Login: faris / karate123. Sekarang hapus file setup.php.';