<?php
	include ('includes/header.php');
  include ('includes/sidebar.php');
  if (!isset($_SESSION['admin_email'])) {
      header("Location: ../users/login.php");
      exit();
  }

  $email = isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : '';
  $message = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['admin_email'];
    $c_pass = $_POST['current_pass'];
    $n_pass = $_POST['new_pass'];
    $co_pass = $_POST['confirm_pass'];

    if ($n_pass !== $co_pass) {
      $message = "New Password and Confirm Password Does not match!";
     
    }

    else{
      if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $n_pass)) {
          $message = '<div style="color: red;">Password must be at least 6 characters long and contain at least one letter and one digit.</div>';
        }
        else{
        $sql_admin = "SELECT a_id,a_password FROM admin WHERE a_email = ?";
        $stmt_admin = mysqli_prepare($conn, $sql_admin);
        mysqli_stmt_bind_param($stmt_admin, "s", $email);
        mysqli_stmt_execute($stmt_admin);
        mysqli_stmt_store_result($stmt_admin);

        if (mysqli_stmt_num_rows($stmt_admin)>0) {
          mysqli_stmt_bind_result($stmt_admin, $admin_id, $stored_pass);
          mysqli_stmt_fetch($stmt_admin);

          if (password_verify($c_pass, $stored_pass) || ($c_pass === $stored_pass)) {
            $hash_new_password = password_hash($n_pass, PASSWORD_DEFAULT);
            $sql_upass = "UPDATE admin SET a_password = ? WHERE a_id = ?";
            $stmt_upass = mysqli_prepare($conn, $sql_upass);
            mysqli_stmt_bind_param($stmt_upass, "si", $hash_new_password, $admin_id);
            mysqli_stmt_execute($stmt_upass);

            $message = "Password Update Successfully!";
            
          }
          else{
            $message = "Invalid Current Password!";
          }
        }
        else{
          $message = "Email Does not Found!";
        }
        mysqli_stmt_close($stmt_admin);
        
      }
    }
  mysqli_close($conn);
  }
?>

<div class="container">
	<div class="header">
		<h2>Change Your Password</h2>
	</div>
	<div class="change-pass">
		<form action="" method="POST">
			<div class="form-data">
				<input type="email" name="email" placeholder="Enter Your Email" value="<?php echo $email; ?>" readonly>
			</div>
			<div class="form-data">
				<input type="password" name="current_pass" placeholder="Enter Current Password" required>
			</div>
			<div class="form-data">
				<input type="password" name="new_pass" placeholder="Enter New Password" required>
			</div>
			<div class="form-data">
				<input type="password" name="confirm_pass" placeholder="Enter Confirm Password" required>
			</div>
			<div class="form-btn">
				<button type="submit" name="forget-pass">Submit</button>
			</div>

  <?php if (!empty($message)) : ?>
    <p style="color: green;"> <?php echo $message; ?> </p>
  <?php endif; ?>
		</form>
	</div>
</div>

<!--Style for Change Password start-->
<style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 650px;
      margin: 50px auto;
      background-color: #e8faf1;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      border-radius: 15px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    h2 {
      color: #333;
    }

    .change-pass {
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .form-data {
      margin-bottom: 15px;
    }

    input {
      width: 350px;
      padding: 10px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 10px;
      margin-top: 5px;
    }

    button {
      background-color: #4caf50;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
<!--Style for Change Password end-->





<?php
	include ('includes/footer.php');
?>