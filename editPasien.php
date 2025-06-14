<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if(!isset($_GET['id'])) {
    header('Location: pasien.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM pasien WHERE id = ?");
$stmt->execute([$id]);
$pasien = $stmt->fetch();

if(!$pasien) {
    header('Location: pasien.php');
    exit();
}

$title = 'Edit Pasien - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-edit"></i> Edit Pasien</h2>
            <a href="pasien.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        Gagal mengupdate data pasien!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Edit Pasien</h5>
            </div>
            <div class="card-body">
                <form action="updatePasien.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $pasien['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               value="<?php echo htmlspecialchars($pasien['nama']); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                       value="<?php echo $pasien['tanggal_lahir']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" <?php echo $pasien['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?php echo $pasien['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" 
                               value="<?php echo htmlspecialchars($pasien['no_telp']); ?>" placeholder="081234567890">
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"><?php echo htmlspecialchars($pasien['alamat']); ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="pasien.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-info-circle"></i> Informasi</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-calendar text-info"></i> Dibuat: <?php echo date('d/m/Y H:i', strtotime($pasien['created_at'])); ?></li>
                    <li><i class="fas fa-birthday-cake text-info"></i> Umur: 
                        <?php 
                        $birthDate = new DateTime($pasien['tanggal_lahir']);
                        $today = new DateTime('today');
                        $age = $birthDate->diff($today)->y;
                        echo $age . ' tahun';
                        ?>
                    </li>
                    <li><i class="fas fa-check text-success"></i> Pastikan data sudah benar sebelum update</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
