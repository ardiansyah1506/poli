<?php
// config.php - Konfigurasi Database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "poli_feb";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Membuat tabel jika belum ada
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS dokter (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        spesialisasi VARCHAR(100) NOT NULL,
        telepon VARCHAR(15),
        alamat TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS pasien (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        tanggal_lahir DATE NOT NULL,
        jenis_kelamin ENUM('L', 'P') NOT NULL,
        telepon VARCHAR(15),
        alamat TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS obat (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        jenis VARCHAR(50) NOT NULL,
        stok INT DEFAULT 0,
        harga DECIMAL(10,2) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS pemeriksaan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        dokter_id INT,
        pasien_id INT,
        tanggal_periksa DATETIME DEFAULT CURRENT_TIMESTAMP,
        keluhan TEXT,
        diagnosa TEXT,
        tindakan TEXT,
        FOREIGN KEY (dokter_id) REFERENCES dokter(id),
        FOREIGN KEY (pasien_id) REFERENCES pasien(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS pemeriksaan_obat (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pemeriksaan_id INT,
        obat_id INT,
        jumlah INT DEFAULT 1,
        dosis VARCHAR(50),
        FOREIGN KEY (pemeriksaan_id) REFERENCES pemeriksaan(id),
        FOREIGN KEY (obat_id) REFERENCES obat(id)
    )"
];

foreach ($tables as $sql) {
    $pdo->exec($sql);
}

// Insert default user jika belum ada
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);
}