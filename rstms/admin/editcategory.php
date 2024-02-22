<?php
    include('includes/header.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
?>

	<div class="container">
		<div class="header">
			<h2>Edit Category</h2>
		</div>
    <?php
        $message = '';

            
        if ($_SESSION['admin_email']) {
            if (isset($_POST['category-btn'])) {
                if (empty($_POST['ct_name']) || empty($_POST['ct_title']) || empty($_POST['ct_active']) || empty($_POST['ct_featured'])) {
                    $message = "All Fields are Required!";
                } else {
                    $c_id = $_POST['c_id'];
                    $ct_name = $_POST['ct_name'];
                    $ct_title = $_POST['ct_title'];
                    $ct_active = $_POST['ct_active'];
                    $ct_featured = $_POST['ct_featured'];

                        if ($_FILES['ct_image']['size'] > 0) {
                            $ct_image = $_FILES['ct_image']['name'];
                            $ct_image_temp = $_FILES['ct_image']['tmp_name'];
                            move_uploaded_file($ct_image_temp, '../assets/image/'.$ct_image);

                            $sql = "UPDATE tbl_catagory SET c_name = ?, c_title = ?, c_active = ?, c_featured = ?, c_image = ? WHERE c_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("sssssi", $ct_name, $ct_title, $ct_active, $ct_featured, $ct_image, $c_id);
                        }
                        else{
                            $sql = "UPDATE tbl_catagory SET c_name = ?, c_title = ?, c_active = ?, c_featured = ? WHERE c_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssssi", $ct_name, $ct_title, $ct_active, $ct_featured, $c_id);
                        }
                        
                        if ($stmt->execute()) {
                            $message = "Category Update Successful!";
                        } else {
                            $message = "Unable to Update!";
                        }

                        $stmt->close();
                    
                }
            }
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT * FROM tbl_catagory WHERE c_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                } else {
                    $message = "No Data Found!";
                }

                $stmt->close();
            }
        }
    ?>
		<div class="form-controller">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="c_id" value="<?php echo isset($row['c_id']) ? $row['c_id'] : ''; ?>">
                <div class="form-data">
                    <label>Category Name:</label>
                    <input type="text" name="ct_name" placeholder="" value="<?php echo isset($row['c_name']) ? $row['c_name'] : ''; ?>" required>
                </div><br>
                <div class="form-data">
                    <label>Category Title:</label>
                    <input type="text" name="ct_title" placeholder="" value="<?php echo isset($row['c_title']) ? $row['c_title'] : ''; ?>" required>
                </div><br>
                <div class="form-data">
                    <label for="ct_active">Category Status: </label>
                    <select name="ct_active" id="ct_active" required>
                        <option <?php echo ($row['c_active'] == 'Active') ? 'selected' : '';?>>Active</option>
                        <option <?php echo ($row['c_active'] == 'Unactive') ? 'selected' : '';?>>Unactive</option>
                    </select>
                    <!-- <input type="text" name="ct_active" placeholder="" value="<?php echo isset($row['c_active']) ? $row['c_active'] : ''; ?>" required> -->
                </div><br><br>
                <div class="form-data">
                    <label for="ct_featured">Category Featured: </label>
                    <select name="ct_featured" id="ct_featured" required>
                        <option <?php echo ($row['c_featured'] == 'Display') ? 'selected' : '';?>>Display</option>
                        <option <?php echo ($row['c_featured'] == 'Non Display') ? 'selected' : '';?>>Non Display</option>
                    </select>
                    <!-- <input type="text" name="ct_featured" placeholder="" value="<?php echo isset($row['c_featured']) ? $row['c_featured'] : ''; ?>" required> -->
                </div><br>
                <div class="form-image">
                    <label>Choose a file:</label>
                    <input type="file" name="ct_image" id="category-image" style="display: none;">
                    <label for="category-image" class="btn btn-primary">Upload Image</label><br>
                    <img class="form-image" src="<?php echo isset($row['c_image']) ? '../assets/image/'.$row["c_image"] : ''; ?>" alt="categoryImage" required>
                </div>
                <?php if (!empty($message)) : ?>
                    <p style="color: green;"> <?php echo $message;?></p>
                <?php endif; ?>
                <div class="form-btn">
                    <button type="submit" id="category-btn" name="category-btn">Update</button>
                </div>
            </form>
        </div>
    </div>


	<!--Style for add category start-->
	<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: transparent;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .header {
            background-color: #a2f5f5;
            color: #0a0a0a;
            padding: 20px;
            text-align: center;
        }

        .form-controller {
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-image{
            margin: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        img {
            max-width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 90%;
        }

        button:hover {
            background-color: #2980b9;
        }
        .btn-primary{
        	padding: 5px;
        	background-color: #62f5e6;
        	border-radius: 6px;
        	box-shadow: 0 2px 2px 0 #cccc;
        	cursor: pointer;
        }
    </style>
	<!--Style for add category end-->

<?php
    include('includes/footer.php');
    // if (isset($_REQUEST['requpdate'])) {
            //     if (($_REQUEST['ct_name'] == "") || ($_REQUEST['ct_title'] == "") || ($_REQUEST['ct_active'] == "") || ($_REQUEST['ct_featured'] == "")) {
            //         $message = "All Fields are Required!";
            //     }
            //     else{
            //         $c_id = $_REQUEST['c_id'];
            //         $ct_name = $_REQUEST['ct_name'];
            //         $ct_title = $_REQUEST['ct_title'];
            //         $ct_active = $_REQUEST['ct_active'];
            //         $ct_featured = $_REQUEST['ct_featured'];
            //         $ct_image = '../assets/image'.$_FILES['ct_image']['name'];

            //         $sql = "UPDATE tbl_catagory SET c_id=?, c_name=?, c_title=?, c_active=?, c_featured=? WHERE c_id =?";
            //         $stmt = $conn->prepare($sql);
            //         $stmt->bind_param("ssssi", $ct_name, $ct_title, $ct_active, $ct_featured, $c_id);

            //         if ($conn->query($sql) == TRUE) {
            //             $message = "Update Successfull!";
            //         }
            //         else{
            //             $message = "Unable to Add the Category";
            //         }
            //         $stmt->close();
            //     }
            // }
?>