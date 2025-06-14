<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if(!isset($_GET['id'])) {
    header('Location: pemeriksaan.php');
    exit();
}

$id = $_GET['id'];

// Get pemeriksaan detail
$stmt = $pdo->prepare("
    SELECT p.*, d.nama as nama_dokter, d.spesialisasi, pas.nama as nama_pasien, 
           pas.tanggal_lahir, pas.jenis_kelamin, pas.alamat as alamat_pasien, pas.no_telp as telp_pasien
    FROM pemeriksaan p 
    JOIN dokter d ON p.id_dokter = d.id 
    JOIN pasien pas ON p.id_pasien = pas.id 
    WHERE p.id = ?
");
$stmt->execute([$id]);
$pemeriksaan = $stmt->fetch();

if(!$pemeriksaan) {
    header('Location: pemeriksaan.php');
    exit();
}

// Get obat yang diresepkan
$stmt = $pdo->prepare("
    SELECT dop.*, o.nama_obat, o.jenis, o.harga 
    FROM detail_obat_pemeriksaan dop 
    JOIN obat o ON dop.id_obat = o.id 
    WHERE dop.id_pemeriksaan = ?
");
$stmt->execute([$id]);
$obat_resep = $stmt->fetchAll();

$title = 'Detail Pemeriksaan - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-medical"></i> Detail Pemeriksaan</h2>
            <div>
                <button onclick="window.print()" class="btn btn-success me-2">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <a href="pemeriksaan.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-stethoscope"></i> Rekam Medis</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Tanggal Pemeriksaan:</strong><br>
                        <?php echo date('d F Y, H:i', strtotime($pemeriksaan['tanggal_periksa'])); ?> WIB
                    </div>
                    <div class="col-md-6">
                        <strong>Dokter Pemeriksa:</strong><br>
                        Dr. <?php echo htmlspecialchars($pemeriksaan['nama_dokter']); ?><br>
                        <small class="text-muted"><?php echo htmlspecialchars($pemeriksaan['spesialisasi']); ?></small>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Keluhan Pasien:</strong>
                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($pemeriksaan['keluhan'])); ?></p>
                </div>
                
                <div class="mb-3">
                    <strong>Diagnosa:</strong>
                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($pemeriksaan['diagnosa'])); ?></p>
                </div>
                
                <?php if($pemeriksaan['tindakan']): ?>
                <div class="mb-3">
                    <strong>Tindakan:</strong>
                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($pemeriksaan['tindakan'])); ?></p>
                </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <strong>Biaya Pemeriksaan:</strong>
                    <h5 class="text-primary">Rp <?php echo number_format($pemeriksaan['biaya'], 0, ',', '.'); ?></h5>
                </div>
            </div>
        </div>
        
        <?php if(count($obat_resep) > 0): ?>
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-pills"></i> Resep Obat</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Aturan Pakai</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            $total_obat = 0;
                            foreach($obat_resep as $obat): 
                                $subtotal = $obat['jumlah'] * $obat['harga'];
                                $total_obat += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($obat['nama_obat']); ?></td>
                                <td><?php echo htmlspecialchars($obat['jenis']); ?></td>
                                <td><?php echo $obat['jumlah']; ?></td>
                                <td><?php echo htmlspecialchars($obat['aturan_pakai']); ?></td>
                                <td>Rp <?php echo number_format($obat['harga'], 0, ',', '.'); ?></td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <th colspan="6">Total Biaya Obat:</th>
                                <th>Rp <?php echo number_format($total_obat, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-user"></i> Data Pasien</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td><?php echo htmlspecialchars($pemeriksaan['nama_pasien']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Lahir:</strong></td>
                        <td><?php echo date('d/m/Y', strtotime($pemeriksaan['tanggal_lahir'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Umur:</strong></td>
                        <td>
                            <?php 
                            $birthDate = new DateTime($pemeriksaan['tanggal_lahir']);
                            $today = new DateTime('today');
                            $age = $birthDate->diff($today)->y;
                            echo $age . ' tahun';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin:</strong></td>
                        <td><?php echo $pemeriksaan['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td><?php echo htmlspecialchars($pemeriksaan['telp_pasien']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td><?php echo nl2br(htmlspecialchars($pemeriksaan['alamat_pasien'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6><i class="fas fa-calculator"></i> Ringkasan Biaya</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Biaya Pemeriksaan:</td>
                        <td class="text-end">Rp <?php echo number_format($pemeriksaan['biaya'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php if(isset($total_obat) && $total_obat > 0): ?>
                    <tr>
                        <td>Biaya Obat:</td>
                        <td class="text-end">Rp <?php echo number_format($total_obat, 0, ',', '.'); ?></td>
                    </tr>
                    <tr class="table-primary">
                        <th>Total Keseluruhan:</th>
                        <th class="text-end">Rp <?php echo number_format($pemeriksaan['biaya'] + $total_obat, 0, ',', '.'); ?></th>
                    </tr>
                    <?php else: ?>
                    <tr class="table-primary">
                        <th>Total Keseluruhan:</th>
                        <th class="text-end">Rp <?php echo number_format($pemeriksaan['biaya'], 0, ',', '.'); ?></th>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .no-print {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
