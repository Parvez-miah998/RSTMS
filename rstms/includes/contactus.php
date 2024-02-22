<?php
include('dbconnection.php');

$successMessage = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_form'])) {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $message = $conn->real_escape_string($_POST['message'] ?? '');

    $stmt = $conn->prepare("INSERT INTO tbl_contactus (ctus_name, ctus_email, ctus_desc) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $stmt->close();
        $successMessage = 'Your message has been successfully submitted!';
        unset($_POST);
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<div class="contact-container container">
    <div class="contact-img">
        <img src="https://i.postimg.cc/1XvYM67V/restraunt2.jpg" alt="" />
    </div>

    <div class="form-container">
        <h2>Contact Us</h2>
        <?php if (!empty($successMessage)) : ?>
            <p><?php echo $successMessage; ?></p>
        <?php endif; ?>
        
        <form action="" method="POST">
            <?php if (empty($successMessage)) : ?>
                <input type="hidden" name="contact_form" value="1"> 
                <input type="text" name="name" placeholder="Your Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <textarea cols="30" rows="4" name="message" placeholder="Type Your Message" required></textarea><br>
                <input type="submit" name="Submit" style="border: none;background: #3eedd6;font-weight: bolder;">
            <?php endif; ?>
        </form>
    </div>
</div>
