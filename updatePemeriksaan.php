<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if($_POST) {
    $id = $_POST['id'];
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
        
        // Get existing obat resep untuk mengembalikan stok
        $stmt = $pdo->prepare("SELECT id_obat, jumlah FROM detail_obat_pemeriksaan WHERE id_pemeriksaan = ?");
        $stmt->execute([$id]);
        $existing_obat = $stmt->fetchAll();
        
        // Kembalikan stok obat yang lama
        foreach($existing_obat as $obat) {
            $stmt = $pdo->prepare("UPDATE obat SET stok = stok + ? WHERE id = ?");
            $stmt->execute([$obat['jumlah'], $obat['id_obat']]);
        }
        
        // Delete existing obat resep
        $stmt = $pdo->prepare("DELETE FROM detail_obat_pemeriksaan WHERE id_pemeriksaan = ?");
        $stmt->execute([$id]);
        
        // Update pemeriksaan
        $stmt = $pdo->prepare("UPDATE pemeriksaan SET id_dokter = ?, id_pasien = ?, tanggal_periksa = ?, keluhan = ?, diagnosa = ?, tindakan = ?, biaya = ? WHERE id = ?");
        $stmt->execute([$id_dokter, $id_pasien, $tanggal_periksa, $keluhan, $diagnosa, $tindakan, $biaya, $id]);
        
        // Insert new obat resep
        for($i = 0; $i < count($obat_ids); $i++) {
            if(!empty($obat_ids[$i]) && !empty($jumlah_obat[$i])) {
                // Check stok availability
                $stmt = $pdo->prepare("SELECT stok FROM obat WHERE id = ?");
                $stmt->execute([$obat_ids[$i]]);
                $obat_data = $stmt->fetch();
                
                if($obat_data && $obat_data['stok'] >= $jumlah_obat[$i]) {
                    $stmt = $pdo->prepare("INSERT INTO detail_obat_pemeriksaan (id_pemeriksaan, id_obat, jumlah, aturan_pakai) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$id, $obat_ids[$i], $jumlah_obat[$i], $aturan_pakai[$i]]);
                    
                    // Update stok obat
                    $stmt = $pdo->prepare("UPDATE obat SET stok = stok - ? WHERE id = ?");
                    $stmt->execute([$jumlah_obat[$i], $obat_ids[$i]]);
                } else {
                    throw new Exception("Stok obat tidak mencukupi");
                }
            }
        }
        
        $pdo->commit();
        header('Location: pemeriksaan.php?updated=1');
        exit();
    } catch(Exception $e) {
        $pdo->rollback();
        header('Location: editPemeriksaan.php?id=' . $id . '&error=1');
        exit();
    }
} else {
    header('Location: pemeriksaan.php');
    exit();
}
?>
