# Panduan Menjadwalkan Backup Otomatis SportZone di Windows Task Scheduler

Berikut langkah-langkah untuk menjadwalkan backup otomatis setiap 5 menit menggunakan Windows Task Scheduler:

1. Buka **Windows Task Scheduler**:

   - Tekan tombol **Windows** dan ketik "Task Scheduler", lalu buka aplikasinya.

2. Buat Task Baru:

   - Klik **Create Basic Task** di panel sebelah kanan.
   - Beri nama task, misalnya: `SportZone Database Backup`.
   - Klik **Next**.

3. Atur Trigger:

   - Pilih **Daily** sebagai trigger.
   - Klik **Next**.
   - Atur waktu mulai (misalnya jam sekarang).
   - Klik **Next**.

4. Atur Pengulangan Task:

   - Setelah membuat task, buka **Task Scheduler Library**.
   - Cari task yang baru dibuat, klik kanan dan pilih **Properties**.
   - Pada tab **Triggers**, pilih trigger yang sudah dibuat, klik **Edit**.
   - Centang **Repeat task every:** dan pilih **5 minutes**.
   - Pada **for a duration of:** pilih **Indefinitely**.
   - Klik **OK**.

5. Atur Action:

   - Pada tab **Actions**, klik **New**.
   - Pada **Program/script**, isi dengan path lengkap executable PHP Anda, misalnya:
     ```
     C:\laragon\bin\php\php-8.0.30\php.exe
     ```
   - Pada **Add arguments (optional):** isi dengan path lengkap file backup.php, misalnya:
     ```
     C:\Users\SIAK\OneDrive\Documents\GitHub\sportzone\backup.php
     ```
   - Klik **OK**.

6. Simpan Task:

   - Klik **OK** untuk menyimpan task.

7. Verifikasi:
   - Task akan berjalan otomatis setiap 5 menit.
   - Anda dapat memeriksa hasil backup di folder `storage/backups` pada proyek SportZone.

---
