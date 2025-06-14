<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

// Get all pemeriksaan with relations
$stmt = $pdo->query("
    SELECT p.*, d.nama as nama_dokter, pas.nama as nama_pasien 
    FROM pemeriksaan p 
    JOIN dokter d ON p.id_dokter = d.id 
    JOIN pasien pas ON p.id_pasien = pas.id 
    ORDER BY p.tanggal_periksa DESC
");
$pemeriksaan_list = $stmt->fetchAll();

$title = 'Data Pemeriksaan - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-stethoscope"></i> Data Pemeriksaan</h2>
            <a href="formPemeriksaan.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pemeriksaan
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Dokter</th>
                        <th>Pasien</th>
                        <th>Keluhan</th>
                        <th>Diagnosa</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($pemeriksaan_list) > 0): ?>
                        <?php $no = 1; foreach($pemeriksaan_list as $periksa): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($periksa['tanggal_periksa'])); ?></td>
                            <td><?php echo htmlspecialchars($periksa['nama_dokter']); ?></td>
                            <td><?php echo htmlspecialchars($periksa['nama_pasien']); ?></td>
                            <td><?php echo htmlspecialchars(substr($periksa['keluhan'], 0, 50)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars(substr($periksa['diagnosa'], 0, 50)) . '...'; ?></td>
                            <td>Rp <?php echo number_format($periksa['biaya'], 0, ',', '.'); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="detailPemeriksaan.php?id=<?php echo $periksa['id']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="editPemeriksaan.php?id=<?php echo $periksa['id']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data pemeriksaan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
