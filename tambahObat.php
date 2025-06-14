<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if($_POST) {
    $nama_obat = $_POST['nama_obat'];
    $jenis = $_POST['jenis'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $keterangan = $_POST['keterangan'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO obat (nama_obat, jenis, stok, harga, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama_obat, $jenis, $stok, $harga, $keterangan]);
        
        header('Location: obat.php?success=1');
        exit();
    } catch(PDOException $e) {
        header('Location: formObat.php?error=1');
        exit();
    }
} else {
    header('Location: formObat.php');
    exit();
}
?>
