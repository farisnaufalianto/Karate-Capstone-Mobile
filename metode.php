<?php
require 'config.php';
wajib_login();
$j = ambil_jadwal($pdo, (int)($_GET['id'] ?? 0));
if (!$j) { header('Location: jadwal.php'); exit; }

mulai('Pilih Metode Absensi', 'jadwal.php', 'Jadwal Latihan');
?>
<h1>Pilih Metode Absensi</h1>
<p class="kecil">Pilih cara untuk mengisi absensi latihan.</p>
<?= kartu_jadwal($j) ?>

<h2>Metode absensi</h2>
<a class="kartu opsi" href="qr.php?id=<?= $j['id_jadwal'] ?>">
  <span class="ikon-box"><?= ikon('qr') ?></span>
  <div><strong>Scan QR Code</strong><small>Pindai kode QR yang disediakan pelatih.</small></div><?= ikon('chev') ?></a>
<a class="kartu opsi" href="manual.php?id=<?= $j['id_jadwal'] ?>">
  <span class="ikon-box"><?= ikon('edit') ?></span>
  <div><strong>Absensi Manual</strong><small>Pilih status hadir atau ajukan izin latihan.</small></div><?= ikon('chev') ?></a>
<p class="info">Tidak dapat memindai QR? Gunakan Absensi Manual.</p>
<?php selesai('absensi');