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
    $stmt = $pdo->prepare("DELETE FROM obat WHERE id = ?");
    if($stmt->execute([$id])) {
        $success = "Data obat berhasil dihapus!";
    } else {
        $error = "Gagal menghapus data obat!";
    }
}

// Get all obat
$stmt = $pdo->query("SELECT * FROM obat ORDER BY nama_obat ASC");
$obat_list = $stmt->fetchAll();

$title = 'Data Obat - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-pills"></i> Data Obat</h2>
            <a href="formObat.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Obat
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

<?php if(isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo $error; ?>
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
                        <th>Nama Obat</th>
                        <th>Jenis</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($obat_list) > 0): ?>
                        <?php $no = 1; foreach($obat_list as $obat): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($obat['nama_obat']); ?></td>
                            <td><?php echo htmlspecialchars($obat['jenis']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $obat['stok'] < 20 ? 'danger' : 'success'; ?>">
                                    <?php echo $obat['stok']; ?>
                                </span>
                            </td>
                            <td>Rp <?php echo number_format($obat['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($obat['keterangan']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="editObat.php?id=<?php echo $obat['id']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $obat['id']; ?>" 
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
                            <td colspan="7" class="text-center">Tidak ada data obat</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
