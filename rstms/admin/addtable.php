<?php
include('includes/header.php');
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../users/login.php");
    exit();
}

	if (isset($_SESSION['admin_email'])) {
		if (isset($_REQUEST['tbl-btn'])) {
	    $message = '';

	    $t_name = mysqli_real_escape_string($conn, $_REQUEST['t_name']);
	    $t_seat = mysqli_real_escape_string($conn, $_REQUEST['t_seat']);
	    $t_status = mysqli_real_escape_string($conn, $_REQUEST['t_status']);
	    $t_category = mysqli_real_escape_string($conn, $_REQUEST['t_category']);
	    // $u_name = mysqli_real_escape_string($conn, $_REQUEST['u_name']);
	    $u_name = isset($_REQUEST['u_name']) ? mysqli_real_escape_string($conn, $_REQUEST['u_name']) : '';

	    if (empty($t_name) || empty($t_seat) || empty($t_status) || empty($t_category)) {
	        $message = "All Fields are Required!";
	    } else {
	        $check_title_sql = $conn->prepare("SELECT COUNT(*) as count FROM tbl_table WHERE t_name = ?");
	        $check_title_sql->bind_param("s", $t_name);
	        $check_title_sql->execute();
	        $check_title_result = $check_title_sql->get_result();
	        $row = $check_title_result->fetch_assoc();

	        if ($row['count'] > 0) {
	            $message = "Same Table name already exists!";
	        } else {
	            // Check if u_name is provided and the category is "Booked"
	            if (!empty($u_name) && $t_category === 'Booked') {
	                $stmt = $conn->prepare("SELECT t_id FROM tbl_bookedtable WHERE u_name = ?");
	                $stmt->bind_param("s", $u_name);
	                $stmt->execute();
	                $result = $stmt->get_result();

	                if ($result->num_rows > 0) {
	                    $row = $result->fetch_assoc();
	                    $t_id = $row['t_id'];
	                    $stmt = $conn->prepare("INSERT INTO tbl_table (t_name, t_seat, t_status, t_category, u_name, t_id) VALUES (?, ?, ?, ?, ?, ?)");
	                    $stmt->bind_param("sssssi", $t_name, $t_seat, $t_status, $t_category, $u_name, $t_id);
	                    if ($stmt->execute()) {
	                        $message = "Table Added Successfully!";
	                    } else {
	                        $message = "Unable to Add: " . $stmt->error;
	                    }
	                } else {
	                    $message = "Invalid User Name or Table Id not found!";
	                }
	            } else {
	                $u_name_value = ($t_category === 'Regular') ? null : $u_name;
	                $stmt = $conn->prepare("INSERT INTO tbl_table (t_name, t_seat, t_status, t_category, u_name, t_id) VALUES (?, ?, ?, ?, ?, NULL)");
	                $stmt->bind_param("sssss", $t_name, $t_seat, $t_status, $t_category, $u_name_value);
	                if ($stmt->execute()) {
	                    $message = "Table Added Successfully!";
	                } else {
	                    $message = "Unable to Add: " . $stmt->error;
	                }
	            }
	        }
	    }
	}
}
?>


<!--Add Table form area start-->
	<div class="container">
		<div class="header">
			<h1>Add Table</h1>
		</div>
		<?php if(!empty($message)): ?>
			<p style="color: green;"> <?php echo $message; ?> </p>
		<?php endif; ?>
		<div class="form-controller">
			<div class="form">
				<form action="" method="POST">
					<div class="form-data">
						<input type="text" name="t_name" placeholder="Table Name" required>
					</div>
					<div class="form-data">
						<input type="text" name="t_seat" placeholder="Table Seats" required>
					</div>
					<div class="form-data">
						<label for="t_status">Table Status:</label>
						<select  name="t_status" id="t_status">
							<option>Enable</option>
							<option>Disable</option>
						</select>
					</div>
					<div class="form-data">
						<label for="t_category">Table Category:</label>
						<select  name="t_category" id="t_category" onchange="toggleUserNameField()">
							<option>Regular</option>
							<option>Booked</option>
						</select>
					</div>
					<div class="form-data" id="user_name_field">
					    <input type="text" name="u_name" id="u_name_input" placeholder="User Name" required>
					</div>
					<div class="form-btn">
						<button type="submit" name="tbl-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<!--Add Table form area end-->


<!--Add Table form style start-->
	<style type="text/css">
		body {
	            font-family: 'Arial', sans-serif;
	            margin: 0;
	            padding: 0;
	            background-color: #f4f4f4;
	        }

	        .container {
	           max-width: 600px;
	            margin: 50px auto;
/*	            background-color: #fff;*/
	            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
	            border-radius: 5px;
	            overflow: hidden;
	        }

	        .header {
	            text-align: center;
	            margin-bottom: 20px;
	        }

	        .form-controller {
	            display: flex;
	            justify-content: center;
	            align-items: center;
	        }

	        .form {
	            width: 70%;
	        }

	        .form-data {
	            margin-bottom: 20px;
	        }

	        input {
	            width: 100%;
	            padding: 10px;
	            box-sizing: border-box;
	            border: 1px solid #ddd;
	            border-radius: 5px;
	            margin-top: 5px;
	        }

	        .form-btn {
	            text-align: center;
	        }

	        button {
	            background-color: #3498db;
	            color: #fff;
	            border: none;
	            padding: 10px 20px;
	            cursor: pointer;
	            border-radius: 5px;
	            margin-left: 85%;
	            margin-bottom: 35px;
	        }

	        button:hover {
	            background-color: #2980b9;
	        }
    </style>
    <!--Add Table form style end-->

    <!--Script for controlling the User name fields area start-->
	<script>
	    function toggleUserNameField() {
	        var categorySelect = document.getElementById('t_category');
	        var userNameField = document.getElementById('user_name_field');
	        var userNameInput = document.getElementById('u_name_input');

	        // Check if the selected option is "Booked"
	        if (categorySelect.value === 'Booked') {
	            userNameField.style.display = 'block';
	            userNameInput.removeAttribute('disabled');
	        } else {
	            userNameField.style.display = 'none';
	            userNameInput.setAttribute('disabled', 'disabled');
	        }
	    }

	    // Call the function once when the page loads
	    window.onload = toggleUserNameField;

	    // Add an event listener for changes to the category dropdown
	    document.getElementById('t_category').addEventListener('change', toggleUserNameField);
	</script>
<!--Script for controlling the User name fields area end-->



<!--Footer area-->
<?php include('includes/footer.php'); ?>