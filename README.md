# ⚽⚾ Sportzone (Contoh Proyek UAP)
Proyek ini merupakan sistem perbankan sederhana yang dibangun menggunakan PHP dan MySQL. Tujuannya sebagai platform penyewaan dan manajemen fasilitas olahraga untuk komunitas dengan memanfaatkan transaction, procedure, function, trigger, dan backup database + task scheduler. Sistem ini juga dilengkapi mekanisme backup otomatis untuk menjaga keamanan data jika terjadi hal yang tidak diinginkan.
<img src="https://github.com/slabkim/sportzone/blob/main/imgAset/dashboard.png" >

<h1>📌Detail Konsep</h1>
👣Stored procedure bertindak seperti SOP internal yang menetapkan alur eksekusi berbagai operasi penting di sistem . Procedure ini disimpan langsung di lapisan database, sehingga dapat menjamin konsistensi, efisiensi, dan keamanan eksekusi, terutama dalam sistem terdistribusi atau multi-user.
<img src="https://github.com/slabkim/sportzone/blob/main/imgAset/routine.png" >
Beberapa procedure penting yang digunakan:

`booking.php`


`AddBooking (p_user_id, p_facility_id, p_booking_date, p_booking_time)` : Menambahkan booking pada fasilitas.

```
$stmt = mysqli_prepare($conn, 'CALL AddBooking(?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'iiss', $_SESSION['user_id'], $facility_id, $booking_date, $booking_time);
            mysqli_stmt_execute($stmt);
            $success = "Booking successful!";
```


`facilities.php.`

`trg_update_facility_availability` : Trigger ini bertujuan untuk menjaga konsistensi ketersediaan fasilitas berdasarkan status booking:

Jika booking dikonfirmasi, maka fasilitas terkait tidak lagi tersedia.

Jika booking dibatalkan, maka fasilitas tersebut kembali tersedia.
