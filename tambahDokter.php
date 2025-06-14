<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if($_POST) {
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO dokter (nama, spesialisasi, no_telp, alamat) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $spesialisasi, $no_telp, $alamat]);
        
        header('Location: dokter.php?success=1');
        exit();
    } catch(PDOException $e) {
        header('Location: formDokter.php?error=1');
        exit();
    }
} else {
    header('Location: formDokter.php');
    exit();
}
?>
