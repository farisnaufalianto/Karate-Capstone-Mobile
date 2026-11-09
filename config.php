<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=karate_absensi;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function wajib_login() {
    if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
    return $_SESSION['user'];
}

function tgl($d, $hari = true) {
    $h = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $b = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $t = strtotime($d);
    return ($hari ? $h[date('w', $t)] . ', ' : '') . date('j', $t) . ' ' . $b[(int)date('n', $t)] . ' ' . date('Y', $t);
}

function tgl_pendek($d, $hari = false) {
    $h = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $b = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $t = strtotime($d);
    return ($hari ? $h[date('w', $t)] . ', ' : '') . date('j', $t) . ' ' . $b[(int)date('n', $t)];
}

function jam($a, $b) {
    return str_replace(':', '.', substr($a, 0, 5)) . '–' . str_replace(':', '.', substr($b, 0, 5));
}

function ikon($n) {
    $p = [
     'home'=>'<path d="M3 10.5 12 3l9 7.5V21H3z"/>',
     'calendar'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
     'clipboard'=>'<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2M9 14l2 2 4-4"/>',
     'user'=>'<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
     'pin'=>'<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
     'check'=>'<path d="M20 6 9 17l-5-5"/>',
     'clock'=>'<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
     'x'=>'<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
     'qr'=>'<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3h-3zM20 14v7h-3"/>',
     'edit'=>'<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
     'chev'=>'<path d="m9 18 6-6-6-6"/>',
     'back'=>'<path d="m15 18-6-6 6-6"/>',
     'bell'=>'<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10 21a2 2 0 0 0 4 0"/>',
     'history'=>'<path d="M3 12a9 9 0 1 0 3-6.7L3 8M3 3v5h5M12 7v5l3 2"/>',
     'book'=>'<path d="M2 4h7a3 3 0 0 1 3 3v14a2 2 0 0 0-2-2H2zM22 4h-7a3 3 0 0 0-3 3v14a2 2 0 0 1 2-2h8z"/>',
     'lock'=>'<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
     'eye'=>'<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
    ];
    return '<svg class="ikon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . ($p[$n] ?? '') . '</svg>';
}

function logo() {
    // Placeholder siluet Karate. Ganti dengan logo asli (export SVG dari Figma).
    return '<svg viewBox="0 0 32 32" fill="none" stroke="#1D4ED8" stroke-width="3" stroke-linecap="round"><circle cx="11" cy="6" r="2.5" fill="#1D4ED8"/><path d="M11 10v7M11 12l-5 3M11 12l5-2M11 17l15-4M11 17l-4 9"/></svg>';
}

function badge($status) {
    $m = ['Hadir'=>['hadir','check'], 'Izin'=>['izin','clock'], 'Tidak Hadir'=>['tidak','x']];
    [$c, $i] = $m[$status];
    return '<span class="badge ' . $c . '">' . ikon($i) . e($status) . '</span>';
}

function kartu_jadwal($j) {
    return '<div class="kartu jadwal-info"><div class="ikon-box">' . ikon('calendar') . '</div><div>'
        . '<strong>' . e($j['nama_latihan']) . '</strong>'
        . '<small>' . tgl($j['tanggal']) . '<br>' . jam($j['jam_mulai'], $j['jam_selesai']) . '</small>'
        . '<small class="lokasi">' . ikon('pin') . e($j['lokasi']) . '</small></div></div>';
}

function ambil_jadwal($pdo, $id) {
    $s = $pdo->prepare('SELECT * FROM jadwal_latihan WHERE id_jadwal = ?');
    $s->execute([$id]);
    return $s->fetch();
}

function mulai($judul, $kembali = null, $label = '') {
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1">'
       . '<title>' . e($judul) . ' | Karate</title>'
       . '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">'
       . '<link rel="stylesheet" href="assets/style.css"></head><body><div class="app"><main class="isi">';
    if ($kembali) echo '<a class="kembali" href="' . e($kembali) . '">' . ikon('back') . e($label) . '</a>';
}

function selesai($aktif = '') {
    $m = ['beranda'=>['Beranda','home'], 'jadwal'=>['Jadwal','calendar'], 'absensi'=>['Absensi','clipboard'], 'profil'=>['Profil','user']];
    echo '</main><nav class="nav">';
    foreach ($m as $k => [$t, $i]) {
        echo '<a href="' . ($k === 'absensi' ? 'jadwal.php' : $k . '.php') . '" class="' . ($aktif === $k ? 'aktif' : '') . '"><span>' . ikon($i) . '</span>' . $t . '</a>';
    }
    echo '</nav></div></body></html>';
}