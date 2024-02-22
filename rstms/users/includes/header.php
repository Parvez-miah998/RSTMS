<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>My Profile</title>
	<link rel="icon" type="image/x-icon" href="../assets/icons/id-card-solid.svg">
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- jQuery UI -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<!--Notification-->
	<!--Icons-->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<!-- This links for feedback star statrt-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <!-- This links for feedback star end -->

	<link rel="stylesheet" type="text/css" href="../assets/css/userprofile.css">
	<!--Script for stop resubmission-->
	<script>
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>

</head>
<body>
	<div class="container-xl px-4 mt-4">
    <!-- Account page navigation-->
    <nav class="nav nav-borders">
        <a class="nav-link" href="myprofile.php" style="font-family: Arial;"><i class="fa-solid fa-id-card-clip"></i> Profile</a>
        <a class="nav-link" href="feedback.php" target="__blank"  style="font-family: Arial;"><i class="fa-solid fa-comments"></i> Feedback</a>
        <a class="nav-link" href="changepassword.php" target="__blank"  style="font-family: Arial;"><i class="fa-solid fa-lock"></i> Change Password</a>
        <a class="nav-link" href="viewall.php" target="__blank"  style="font-family: Arial;"><i class="fa-solid fa-eye"></i> View All</a>
        <a class="nav-link" href="notification.php"  target="__blank"  style="font-family: Arial;"><i class="fa-solid fa-bell"></i> Notifications</a>
        <a class="nav-link" href="../logout.php"  style="font-family: Arial;color: #fa0710;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </nav>

    <script type="text/javascript">
    	// Get the current URL
		const currentUrl = window.location.href;

		// Get all navigation links
		const navLinks = document.querySelectorAll('.nav-link');

		// Loop through each link and check if its href matches the current URL
		navLinks.forEach(link => {
		    if (link.href === currentUrl) {
		        link.classList.add('active');
		    }
		});

    </script>