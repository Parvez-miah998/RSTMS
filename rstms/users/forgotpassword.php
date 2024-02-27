<?php 
    include('includes/dbconnection.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
     
    // Send the new password to the user's email
    require('vendor/autoload.php');

    if (isset($_POST["submit"])) {
        $email = $_POST["u_email"];

        // Check if email exists in the database
        $sql = "SELECT * FROM users WHERE u_email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Generate a new password
            $new_password = substr(md5(rand()), 0, 8);
            $encrypted_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $update_sql = "UPDATE users SET u_password = '$encrypted_password' WHERE u_email = '$email'";
            $update_result = mysqli_query($conn, $update_sql);

            if ($update_result) {

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'parvezmosarof195@gmail.com';
                    $mail->Password = 'kqew cmbn xnrh lcep';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

                    $mail->setFrom('parvezmosarof195@gmail.com', 'parvezmosarof195');
                    $mail->addAddress($email, $row['u_name']);
                    $mail->isHTML(true);

                    $mail->Subject = 'Password Reset';
                    $mail->Body = '<p>Your new password is: <strong>' . $new_password . '</strong></p>';
                    $mail->send();

                    header("Location: login.php?success=New password sent to your email");
                    exit();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo "Failed to update password.";
            }
        } else {
            echo "Email not found in the database.";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgotpassword Page</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/userlogin.css">
</head>
<body>
    <div class="container" id="form-fpass">
        <div class="title" id="fpass">
            <a href="#">BIGTree</a><br>
            <h1>Forgot<span> Password</span> </h1>
            <div class="container-fluid">
            <form action="" method="POST">
                <div class="form-data">
                    <div class="form-control">
                        <input type="email" name="u_email" id="email" placeholder="   Enter Your Register Email" required>
                    </div><br>
                    <div class="form-control text-center">
                        <button type="submit" class="button" name="submit" value="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
