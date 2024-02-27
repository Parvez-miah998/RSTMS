<?php  
	include('includes/dbconnection.php');
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require('vendor/autoload.php');

	if (isset($_POST['register'])) {
	    $fullname = filter_var($_POST['u_name'], FILTER_SANITIZE_STRING);
	    $contact = filter_var($_POST['u_contact'], FILTER_SANITIZE_STRING);
	    $email = filter_var($_POST['u_email'], FILTER_SANITIZE_EMAIL);
	    $password = $_POST['u_password'];
	    $confirm_password = $_POST['confirm_password'];

	    // Perform password matching check
	    if ($password !== $confirm_password) {
	    	header("Location: registration.php?error=Password and Confirm Password does not match.");
	        // Handle password mismatch error here
	        // echo "Passwords does not match.";
	        exit();
	    }
	    // Check if the email already exists in the database
	    $conn = mysqli_connect("localhost", "root", "", "restaurentdb");
	    $check_email_query = "SELECT * FROM users WHERE u_email = '$email'";
	    $check_email_result = mysqli_query($conn, $check_email_query);

	    if (mysqli_num_rows($check_email_result) > 0) {
	    	header("Location: registration.php?error=Email already exists. Please use a different email.");
	        // If the email already exists, show an error message
	        // echo "Email already exists. Please use a different email.";
	        exit();
	    }


	    $mail = new PHPMailer(true);

	    try {
	        // Configure the mailer
	        //$mail = new PHPMailer(true);
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'parvezmosarof195@gmail.com';
			$mail->Password = 'kqew cmbn xnrh lcep';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port = 587;
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;

	        $mail->setFrom('parvezmosarof195@gmail.com', 'parvezmosarof195');
	        $mail->addAddress($email, $fullname);
	        $mail->isHTML(true);

	        // Generate verification code
        	$verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

	        // Compose and send the email
	        $mail->Subject = 'Email Verification Code!';
	        $mail->Body = '<p>Your verification code is: <b style="font-size:30px">' . $verification_code . '</b></p>';
	        $mail->send();

	        // Hash password
        	$encrypted_password = password_hash($password, PASSWORD_DEFAULT);

	        $conn = mysqli_connect("localhost", "root", "", "restaurentdb");
	        $sql = "INSERT INTO users (u_name, u_contact, u_email, u_password, verification_code) 
        		VALUES ('$fullname', '$contact', '$email', '$encrypted_password', '$verification_code')";
	        $result = mysqli_query($conn, $sql);
	        if ($result) {
	            header("Location: emailverification.php?u_email=" . $email);
	            exit();
	        } else {
	            // Handle database insertion failure
	            echo "Database insertion failed.";
	            exit();
	        }
	    } catch (Exception $e) {
	        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	    }
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>KING Restaurant</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/icon1.svg">
	<link rel="stylesheet" type="text/css" href="../assets/css/userlogin.css">
</head>
<body>
	<div class="container" id="form-reg">
		<div class="row" id="row1">
			<a href="#" class="logo">BIGTree</a><br>
			<br><h1>Registration Here!</h1>
			<div class="container-fluid" id="row2">
			<form action="" method="POST">
				<div class="form-data">
					<div class="form-control">
						<input type="text" name="u_name" id="name" class="text-white text-center mt-2 p-3" placeholder="   Enter Your Name" required>
					</div><br>
					<div class="form-control">
						<input type="text" name="u_contact" id="contact" placeholder="   Enter Your Contact No." required>
					</div><br>
					<div class="form-control">
						<input type="email" name="u_email" id="email" placeholder="   Enter Your Email" required>
					</div><br>
					<div class="form-control">
						<input type="password" name="u_password" id="password" placeholder="   Password" required>
					</div><br>
					<div class="form-control">
						<input type="password" name="confirm_password" id="confirm_password" placeholder="   Confirm Password" required>
					</div><br>
					<div class="form-control">
						<!-- <label for="password">Confirm Password</label> -->
						<button type="submit" class="button" name="register" value="register" id="register">Register</button>
					</div>
				</div>
			</form>
		</div><br>
			<div class="footer-form-controler">
				<h5 class="text">Alredy have an account? <a href="login.php"> Sign In</a></h5>
			</div>
	</div>

</body>
</html>