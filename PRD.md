# Product Requirements Document (PRD) - EduConnect

## 1. Project Overview
**Nama Proyek**: EduConnect
**Instansi**: SMP Negeri 2 Mungkid, Jawa Tengah
**Deskripsi**: Sistem Manajemen Sekolah Terpadu yang mengintegrasikan akademik, presensi, pengumuman, dan bimbingan konseling dalam satu platform web berbasis Laravel.

## 2. Tech Stack
- **Framework**: Laravel 11 (PHP 8.2+)
- **Frontend**: Tailwind CSS & Blade Components
- **Database**: MySQL / PostgreSQL
- **Architecture**: Monolithic with Role-Based Access Control (RBAC)

## 3. Core Roles (RBAC)
1. **Admin Sekolah**: Manajemen Master Data (User, Siswa, Kelas, Mapel).
2. **Guru Mata Pelajaran**: Input Presensi, Input Nilai, Materi Ajar.
3. **Wali Kelas**: Monitoring kelas, Rekapitulasi, Koordinasi Orang Tua.
4. **Guru BK**: Catatan kedisiplinan dan konseling siswa.
5. **Kepala Sekolah**: Laporan global dan Pengumuman sekolah.
6. **Orang Tua**: Memantau perkembangan anak (Nilai & Presensi).

## 4. Key Functional Requirements (FR)
- **FR-01 (Presensi)**: Sistem input kolektif berbasis Mapel & Kelas (Bulk Input).
- **FR-02 (Nilai)**: Manajemen nilai tugas, UTS, UAS dengan kalkulasi otomatis.
- **FR-03 (Siswa)**: Database siswa terpusat dengan Primary Key NISN (string).
- **FR-04 (Pengumuman)**: Sistem broadcast pesan tertarget per role/kelas.
- **FR-05 (BK)**: Pencatatan poin dan layanan konseling.

## 5. Security & Business Rules
- NISN bersifat unik dan 10 digit.
- Wali Kelas memiliki otoritas untuk mengedit presensi di kelas ampuannya meskipun diinput guru lain.
- Admin tidak diperbolehkan melakukan transaksi harian (Presensi/Nilai).