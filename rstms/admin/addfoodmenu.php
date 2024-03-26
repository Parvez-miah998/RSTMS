<?php
    include('includes/header.php');
    include('includes/sidebar.php');

    if (isset($_SESSION['admin_email'])) {
        if (isset($_REQUEST['menu-btn'])) {
            $message = '';

            $fm_title = mysqli_real_escape_string($conn, $_REQUEST['fm_title']);
            $fm_desc = mysqli_real_escape_string($conn, $_REQUEST['fm_desc']);
            $fm_price = mysqli_real_escape_string($conn, $_REQUEST['fm_price']);
            $fm_disprice = mysqli_real_escape_string($conn, $_REQUEST['fm_disprice']);
            $fm_vat = mysqli_real_escape_string($conn, $_REQUEST['fm_vat']);
            $fm_catname = mysqli_real_escape_string($conn, $_REQUEST['fm_catname']);
            $fm_featured = mysqli_real_escape_string($conn, $_REQUEST['fm_featured']);

            if (empty($_REQUEST['fm_title']) || empty($_REQUEST['fm_desc']) || empty($_REQUEST['fm_price']) || empty($_REQUEST['fm_disprice']) || empty($_REQUEST['fm_vat']) || empty($_REQUEST['fm_catname']) || empty($_REQUEST['fm_featured'])) {
                $message = "All Fields are Required!";
            }
            else{
                $check_title_sql = $conn->prepare("SELECT COUNT(*) as count FROM tbl_food WHERE f_title = ?");
                $check_title_sql->bind_param("s", $fm_title);
                $check_title_sql->execute();
                $check_title_result = $check_title_sql->get_result();
                $row = $check_title_result->fetch_assoc();

                if ($row['count']>0) {
                    $message = "Food Menu with the same title already exists!";
                }
                else{
                    $fm_image = $_FILES['fm_image']['name'];
                    $fm_image_temp = $_FILES['fm_image']['tmp_name'];
                    $image = $fm_image;
                    move_uploaded_file($fm_image_temp, '../assets/image/'.$image);

                    $cat_sql = $conn->prepare("SELECT c_id FROM tbl_catagory WHERE c_name = ?");
                    $cat_sql->bind_param("s", $fm_catname);
                    $cat_sql->execute();
                    $cat_result = $cat_sql->get_result();

                    if($cat_result->num_rows>0){
                        $row = $cat_result->fetch_assoc();
                        $c_id = $row['c_id'];
                        $stmt = $conn->prepare("INSERT INTO tbl_food (f_title, f_desc, f_price, f_disctprice, f_vat, c_name, c_id, f_featured, f_image) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssssss", $fm_title, $fm_desc, $fm_price, $fm_disprice, $fm_vat, $fm_catname, $c_id, $fm_featured, $fm_image);
                        if ($stmt->execute()) {
                            $message = "Food Menu Added Successfilly!";
                        }
                        else{
                            $message = "Unable to Add Food Menu" . $stmt->error;
                        }
                    }
                    else{
                        $message = "Category Id Not Found!";
                    }
                }
            }
        }
        $cat_sql = $conn->query("SELECT c_name FROM tbl_catagory");
        $categories = [];
        while($row = $cat_sql->fetch_assoc()){
            $categories [] = $row['c_name'];
        }
    }
?>

<div class="container">
		<div class="header">
			<h2>Add Food Menu</h2>
		</div>
		<div class="form-controller">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="form-data">
					<input type="text" name="fm_title" placeholder="Enter Menu Title" required>
				</div>
				<div class="form-data">
					<input type="text" name="fm_desc" placeholder="Enter Food Description" required>
				</div>
				<div class="form-data">
					<input type="text" name="fm_price" placeholder="Enter Food Price. e.g. &#36; 12" required>
				</div>
				<div class="form-data">
					<input type="text" name="fm_disprice" placeholder="Enter Food Discount Price. e.g. &#36; 10" required>
				</div>
                <div class="form-data">
                    <input type="text" name="fm_vat" placeholder="Enter Food VAT. e.g. 10 &#37;" required>
                </div>
				<div class="form-data">
                    <label for="fm_catname">Select Category:</label>
                    <select name="fm_catname" id="fm_catname" required>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category; ?>"> <?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
				</div>
				<div class="form-data">
                    <label for="fm_featured">Food Featured: </label>
                    <select name="fm_featured" id="fm_featured" required>
                        <option>Display</option>
                        <option>Non Display</option>
                    </select>
					<!-- <input type="text" name="fm_featured" placeholder="Menu Featured" required> -->
				</div>
				<div class="form-image">
					<label>Choose a file:</label>
					<input type="file" name="fm_image" id="menu-image" style="display: none;" required>
					<label for="menu-image" class="btn btn-primary">Upload Image</label>
				</div>
                <?php if (!empty($message)) : ?>
                    <p style="color: green;"><?php echo $message;?></p>
                <?php endif; ?>
				<div class="form-btn">
					<button type="submit" name="menu-btn">Submit</button>
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
            background-color: #3498db;
            color: #fff;
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
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 88.2%;
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
?>