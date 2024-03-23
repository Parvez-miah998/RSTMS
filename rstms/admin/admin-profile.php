<?php
    include('includes/header.php');
    include('includes/sidebar.php');


        $message = '';
    if (isset($_SESSION['admin_email'])) {
        $a_email = $_SESSION['admin_email'];

        $sql_admin = $conn->prepare("SELECT * FROM admin WHERE a_email = ? ");
        $sql_admin -> bind_param("s", $a_email);
        $sql_admin -> execute();
        $result_admin = $sql_admin->get_result();

        if ($result_admin->num_rows === 1) {
            $row = $result_admin->fetch_assoc();
            $aid = $row['a_id'];
            $aname = $row['a_name'];
            $acontact = $row['a_contact'];
            $aemail = $row['a_email'];
            $acaddress = $row['a_caddress'];
            $apaddress = $row['a_paddress'];
            $anid = $row['a_nid'];
            $adob = $row['a_dob'];
            $aimg = $row['a_img'];

            if (isset($_POST['admin_btn'])) {
                $aname = $_POST['a_name'];
                $acontact = $_POST['a_contact'];
                $aemail = $_POST['a_email'];
                $acaddress = $_POST['a_caddress'];
                $apaddress = $_POST['a_paddress'];
                $anid = $_POST['a_nid'];
                $adminBrDate = $_POST['a_dob'];
                $formattedDate = date('Y-m-d', strtotime($adminBrDate));

                if (!empty($_FILES['a_image']['name'])) {
                    $a_image = $_FILES['a_image']['name'];
                    $a_image_temp = $_FILES['a_image']['tmp_name'];
                    $image_folder = '../assets/admin_image/'. $a_image;

                    if (move_uploaded_file($a_image_temp, $image_folder)) {
                        // Update the image path in the database
                        $sql_update = $conn->prepare("UPDATE admin SET a_name = ?, a_email = ?, a_contact = ?, a_caddress = ?, a_paddress = ?, a_dob = ?, a_nid = ?, a_img = ? WHERE a_email = ?");
                        $sql_update->bind_param("sssssssss", $aname, $aemail, $acontact, $acaddress, $apaddress, $formattedDate, $anid, $image_folder, $a_email);
                    } else {
                        $message = '<div class="alert alert-danger col-sm-6 mt-2 ml-5">Image Upload Failed</div>';
                    }
                } else {
                    // If no new image is uploaded, do not update the image column in the database
                    $sql_update = $conn->prepare("UPDATE admin SET a_name = ?, a_email = ?, a_contact = ?, a_caddress = ?, a_paddress = ?, a_dob = ?, a_nid = ? WHERE a_email = ?");
                    $sql_update->bind_param("ssssssss", $aname, $aemail, $acontact, $acaddress, $apaddress, $formattedDate, $anid, $a_email);
                }

                if (isset($sql_update)) {
                    if ($sql_update->execute()) {
                        $message = '<div class="alert alert-success col-sm-6 mt-2 ml-5">Update Successful.</div>';
                    } else {
                        $message = '<div class="alert alert-danger col-sm-6 mt-2 ml-5">Unable to Update</div>';
                    }
                }
            }

        }
    }
?>

<div class="container">
	<div class="header">
		<h3>Admin Profile</h3>
	</div>
    <?php if (isset($message)) {
            echo $message;
        } 
    ?>
    <div class="form-controller">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-data">
                <label>Enter Your Name</label>
                <input type="text" name="a_name" id="a_name" value="<?php if (isset($aname)) {echo $row['a_name'] ;} ?>" required>
            </div>
            <div class="form-data">
                <label>Enter Your Contact</label>
                <input type="text" name="a_contact" id="a_contact" value="<?php if(isset($acontact)) {echo $row['a_contact'];} ?>" required>
            </div>
            <div class="form-data">
                <label>Enter Your Email</label>
                <input type="text" name="a_email" id="a_email" value="<?php if(isset($a_email)) {echo $row['a_email'];} ?>" readonly>
            </div>
            <div class="form-data">
                <label>Your Current Address</label>
                <input type="text" name="a_caddress" id="a_caddress" value="<?php if(isset($acaddress)) {echo $row['a_caddress'];} ?>" required>
            </div>
            <div class="form-data">
                <label>Your Permanent Address</label>
                <input type="text" name="a_paddress" id="a_paddress" value="<?php if(isset($apaddress)) {echo $row['a_paddress'];} ?>" required>
            </div>
            <div class="form-data">
                <label>Your NID or Passport No</label>
                <input type="text" name="a_nid" id="a_nid" value="<?php if(isset($anid)) {echo $row['a_nid'];} ?>" readonly>
            </div>
            <div class="form-data">
                <label>Date of Birth</label>
                    <input type="text" name="a_dob" id="a_dob" class="custom-date-picker" value="<?php if(isset($adob)) {echo $row['a_dob'];} ?>" readonly>
            </div>
            <div class="form-image">
                <label>Choose Your File:</label>
                <input type="file" name="a_image" id="a_image" class="a_image">
                <label for="a_image" class="a_image">Upload Iamge</label>
                <div class="admin_image">
                    <img src="<?php echo $aimg; ?>" alt="admin_image">
                </div>
                
            </div>
            <div class="form-btn">
                <button type="submit" name="admin_btn" id="admin_btn">Save</button>
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

        .form-data input[type="text"] {
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