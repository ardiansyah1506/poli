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
    $stmt = $pdo->prepare("DELETE FROM dokter WHERE id = ?");
    if($stmt->execute([$id])) {
        $success = "Data dokter berhasil dihapus!";
    } else {
        $error = "Gagal menghapus data dokter!";
    }
}

// Get all dokter
$stmt = $pdo->query("SELECT * FROM dokter ORDER BY nama ASC");
$dokter_list = $stmt->fetchAll();

$title = 'Data Dokter - Sistem Klinik';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-md"></i> Data Dokter</h2>
            <a href="formDokter.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Dokter
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
                        <th>Nama Dokter</th>
                        <th>Spesialisasi</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($dokter_list) > 0): ?>
                        <?php $no = 1; foreach($dokter_list as $dokter): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($dokter['nama']); ?></td>
                            <td><?php echo htmlspecialchars($dokter['spesialisasi']); ?></td>
                            <td><?php echo htmlspecialchars($dokter['no_telp']); ?></td>
                            <td><?php echo htmlspecialchars($dokter['alamat']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="editDokter.php?id=<?php echo $dokter['id']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $dokter['id']; ?>" 
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
                            <td colspan="6" class="text-center">Tidak ada data dokter</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
