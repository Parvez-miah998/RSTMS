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
    <title>KING Restaurant</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/icon1.svg">
    <link rel="stylesheet" type="text/css" href="../assets/css/rfl.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container" id="form-fpass">
        <div class="title" id="fpass">
            <a href="#" class="logo2"><span style="color: #57f745; font-family: Matura MT Script Capitals;">K</span><i class="fa-solid fa-crown" style="color: #faee6b;"></i><span style="color: #15e2ed;font-family: Algerian;">N</span><span style="color: #ed1548;font-family: Lucida Sans;">G</span></a><br>
            <h1>Forgot Password</h1>
            <div class="container-fluid">
            <form action="" method="POST">
                <div class="form-data" id="text-form">
                    <div class="form-control" id="text-data">
                        <input type="email" class="pa" name="u_email" id="email" placeholder="   Enter Your Register Email" required>
                    </div><br>
                    <div class="form-control text-center" id="text-data">
                        <button type="submit" class="button" id="btn-submit" name="submit" value="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
