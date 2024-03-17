
	<nav class="navbar">
		<div class="row">
			<div class="logo">
				<a href="dashboard.php"><span style="color: #57f745; font-family: Matura MT Script Capitals;">K</span><i class="fa-solid fa-crown"></i><span style="color: #15e2ed;font-family: Algerian;">N</span><span style="color: #ed1548;font-family: Lucida Sans;">G</span></a>		
			</div>
			<div class="admin-prof"style="display: flex;align-items:center;">
				<?php
				if (isset($_SESSION['admin_email'])) {
					$a_email = $_SESSION['admin_email'];
					$sql_admin = $conn->prepare("SELECT a_name, a_email, a_img, s_admin FROM admin WHERE a_email = ?");
					$sql_admin -> bind_param("s", $a_email);
					$sql_admin -> execute();
					$result_admin = $sql_admin->get_result();
					if ($result_admin && $result_admin->num_rows>0) {
						$row = $result_admin->fetch_assoc()
				?>
						<div class="active-indicator"></div>
						<img src="<?php echo $row['a_img']; ?>" alt="admin profile">
						<div class="detils">
							<span class="admin-name"><?php echo $row['a_name']; ?></span><br>
			                <span class="admin-email"><?php echo $row['a_email']; ?></span>
						</div>
				
				<?php
						
					}
				}
				?>
				
			</div>
		</div>
	</nav>
	
	<!-- side bar start -->
			<div class="side-bar">
				<div class="category">
					<div class="bar">
						<a href="dashboard.php"><i class="fa-solid fa-gauge"></i></a>
					</div>
					<div class="bar">
						<a href="category.php"><i class="fa-solid fa-utensils"></i> </a>
					</div>
					<div class="bar">
						<a href="foodmenu.php"><i class="fa-solid fa-ice-cream"></i> </a>
					</div>
					<div class="bar">
						<a href="table.php"><i class="fa-solid fa-chair"></i> </a>
					</div>
					<div class="bar">
						<a href="bookedtable.php"><i class="fa-solid fa-couch"></i></a>
					</div>
					<div class="bar">
						<a href="userdetails.php"><i class="fa-solid fa-users"></i> </a>
					</div>
					<div class="bar">
						<a href="payment-status.php"><i class="fa-solid fa-credit-card"></i> </a>
					</div>
					<div class="bar">
						<a href="sales-report.php"><i class="fa-solid fa-file-word"></i> </a>
					</div>
					<div class="bar">
						<a href="contactus-details.php"><i class="fa-solid fa-comments-dollar"></i></a>
					</div>
					<div class="bar">
						<a href="user-feedback.php"><i class="fa-solid fa-comment"></i></a>
					</div>
					<div class="bar">
						<a href="account.php"><i class="fa-solid fa-keyboard"></i></a>
					</div>
					<?php
					if ($row['s_admin'] == 'Yes') {
						echo '<div class="bar">
							<a href="admin-info.php"><i class="fa-solid fa-user-tie"></i></a>
						</div>';
					}
					
					?>
					<div class="bar">
						<a href="admin-profile.php"><i class="fa-solid fa-gear"></i></a>
					</div>
					<div class="bar">
						<a href="changepass.php"><i class="fa-solid fa-key"></i>  </a>
					</div>
					<div class="bar">
						<a href="includes/logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> </a>
						<!-- <form action="includes/logout.php" method="POST">
							<div class="btn">
								<button type="submit" id="submit" name="submit" class="btn btn-primary">Logout</button>
							</div>
						</form> -->
					</div>
				</div>
			</div>
<!-- side bar end -->
<!-- Style for Profile active green dot start -->
<style type="text/css">
	.active-indicator {
    width: 10px;
    height: 10px;
    background-color: green;
    border-radius: 50%;
    margin-right: -5px;
    margin-top: -20px;
    animation: pulse 0.5s infinite alternate;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.2);
    }
}

</style>
<!-- Style for Profile active green dot end -->
