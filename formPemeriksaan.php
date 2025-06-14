<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

// Get dokter list
$stmt = $pdo->query("SELECT * FROM dokter ORDER BY nama ASC");
$dokter_list = $stmt->fetchAll();

// Get pasien list
$stmt = $pdo->query("SELECT * FROM pasien ORDER BY nama ASC");
$pasien_list = $stmt->fetchAll();

// Get obat list
$stmt = $pdo->query("SELECT * FROM obat WHERE stok > 0 ORDER BY nama_obat ASC");
$obat_list = $stmt->fetchAll();

$title = 'Tambah Pemeriksaan - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-plus"></i> Tambah Pemeriksaan</h2>
            <a href="pemeriksaan.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<form action="tambahPemeriksaan.php" method="POST">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Form Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_dokter" class="form-label">Dokter <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_dokter" name="id_dokter" required>
                                    <option value="">Pilih Dokter</option>
                                    <?php foreach($dokter_list as $dokter): ?>
                                    <option value="<?php echo $dokter['id']; ?>">
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
                                    <option value="<?php echo $pasien['id']; ?>">
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
                               value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keluhan" class="form-label">Keluhan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keluhan" name="keluhan" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="diagnosa" class="form-label">Diagnosa <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="diagnosa" name="diagnosa" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tindakan" class="form-label">Tindakan</label>
                        <textarea class="form-control" id="tindakan" name="tindakan" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="biaya" class="form-label">Biaya Pemeriksaan (Rp)</label>
                        <input type="number" class="form-control" id="biaya" name="biaya" min="0" step="0.01" value="0">
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
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahObat()">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </button>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pemeriksaan
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
    
    // Add remove button
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'btn btn-sm btn-outline-danger mt-2';
    removeBtn.innerHTML = '<i class="fas fa-trash"></i> Hapus';
    removeBtn.onclick = function() {
        obatItem.remove();
    };
    
    obatItem.appendChild(removeBtn);
    container.appendChild(obatItem);
}
</script>

<?php include 'includes/footer.php'; ?>
