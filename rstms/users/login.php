<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $email = $_POST['u_email'];
    $password = $_POST['u_password'];
    $_SESSION['u_email'] = $email;

    $sql_user = "SELECT * FROM users WHERE u_email = ?";
    $stmt_user = mysqli_prepare($conn, $sql_user);
    mysqli_stmt_bind_param($stmt_user, "s", $email);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);

    if (mysqli_num_rows($result_user) > 0) {
        $user = mysqli_fetch_object($result_user);

        if (!password_verify($password, $user->u_password)) {
            // User login failed
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

            if ($_SESSION['login_attempts'] >= 2) {
                // Show the forgot password fields instead of redirecting to forgotpassword.php
                $showForgotPasswordFields = true;
            }
            $_SESSION['error'] = "Password is incorrect!";
    		header("Location: login.php");
            // header("Location: login.php?error=Password is incorrect!");
            exit();
        }

        if ($user->email_verified_at == null) {
        	$_SESSION['error'] = "Please verify your email first.";
    		header("Location: login.php");
            // header("Location: login.php?error=Please verify your email <a href='emailverification.php?u_email=" . $email . "'>from here.</a>");
            exit();
        }

        $_SESSION['user'] = $email;
        // $_SESSION['user_id'] = $user->user_id;
        header("Location: ../index.php");
        exit();
    } else {
        // Check admin credentials if user login failed
        $sql_admin = "SELECT a_password FROM admin WHERE a_email = ?";
        $stmt_admin = mysqli_prepare($conn, $sql_admin);
        mysqli_stmt_bind_param($stmt_admin, "s", $email);
        mysqli_stmt_execute($stmt_admin);
        $result_admin = mysqli_stmt_get_result($stmt_admin);

        if (mysqli_num_rows($result_admin) > 0) {
            $admin = mysqli_fetch_assoc($result_admin);
            $stored_password = $admin['a_password'];

            if (password_verify($password, $stored_password) || ($password === $stored_password)) {
                $_SESSION['admin_email'] = $email;
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
            	$_SESSION['error'] = "Invalid email or password.";
    			header("Location: login.php");
                // header("Location: login.php?error=Invalid email or password");
                exit();
            }
        } else {
        	$_SESSION['error'] = "Email Does not Found!";
    		header("Location: login.php");
            // header("Location: login.php?error=Email Does not Found!");
            exit();
        }
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
	<link rel="stylesheet" type="text/css" href="../assets/css/rfl.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
	<div class="container">
		<div class="row">
			<a href="#" class="logo1"><span style="color: #57f745; font-family: Matura MT Script Capitals;">K</span><i class="fa-solid fa-crown" style="color: #faee6b;"></i><span style="color: #15e2ed;font-family: Algerian;">N</span><span style="color: #ed1548;font-family: Lucida Sans;">G</span></a><br>
			<h1>Login <span>Here!</span> </h1>
			<?php if (isset($_SESSION['error'])): ?>
			  <div class="error-message"><?php echo $_SESSION['error']; ?></div>
			  <?php unset($_SESSION['error']); ?>
			<?php endif; ?>
			<div class="container-fluid">
			<form action="" method="POST">
			    <div class="form-data">
			        <div class="form-control">
                        <input type="email" class="pa" name="u_email" id="email" class="text-white text-center mt-2 p-3" placeholder="   Enter Your Email" required>
                    </div><br>
					<div class="form-control">
						<input type="password" class="pa" name="u_password" id="password" class="text-white text-center mt-2 p-3" placeholder="   Enter Password" required>
					</div><br>
			        <div class="form-control">
			            <button type="submit" class="button" name="login" value="login">Login</button>
			        </div>
			        <?php
                        // Display "Forgot Password" link after two failed login attempts
                        if(isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 2) {
                            echo '<h6 class="text-center">Lost your password?<a href="forgotpassword.php"> Forgot Password</a></h6>';
                        }
                    ?>
			    </div>
			</form>
		</div>
		<div class="footer-form-controler">
			<h5 class="login-footer">Are you new here? <a href="registration.php">Sign Up</a></h5>
		</div>
	</div>

</body>
</html> 