<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if($_POST) {
    $id_dokter = $_POST['id_dokter'];
    $id_pasien = $_POST['id_pasien'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $keluhan = $_POST['keluhan'];
    $diagnosa = $_POST['diagnosa'];
    $tindakan = $_POST['tindakan'];
    $biaya = $_POST['biaya'];
    
    // Arrays untuk obat
    $obat_ids = $_POST['obat'] ?? [];
    $jumlah_obat = $_POST['jumlah'] ?? [];
    $aturan_pakai = $_POST['aturan_pakai'] ?? [];
    
    try {
        $pdo->beginTransaction();
        
        // Insert pemeriksaan
        $stmt = $pdo->prepare("INSERT INTO pemeriksaan (id_dokter, id_pasien, tanggal_periksa, keluhan, diagnosa, tindakan, biaya) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_dokter, $id_pasien, $tanggal_periksa, $keluhan, $diagnosa, $tindakan, $biaya]);
        
        $id_pemeriksaan = $pdo->lastInsertId();
        
        // Insert detail obat jika ada
        for($i = 0; $i < count($obat_ids); $i++) {
            if(!empty($obat_ids[$i]) && !empty($jumlah_obat[$i])) {
                $stmt = $pdo->prepare("INSERT INTO detail_obat_pemeriksaan (id_pemeriksaan, id_obat, jumlah, aturan_pakai) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_pemeriksaan, $obat_ids[$i], $jumlah_obat[$i], $aturan_pakai[$i]]);
                
                // Update stok obat
                $stmt = $pdo->prepare("UPDATE obat SET stok = stok - ? WHERE id = ?");
                $stmt->execute([$jumlah_obat[$i], $obat_ids[$i]]);
            }
        }
        
        $pdo->commit();
        header('Location: pemeriksaan.php?success=1');
        exit();
    } catch(PDOException $e) {
        $pdo->rollback();
        header('Location: formPemeriksaan.php?error=1');
        exit();
    }
} else {
    header('Location: formPemeriksaan.php');
    exit();
}
?>
