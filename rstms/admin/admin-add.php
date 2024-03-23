<?php
    include('includes/header.php');
    include('includes/sidebar.php');

    $message = '';
    if (isset($_POST['reg_btn'])) {
    	$a_name = mysqli_real_escape_string($conn, $_POST['a_name']);
    	$a_email = mysqli_real_escape_string($conn, $_POST['a_email']);
    	$a_contact = mysqli_real_escape_string($conn, $_POST['a_contact']);
    	$a_caddress = mysqli_real_escape_string($conn, $_POST['a_caddress']);
    	$a_paddress = mysqli_real_escape_string($conn, $_POST['a_paddress']);
    	$a_nid = mysqli_real_escape_string($conn, $_POST['a_nid']);
    	$a_dob = mysqli_real_escape_string($conn, $_POST['a_dob']);
    	$a_pass = mysqli_real_escape_string($conn, $_POST['a_pass']);
    	$a_cpass = mysqli_real_escape_string($conn, $_POST['a_cpass']);
    	$s_admin = mysqli_real_escape_string($conn, $_POST['s_admin']);

    	if ($a_pass !== $a_cpass) {
    		$message = '<div>Passwords and Confirm Password does not match!</div>';
    		exit();
    	}
    	else{
    		if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $a_pass)) {
    			$message = '<div style="color: red;">Password must be at least 6 characters long and contain at least one letter and one digit.</div>';
    		}
    		else{
		    	$hashed_password = password_hash($a_pass, PASSWORD_DEFAULT);
		    	$sql_check_email = $conn->prepare("SELECT * FROM admin WHERE a_email = '$a_email'");
		    	$sql_check_email -> execute();
		    	$result_check_email = $sql_check_email->get_result();
		    	if ($result_check_email->num_rows>0) {
		    		$message = '<div style="color: red;">Email already exists!</div>';
		    	}
		    	else{
			    	$target_dir = "../assets/admin_image/";
			    	$target_file = $target_dir. basename($_FILES['a_image']['name']);
			    	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

			    	if (isset($_POST['reg_btn'])) {
			    		$check = getimagesize($_FILES['a_image']['tmp_name']);
			    		if ($check !== false) {
			    			if(move_uploaded_file($_FILES['a_image']['tmp_name'], $target_file)){
				    			$sql_admin = $conn->prepare("INSERT INTO admin (a_name, a_email, a_contact, a_caddress, a_paddress, a_img, a_dob, a_nid, s_admin, a_password) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
						    	$sql_admin -> bind_param("ssssssssss", $a_name, $a_email, $a_contact, $a_caddress, $a_paddress, $target_file, $a_dob, $a_nid, $s_admin, $hashed_password);
						    	if ($sql_admin->execute()) {
						    		$message = '<div style="color: green;">Admin registered successfully!</div>';
						    	}
						    	else{
						    		$message = '<div style="color: red;">Error: </div>'. $sql_admin. '<br>'.$sql_admin->error;
						    	}
						    }
						    else {
				                $message = '<div style="color: red;">Error uploading file!</div>';
				            }
			    		}
			    		else{
			    			$message = '<div style="color: red;">File is not an Image!</div>';
			    			exit();
			    		}
			    	}
			    }
			}
	    	
	    }


    }
    if (isset($sql_admin)) {
    	$sql_admin->close();
    }
    
?>
	<div class="container">
	<div class="header">
		<h3>Admin Register</h3>
	</div>
	<?php if (!empty($message)) : ?>
        <p> <?php echo $message;?></p>
    <?php endif; ?>
    <div class="form-controller">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-data">
                <label>Admin Name</label>
                <input type="text" name="a_name" id="a_name" value="" required>
            </div>
            <div class="form-data">
                <label>Admin Contact</label>
                <input type="text" name="a_contact" id="a_contact" value="" required>
            </div>
            <div class="form-data">
                <label>Admin Email</label>
                <input type="text" name="a_email" id="a_email" value="" required>
            </div>
            <div class="form-data">
                <label>Admin Current Address</label>
                <input type="text" name="a_caddress" id="a_caddress" value="" required>
            </div>
            <div class="form-data">
                <label>Admin Permanent Address</label>
                <input type="text" name="a_paddress" id="a_paddress" value="" required>
            </div>
            <div class="form-data">
                <label>Admin NID or Passport No</label>
                <input type="text" name="a_nid" id="a_nid" value="" required>
            </div>
            <div class="form-data">
                <label>Date of Birth</label>
                    <input type="text" name="a_dob" id="a_dob" class="custom-date-picker" value="" required>
            </div>
            <div class="form-data">
                <label>Password</label>
                    <input type="password" name="a_pass" id="a_pass" required>
            </div>
            <div class="form-data">
                <label>Confirm Password</label>
                    <input type="password" name="a_cpass" id="a_cpass" required>
            </div>
            <div class="radio-data">
			    <label>Super Admin:</label>
			    <input type="radio" name="s_admin" id="s_admin_yes" value="Yes" required>
			    <label for="s_admin_yes">Yes</label>
			    <input type="radio" name="s_admin" id="s_admin_no" value="No" checked required>
			    <label for="s_admin_no">No</label>
			</div>
            <div class="form-image">
                <label>Choose Your File:</label>
                <input type="file" name="a_image" id="a_image" class="a_image">
                <label for="a_image" class="a_image">Upload Iamge</label>
                <div class="admin_image">
                    <img src="../assets/iamge/image34.jpg" alt="admin_image">
                </div>
                
            </div>
            <div class="form-btn">
                <button type="submit" name="reg_btn" id="reg_btn">Register</button>
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