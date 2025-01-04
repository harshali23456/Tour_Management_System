<?php
session_start();
include('includes/config.php');
require 'vendor/autoload.php'; // Include the PHPMailer library
$error = '';
$msg = '';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['razorpay_payment_id'])) {
    $booking_details = $_SESSION['booking_details'];
    $pid = $booking_details['pid'];
    $useremail = $booking_details['useremail'];
    $fromdate = $booking_details['fromdate'];
    $todate = $booking_details['todate'];
    $comment = $booking_details['comment'];
    $travel_vehicles = $booking_details['travel_vehicles'];
    $driver = $booking_details['driver'];
    $guide = $booking_details['guide'];
    $hotel_booking_preference = $booking_details['hotel_booking_preference'];
    $aemail = $booking_details['aemail'];
    $payment_id = $_POST['razorpay_payment_id'];

    $status = 'Confirmed';

    try {
        $sql = "INSERT INTO tblbooking(PackageId, UserEmail, FromDate, ToDate, Comment, status, travel_vehicles, driver, guide, hotel, aemail, PaymentId) 
                VALUES(:pid, :useremail, :fromdate, :todate, :comment, :status, :travel_vehicles, :driver, :guide, :hotel_booking_preference, :aemail, :payment_id)";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':pid', $pid, PDO::PARAM_STR);
        $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
        $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
        $query->bindParam(':todate', $todate, PDO::PARAM_STR);
        $query->bindParam(':comment', $comment, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':travel_vehicles', $travel_vehicles, PDO::PARAM_STR);
        $query->bindParam(':driver', $driver, PDO::PARAM_STR);
        $query->bindParam(':guide', $guide, PDO::PARAM_STR);
        $query->bindParam(':hotel_booking_preference', $hotel_booking_preference, PDO::PARAM_STR);
        $query->bindParam(':aemail', $aemail, PDO::PARAM_STR);
        $query->bindParam(':payment_id', $payment_id, PDO::PARAM_STR);

        if ($query->execute()) {
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                // Retrieve the username from the database
                $sql = "SELECT FullName FROM tblusers WHERE EmailId = :useremail";
                $query = $dbh->prepare($sql);
                $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
                $query->execute();
                $userResult = $query->fetch(PDO::FETCH_OBJ);
                $username = $userResult->FullName;

                // Format the from date
                $formattedFromDate = date("d-m-Y", strtotime($fromdate));

                //retrieve package name
                $sql = "SELECT PackageName FROM tbltourpackages WHERE PackageId = :pid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':pid', $pid, PDO::PARAM_STR);
                $query->execute();
                $packageResult = $query->fetch(PDO::FETCH_OBJ);

                // Send confirmation email
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'youremail'; // Replace with your email
                    $mail->Password = 'your app pass'; // Replace with your email password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('Your email', 'Yeh Mera India'); // Replace with your email
                    $mail->addAddress($useremail);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Booking Confirmation';
                    $mail->Body    = "Dear $username,<br><br>Your booking has been confirmed. Below are the details:<br>
                                      Package Name: $packageResult->PackageName<br>
                                      Booking ID: $lastInsertId<br>
                                      Payment ID: $payment_id<br>
                                      From Date: $formattedFromDate<br><br>Thank you for choosing us.<br>Yeh Mera India";

                    $mail->send();
                    $msg = "Booking confirmed and email sent successfully.";
                } catch (Exception $e) {
                    $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                unset($_SESSION['booking_details']); // Clear session data
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            $error = "Execution failed: " . implode(", ", $query->errorInfo());
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
} else {
    $error = "Payment verification failed. Please try again.";
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Payment Success</title>
</head>
<body>
    <?php if ($error) { ?>
        <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
    <?php } else if ($msg) { 
        header("Location: package-list.php?msg=" . urlencode($msg));
     } ?>
</body>
</html>
