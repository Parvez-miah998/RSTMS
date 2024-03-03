<?php 
include('includes/dbconnection.php'); 

if (isset($_POST["verify_email"])) {
    $email = $_POST["u_email"];
    $verification_code = $_POST["verification_code"];

    // Updated SQL query with corrected concatenation
    $sql = "UPDATE users SET email_verified_at = NOW() WHERE u_email = '$email' AND verification_code = '$verification_code'";
    $result = mysqli_query($conn, $sql);

	if (!$result) {
	    // Display the SQL error for debugging purposes
	    echo "Error: " . mysqli_error($conn);
	    exit(); // Exit the script after displaying the error
	}

    // $result = mysqli_query($conn, $sql);

    // if (!$result || mysqli_affected_rows($conn) == 0) {
    //     header("Location: emailverification.php?error=Verification code is invalid");
    //     exit();
    // } 
    else {
        header("Location: login.php?success=Your email has been verified successfully");
        exit();
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
	<link rel="stylesheet" type="text/css" href="../assets/css/emailverification.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
	<div class="container">
		<div class="title">
			<h1 class="text-center"><span style="color: #57f745; font-family: Matura MT Script Capitals;">K</span><i class="fa-solid fa-crown" style="color: #faee6b;"></i><span style="color: #15e2ed;font-family: Algerian;">N</span><span style="color: #ed1548;font-family: Lucida Sans;">G</span></h1>
			<h6 class="text-center">Alredy verified?<a href="login.php"> Login</a></h6>
		</div>
		<div class="form">
    <form action="emailverification.php" method="POST">
        <div class="form-data">
            <div class="verification-code-boxes">
                <input type="hidden" name="u_email" value="<?php echo isset($_GET['u_email']) ? htmlspecialchars($_GET['u_email']) : ''; ?>">
                <input type="text" name="verification_code[]" maxlength="1" class="code-box" placeholder="_" style="height: 50px; width: 50px; text-align: center; font-size: 22px;" oninput="moveFocus(this)">
                <input type="text" name="verification_code[]" maxlength="1" class="code-box" placeholder="_" style="height: 50px; width: 50px; text-align: center; font-size: 22px;" oninput="moveFocus(this)">
                <input type="text" name="verification_code[]" maxlength="1" class="code-box" placeholder="_" style="height: 50px; width: 50px; text-align: center; font-size: 22px;" oninput="moveFocus(this)">
                <input type="text" name="verification_code[]" maxlength="1" class="code-box" placeholder="_" style="height: 50px; width: 50px; text-align: center; font-size: 22px;" oninput="moveFocus(this)">
                <input type="text" name="verification_code[]" maxlength="1" class="code-box" placeholder="_" style="height: 50px; width: 50px; text-align: center; font-size: 22px;" oninput="moveFocus(this)">
                <input type="text" name="verification_code[]" maxlength="1" class="code-box" placeholder="_" style="height: 50px; width: 50px; text-align: center; font-size: 22px;" oninput="moveFocus(this)">
                <!-- Add more input fields for verification codes -->
                <!-- Example: -->
                <!-- <input type="text" name="verification_code[]" maxlength="1" class="code-box" placeholder="_" style="height: 50px; width: 50px; text-align: center; font-size: 22px;" oninput="moveFocus(this)"> -->
            </div>
        </div><br>
        <div class="btn">
            <button type="submit" class="btn btn-secondary" name="verify_email">Submit</button>
        </div>
    </form>
</div>

		

	<script>
	    function moveFocus(input) {
	        const nextInput = input.nextElementSibling;
	        const prevInput = input.previousElementSibling;

	        if (input.value === "") {
	            // If the current input is emptied, focus on the previous input
	            if (prevInput) {
	                prevInput.focus();
	            }
	        } else if (nextInput && input.value !== "") {
	            // If the current input is not empty, focus on the next input
	            nextInput.focus();
	        }

	        // Prevent the form submission
	        return false;
	    }
	</script>



</body>
</html>