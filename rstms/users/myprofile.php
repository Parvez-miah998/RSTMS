<?php
session_start();
include 'includes/header.php';
include 'includes/dbconnection.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['user'])) {
    $email = $_SESSION['u_email'];

    $sql = "SELECT * FROM users WHERE u_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $uid = $row['u_id'];
        $uname = $row['u_name'];
        $ucontact = $row['u_contact'];
        $uemail = $row['u_email'];
        $uocc = $row['u_occ'];
        $uaddress = $row['u_paddress'];
        $udob = $row['u_dob'];
        $uimage = $row['u_img'];

        if (isset($_POST['saveChange'])) {
            $uname = $_POST['uname'];
            $ucontact = $_POST['ucontact'];
            $uemail = $_POST['uemail'];
            $uocc = $_POST['uocc'];
            $uaddress = $_POST['uaddress'];
            $userInputDate = $_POST['udob']; 
            $formattedDate = date("Y-m-d", strtotime($userInputDate)); 

            // Check if a file is selected for upload
            if (!empty($_FILES['user_image']['name'])) {
        		$u_image = $_FILES['user_image']['name'];
		        $u_image_temp = $_FILES['user_image']['tmp_name'];
		        $image_folder = '../assets/user_images/' . $u_image;

                // Move uploaded file to destination folder
                if (move_uploaded_file($u_image_temp, $image_folder)) {
		            // Update database with image and converted date
		            $sql = "UPDATE users SET u_name = ?, u_contact = ?, u_email = ?, u_occ = ?, u_paddress = ?, u_dob = ?, u_img = ? WHERE u_email = ?";
		            $stmt = $conn->prepare($sql);
		            $stmt->bind_param("ssssssss", $uname, $ucontact, $uemail, $uocc, $uaddress, $formattedDate, $u_image, $email);
                } else {
                    // Image upload failed
                    $passmsg = '<div class="alert alert-danger col-sm-6 mt-2 ml-5">Image Upload Failed</div>';
                }
            } else {
		        // Update database without image
		        $sql = "UPDATE users SET u_name = ?, u_contact = ?, u_occ = ?, u_paddress = ?, u_dob = ? WHERE u_email = ?";
		        $stmt = $conn->prepare($sql);
		        $stmt->bind_param("ssssss", $uname, $ucontact, $uocc, $uaddress, $formattedDate, $email);
		    }
		    // Execute SQL statement
		    if (isset($stmt)) {
		        if ($stmt->execute()) {
		            $passmsg = '<div class="alert alert-success col-sm-6 mt-2 ml-5">Update Successful.</div>';
		            // $_SESSION['u_image'] = $u_image;
		        } else {
		            $passmsg = '<div class="alert alert-danger col-sm-6 mt-2 ml-5">Unable to Update</div>';
		        }
            }
        }
    }
}
?>
<!--Header area End-->

    <div class="row">
        <div class="col-xl-4" id="profile-form1" style="display:inline-block!important;margin-left: 50px;margin-top: 20px;height: auto;width: 380px;vertical-align: top;">
		    <!-- Profile picture card-->
		    <div class="card mb-4 mb-xl-0">
		        <div class="card-header">Profile Picture</div>
		        <div class="card-body text-center">
		            <!-- Profile picture image-->
		            <img class="img-account-profile rounded-circle mb-2" src="<?php echo isset($uimage) ? '../assets/user_images/' . $uimage : ''; ?>" alt="">
		        </div>
		        <!-- Profile details section -->
        <div class="col-xl-4" id="profile-details" style="color: #0b7ee3; font-family: Arial;">
            <h3><u>User Details</u></h3>
            <div>
                <p><strong><i class="fas fa-user"></i> Name:</strong> <?php echo $uname; ?></p>
                <p><strong><i class="fa-solid fa-phone"></i> Contact:</strong> <?php echo $ucontact; ?></p>
                <p><strong><i class="fa-regular fa-envelope"></i> Email:</strong> <?php echo $uemail; ?></p>
                <p><strong><i class="fa-solid fa-location-dot"></i> Address:</strong> <?php echo $uaddress; ?></p>
                <p><strong><i class="fa-solid fa-suitcase"></i> Occupation:</strong> <?php echo $uocc; ?></p>
                <p><strong><i class="fa-solid fa-cake-candles"></i> Date of Birth:</strong> <?php echo $udob; ?></p>
                
            </div>
            <button class="btn btn-primary" id="editButton" style="margin-left: 25%;margin-top: 15px;"><i class="fas fa-pen"></i> Edit</button>
        </div>
		    </div>
		</div>
        <div class="col-xl-8" id="profile-form2" style="display:inline-block;margin-left: 30px;margin-top: 20px!important; width: 800px; vertical-align: top;">
			<div class="col-xl-8" id="edit-form" style="display: none;">
            <!-- Account details card-->
			<div class="card mb-4">
			    <div class="card-header">Account Details</div>
			    <div class="card-body">
			        <form action="" method="POST" enctype="multipart/form-data">
			            <!-- Form Group (Full Name) -->
			            <div class="mb-3 row">
			                <div class="col-md-4" style="width: 720px;margin-top: 10px;margin-left: 5px; margin-bottom: 8px;">
			                    <label class="small mb-1" for="inputUsername">Full Name</label>
			                    <input class="form-control" id="inputUsername" type="text" name="uname" value="<?php if (isset($uname)) {echo $row['u_name'];} ?>">
			                </div>
			            </div>

			            <!-- Form Group (organization name, location) -->
			            <div class="mb-3 row">
			                <div class="col-md-4" style="width: 720px;margin-left: 5px; margin-bottom: 8px;">
			                    <label class="small mb-1" for="inputPhone">Contact Number</label>
			                    <input class="form-control" id="inputPhone" type="tel" name="ucontact" value="<?php echo isset($row['u_contact']) ? $row['u_contact'] : ''; ?>">
			                </div>
			                <div class="col-md-4" style="width: 720px;margin-left: 5px; margin-bottom: 8px;">
			                    <label class="small mb-1" for="inputEmailAddress">Email Address</label>
			                    <input class="form-control" id="inputEmailAddress" type="email" name="uemail" value="<?php if (isset($uemail)) {echo $row['u_email'];} ?>" readonly>
			                </div>
			            </div>

			            <!-- Form Group (email address, phone number, birthday) -->
			            <div class="mb-3 row">
			                <div class="col-md-6" style="width: 720px;margin-left: 5px; margin-bottom: 8px;">
			                    <label class="small mb-1" for="inputLocation">Address</label>
			                    <input class="form-control" id="inputLocation" type="text" name= "uaddress" value="<?php echo isset($row['u_paddress']) ? $row['u_paddress'] : ''; ?>">
			                </div>
			                <div class="col-md-6" style="width: 720px;margin-left: 5px; margin-bottom: 8px;">
			                    <label class="small mb-1" for="inputOrgName">Occuption</label>
			                    <input class="form-control" id="inputOrgName" type="text" name= "uocc" value="<?php if (isset($uocc)) {echo $row['u_occ'];} ?>">
			                </div>
			                <div class="col-md-4" style="width: 720px; margin-left: 5px; margin-bottom: 8px;">
							    <label class="small mb-1" for="inputBirthday">Birthday</label>
							    <input class="form-control" id="inputBirthday" type="text" name="udob" value="<?php if (isset($udob)) {echo $row['u_dob'];} ?>">
							</div>
			            </div><br>
			            <div class="mb-3 row" style="margin-left: 20px;">
						    <label style="margin-left: 10px;">Choose a file:</label>
						    <input type="file" name="user_image" id="user_image" class="form-control" style="display: none;">
						    <label for="user_image" class="btn btn-primary">
						        Upload Image
						    </label>
						    <?php if (isset($uimage)) { ?>
						        <div>
						            <img src="../assets/user_images/<?php echo $uimage; ?>" alt="User Image" style="width: 150px !important; height: 150px !important;padding: 15px;">
						        </div>
						    <?php } ?>
						</div>
			            <!-- Save changes button-->
			            <button class="btn btn-primary" id="action-btn" type="submit" name="saveChange">Save changes</button>
			        </form>
			    </div>
			    <?php if (isset($passmsg)) {
					echo $passmsg;
				} ?>
			</div>

        </div>
    	</div>
    </div>
</div>

<!-- Include jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // jQuery to toggle visibility of profile details and edit form
    $(document).ready(function () {
        $("#editButton").on("click", function () {
            $("#profile-details").hide();
            $("#edit-form").show();
        });

        $("#cancelButton").on("click", function () {
            $("#edit-form").hide();
            $("#profile-details").show();
        });
    });
</script>


<script>
    $(document).ready(function() {
        $("#inputBirthday").datepicker({
            dateFormat: 'mm/dd/yy', 
            changeMonth: true,
            changeYear: true,
            maxDate: '0', 
            yearRange: 'c-100:c+10' 
        });
    });
</script>

<!--Footer area-->
<?php include ('includes/footer.php');