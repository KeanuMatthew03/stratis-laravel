# Smart Tourism Insight AI 🇮🇩

Aplikasi *chatbot* berbasis AI yang dirancang khusus untuk menganalisis dan memberikan *insight* mengenai tren pariwisata (berdasarkan data kunjungan wisatawan mancanegara ke Indonesia).

Proyek ini dibangun sebagai bagian dari *Technical Test* dengan menerapkan **Clean Architecture** dan **SOLID Principles**.

---

## 🚀 Fitur Utama
1. **AI Chatbot Terintegrasi (Google Gemini 3.5 Flash)**
   Menggunakan model AI terbaru dari Google yang diarahkan (*Prompt Engineering*) secara spesifik untuk memprioritaskan jawaban berdasarkan dataset pariwisata yang dilampirkan.
2. **Arsitektur SOLID (MVC + Service Pattern)**
   Memisahkan *Business Logic* dari *Controller* agar kode lebih rapi, teruji, dan mudah dipelihara.
3. **Persistent Chat History**
   Riwayat percakapan pengguna akan tersimpan di dalam *browser* (menggunakan `localStorage`), sehingga obrolan tidak hilang saat halaman di-refresh. Mendukung fitur penambahan obrolan baru (*New Chat*).
4. **Modern & Friendly UI**
   Antarmuka dibangun tanpa *build tools* berat (Node.js/NPM), memanfaatkan Alpine.js dan Tailwind CSS (via CDN) dengan skema warna yang ramah (Teal/Slate) untuk berbagai kalangan.

---

## 🛠️ Teknologi yang Digunakan
- **Backend:** PHP 8.x, Laravel 11
- **Frontend:** HTML5, Alpine.js, Tailwind CSS, Marked.js (Markdown Parser)
- **AI Engine:** Google Gemini API (`gemini-3.5-flash`)
- **Database (Mock):** Local JSON (`storage/app/dataset.json`)

---

## 🏗️ Arsitektur & Struktur Kode
Untuk memenuhi standar *Clean Code*, aplikasi ini menggunakan pola arsitektur berikut:

- `app/Http/Controllers/ChatbotController.php`
  Berfungsi murni sebagai *Router/Controller* untuk menerima request HTTP, tanpa logika bisnis yang kotor.
- `app/Services/TourismDataService.php`
  Mengimplementasikan *Single Responsibility Principle (SRP)* khusus untuk membaca dan memparsing file `dataset.json`.
- `app/Services/GeminiApiService.php`
  Mengimplementasikan SRP khusus untuk menangani panggilan HTTP eksternal ke Google Generative Language API.

Kedua *Service* di atas disuntikkan (*Injected*) ke dalam *Controller* menggunakan mekanisme **Dependency Injection** bawaan Laravel (sesuai *Dependency Inversion Principle*).

---

## ⚙️ Cara Instalasi & Menjalankan Aplikasi

1. **Clone / Ekstrak Repository**
   Pastikan Anda telah mengekstrak seluruh file ke dalam direktori lokal Anda.

2. **Install Dependensi PHP**
   Jalankan perintah berikut di terminal:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   - *Copy* file `.env.example` menjadi `.env` (jika belum ada).
   - Pastikan variabel `GEMINI_API_KEY` diisi dengan API Key yang valid dari Google AI Studio:
   ```env
   GEMINI_API_KEY=KODE_API_KEY_ANDA_DI_SINI
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000`

---

## 💡 Prompt Engineering & Konteks Data
AI diatur (melalui *System Instruction* di `GeminiApiService`) agar selalu mengambil data primer dari file `dataset.json` terlebih dahulu sebelum menggunakan pengetahuan umumnya. Hal ini meminimalisir kemungkinan halusinasi AI terkait data pariwisata Indonesia.
