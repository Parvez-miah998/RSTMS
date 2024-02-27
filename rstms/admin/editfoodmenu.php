<?php 
include ('includes/header.php');
include ('includes/sidebar.php');
if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
?>

<div class="container">
        <div class="header">
            <h2>Edit Food Menu</h2>
        </div>
        <?php

        $message = '';
        if ($_SESSION['admin_email']) {
            if (isset($_POST['menu-btn'])) {
                if (empty($_POST['fm_title']) || empty($_POST['fm_desc']) || empty($_POST['fm_price']) || empty($_POST['fm_disprice']) || empty($_POST['fm_disprice']) || empty($_POST['fm_catname']) || empty($_POST['fm_active']) || empty($_POST['fm_featured'])) {
                    $message = "All Fields are Required!";
                }
                else{
                    $f_id = $_POST['f_id'];
                    $fm_title = $_POST['fm_title'];
                    $fm_desc = $_POST['fm_desc'];
                    $fm_price = $_POST['fm_price'];
                    $fm_disprice = $_POST['fm_disprice'];
                    $fm_vat = $_POST['fm_vat'];
                    $fm_catname = $_POST['fm_catname'];
                    $fm_active = $_POST['fm_active'];
                    $fm_featured = $_POST['fm_featured'];

                        if ($_FILES['fm_image']['size'] > 0) {
                            $fm_image = $_FILES['fm_image']['name'];
                            $fm_image_temp = $_FILES['fm_image']['tmp_name'];
                            move_uploaded_file($fm_image_temp, '../assets/image/'.$fm_image);
                            $sql = "UPDATE tbl_food SET f_title = ?, f_desc = ?, f_price = ?, f_disctprice = ?, f_vat = ?, c_name = ?, f_active = ?, f_featured = ?, f_image = ? WHERE f_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("sssssssssi", $fm_title, $fm_desc, $fm_price, $fm_disprice, $fm_vat, $fm_catname, $fm_active, $fm_featured, $fm_image, $f_id);
                        }
                        else{
                            $sql = "UPDATE tbl_food SET f_title = ?, f_desc = ?, f_price = ?, f_disctprice = ?, f_vat = ?, c_name = ?, f_active = ?, f_featured = ? WHERE f_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssssssssi", $fm_title, $fm_desc, $fm_price, $fm_disprice, $fm_vat, $fm_catname, $fm_active, $fm_featured, $f_id);
                        }
                        if ($stmt->execute()) {
                            $message = "Food Menu Update Successful!";
                        } else {
                            $message = "Unable to Update!";
                        }
                        $stmt->close();
                    
                }
            }
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT * FROM tbl_food WHERE f_id = ?";
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
            $cat_sql = $conn->query("SELECT * FROM tbl_catagory");
            $categories = [];
            while ($row_cat = $cat_sql->fetch_assoc()) {
                $categories[] = $row_cat['c_name'];
            }
        }
        ?>
        <div class="form-controller">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="f_id" value="<?php echo isset($row['f_id']) ? $row['f_id'] : ''; ?>">
                <div class="form-data">
                    <input type="text" name="fm_title" placeholder="Enter Menu Title" value="<?php echo isset($row['f_title']) ? $row['f_title'] : ''; ?>" required>
                </div>
                <div class="form-data">
                    <input type="text" name="fm_desc" placeholder="Enter Food Description" value="<?php echo isset($row['f_desc']) ? $row['f_desc'] : ''; ?>" required>
                </div>
                <div class="form-data">
                    <input type="text" name="fm_price" placeholder="Enter Food Price. e.g. &#36; 12" value="<?php echo isset($row['f_price']) ? $row['f_price'] : ''; ?>" required>
                </div>
                <div class="form-data">
                    <input type="text" name="fm_disprice" placeholder="Enter Food Discount Price. e.g. &#36; 10" value="<?php echo isset($row['f_disctprice']) ? $row['f_disctprice'] : ''; ?>" required>
                </div>
                <div class="form-data">
                    <input type="text" name="fm_vat" placeholder="Enter Food VAT. e.g. 10 &#37;" value="<?php echo isset($row['f_vat']) ? $row['f_vat'] : ''; ?>" required>
                </div>
                <div class="form-data">
                    <label for="fm_catname">Select Category:</label>
                    <select name="fm_catname" id="fm_catname" required>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category; ?>" <?php echo isset($row['c_name']) && $row['c_name'] == $category ? 'selected' : ''; ?>><?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" name="fm_catname" placeholder="Category Name" value="<?php echo isset($row['c_name']) ? $row['c_name'] : ''; ?>" required> -->
                </div>
                <div class="form-data">
                    <label for="fm_active">Food Status: </label>
                    <select name="fm_active" id="fm_active" required>
                        <option <?php echo ($row['f_active'] == 'Active') ? 'selected' : '';?>>Active</option>
                        <option <?php echo ($row['f_active'] == 'Unactive') ? 'selected' : '';?>>Unactive</option>
                    </select>
                    <!-- <input type="text" name="fm_active" placeholder="Menu Status" value="<?php echo isset($row['f_active']) ? $row['f_active'] : ''; ?>" required> -->
                </div>
                <div class="form-data">
                    <label for="fm_featured">Food Featured: </label>
                    <select name="fm_featured" id="fm_featured" required>
                        <option <?php echo ($row['f_featured'] == 'Display') ? 'selected' : '';?>>Display</option>
                        <option <?php echo ($row['f_featured'] == 'Non Display') ? 'selected' : '';?>>Non Display</option>
                    </select>
                    <!-- <input type="text" name="fm_featured" placeholder="Menu Featured" value="<?php echo isset($row['f_featured']) ? $row['f_featured'] : ''; ?>" required> -->
                </div><br>
                <div class="form-image">
                    <label>Choose a file:</label>
                    <input type="file" name="fm_image" id="menu-image" style="display: none;" required>
                    <label for="menu-image" class="btn btn-primary">Upload Image</label><br>
                    <img class="form-image" src="<?php echo isset($row['f_image']) ? '../assets/image/'.$row['f_image'] : ''; ?>" alt="Food-Image">
                </div>
                <?php if (!empty($message)) : ?>
                    <p style="color: green;"><?php echo $message;?></p>
                <?php endif; ?>
                <div class="form-btn">
                    <button type="submit" id="menu-btn" name="menu-btn" onclick="console.log('Button clicked')">Update</button>
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
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .header {
            background-color: #cce6dc;
            color: #090a09;
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

        .form-data,
        .form-image,
        .form-btn {
            margin-bottom: 15px;
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
            border-radius: 4px;
            object-fit: cover;
            margin: 15px auto;
        }

        button {
            background-color: #07e883;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 88.2%;
        }

        button:hover {
            background-color: #09b065;
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
?>