<?php
require 'config.php';
wajib_login();
$j = ambil_jadwal($pdo, (int)($_GET['id'] ?? 0));
if (!$j) { header('Location: jadwal.php'); exit; }

mulai('Scan QR Code', 'metode.php?id=' . $j['id_jadwal'], 'Pilih metode');
?>
<h1>Scan QR Code</h1>
<p class="kecil">Arahkan kamera ke kode QR latihan.</p>
<?= kartu_jadwal($j) ?>
<!-- Tampilan kamera masih berupa placeholder. Pemindaian QR asli bisa ditambahkan nanti dengan library JavaScript. -->
<div class="kamera"><div class="bingkai"><i></i><i></i><i></i><i></i></div></div>
<p class="kecil center">Posisikan kode QR di dalam bingkai.<br>Pastikan kode terlihat jelas.</p>
<a class="btn garis" href="manual.php?id=<?= $j['id_jadwal'] ?>">Gunakan Absensi Manual</a>
<?php selesai('absensi');