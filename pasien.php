<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

// Handle delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM pasien WHERE id = ?");
    if($stmt->execute([$id])) {
        $success = "Data pasien berhasil dihapus!";
    } else {
        $error = "Gagal menghapus data pasien!";
    }
}

// Get all pasien
$stmt = $pdo->query("SELECT * FROM pasien ORDER BY nama ASC");
$pasien_list = $stmt->fetchAll();

$title = 'Data Pasien - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users"></i> Data Pasien</h2>
            <a href="formPasien.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pasien
            </a>
        </div>
    </div>
</div>

<?php if(isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($pasien_list) > 0): ?>
                        <?php $no = 1; foreach($pasien_list as $pasien): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($pasien['nama']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($pasien['tanggal_lahir'])); ?></td>
                            <td><?php echo $pasien['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                            <td><?php echo htmlspecialchars($pasien['no_telp']); ?></td>
                            <td><?php echo htmlspecialchars($pasien['alamat']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="editPasien.php?id=<?php echo $pasien['id']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $pasien['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pasien</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
