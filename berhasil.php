<?php
require 'config.php';
$user = wajib_login();
$j = ambil_jadwal($pdo, (int)($_GET['id'] ?? 0));
if (!$j) { header('Location: beranda.php'); exit; }

mulai('Absensi Berhasil');
?>
<div class="sukses">
  <div class="lingkaran"><?= ikon('check') ?></div>
  <h1>Absensi Berhasil</h1>
  <p class="kecil">Terima kasih, <?= e($user['nama']) ?>.<br>Tetap semangat dan disiplin berlatih!</p>
</div>
<?= kartu_jadwal($j) ?>
<a class="btn" href="beranda.php">Kembali ke Beranda</a>
<?php selesai('absensi');