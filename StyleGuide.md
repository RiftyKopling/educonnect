# Style Guide - EduConnect UI/UX

## 1. Color Palette
- **Primary Navy**: `#03045E` (Digunakan untuk Header, Sidebar, Tombol Utama, Teks Judul).
- **Background**: `#F5F5F5` (Abu-abu sangat terang).
- **Card/Container**: `#FFFFFF` (Putih bersih).
- **Accents**: 
  - Success: Emerald/Green-500
  - Warning: Amber/Yellow-500
  - Danger: Red-500

## 2. Component Standards
- **Containers**: Gunakan `bg-white rounded-[2rem] shadow-sm p-8`.
- **Buttons**:
  - Primary: `bg-[#03045E] text-white rounded-full font-bold px-8 py-3`.
  - Secondary/Action: `rounded-2xl` dengan latar belakang transparan/light (contoh: `bg-amber-100`).
- **Forms**:
  - Input: `rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]`.
- **Tables**:
  - Header: `bg-[#03045E] text-white font-black uppercase rounded-l-full` (pada kolom pertama) dan `rounded-r-full` (pada kolom terakhir).
  - Rows: `bg-gray-50` dengan `border-separate` dan `border-spacing-y-3`.

## 3. Typography
- **Heading**: Black/ExtraBold, Tracking-tight, Uppercase untuk Judul Fitur.
- **Body**: Medium/Bold untuk teks tabel agar mudah dibaca.

## 4. Layout
- Menggunakan `<x-app-layout>` dengan Sidebar di kiri dan Header Gradasi di atas.