# ⚽⚾ Sportzone (Proyek UAP)
Proyek ini merupakan sistem perbankan sederhana yang dibangun menggunakan PHP dan MySQL. Tujuannya sebagai platform penyewaan dan manajemen fasilitas olahraga untuk komunitas dengan memanfaatkan transaction, procedure, function, trigger, dan backup database + task scheduler. Sistem ini juga dilengkapi mekanisme backup otomatis untuk menjaga keamanan data jika terjadi hal yang tidak diinginkan.
<img src="https://github.com/slabkim/sportzone/blob/main/imgAset/dashboard.png" >

<h1>📌Detail Konsep</h1>
👣Stored procedure bertindak seperti SOP internal yang menetapkan alur eksekusi berbagai operasi penting di sistem . Procedure ini disimpan langsung di lapisan database, sehingga dapat menjamin konsistensi, efisiensi, dan keamanan eksekusi, terutama dalam sistem terdistribusi atau multi-user.
<img src="https://github.com/slabkim/sportzone/blob/main/imgAset/routine.png" >
Beberapa procedure dan function penting yang digunakan:

`booking.php`


`AddBooking (p_user_id, p_facility_id, p_booking_date, p_booking_time)` : Menambahkan booking pada fasilitas.

```
$stmt = mysqli_prepare($conn, 'CALL AddBooking(?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'iiss', $_SESSION['user_id'], $facility_id, $booking_date, $booking_time);
            mysqli_stmt_execute($stmt);
            $success = "Booking successful!";
```
 📄 `App\Models\Facility.php`

- **`IsFacilityAvailable(p_facility_id, p_booking_date, p_booking_time)`**  
  Mengecek apakah suatu fasilitas tersedia pada tanggal dan waktu tertentu.  
  Function ini mengembalikan nilai `TRUE` jika fasilitas tersedia (belum dibooking dengan status `confirmed`) dan `FALSE` jika tidak tersedia.

```php
// Memanggil fungsi IsFacilityAvailable untuk mengecek ketersediaan fasilitas
$stmt = $this->conn->prepare("SELECT IsFacilityAvailable(?, ?, ?) AS available;");
$stmt->execute([
    $facilityId,
    $bookingDate,
    $bookingTime
]);

$result = $stmt->fetch();
$isAvailable = $result['available'];


`facilities.php.`

`trg_update_facility_availability` : Trigger ini bertujuan untuk menjaga konsistensi ketersediaan fasilitas berdasarkan status booking:
- Jika booking dikonfirmasi, maka fasilitas terkait tidak lagi tersedia.
- Jika booking dibatalkan, maka fasilitas tersebut kembali tersedia.


``` 
BEGIN
    IF NEW.status = 'confirmed' THEN
        UPDATE facilities SET available = FALSE WHERE id = NEW.facility_id;
    ELSEIF NEW.status = 'cancelled' THEN
        UPDATE facilities SET available = TRUE WHERE id = NEW.facility_id;
    END IF;
END
```
