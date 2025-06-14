<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $dokter = $_POST['dokter_id'];
    $pasien = $_POST['pasien_id'];
    $keluhan = $_POST['keluhan'];
    $diagnosa = $_POST['diagnosa'];
    $tindakan = $_POST['tindakan'];
    $obat = $_POST['obat_id'];
    $tanggal = $_POST['tanggal'];

    $sql = "INSERT INTO rekam_medis (dokter_id, pasien_id, keluhan, diagnosa, tindakan, obat_id, tanggal)
            VALUES ('$dokter', '$pasien', '$keluhan', '$diagnosa', '$tindakan', '$obat', '$tanggal')";

    if ($koneksi->query($sql)) {
        echo "<div class='alert alert-success'>Data berhasil disimpan.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan data.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rekam Medis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Form Rekam Medis</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row mb-3">
                    <div class="col">
                        <label>Dokter</label>
                        <select name="dokter_id" class="form-select">
                            <?php
                            $dokter = $koneksi->query("SELECT * FROM dokter");
                            while ($d = $dokter->fetch_assoc()) {
                                echo "<option value='{$d['id']}'>{$d['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label>Pasien</label>
                        <select name="pasien_id" class="form-select">
                            <?php
                            $pasien = $koneksi->query("SELECT * FROM pasien");
                            while ($p = $pasien->fetch_assoc()) {
                                echo "<option value='{$p['id']}'>{$p['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Keluhan</label>
                    <textarea name="keluhan" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Diagnosa</label>
                    <textarea name="diagnosa" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Tindakan</label>
                    <textarea name="tindakan" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Obat</label>
                    <select name="obat_id" class="form-select">
                        <?php
                        $obat = $koneksi->query("SELECT * FROM obat");
                        while ($o = $obat->fetch_assoc()) {
                            echo "<option value='{$o['id']}'>{$o['nama_obat']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control">
                </div>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
