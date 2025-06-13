-- Database schema for SportZone

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Facilities table
CREATE TABLE facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    facility_id INT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    status ENUM(
        'pending',
        'confirmed',
        'cancelled'
    ) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (facility_id) REFERENCES facilities (id)
);

-- Payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM(
        'pending',
        'completed',
        'failed'
    ) DEFAULT 'pending',
    FOREIGN KEY (booking_id) REFERENCES bookings (id)
);

-- Stored Procedure: Add Booking with Transaction
DELIMITER / /

CREATE PROCEDURE AddBooking(
    IN p_user_id INT,
    IN p_facility_id INT,
    IN p_booking_date DATE,
    IN p_booking_time TIME
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    INSERT INTO bookings (user_id, facility_id, booking_date, booking_time, status)
    VALUES (p_user_id, p_facility_id, p_booking_date, p_booking_time, 'pending');

    COMMIT;
END //

DELIMITER;

-- Function: Check Facility Availability
DELIMITER / /

CREATE FUNCTION IsFacilityAvailable(p_facility_id INT, p_booking_date DATE, p_booking_time TIME)
RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE cnt INT;
    SELECT COUNT(*) INTO cnt FROM bookings
    WHERE facility_id = p_facility_id
      AND booking_date = p_booking_date
      AND booking_time = p_booking_time
      AND status = 'confirmed';
    RETURN cnt = 0;
END //

DELIMITER;

-- Trigger: Update Facility Availability on Booking Confirm
DELIMITER / /

CREATE TRIGGER trg_update_facility_availability
AFTER UPDATE ON bookings
FOR EACH ROW
BEGIN
    IF NEW.status = 'confirmed' THEN
        UPDATE facilities SET available = FALSE WHERE id = NEW.facility_id;
    ELSEIF NEW.status = 'cancelled' THEN
        UPDATE facilities SET available = TRUE WHERE id = NEW.facility_id;
    END IF;
END //

DELIMITER;