<?php
require 'config.php';
$user = wajib_login();
$id = (int)($_GET['id'] ?? 0);
$j = ambil_jadwal($pdo, $id);
if (!$j) { header('Location: jadwal.php'); exit; }

$error = '';
$status = $_POST['status'] ?? 'Hadir';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alasan = trim($_POST['alasan'] ?? '');
    $ada = $pdo->prepare('SELECT 1 FROM kehadiran WHERE id_jadwal = ? AND id_pengguna = ?');
    $ada->execute([$id, $user['id']]);

    if (!in_array($status, ['Hadir', 'Izin'], true)) {
        $error = 'Pilih status kehadiran.';
    } elseif ($status === 'Izin' && $alasan === '') {
        $error = 'Tuliskan alasan izin sebelum mengirim absensi.';
    } elseif ($ada->fetch()) {
        $error = 'Anda sudah mengisi absensi untuk latihan ini.';
    } else {
        $pdo->prepare('INSERT INTO kehadiran (id_jadwal, id_pengguna, waktu_absen, metode, status, alasan_izin) VALUES (?,?,NOW(),?,?,?)')
            ->execute([$id, $user['id'], 'Manual', $status, $status === 'Izin' ? $alasan : null]);
        header('Location: berhasil.php?id=' . $id); exit;
    }
}

mulai('Absensi Manual', 'metode.php?id=' . $id, 'Pilih metode');
?>
<h1>Absensi Manual</h1>
<p class="kecil">Isi status kehadiran untuk latihan ini.</p>
<?= kartu_jadwal($j) ?>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>

<form method="post">
  <h2>Status kehadiran</h2>
  <div class="radio-grup">
    <label class="radio hadir <?= $status === 'Hadir' ? 'on' : '' ?>"><span class="badge hadir"><?= ikon('check') ?>Hadir</span>
      <input type="radio" name="status" value="Hadir" <?= $status === 'Hadir' ? 'checked' : '' ?>></label>
    <label class="radio izin <?= $status === 'Izin' ? 'on' : '' ?>"><span class="badge izin"><?= ikon('clock') ?>Izin</span>
      <input type="radio" name="status" value="Izin" <?= $status === 'Izin' ? 'checked' : '' ?>></label>
  </div>
  <p class="kecil" id="petunjuk">Pilih Hadir jika Anda mengikuti latihan.</p>

  <div id="kotak-alasan" hidden>
    <h2>Alasan Izin</h2>
    <textarea name="alasan" rows="4" placeholder="Tuliskan alasan tidak mengikuti latihan"><?= e($_POST['alasan'] ?? '') ?></textarea>
    <p class="kecil">Sertakan alasan izin sebelum mengirim absensi.</p>
  </div>
  <button class="btn">Kirim Absensi</button>
</form>

<script>
  const radios = document.querySelectorAll('input[name=status]');
  function ubah() {
    const izin = document.querySelector('input[name=status]:checked').value === 'Izin';
    document.getElementById('kotak-alasan').hidden = !izin;
    document.getElementById('petunjuk').hidden = izin;
    document.querySelectorAll('.radio').forEach(r => r.classList.toggle('on', r.querySelector('input').checked));
  }
  radios.forEach(r => r.addEventListener('change', ubah));
  ubah();
</script>
<?php selesai('absensi');