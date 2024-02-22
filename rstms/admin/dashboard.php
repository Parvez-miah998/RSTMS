<?php
	include('includes/header.php');
	if (!isset($_SESSION['admin_email'])) {
	    header("Location: ../users/login.php");
	    exit();
	}
?>


	<h1 style="font-size: 48px;text-align: center;color: greenyellow;">Wlecome to Dashbord</h1>
	<p style="font-size: 18px;text-align: center;color: green; margin-top: 12px;">Work in Porgress</p>


	<form action="includes/logout.php" method="POST">
		<div class="btn" style="text-align: center; margin-bottom: 10px;">
			<button type="submit" id="submit" name="submit" class="btn btn-primary">Logout</button>
		</div>
	</form>
	<div class="category" style="text-align: center; margin-bottom: 20px;">
		<a href="category.php" style="text-decoration: none; margin: 15px; padding: 5px;">Category</a>
		<a href="foodmenu.php" style="text-decoration: none; margin: 15px; padding: 5px;">Food Menu</a>
		<a href="changepass.php" style="text-decoration: none; margin: 15px; padding: 5px;">Change Password</a>
		<a href="table.php" style="text-decoration: none; margin: 15px; padding: 5px;">Table & Seats</a>
		<a href="bookedtable.php" style="text-decoration: none; margin: 15px; padding: 5px;">Booked Table</a>
	</div>

<?php include('includes/footer.php'); ?>