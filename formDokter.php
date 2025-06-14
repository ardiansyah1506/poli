<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$title = 'Tambah Dokter - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-plus"></i> Tambah Dokter</h2>
            <a href="dokter.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        Gagal menambahkan data dokter!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Tambah Dokter</h5>
            </div>
            <div class="card-body">
                <form action="tambahDokter.php" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="spesialisasi" class="form-label">Spesialisasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="spesialisasi" name="spesialisasi" required>
                            <option value="">Pilih Spesialisasi</option>
                            <option value="Umum">Dokter Umum</option>
                            <option value="Anak">Dokter Anak</option>
                            <option value="Kandungan">Dokter Kandungan</option>
                            <option value="Jantung">Dokter Jantung</option>
                            <option value="Mata">Dokter Mata</option>
                            <option value="THT">Dokter THT</option>
                            <option value="Kulit">Dokter Kulit</option>
                            <option value="Saraf">Dokter Saraf</option>
                            <option value="Bedah">Dokter Bedah</option>
                            <option value="Gigi">Dokter Gigi</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="081234567890">
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
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
                    <li><i class="fas fa-check text-success"></i> Pastikan nama dokter sudah benar</li>
                    <li><i class="fas fa-check text-success"></i> Pilih spesialisasi yang sesuai</li>
                    <li><i class="fas fa-check text-success"></i> No. telepon dan alamat bersifat opsional</li>
                    <li><i class="fas fa-check text-success"></i> Data akan tersimpan otomatis</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
