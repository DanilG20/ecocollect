CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  points INT DEFAULT 0
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  type ENUM('pickup','dropoff'),
  points INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE dropoff (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama VARCHAR(100),
  lokasi VARCHAR(255),
  tanggal DATE,
  jenis_sampah VARCHAR(50),
  status ENUM('pending','selesai') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE riwayat_poin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  jenis_transaksi ENUM('pickup', 'dropoff') NOT NULL,
  poin_tambah INT NOT NULL,
  tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  keterangan VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE pickup (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama VARCHAR(100),
  alamat VARCHAR(255),
  tanggal DATE,
  jenis_sampah VARCHAR(50),
  status ENUM('pending','selesai') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

DELIMITER //
CREATE TRIGGER tambah_poin_pickup
AFTER UPDATE ON pickup
FOR EACH ROW
BEGIN
  IF NEW.status = 'selesai' AND OLD.status != 'selesai' THEN
    UPDATE users SET poin = poin + 5 WHERE id = NEW.user_id;
    INSERT INTO riwayat_poin (user_id, jenis_transaksi, poin_tambah, keterangan)
    VALUES (NEW.user_id, 'pickup', 5, 'Transaksi pickup selesai');
  END IF;
END;
//
DELIMITER ;
