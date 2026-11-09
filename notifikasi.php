<?php
require 'config.php';
$user = wajib_login();
$s = $pdo->prepare("SELECT k.status, k.waktu_absen, j.* FROM kehadiran k JOIN jadwal_latihan j ON j.id_jadwal = k.id_jadwal
                    WHERE k.id_pengguna = ? AND k.status IN ('Hadir','Izin') ORDER BY k.waktu_absen DESC");
$s->execute([$user['id']]);
$data = $s->fetchAll();

mulai('Notifikasi', 'beranda.php', 'Beranda');
?>
<h1>Notifikasi</h1>
<p class="kecil">Konfirmasi kehadiran latihanmu, <?= e($user['nama']) ?>.</p>
<div class="baris-judul"><h2>Terbaru</h2><small><?= count($data) ?> notifikasi</small></div>

<?php foreach ($data as $r): ?>
  <div class="kartu notif">
    <span class="centang"><?= ikon('check') ?></span>
    <div><strong>Absensi berhasil</strong>
      <small><?= e($r['nama_latihan']) ?><br><?= tgl($r['tanggal']) ?></small>
      <small class="abu">Status <?= e($r['status']) ?> tercatat pukul <?= date('H.i', strtotime($r['waktu_absen'])) ?></small></div>
  </div>
<?php endforeach; ?>
<?php if (!$data): ?><div class="kartu"><p class="kecil">Belum ada notifikasi.</p></div><?php endif; ?>
<?php selesai('beranda');