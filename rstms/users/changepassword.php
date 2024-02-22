<?php 
	session_start();
	include ('includes/header.php');	
	include ('includes/dbconnection.php');

	$message = '';

	if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    } 

	if (isset($_POST['button'])) {
		$currentPassword = $_POST['password'];
		$newPassword = $_POST['n_password'];
		$confirmPassword = $_POST['c_password'];

		$email = $_SESSION['u_email'];

		if ($newPassword !== $confirmPassword) {
			$message = "<p style='color: red; text-align: center;'>New Password and Confirm Password does not match.</p>";
		} else {
			$fetch_password_query = "SELECT u_password FROM users WHERE u_email = '$email'";
			$fetch_password_result = mysqli_query($conn, $fetch_password_query);

			if ($fetch_password_result) {
				$row = mysqli_fetch_assoc($fetch_password_result);
				$hashed_password = $row['u_password'];

				if (password_verify($currentPassword, $hashed_password)) {
					$encrypted_password = password_hash($newPassword, PASSWORD_DEFAULT);
					$update_query = "UPDATE users SET u_password = '$encrypted_password' WHERE u_email = '$email'";
					$update_result = mysqli_query($conn, $update_query);

					if ($update_result) {
						$message = "<p style='color: green; text-align: center;'>Password changed successfully!</p>";
					} else {
						$message = "<p style='color: red; text-align: center;'>Failed to update password. Please try again later.</p>";
					}
				} else {
					$message = "<p style='color: red; text-align: center;'>Incorrect current password.</p>";
				}
			}
		}
	}
?>

<!--Header area End-->

<!--Change Password body area start-->
	<div class="container" id="cng-pass">
		<div class="change-pass">
		<h2>Change Your Password</h2>
		<?php echo $message; ?>
		<div class="form">
			<form action="" method="POST">
				<div class="form-data">
					<label class="form-label"><i class="fa-solid fa-key"></i> Current Password</label>
					<input class="form-control" type="password" name="password" placeholder="Current Password" required>
				</div>
				<div class="form-data">
					<label class="form-label"><i class="fa-solid fa-unlock-keyhole"></i> New Password</label>
					<input class="form-control" type="password" name="n_password" placeholder="New Password" required>
				</div>
				<div class="form-data">
					<label class="form-label"><i class="fa-solid fa-unlock-keyhole"></i> Confirm Password</label>
					<input class="form-control" type="password" name="c_password" placeholder="Confirm Password" required>
				</div>
				<div class="button">
					<button type="submit" name="button" class="btn btn-secondary">Submit</button>
				</div>
			</form>
		</div>			
		</div>
	</div>
<!--Change Password body area end-->

<style type="text/css">
	.change-pass {
		margin: 0px!important;
		padding: 0px!important;
	}
	.change-pass h2 {
		margin-left: 55px;
		font-family: Arial;
		margin-bottom: 30px; 
	}
	.form {
		width: 550px;
		margin-left: 45px;
		margin-top: 30px; 
	}
	.form-data{
		margin-top: 25px;
	}
	.form-label {
		margin-left: 20px;
		color: #03a9fc;
		font-family: Lucida Sans;
	}
	.form-control {
		margin: 10px;
		border-radius: 25px;
	}
	.button {
		margin-left: 43%;
	}
	.btn-secondary {
		padding: 10px;
		background: #05edce;
		border-radius: 10px;
		border: none;
		color: #ffff;
	}
</style>



<!--Footer area-->
<?php include ('includes/footer.php');