<?php
    include('includes/header.php');
    include('includes/sidebar.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
?>

<div class="container">
	<div class="header">
		<h3>BIG WELCOME</h3>
	</div>
</div>

<?php
    include('includes/footer.php');
?>