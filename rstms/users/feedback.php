<!--Header area start-->
<?php 
	session_start();
	include ('includes/header.php'); 
	include ('includes/dbconnection.php');
	if (!isset($_SESSION['user'])) {
	 	header('Location: login.php');
	 	exit();
	 } 

	 $feedbackmsg = '';

	 $logInuser = $_SESSION['user'];
	 $sql = "SELECT u_email, u_id FROM users WHERE u_email = ?";
	 $stmt = $conn->prepare($sql);
	 $stmt -> bind_param("s", $logInuser);
	 $stmt->execute();
	 $stmt->bind_result($userEmail, $userId);

	 $stmt->fetch();
	 $stmt->close();

	 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    $uemail = $_POST['uemail'];
	    $rating = $_POST['rating'];
	    $feedback = $_POST['f_content'];

	    $sql = "INSERT INTO tbl_feedback (u_id, f_rate, f_content) VALUES (?, ?, ?)";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("iis", $userId, $rating, $feedback);

	    if ($stmt->execute()) {
	        $feedbackmsg = "Thank you for your feedback!";
	    } else {
	        $feedbackmsg = "Feedback Error!";
	    }

	    $stmt->close();
	    $conn->close();
	}
?>

<!--Header area End-->

<!--Feedback body area start-->
<div class="container" id="feedback">
		<div class="change-pass">
		<h2>Give us your feedback!</h2>
		<?php
			if ($feedbackmsg) {
				echo "<p>$feedbackmsg</p>";
			}
		?>
		<div class="form">
			<form action="" method="POST">
				<div class="form-group">
                    <label class="form-label" for="uemail"><i class="fa-solid fa-envelope"></i> User Email</label>
                    <input type="text" class="form-control" id="uemail" name="uemail" value="<?php echo $userEmail; ?>" readonly>
                </div><br>
                <div class="form-group">
				    <label class="form-label" for="rating"><i class="fa-solid fa-star"></i> Rating</label>
				    <div id="rating"></div>
				    <!-- Hidden input to store the selected rating -->
				    <input type="hidden" name="rating" id="rating_input">
				</div><br>
				<div class="form-group">
                    <label class="form-label" for="f_content"><i class="fa-solid fa-comment"></i> Feedback</label>
                    <textarea class="form-control" id="f_content" name="f_content" rows="2" placeholder="What's on your mind!" required></textarea>
                </div><br>
				<div class="button">
					<button type="submit" name="button" class="btn btn-secondary">Submit</button>
				</div>
			</form>
		</div>			
		</div>
	</div>
<!--Feedback body area end-->

<style type="text/css">
	.change-pass {
		margin: 0px!important;
		padding: 0px!important;
	}
	.change-pass h2 {
		margin-left: 55px;
		font-family: Arial;
		margin-bottom: 30px; 
	}
	.form {
		width: 550px;
		margin-left: 45px;
		margin-top: 30px; 
	}
	.form-label {
		margin-left: 20px;
		color: #03a9fc;
		font-family: Lucida Sans;
	}
	.form-control {
		margin: 10px;
		border-radius: 15px;
	}
	.button {
		margin-left: 43%;
	}
	.btn-secondary {
		padding: 10px;
		background: #05edce;
		border-radius: 10px;
		border: none;
		color: #ffff;
	}
	#rating{
		margin-left: 20px;
		padding: 5px;
	}
</style>

<script>
    $(function () {
        $("#rating").rateYo({
            starWidth: "40px",
            rating: 0,
            fullStar: true,
            onSet: function (rating, rateYoInstance) {
                // Store the selected rating in a hidden input field
                $("#rating_input").val(rating);
            }
        });
    });
</script>




<!--Footer area-->
<?php include ('includes/footer.php');