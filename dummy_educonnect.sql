-- ============================================================
-- DUMMY DATA EDUCONNECT
-- Urutan: Users → Kelas → Siswa
-- ============================================================

-- ============================================================
-- 1. INSERT USER WALI KELAS (role_id = 3, ID akan jadi 8,9,10)
-- ============================================================
INSERT INTO `users` (`role_id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(3, 'Budi Santoso', 'wali.7a@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(3, 'Siti Rahayu', 'wali.8a@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(3, 'Ahmad Fauzi', 'wali.9a@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW());

-- ============================================================
-- 2. INSERT USER GURU MAPEL (role_id = 1, ID akan jadi 11-19)
-- ============================================================
INSERT INTO `users` (`role_id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Dewi Kurniawati', 'guru.mtk@educonnect.test',    '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Eko Prasetyo',   'guru.ipa@educonnect.test',    '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Fitri Handayani','guru.ips@educonnect.test',    '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Gunawan Saputra', 'guru.bindo@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Hana Pertiwi',   'guru.bingg@educonnect.test',  '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Irwan Maulana',  'guru.pkn@educonnect.test',    '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Joko Widodo',    'guru.agama@educonnect.test',  '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Kartini Susanti','guru.seni@educonnect.test',   '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(1, 'Lukman Hakim',   'guru.penjas@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW());

-- ============================================================
-- 3. INSERT USER ORANG TUA (role_id = 5, ID akan jadi 20-49)
-- ============================================================
INSERT INTO `users` (`role_id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
-- Ortu siswa kelas 7A (20-29)
(5, 'Ortu Siswa 7A-01', 'ortu.7a01@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-02', 'ortu.7a02@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-03', 'ortu.7a03@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-04', 'ortu.7a04@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-05', 'ortu.7a05@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-06', 'ortu.7a06@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-07', 'ortu.7a07@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-08', 'ortu.7a08@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-09', 'ortu.7a09@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 7A-10', 'ortu.7a10@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
-- Ortu siswa kelas 8A (30-39)
(5, 'Ortu Siswa 8A-01', 'ortu.8a01@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-02', 'ortu.8a02@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-03', 'ortu.8a03@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-04', 'ortu.8a04@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-05', 'ortu.8a05@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-06', 'ortu.8a06@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-07', 'ortu.8a07@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-08', 'ortu.8a08@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-09', 'ortu.8a09@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 8A-10', 'ortu.8a10@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
-- Ortu siswa kelas 9A (40-49)
(5, 'Ortu Siswa 9A-01', 'ortu.9a01@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-02', 'ortu.9a02@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-03', 'ortu.9a03@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-04', 'ortu.9a04@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-05', 'ortu.9a05@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-06', 'ortu.9a06@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-07', 'ortu.9a07@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-08', 'ortu.9a08@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-09', 'ortu.9a09@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW()),
(5, 'Ortu Siswa 9A-10', 'ortu.9a10@educonnect.test', '$2y$12$ZkTyLJ75E7JN9Th7OXbbzeVvrJdHF.hD4wlgABfvoZtd4yGNTiSn6', NOW(), NOW());

-- ============================================================
-- 4. INSERT KELAS 7A, 8A, 9A + isi wali_kelas_id
--    (KelasSeeder belum dijalankan, jadi insert baru)
--    Wali kelas: ID 8=7A, 9=8A, 10=9A
-- ============================================================
INSERT INTO `kelas` (`nama_kelas`, `tingkat`, `wali_kelas_id`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
('7A', 7, 8,  '2025/2026', NOW(), NOW()),
('8A', 8, 9,  '2025/2026', NOW(), NOW()),
('9A', 9, 10, '2025/2026', NOW(), NOW());

-- ============================================================
-- 5. INSERT SISWA
--    kelas_id: 7A=1, 8A=2 (atau 3), 9A=3 (atau 5) 
--    PERHATIAN: sesuaikan kelas_id dengan hasil auto increment tabel kelas
--    Asumsi kelas kosong sebelumnya → 7A=1, 8A=2, 9A=3
--    orang_tua_id: urut dari ID 20-49
-- ============================================================

-- Siswa Kelas 7A (kelas_id=1, ortu ID 20-29)
INSERT INTO `siswa` (`nisn`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `kelas_id`, `orang_tua_id`, `status`, `created_at`, `updated_at`) VALUES
('0123456701', 'Aldi Firmansyah',    'L', 'Jakarta',   '2012-03-10', 'Jl. Mawar No.1',   1, 20, 'aktif', NOW(), NOW()),
('0123456702', 'Bella Safitri',      'P', 'Bandung',   '2012-05-14', 'Jl. Melati No.2',  1, 21, 'aktif', NOW(), NOW()),
('0123456703', 'Cahyo Nugroho',      'L', 'Surabaya',  '2012-01-20', 'Jl. Anggrek No.3', 1, 22, 'aktif', NOW(), NOW()),
('0123456704', 'Dina Permatasari',   'P', 'Yogyakarta','2012-07-08', 'Jl. Kenanga No.4', 1, 23, 'aktif', NOW(), NOW()),
('0123456705', 'Eko Saputra',        'L', 'Semarang',  '2012-09-25', 'Jl. Dahlia No.5',  1, 24, 'aktif', NOW(), NOW()),
('0123456706', 'Farah Aulia',        'P', 'Medan',     '2012-11-03', 'Jl. Tulip No.6',   1, 25, 'aktif', NOW(), NOW()),
('0123456707', 'Gilang Ramadhan',    'L', 'Palembang', '2012-04-17', 'Jl. Sakura No.7',  1, 26, 'aktif', NOW(), NOW()),
('0123456708', 'Hana Maharani',      'P', 'Makassar',  '2012-06-22', 'Jl. Flamboyan No.8',1,27, 'aktif', NOW(), NOW()),
('0123456709', 'Ilham Pratama',      'L', 'Depok',     '2012-02-11', 'Jl. Cempaka No.9', 1, 28, 'aktif', NOW(), NOW()),
('0123456710', 'Julia Anggraini',    'P', 'Bekasi',    '2012-08-30', 'Jl. Lavender No.10',1,29, 'aktif', NOW(), NOW()),

-- Siswa Kelas 8A (kelas_id=2, ortu ID 30-39)
('0123456711', 'Kevin Andrean',      'L', 'Jakarta',   '2011-03-10', 'Jl. Mawar No.11',   2, 30, 'aktif', NOW(), NOW()),
('0123456712', 'Lina Marlina',       'P', 'Bandung',   '2011-05-14', 'Jl. Melati No.12',  2, 31, 'aktif', NOW(), NOW()),
('0123456713', 'Muhammad Rizky',     'L', 'Surabaya',  '2011-01-20', 'Jl. Anggrek No.13', 2, 32, 'aktif', NOW(), NOW()),
('0123456714', 'Nadia Putri',        'P', 'Yogyakarta','2011-07-08', 'Jl. Kenanga No.14', 2, 33, 'aktif', NOW(), NOW()),
('0123456715', 'Oscar Hidayat',      'L', 'Semarang',  '2011-09-25', 'Jl. Dahlia No.15',  2, 34, 'aktif', NOW(), NOW()),
('0123456716', 'Putri Ramadhani',    'P', 'Medan',     '2011-11-03', 'Jl. Tulip No.16',   2, 35, 'aktif', NOW(), NOW()),
('0123456717', 'Qori Ananda',        'L', 'Palembang', '2011-04-17', 'Jl. Sakura No.17',  2, 36, 'aktif', NOW(), NOW()),
('0123456718', 'Rina Agustina',      'P', 'Makassar',  '2011-06-22', 'Jl. Flamboyan No.18',2,37,'aktif', NOW(), NOW()),
('0123456719', 'Surya Dinata',       'L', 'Depok',     '2011-02-11', 'Jl. Cempaka No.19', 2, 38, 'aktif', NOW(), NOW()),
('0123456720', 'Tania Kusuma',       'P', 'Bekasi',    '2011-08-30', 'Jl. Lavender No.20',2, 39, 'aktif', NOW(), NOW()),

-- Siswa Kelas 9A (kelas_id=3, ortu ID 40-49)
('0123456721', 'Umar Habibi',        'L', 'Jakarta',   '2010-03-10', 'Jl. Mawar No.21',   3, 40, 'aktif', NOW(), NOW()),
('0123456722', 'Vina Oktaviani',     'P', 'Bandung',   '2010-05-14', 'Jl. Melati No.22',  3, 41, 'aktif', NOW(), NOW()),
('0123456723', 'Wahyu Setiawan',     'L', 'Surabaya',  '2010-01-20', 'Jl. Anggrek No.23', 3, 42, 'aktif', NOW(), NOW()),
('0123456724', 'Xena Fitriani',      'P', 'Yogyakarta','2010-07-08', 'Jl. Kenanga No.24', 3, 43, 'aktif', NOW(), NOW()),
('0123456725', 'Yoga Pratama',       'L', 'Semarang',  '2010-09-25', 'Jl. Dahlia No.25',  3, 44, 'aktif', NOW(), NOW()),
('0123456726', 'Zahra Nabila',       'P', 'Medan',     '2010-11-03', 'Jl. Tulip No.26',   3, 45, 'aktif', NOW(), NOW()),
('0123456727', 'Arya Wicaksono',     'L', 'Palembang', '2010-04-17', 'Jl. Sakura No.27',  3, 46, 'aktif', NOW(), NOW()),
('0123456728', 'Bunga Citra',        'P', 'Makassar',  '2010-06-22', 'Jl. Flamboyan No.28',3,47,'aktif', NOW(), NOW()),
('0123456729', 'Chandra Kusuma',     'L', 'Depok',     '2010-02-11', 'Jl. Cempaka No.29', 3, 48, 'aktif', NOW(), NOW()),
('0123456730', 'Desi Ratnasari',     'P', 'Bekasi',    '2010-08-30', 'Jl. Lavender No.30',3, 49, 'aktif', NOW(), NOW());
