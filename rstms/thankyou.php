<?php 
session_start();
require('config.php');
require('users/vendor/autoload.php');
include('includes/dbconnection.php');

if (isset($_SESSION['totalAmount'])) {
    $totalAmount = $_SESSION['totalAmount'];

    if (isset($_POST['stripeToken'])) {
        \Stripe\Stripe::setApiKey($secretKey); // Set your secret key

        try {
            // Create PaymentIntent with return_url
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $totalAmount * 100, // Amount in cents
                'currency' => 'usd',
                'description' => 'A1 Restaurant Payment',
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => ['token' => $_POST['stripeToken']],
                ],
                'return_url' => 'http://localhost/rstms/users/viewall.php',
                'confirm' => true,
            ]);

            if (isset($_SESSION['order_id'])) {
			    $order_id = $_SESSION['order_id'];

			    // Update payment_status in tbl_payment
			    $update_sql = $conn->prepare("UPDATE tbl_payment SET payment_status = ? WHERE order_id = ?");
			    $payment_status = "Payment Successful &#36; $totalAmount";
			    $update_sql->bind_param("ss", $payment_status, $order_id);
			    $update_sql->execute();

			    echo "<h2>Thank you for your payment!</h2>";
			} 
			else {
			    echo "<h2>Error: Unable to retrieve order ID.</h2>";
			}
        } catch (\Stripe\Exception\CardException $e) {
            // Payment failed
            echo "<h2>Error: {$e->getError()->message}</h2>";
        }
    }
} else {
    echo "Total amount not found!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KING Restaurant</title>
  <link rel="icon" type="image/x-icon" href="assets/icons/icon1.svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript">
  if (window.history.replaceState) {
    window.history.replaceState(null,null,window.location.href);
  }
</script>
</head>
<body>
    <h2>Thank you</h2>
</body>
</html>
