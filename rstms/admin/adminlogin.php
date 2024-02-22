<?php
// session_start();
// include('includes/dbconnection.php');

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     // Retrieve hashed password from the database based on the provided email
//     $sql = "SELECT a_password FROM admin WHERE a_email = '$email'";
//     $result = $conn->query($sql);

//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $stored_password = $row['a_password'];

//         // Compare the provided password with the hashed password from the database
//         if ($password === $stored_password) {
//             $_SESSION['admin_email'] = $email; // Set session variable upon successful login
//             header("Location: dashboard.php");
//             exit();
//         } else {
//             echo "Invalid email or password";
//         }
//     } else {
//         echo "Invalid email or password";
//     }
// }
?>
<?php  
// 	session_start();
// 	include('includes/dbconnection.php');

// 	if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     // Retrieve hashed password from the database based on the provided email
//     $sql = "SELECT a_password FROM admin WHERE a_email = '$email'";
//     $result = $conn->query($sql);

//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $stored_password = $row['a_password'];

//         // Compare the provided password with the hashed password from the database
//         if ($password === $stored_password) {
//             echo "Login successful";
//             header("Location: dashboard.php");
//             exit();
//         } else {
//             echo "Invalid email or password";
//         }
//     } else {
//         echo "Invalid email or password";
//     }
// }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="../assets/css/login.css">
</head>
<body>
	<div class="container">
		<div class="title">
			<h2 class="text-center text-white">Admin Login</h2>
		</div>
		<div class="form">
			<form action="" method="POST">
				<div class="form-data">
					<div class="user-details">
						<label for="email">Email</label>
						<input type="email" id="email" name="email" placeholder="e.g. example@gmail.com" required>
					</div><br>
					<div class="user-details">
						<label for="password">Password</label>
						<input type="password" id="password" name="password" placeholder="Enter Your Password" required>
					</div><br>
					<div class="btn">
						<button type="submit" id="submit" name="submit" class="btn btn-primary">Login</button>
					</div>
				</div>
			</form>
		</div>
	</div>


</body>
</html>