<p align="center">
  <img src="https://i.imgur.com/uR12gYF.jpg" width="800" alt="WebGIS Cuaca Screenshot">
</p>

# WebGIS Cuaca

<p align="center">
  <a href="https://github.com/USERNAME/REPO/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
  <a href="#"><img src="https://img.shields.io/badge/Laravel-10.x-orange.svg" alt="Laravel Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.1%2B-blue.svg" alt="PHP Version"></a>
</p>

**WebGIS Cuaca** adalah sebuah aplikasi Sistem Informasi Geografis (SIG) berbasis web yang dirancang untuk menyajikan data cuaca dan geospasial yang kompleks dalam satu platform yang interaktif dan mudah digunakan. Dengan antarmuka yang simpel dan modern, pengguna dapat memperoleh wawasan cuaca, menganalisis data spasial, dan melihat kondisi terkini untuk lokasi di seluruh dunia.

Proyek ini dibangun sebagai implementasi dari praktik Pemrograman Geospasial Web, mengintegrasikan data dari **OpenWeatherMap** dan **data GeoJSON lokal** ke dalam peta dinamis yang didukung oleh **Leaflet.js** dengan *basemap* dari **Mapbox**.

---

## ✨ Fitur Unggulan

Berikut adalah beberapa fitur utama yang ditawarkan oleh aplikasi ini:

* 🗺️ **Peta Interaktif**: Visualisasikan lokasi dan data cuaca secara langsung pada peta yang responsif dan mudah dinavigasi. Pengguna dapat melakukan zoom, pan, dan klik pada fitur untuk mendapatkan informasi detail.

* 🌦️ **Data Cuaca Real-time**: Dapatkan informasi cuaca terkini termasuk suhu, kelembapan, kecepatan angin, dan curah hujan yang diambil langsung dari API OpenWeatherMap.

* 🌡️ **Heatmap Dinamis**: Analisis sebaran data secara visual dengan fitur heatmap. Ganti tipe data yang ingin ditampilkan (curah hujan, suhu, tutupan awan) melalui panel kontrol yang intuitif.

* 📊 **Manajemen Data Spasial**: Tampilkan berbagai lapisan data geospasial seperti batas negara, area terdampak curah hujan (poligon), dan titik lokasi penting lainnya dari sumber data GeoJSON.

* 🗂️ **Tabel Data Interaktif**: Akses data mentah dari fitur Point, Polyline, dan Polygon dalam format tabel yang terstruktur, lengkap dengan fitur pencarian dan paginasi.

* ✏️ **Alat Gambar (Draw Tools)**: Pengguna dapat menggambar fitur geospasial (Point, Polyline, Polygon) langsung di peta dan menyimpannya ke dalam database.

---

## 📸 Tampilan Aplikasi

<table>
  <tr>
    <td align="center"><strong>Tampilan Peta Utama</strong></td>
    <td align="center"><strong>Tabel Data</strong></td>
  </tr>
  <tr>
    <td><img src="https://i.imgur.com/qE4Jv9w.jpg" alt="Tampilan Peta Utama"></td>
    <td><img src="https://i.imgur.com/8Qj91i3.png" alt="Tampilan Tabel Data"></td>
  </tr>
   <tr>
    <td align="center"><strong>Heatmap & Legenda</strong></td>
    <td align="center"><strong>Popup Detail</strong></td>
  </tr>
   <tr>
    <td><img src="https://i.imgur.com/kSj9s7d.jpg" alt="Tampilan Heatmap dan Legenda"></td>
    <td><img src="https://i.imgur.com/jI9nJ5b.jpg" alt="Popup Detail Lokasi"></td>
  </tr>
</table>

---

## 🛠️ Teknologi yang Digunakan

Proyek ini dibangun menggunakan teknologi modern berikut:

* **Backend**: Laravel 10
* **Frontend**: Blade, Bootstrap 5, JavaScript
* **Pustaka Peta**: Leaflet.js
* **Basemap & Tiles**: Mapbox
* **Data Cuaca**: OpenWeatherMap API
* **Utilitas Geospasial**: Turf.js, Proj4js
* **Lainnya**: jQuery, AJAX

---

## 🚀 Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini secara lokal:

1.  **Clone repositori ini:**
    ```bash
    git clone [https://github.com/NAMA_USER/NAMA_REPO.git](https://github.com/NAMA_USER/NAMA_REPO.git)
    cd NAMA_REPO
    ```

2.  **Install dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Buat file `.env` dari contoh:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi file `.env`:**
    Buka file `.env` dan atur koneksi database Anda (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    
    Tambahkan juga API Key Anda:
    ```env
    OPENWEATHER_KEY="YOUR_OPENWEATHER_API_KEY"
    MAPBOX_TOKEN="YOUR_MAPBOX_ACCESS_TOKEN"
    ```

6.  **Jalankan migrasi database:**
    ```bash
    php artisan migrate
    ```

7.  **Jalankan server pengembangan:**
    ```bash
    php artisan serve
    ```

8.  **Buka di browser:**
    Akses `http://127.0.0.1:8000` di browser Anda.

---

## 🧑‍💻 Informasi Pengembang

Proyek ini dikembangkan sebagai bagian dari praktik Pemrograman Geospasial Web.

* **Nama**: Muhammad Fauzil Adhim Sulistyo
* **NIM/Kelas**: 23/524853/SV/23514 | PGWEBL A
* **GitHub**: [fauziiladhim1](https://github.com/fauziiladhim1)
* **LinkedIn**: [fauziiladim](https://www.linkedin.com/in/fauziiladhim)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE.md).
