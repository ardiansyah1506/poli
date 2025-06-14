<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

if(!isset($_GET['id'])) {
    header('Location: obat.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM obat WHERE id = ?");
$stmt->execute([$id]);
$obat = $stmt->fetch();

if(!$obat) {
    header('Location: obat.php');
    exit();
}

$title = 'Edit Obat - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-edit"></i> Edit Obat</h2>
            <a href="obat.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Edit Obat</h5>
            </div>
            <div class="card-body">
                <form action="updateObat.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $obat['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_obat" name="nama_obat" 
                               value="<?php echo htmlspecialchars($obat['nama_obat']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Obat <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis" name="jenis" required>
                            <option value="">Pilih Jenis Obat</option>
                            <option value="Tablet" <?php echo $obat['jenis'] == 'Tablet' ? 'selected' : ''; ?>>Tablet</option>
                            <option value="Kapsul" <?php echo $obat['jenis'] == 'Kapsul' ? 'selected' : ''; ?>>Kapsul</option>
                            <option value="Sirup" <?php echo $obat['jenis'] == 'Sirup' ? 'selected' : ''; ?>>Sirup</option>
                            <option value="Salep" <?php echo $obat['jenis'] == 'Salep' ? 'selected' : ''; ?>>Salep</option>
                            <option value="Injeksi" <?php echo $obat['jenis'] == 'Injeksi' ? 'selected' : ''; ?>>Injeksi</option>
                            <option value="Tetes" <?php echo $obat['jenis'] == 'Tetes' ? 'selected' : ''; ?>>Tetes</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stok" name="stok" 
                                       value="<?php echo $obat['stok']; ?>" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="harga" name="harga" 
                                       value="<?php echo $obat['harga']; ?>" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($obat['keterangan']); ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="obat.php" class="btn btn-secondary">
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
                    <li><i class="fas fa-calendar text-info"></i> Dibuat: <?php echo date('d/m/Y H:i', strtotime($obat['created_at'])); ?></li>
                    <li><i class="fas fa-check text-success"></i> Pastikan data sudah benar sebelum update</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
