<?php
require 'config.php';
wajib_login();

$daftar = $pdo->query('SELECT * FROM jadwal_latihan WHERE tanggal >= CURDATE() ORDER BY tanggal, jam_mulai')->fetchAll();

mulai('Jadwal Latihan');
?>
<h1>Jadwal Latihan</h1>
<p class="kecil">Pilih jadwal latihan yang akan diikuti.</p>
<div class="baris-judul"><h2>Minggu ini</h2><small><?= count($daftar) ?> latihan</small></div>

<?php foreach ($daftar as $j): ?>
  <a class="kartu pilih" href="metode.php?id=<?= $j['id_jadwal'] ?>">
    <?= kartu_jadwal($j) ?>
    <span class="link kanan">Pilih jadwal</span>
  </a>
<?php endforeach; ?>
<?php if (!$daftar): ?><div class="kartu"><p class="kecil">Belum ada jadwal latihan.</p></div><?php endif; ?>
<?php selesai('jadwal');