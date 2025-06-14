<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if(!isset($_GET['id'])) {
    header('Location: dokter.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM dokter WHERE id = ?");
$stmt->execute([$id]);
$dokter = $stmt->fetch();

if(!$dokter) {
    header('Location: dokter.php');
    exit();
}

$title = 'Edit Dokter - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-edit"></i> Edit Dokter</h2>
            <a href="dokter.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        Gagal mengupdate data dokter!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Edit Dokter</h5>
            </div>
            <div class="card-body">
                <form action="updateDokter.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $dokter['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               value="<?php echo htmlspecialchars($dokter['nama']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="spesialisasi" class="form-label">Spesialisasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="spesialisasi" name="spesialisasi" required>
                            <option value="">Pilih Spesialisasi</option>
                            <option value="Umum" <?php echo $dokter['spesialisasi'] == 'Umum' ? 'selected' : ''; ?>>Dokter Umum</option>
                            <option value="Anak" <?php echo $dokter['spesialisasi'] == 'Anak' ? 'selected' : ''; ?>>Dokter Anak</option>
                            <option value="Kandungan" <?php echo $dokter['spesialisasi'] == 'Kandungan' ? 'selected' : ''; ?>>Dokter Kandungan</option>
                            <option value="Jantung" <?php echo $dokter['spesialisasi'] == 'Jantung' ? 'selected' : ''; ?>>Dokter Jantung</option>
                            <option value="Mata" <?php echo $dokter['spesialisasi'] == 'Mata' ? 'selected' : ''; ?>>Dokter Mata</option>
                            <option value="THT" <?php echo $dokter['spesialisasi'] == 'THT' ? 'selected' : ''; ?>>Dokter THT</option>
                            <option value="Kulit" <?php echo $dokter['spesialisasi'] == 'Kulit' ? 'selected' : ''; ?>>Dokter Kulit</option>
                            <option value="Saraf" <?php echo $dokter['spesialisasi'] == 'Saraf' ? 'selected' : ''; ?>>Dokter Saraf</option>
                            <option value="Bedah" <?php echo $dokter['spesialisasi'] == 'Bedah' ? 'selected' : ''; ?>>Dokter Bedah</option>
                            <option value="Gigi" <?php echo $dokter['spesialisasi'] == 'Gigi' ? 'selected' : ''; ?>>Dokter Gigi</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" 
                               value="<?php echo htmlspecialchars($dokter['no_telp']); ?>" placeholder="081234567890">
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"><?php echo htmlspecialchars($dokter['alamat']); ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="dokter.php" class="btn btn-secondary">
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
                    <li><i class="fas fa-calendar text-info"></i> Dibuat: <?php echo date('d/m/Y H:i', strtotime($dokter['created_at'])); ?></li>
                    <li><i class="fas fa-check text-success"></i> Pastikan data sudah benar sebelum update</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
