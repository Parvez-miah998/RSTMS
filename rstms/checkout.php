<?php
session_start();
include('includes/dbconnection.php');
require('config.php');

if (!isset($_SESSION['user'])) {
    header('Location: users/login.php');
    exit();
}

if (isset($_POST['order_confirm'])) {
    if (!empty($_POST['table']) || (isset($_SESSION['selected_table']) && !empty($_SESSION['selected_table']))) {
        if (!empty($_POST['table'])) {
            $selected_table = $_POST['table'];
        } else {
            $selected_table = $_SESSION['selected_table'];
        }

        // Check if $_SESSION['selected_table'] is set
        if (isset($_SESSION['selected_table']) && !empty($_SESSION['selected_table'])) {
            // Retrieve ta_id for the selected table
            $fetch_sql = $conn->prepare("SELECT ta_id FROM tbl_table WHERE t_name = ?");
            $fetch_sql->bind_param("s", $_SESSION['selected_table']);
            $fetch_sql->execute();
            $fetch_result = $fetch_sql->get_result();
            $fetch_row = $fetch_result->fetch_assoc();
            $ta_id = $fetch_row['ta_id'];
        } else {
            // If $_SESSION['selected_table'] is not set or empty, set ta_id to null
            $ta_id = null;
        }

        // Logic for moving order to payment table
        $email = $_SESSION['user'];
        $user_sql = $conn->prepare("SELECT u_id, u_name FROM users WHERE u_email = ?");
        $user_sql->bind_param("s", $email);
        $user_sql->execute();
        $user_result = $user_sql->get_result();
        if ($user_row = $user_result->fetch_assoc()) {
            $user_id = $user_row['u_id'];
            $user_name = $user_row['u_name'];

            $totalAmount = $_SESSION['totalAmount'];

            $move_sql = $conn->prepare("INSERT INTO tbl_payment (order_id, f_title, f_disctprice, total_amount, o_quantity, f_vat, o_date, u_id, u_name, ta_id, t_name, amount) SELECT ?, f_title, f_disctprice, total_amount, o_quantity, f_vat, o_date, u_id, u_name, ?, ?, ? FROM tbl_order WHERE u_id = ?");
            $order_id = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
            $_SESSION['order_id'] = $order_id;
            $move_sql->bind_param("sssdi", $order_id, $ta_id, $selected_table, $totalAmount, $user_id);
            if ($move_sql->execute()) {
                $delete_sql = $conn->prepare("DELETE FROM tbl_order WHERE u_id = ?");
                $delete_sql->bind_param("i", $user_id);
                if ($delete_sql->execute()) {
                    $message = "Order confirmed successfully!";
                } else {
                    $message = "Error deleting order.";
                }
            } else {
                $message = "Error moving order to payment table.";
            }
        } else {
            $message = "User not found.";
        }
    } else {
        $message = "Please select your table.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KING Restaurant</title>
    <link rel="icon" type="image/x-icon" href="assets/icons/icon1.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <!--This link is responsible for show the icon on mozilaFireFox-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
    <!--This is responsible for stop resubmission -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>
<body>
	<div class="payment">
		<div class="header">
			<h2>Welcome to Payment Page</h2>
		</div>
				<?php

                if (isset($_SESSION['totalAmount'])) {
                    $totalAmount = $_SESSION['totalAmount'];
                    if (isset($_SESSION['user'])) {
                        $user_email = $_SESSION['user'];
                        // Assuming $conn is your database connection
                        
                        $sql = $conn->prepare("SELECT * FROM users WHERE u_email = ?");
                        $sql->bind_param("s", $user_email);
                        
                        if ($sql->execute()) {
                            $result = $sql->get_result();

                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $user_name = $row['u_name'];
                            } else {
                                echo "No data found!";
                            }
                        } else {
                            echo "Error executing the SQL query: " . $sql->error;
                        }

                        $sql->close();
                        // $conn->close(); // Assuming you don't want to close the connection here
                    } else {
                        header('Location: users/login.php');
                        exit();
                    }
                } else {
                    echo "No Total amount found!";
                }
            ?>

            <div class="pay-body">
                <form action="thankyou.php" method="POST">
                    <div class="form-data">
                        <label>Name: </label>
                        <input type="text" name="u_name" id="u_name" value="<?php echo isset($user_name) ? $user_name : ''; ?>">
                    </div>
                    <div class="form-data">
                        <label>Email: </label>
                        <input type="text" name="u_email" id="u_email" value="<?php echo isset($user_email) ? $user_email : ''; ?>">
                    </div>
                    <div class="form-data">
                        <label>Total Amount: </label>
                        <input type="text" name="total_amount" id="total_amount" value="<?php echo isset($totalAmount) ? '$'.$totalAmount : ''; ?>">
                    </div>
				<div class="btn-pay">
					<script 
						src="https://checkout.stripe.com/checkout.js"
						class="stripe-button"
					    data-key="<?php echo $publishableKey ?>"
					    data-amount="<?php echo $totalAmount * 100; ?>"
					    data-name="KING RESTAURANT"
					    data-description="A1 Restaurent"
					    data-image="https://img.freepik.com/premium-vector/cute-panda-paws-up-wall-panda-face-cartoon-icon_42750-498.jpg"
					    data-currency="usd">

					</script>
				</div>
			</form>
		</div>
	</div>


	<!--Payment page style start-->
	<style type="text/css">
		.payment{
			margin-left: 120px;
			margin-top: 50px;
			padding: 30px;
			background-color: #defafc;
			height: 350px;
			width: 400px;
			border: 4px solid #ddd;
			border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}
		.header{
			text-align: center;
			font-family: sans-serif;
            color: #098aed;
            padding: 10px 0;
            background-color: #f2f2f2;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
		}
		.pay-body{
			padding-left: 30px;
		}
		.form-data input{
			background-color: transparent;
			border: none;
			padding: 5px;
			font-size: 15px;
			color: #068a29;
		}
		.btn-pay{
			margin-top: 30px;
			margin-left: 25%;
		}
	</style>

	<!--Payment page style end-->

</body>
</html>