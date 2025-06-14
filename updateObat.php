<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if($_POST) {
    $id = $_POST['id'];
    $nama_obat = $_POST['nama_obat'];
    $jenis = $_POST['jenis'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $keterangan = $_POST['keterangan'];
    
    try {
        $stmt = $pdo->prepare("UPDATE obat SET nama_obat = ?, jenis = ?, stok = ?, harga = ?, keterangan = ? WHERE id = ?");
        $stmt->execute([$nama_obat, $jenis, $stok, $harga, $keterangan, $id]);
        
        header('Location: obat.php?updated=1');
        exit();
    } catch(PDOException $e) {
        header('Location: editObat.php?id=' . $id . '&error=1');
        exit();
    }
} else {
    header('Location: obat.php');
    exit();
}
?>
