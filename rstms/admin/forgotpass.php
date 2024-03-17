<?php
    include('includes/header.php');
    include('includes/sidebar.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }

    $message = '';
    if (isset($_GET['id'])) {
	    $id = $_GET['id'];
	    $sql = $conn->prepare("SELECT * FROM admin WHERE a_id = ?");
	    $sql -> bind_param("i", $id);
	    $sql -> execute();
	    $result = $sql->get_result();
	    if ($result && $result->num_rows>0) {
	    	$row = $result->fetch_assoc();
	    }
	    else{
	    	$message = '<div style="color: red;">No Data Found!</div>';
	    }
	}
    if (isset($_POST['for_pass'])) {
	    $new_pass = mysqli_real_escape_string($conn, $_POST['a_pass']);
	    $co_pass = mysqli_real_escape_string($conn, $_POST['a_cpass']);

	    if ($new_pass != $co_pass) {
	    	$message = '<div style="color: red;">Passwords do not match!</div>';
	    }
	    else{
	    	if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $new_pass)) {
	    		$message = '<div style="color: red;">Password must be at least 6 characters long and contain at least one letter and one digit.</div>';
	    	}
	    	else{
		    	$hased_password = password_hash($new_pass, PASSWORD_DEFAULT);
		    	$sql_update = $conn->prepare("UPDATE admin SET a_password = ? WHERE a_id = ?");
		    	$sql_update -> bind_param("si", $hased_password, $id);
		    	if ($sql_update->execute()) {
		    		$message = '<div style="color: green;">Password updated successfully!</div>';
		    	}
		    	else{
		    		$message = '<div style="color: red;">Failed to update password. Please try again!</div>';
		    	}
		    }
	    }
    }
?>
<div class="container">
	<div class="header">
		<h3>Forgot Password</h3>
	</div>
	<?php if (!empty($message)) : ?>
        <p> <?php echo $message;?></p>
    <?php endif; ?>
    <div class="form-controller">
        <form action="" method="POST">
            <div class="form-data">
                <label>Admin Name</label>
                <input type="text" name="a_name" id="a_name" value="<?php echo isset($row['a_name']) ? $row['a_name'] : ''; ?>" readonly>
            </div>
            <div class="form-data">
                <label>Admin Contact</label>
                <input type="text" name="a_contact" id="a_contact" value="<?php echo isset($row['a_contact']) ? $row['a_contact'] : ''; ?>" readonly>
            </div>
            <div class="form-data">
                <label>Admin Email</label>
                <input type="text" name="a_email" id="a_email" value="<?php echo isset($row['a_email']) ? $row['a_email'] : ''; ?>" readonly>
            </div>
            <div class="form-data">
                <label>Admin NID or Passport No</label>
                <input type="text" name="a_nid" id="a_nid" value="<?php echo isset($row['a_nid']) ? $row['a_nid'] : ''; ?>" readonly>
            </div>
            <div class="form-data">
                <label>Date of Birth</label>
                <input type="text" name="a_dob" id="a_dob" class="custom-date-picker" value="<?php echo isset($row['a_dob']) ? $row['a_dob'] : ''; ?>" readonly>
            </div>
            <div class="form-data">
                <label>New Password</label>
                    <input type="password" name="a_pass" id="a_pass" required>
            </div>
            <div class="form-data">
                <label>Confirm Password</label>
                    <input type="password" name="a_cpass" id="a_cpass" required>
            </div>
            <div class="form-btn">
                <button type="submit" name="for_pass" id="for_pass">Forgot Password</button>
            </div>
        </form>
    </div>
</div>


 <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f0fc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .header {
            background-color: #55fa8f;
            color: #050505;
            text-align: center;
            padding: 5px 0;
            margin-bottom: 20px;
            border-radius: 10px 10px 0 0;
        }

        .form-controller {
            padding: 20px 0;
            margin-left: 20px;
        }

        .form-data {
            margin-bottom: 20px;
        }

        .form-data label {
            display: inline-block;
            font-weight: bold;
        }

        .form-data input {
            width: calc(100% - 100px);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 16px;
        }
        .form-data #a_dob{
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .form-image {
            margin-bottom: 20px;
        }

        .form-image label {
            display: inline-block;
            margin-bottom: 5px;
            font-weight: bolder;
        }

        .form-image input[type="file"] {
            display: none;
        }

        .form-image .a_image {
            background-color: #05f5d9;
            color: #000000;
            padding: 8px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-weight: 100;
        }

        .admin_image img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            margin-top: 10px;
            object-fit: cover;
        }

        .form-btn {
            text-align: center;
        }

        .form-btn button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 40px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }

        .form-btn button:hover {
            background-color: #05fa7f;
        }
        .radio-data label{
        	display: inline-block;
            margin-bottom: 5px;
            font-weight: bolder;
            margin-bottom: 15px;
            margin-right: 10px;
        }
        /* Styling the calendar start */
        .custom-date-picker {
            border: 2px solid #0f1212;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }

        input[type="text"] {
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }

        input[type="text"]::-webkit-calendar-picker-indicator {
            color: #333;
            font-size: 18px;
            cursor: pointer;
        }
        /* Styling the calendar end */
    </style>

<!--script for calender start-->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Initialize Flatpickr
    flatpickr('#a_dob', {
        dateFormat: 'Y-m-d',
        maxDate: 'today', // Set max date to today
        disableMobile: true // Disable mobile-friendly date picker (optional)
    });
</script>
    <!--script for calender end-->


<?php
    include('includes/footer.php');
?>