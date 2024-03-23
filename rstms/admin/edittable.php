<?php
    include('includes/header.php');
    include('includes/sidebar.php');
?>
	
	<div class="container">
		<div class="header">
			<h1>Edit Table</h1>
		</div>
		<div class="form-controller">
			<div class="form">
				<?php
				if (isset($_SESSION['admin_email'])) {
					if (isset($_REQUEST['tbl-btn'])) {
						if(empty($_POST['t_name']) || empty($_POST['t_seat']) || empty($_POST['t_status']) || empty($_POST['t_category'])){
							$message = "All Fields are Required!";
						}
						else{
							$ta_id = $_POST['ta_id'];
							$t_name = $_POST['t_name'];
							$t_seat = $_POST['t_seat'];
							$t_status = $_POST['t_status'];
							$t_category = $_POST['t_category'];
							$u_name = isset($_POST['u_name']) ? mysqli_real_escape_string($conn, $_POST['u_name']) : '';

							if (!empty($u_name) && $t_category === 'Booked') {
								$sql = $conn->prepare("SELECT t_id FROM tbl_bookedtable WHERE u_name =?");
								$sql -> bind_param("s", $u_name);
								$sql->execute();
								$result = $sql->get_result();
								if ($result && $result->num_rows>0) {
									$row = $result->fetch_assoc();
									$t_id = $row['t_id'];
									$sql = $conn->prepare("UPDATE tbl_table SET t_name = ?, t_seat = ?, t_status = ?, t_category = ?, u_name = ?, t_id =? WHERE ta_id = ?");
									if (!$sql) {
										$message = "Prepare is faield!";
									}
									else{
										$sql->bind_param("sssssii", $t_name, $t_seat, $t_status, $t_category, $u_name, $t_id, $ta_id);
										if ($sql->execute()) {
											$message = "Table Update Successful!";
										}
										else{
											$message = "Unable to Update the Table!". $sql->error;
										}
										$sql->close();
									}
								}
								else{
									$message = "Invalid User Name!";
								}
								
							}
							else{
								$u_name_value = ($t_category === 'Regular') ? null : $u_name ;
								$t_id_value = ($t_category === 'Regular') ? null : $t_id;
								$sql = $conn->prepare("UPDATE tbl_table SET t_name = ?, t_seat = ?, t_status = ?, t_category = ?, u_name = ?, t_id = ? WHERE ta_id = ?");
								$sql->bind_param("ssssisi", $t_name, $t_seat, $t_status, $t_category, $u_name_value, $t_id_value, $ta_id);
								if ($sql->execute()) {
									$message = "Table Update Successful!";
								}
								else{
									$message = "Unable to Update the Table!". $sql->error;
								}
								$sql->close();
							}
							
						}
					}
					if (isset($_GET['id'])) {
						$id = $_GET['id'];
						$sql = $conn->prepare("SELECT * FROM tbl_table WHERE ta_id = ?");
						$sql -> bind_param("i", $id);
						$sql->execute();
						$result = $sql->get_result();
						if ($result && $result->num_rows > 0) {
							$row = $result->fetch_assoc();
						}
						else{
							$message = "No Data Found!";
						}
					}
				}
				?>
				<form action="" method="POST">
					<input type="hidden" name="ta_id" id="ta_id" value="<?php echo isset($row['ta_id']) ? $row['ta_id'] : ''; ?>">
					<div class="form-data">
						<input type="text" name="t_name" placeholder="" value="<?php echo isset($row['t_name']) ? $row['t_name'] : ''; ?>" required>
					</div>
					<div class="form-data">
						<input type="text" name="t_seat" placeholder="Table Seats" value="<?php echo isset($row['t_seat']) ? $row['t_seat'] : ''; ?>" required>
					</div>
					<div class="form-data">
						<label for="t_status">Table Status:</label>
						<select  name="t_status" id="t_status">
							<option <?php echo ($row['t_status'] == 'Enable') ? 'selected' : '' ; ?>>Enable</option>
							<option <?php echo ($row['t_status'] == 'Disable') ? 'selected' : ''; ?>>Disable</option>
						</select>
					</div>
					<div class="form-data">
						<label for="t_category">Table Category:</label>
						<select  name="t_category" id="t_category" onchange="toggleUserNameField()">
							<option <?php echo ($row['t_category'] == 'Regular') ? 'selected' : '';?>>Regular</option>
							<option <?php echo ($row['t_category'] == 'Booked') ? 'selected' : '';?>>Booked</option>
						</select>
					</div>
					<div class="form-data" id="user_name_field">
					    <input type="text" name="u_name" id="u_name_input" placeholder="User Name" value="<?php echo isset($row['u_name']) ? $row['u_name'] : '' ;?>" required>
					</div>
					<?php if (!empty($message)) : ?>
	                    <p style="color: green;"> <?php echo $message;?></p>
	                <?php endif; ?>
					<div class="form-btn">
						<button type="submit" name="tbl-btn">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>


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
	            background-color: #07e5ed;
	            color: #fff;
	            border: none;
	            padding: 10px 20px;
	            cursor: pointer;
	            border-radius: 5px;
	            margin-left: 85%;
	            margin-bottom: 35px;
	        }

	        button:hover {
	            background-color: #04c3c9;
	        }
    </style>

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

<?php
	include('includes/footer.php');
?>