<?php
require 'config.php';
$user = wajib_login();
mulai('Profil');
?>
<h1>Profil</h1>
<div class="kartu jadwal-info"><div class="ikon-box"><?= ikon('user') ?></div>
  <div><strong><?= e($user['nama']) ?></strong><small>Peran: <?= e(ucfirst($user['role'])) ?></small></div></div>
<a class="btn garis" href="logout.php">Keluar</a>
<?php selesai('profil');