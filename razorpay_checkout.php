<?php
session_start();
$booking_details = $_SESSION['booking_details'];

// Assuming you have a price calculation logic based on booking details
$base_price = $booking_details['packagePrice']; // base price for a booking
$additional_charges = 0;

// Calculate additional charges based on booking details
if (isset($booking_details['travel_vehicles'])) {
    if ($booking_details['travel_vehicles'] == 'car') {
        $additional_charges += 1000;
    } elseif ($booking_details['travel_vehicles'] == 'train') {
        $additional_charges += 500;
    } elseif ($booking_details['travel_vehicles'] == 'plane') {
        $additional_charges += 3000;
    }
}

if ($booking_details['driver'] == 'Yes') {
    $additional_charges += 500;
}

if ($booking_details['guide'] == 'Yes') {
    $additional_charges += 500;
}

$total_amount = $base_price + $additional_charges;
$total_amount_paise = $total_amount * 100;
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Razorpay Checkout</title>
</head>
<body>
    <form action="payment_success.php" method="POST">
        <script
            src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="razorpay_key_id"
            data-amount="<?php echo $total_amount_paise; ?>"
            data-currency="INR"
            data-id="<?php echo 'order_id_' . uniqid(); ?>"
            data-buttontext="Pay Now"
            data-name="Travel Management System"
            data-description="Booking Payment"
            data-image="https://avatars.githubusercontent.com/u/106594839?v=4"
            data-prefill.name="<?php echo $booking_details['useremail']; ?>"
            data-prefill.email="<?php echo $booking_details['aemail']; ?>"
            data-theme.color="#F37254">
        </script>
        <input type="hidden" name="booking_id" value="<?php echo uniqid(); ?>">
    </form>
</body>
</html>
