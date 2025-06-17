-- 1. dokter
CREATE TABLE dokter (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  spesialisasi VARCHAR(100) NOT NULL,
  no_telp VARCHAR(15),
  alamat TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. pasien
CREATE TABLE pasien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  tanggal_lahir DATE NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  alamat TEXT,
  no_telp VARCHAR(15),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. obat
CREATE TABLE obat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_obat VARCHAR(100) NOT NULL,
  jenis VARCHAR(50) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  harga DECIMAL(10,2) NOT NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. pemeriksaan
CREATE TABLE pemeriksaan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_dokter INT NOT NULL,
  id_pasien INT NOT NULL,
  tanggal_periksa DATETIME NOT NULL,
  keluhan TEXT NOT NULL,
  diagnosa TEXT NOT NULL,
  tindakan TEXT,
  biaya DECIMAL(10,2) DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_dokter) REFERENCES dokter(id) ON DELETE CASCADE,
  FOREIGN KEY (id_pasien) REFERENCES pasien(id) ON DELETE CASCADE
);

-- 5. detail_obat_pemeriksaan
CREATE TABLE detail_obat_pemeriksaan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_pemeriksaan INT NOT NULL,
  id_obat INT NOT NULL,
  jumlah INT NOT NULL,
  aturan_pakai VARCHAR(100),
  FOREIGN KEY (id_pemeriksaan) REFERENCES pemeriksaan(id) ON DELETE CASCADE,
  FOREIGN KEY (id_obat) REFERENCES obat(id) ON DELETE CASCADE
);

-- 6. users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  role ENUM('admin','dokter') DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
