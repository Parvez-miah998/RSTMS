<?php
    include('includes/header.php');
    include('includes/sidebar.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
?>

<h1 align="center">BIG WELCOME</h1>

<?php
    include('includes/footer.php');
?>