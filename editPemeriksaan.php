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

// Get pemeriksaan data
$stmt = $pdo->prepare("SELECT * FROM pemeriksaan WHERE id = ?");
$stmt->execute([$id]);
$pemeriksaan = $stmt->fetch();

if(!$pemeriksaan) {
    header('Location: pemeriksaan.php');
    exit();
}

// Get dokter list
$stmt = $pdo->query("SELECT * FROM dokter ORDER BY nama ASC");
$dokter_list = $stmt->fetchAll();

// Get pasien list
$stmt = $pdo->query("SELECT * FROM pasien ORDER BY nama ASC");
$pasien_list = $stmt->fetchAll();

// Get obat list
$stmt = $pdo->query("SELECT * FROM obat ORDER BY nama_obat ASC");
$obat_list = $stmt->fetchAll();

// Get existing obat resep
$stmt = $pdo->prepare("
    SELECT dop.*, o.nama_obat, o.stok 
    FROM detail_obat_pemeriksaan dop 
    JOIN obat o ON dop.id_obat = o.id 
    WHERE dop.id_pemeriksaan = ?
");
$stmt->execute([$id]);
$existing_obat = $stmt->fetchAll();

$title = 'Edit Pemeriksaan - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-edit"></i> Edit Pemeriksaan</h2>
            <a href="pemeriksaan.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        Gagal mengupdate data pemeriksaan!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="updatePemeriksaan.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $pemeriksaan['id']; ?>">
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Form Edit Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_dokter" class="form-label">Dokter <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_dokter" name="id_dokter" required>
                                    <option value="">Pilih Dokter</option>
                                    <?php foreach($dokter_list as $dokter): ?>
                                    <option value="<?php echo $dokter['id']; ?>" <?php echo $pemeriksaan['id_dokter'] == $dokter['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($dokter['nama']) . ' - ' . htmlspecialchars($dokter['spesialisasi']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_pasien" class="form-label">Pasien <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_pasien" name="id_pasien" required>
                                    <option value="">Pilih Pasien</option>
                                    <?php foreach($pasien_list as $pasien): ?>
                                    <option value="<?php echo $pasien['id']; ?>" <?php echo $pemeriksaan['id_pasien'] == $pasien['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($pasien['nama']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tanggal_periksa" class="form-label">Tanggal & Waktu Periksa <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="tanggal_periksa" name="tanggal_periksa" 
                               value="<?php echo date('Y-m-d\TH:i', strtotime($pemeriksaan['tanggal_periksa'])); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keluhan" class="form-label">Keluhan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keluhan" name="keluhan" rows="3" required><?php echo htmlspecialchars($pemeriksaan['keluhan']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="diagnosa" class="form-label">Diagnosa <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="diagnosa" name="diagnosa" rows="3" required><?php echo htmlspecialchars($pemeriksaan['diagnosa']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tindakan" class="form-label">Tindakan</label>
                        <textarea class="form-control" id="tindakan" name="tindakan" rows="2"><?php echo htmlspecialchars($pemeriksaan['tindakan']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="biaya" class="form-label">Biaya Pemeriksaan (Rp)</label>
                        <input type="number" class="form-control" id="biaya" name="biaya" min="0" step="0.01" 
                               value="<?php echo $pemeriksaan['biaya']; ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Resep Obat</h6>
                </div>
                <div class="card-body">
                    <div id="obat-container">
                        <?php if(count($existing_obat) > 0): ?>
                            <?php foreach($existing_obat as $index => $obat_resep): ?>
                            <div class="obat-item mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Obat</label>
                                        <select class="form-select mb-2" name="obat[]">
                                            <option value="">Pilih Obat</option>
                                            <?php foreach($obat_list as $obat): ?>
                                            <option value="<?php echo $obat['id']; ?>" <?php echo $obat_resep['id_obat'] == $obat['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($obat['nama_obat']) . ' (Stok: ' . $obat['stok'] . ')'; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" class="form-control mb-2" name="jumlah[]" min="1" 
                                               value="<?php echo $obat_resep['jumlah']; ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Aturan Pakai</label>
                                        <input type="text" class="form-control mb-2" name="aturan_pakai[]" 
                                               value="<?php echo htmlspecialchars($obat_resep['aturan_pakai']); ?>" placeholder="3x1">
                                    </div>
                                </div>
                                <?php if($index > 0): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusObat(this)">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="obat-item mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Obat</label>
                                        <select class="form-select mb-2" name="obat[]">
                                            <option value="">Pilih Obat</option>
                                            <?php foreach($obat_list as $obat): ?>
                                            <option value="<?php echo $obat['id']; ?>">
                                                <?php echo htmlspecialchars($obat['nama_obat']) . ' (Stok: ' . $obat['stok'] . ')'; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" class="form-control mb-2" name="jumlah[]" min="1">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Aturan Pakai</label>
                                        <input type="text" class="form-control mb-2" name="aturan_pakai[]" placeholder="3x1">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahObat()">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </button>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="fas fa-info-circle"></i> Informasi</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-calendar text-info"></i> Dibuat: <?php echo date('d/m/Y H:i', strtotime($pemeriksaan['created_at'])); ?></li>
                        <li><i class="fas fa-check text-success"></i> Pastikan data sudah benar sebelum update</li>
                        <li><i class="fas fa-exclamation-triangle text-warning"></i> Perubahan stok obat akan disesuaikan</li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Pemeriksaan
                        </button>
                        <a href="pemeriksaan.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function tambahObat() {
    const container = document.getElementById('obat-container');
    const obatItem = document.querySelector('.obat-item').cloneNode(true);
    
    // Reset values
    obatItem.querySelectorAll('select, input').forEach(input => {
        input.value = '';
    });
    
    // Remove existing remove button if any
    const existingRemoveBtn = obatItem.querySelector('.btn-outline-danger');
    if(existingRemoveBtn) {
        existingRemoveBtn.remove();
    }
    
    // Add remove button
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'btn btn-sm btn-outline-danger mt-2';
    removeBtn.innerHTML = '<i class="fas fa-trash"></i> Hapus';
    removeBtn.onclick = function() {
        hapusObat(this);
    };
    
    obatItem.appendChild(removeBtn);
    container.appendChild(obatItem);
}

function hapusObat(button) {
    const obatItem = button.closest('.obat-item');
    obatItem.remove();
}
</script>

<?php include 'includes/footer.php'; ?>
