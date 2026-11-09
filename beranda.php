<?php
require 'config.php';
$user = wajib_login();

$j = $pdo->query('SELECT * FROM jadwal_latihan WHERE tanggal >= CURDATE() ORDER BY tanggal, jam_mulai LIMIT 1')->fetch();
$jumlah = $pdo->query("SELECT COUNT(*) FROM pengguna WHERE role = 'peserta'")->fetchColumn();

mulai('Beranda');
?>
<h1>Halo, <?= e($user['nama']) ?></h1>
<p class="kecil">Siap berlatih hari ini?</p>

<div class="baris-judul"><h2><?= ($j && $j['tanggal'] === date('Y-m-d')) ? 'Latihan hari ini' : 'Latihan berikutnya' ?></h2>
  <small><?= tgl_pendek(date('Y-m-d'), true) ?></small></div>
<?php if ($j): ?>
  <div class="kartu">
    <?= kartu_jadwal($j) ?>
    <div class="baris-judul"><small><?= (int)$jumlah ?> peserta terdaftar</small>
      <a class="link" href="metode.php?id=<?= $j['id_jadwal'] ?>">Lihat jadwal →</a></div>
  </div>
<?php else: ?>
  <div class="kartu"><p class="kecil">Belum ada jadwal latihan. Cek lagi nanti.</p></div>
<?php endif; ?>

<h2>Menu cepat</h2>
<div class="menu-cepat">
  <a href="jadwal.php"><span class="ikon-box"><?= ikon('calendar') ?></span>Jadwal Latihan</a>
  <a href="jadwal.php"><span class="ikon-box"><?= ikon('clipboard') ?></span>Absensi</a>
  <a href="riwayat.php"><span class="ikon-box"><?= ikon('history') ?></span>Riwayat Absensi</a>
  <a href="notifikasi.php"><span class="ikon-box"><?= ikon('bell') ?></span>Notifikasi</a>
</div>

<h2>Materi Latihan</h2>
<div class="materi"><span class="ikon-box kecil-box"><?= ikon('book') ?></span><div><strong>Kihon: pukulan &amp; tangkisan</strong><small>Oi-zuki, gyaku-zuki, gedan barai</small></div><?= ikon('chev') ?></div>
<div class="materi"><span class="ikon-box kecil-box"><?= ikon('book') ?></span><div><strong>Kata: Heian Shodan</strong><small>Urutan gerak &amp; kuda-kuda dasar</small></div><?= ikon('chev') ?></div>
<?php selesai('beranda');