<?php
require 'config.php';
$user = wajib_login();
$s = $pdo->prepare('SELECT k.status, j.* FROM kehadiran k JOIN jadwal_latihan j ON j.id_jadwal = k.id_jadwal
                    WHERE k.id_pengguna = ? ORDER BY j.tanggal DESC, j.jam_mulai DESC');
$s->execute([$user['id']]);
$data = $s->fetchAll();

mulai('Riwayat Absensi', 'beranda.php', 'Beranda');
?>
<h1>Riwayat Absensi</h1>
<p class="kecil">Lihat kembali kehadiran latihanmu, <?= e($user['nama']) ?>.</p>
<div class="rentang"><span><?= $data ? tgl_pendek(end($data)['tanggal']) . '–' . tgl_pendek($data[0]['tanggal']) . ' ' . date('Y', strtotime($data[0]['tanggal'])) : 'Belum ada data' ?></span><small><?= count($data) ?> latihan</small></div>

<?php foreach ($data as $r): ?>
  <div class="kartu riwayat">
    <div class="baris-judul"><small><?= tgl($r['tanggal']) ?></small><?= badge($r['status']) ?></div>
    <strong><?= e($r['nama_latihan']) ?></strong>
    <small><?= jam($r['jam_mulai'], $r['jam_selesai']) ?> · <?= e($r['lokasi']) ?></small>
  </div>
<?php endforeach; ?>
<?php if (!$data): ?><div class="kartu"><p class="kecil">Belum ada riwayat. Isi absensi pertamamu di menu Jadwal.</p></div><?php endif; ?>
<?php selesai('absensi');