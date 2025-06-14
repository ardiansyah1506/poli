<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

// Hitung statistik
$stmt = $pdo->query("SELECT COUNT(*) as total FROM dokter");
$total_dokter = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM pasien");
$total_pasien = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM obat");
$total_obat = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM pemeriksaan");
$total_pemeriksaan = $stmt->fetch()['total'];

$title = 'Dashboard - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
        <p class="text-muted">Selamat datang, <?php echo $_SESSION['nama']; ?>!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo $total_dokter; ?></h4>
                        <p class="mb-0">Total Dokter</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-md fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="dokter.php" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo $total_pasien; ?></h4>
                        <p class="mb-0">Total Pasien</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="pasien.php" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo $total_obat; ?></h4>
                        <p class="mb-0">Total Obat</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-pills fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="obat.php" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo $total_pemeriksaan; ?></h4>
                        <p class="mb-0">Total Pemeriksaan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-stethoscope fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="pemeriksaan.php" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Statistik Obat</h5>
            </div>
            <div class="card-body">
                <?php
                $stmt = $pdo->query("SELECT nama_obat, stok FROM obat ORDER BY stok ASC LIMIT 5");
                $obat_stok = $stmt->fetchAll();
                ?>
                <h6>Stok Obat Terendah:</h6>
                <ul class="list-group list-group-flush">
                    <?php foreach($obat_stok as $obat): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?php echo $obat['nama_obat']; ?></span>
                        <span class="badge bg-<?php echo $obat['stok'] < 20 ? 'danger' : 'primary'; ?>">
                            <?php echo $obat['stok']; ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-alt"></i> Pemeriksaan Terbaru</h5>
            </div>
            <div class="card-body">
                <?php
                $stmt = $pdo->query("
                    SELECT p.tanggal_periksa, pas.nama as nama_pasien, d.nama as nama_dokter 
                    FROM pemeriksaan p 
                    JOIN pasien pas ON p.id_pasien = pas.id 
                    JOIN dokter d ON p.id_dokter = d.id 
                    ORDER BY p.tanggal_periksa DESC 
                    LIMIT 5
                ");
                $pemeriksaan_terbaru = $stmt->fetchAll();
                ?>
                <ul class="list-group list-group-flush">
                    <?php foreach($pemeriksaan_terbaru as $periksa): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong><?php echo $periksa['nama_pasien']; ?></strong><br>
                                <small class="text-muted">Dr. <?php echo $periksa['nama_dokter']; ?></small>
                            </div>
                            <small class="text-muted">
                                <?php echo date('d/m/Y H:i', strtotime($periksa['tanggal_periksa'])); ?>
                            </small>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
