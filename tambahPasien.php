<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if($_POST) {
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, no_telp, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $tanggal_lahir, $jenis_kelamin, $no_telp, $alamat]);
        
        header('Location: pasien.php?success=1');
        exit();
    } catch(PDOException $e) {
        header('Location: formPasien.php?error=1');
        exit();
    }
} else {
    header('Location: formPasien.php');
    exit();
}
?>
