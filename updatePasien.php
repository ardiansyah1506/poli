<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if($_POST) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    
    try {
        $stmt = $pdo->prepare("UPDATE pasien SET nama = ?, tanggal_lahir = ?, jenis_kelamin = ?, no_telp = ?, alamat = ? WHERE id = ?");
        $stmt->execute([$nama, $tanggal_lahir, $jenis_kelamin, $no_telp, $alamat, $id]);
        
        header('Location: pasien.php?updated=1');
        exit();
    } catch(PDOException $e) {
        header('Location: editPasien.php?id=' . $id . '&error=1');
        exit();
    }
} else {
    header('Location: pasien.php');
    exit();
}
?>
