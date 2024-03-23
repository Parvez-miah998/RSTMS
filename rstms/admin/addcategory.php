<?Php
include('includes/header.php');
include('includes/sidebar.php');

    if (isset($_SESSION['admin_email'])) {
        if (isset($_REQUEST['category-btn'])) {

            $message = '';

            if (empty($_REQUEST['ct_name']) || empty($_REQUEST['ct_title'])|| empty($_REQUEST['ct_active'])|| empty($_REQUEST['ct_featured'])) {
                $message = "All Fields are Required!";
            }
            else{
                $ct_name = $_REQUEST['ct_name'];
                $ct_title = $_REQUEST['ct_title'];
                $ct_active = $_REQUEST['ct_active'];
                $ct_featured = $_REQUEST['ct_featured'];

                $check_title_sql = $conn->prepare("SELECT COUNT(*) as count FROM tbl_catagory WHERE c_name = ?");
                $check_title_sql->bind_param("s", $ct_name);
                $check_title_sql->execute();
                $check_title_result = $check_title_sql->get_result();
                $row = $check_title_result->fetch_assoc();

                if ($row['count']>0) {
                    $message = "Category with the same name already exists!";
                }
                else{
                    $ct_image = $_FILES['ct_image']['name'];
                    $ct_image_temp = $_FILES['ct_image']['tmp_name'];

                    $image = $ct_image;
                    move_uploaded_file($ct_image_temp, '../assets/image/'.$image);

                    $stmt = $conn->prepare("INSERT INTO tbl_catagory (c_name, c_title, c_image, c_active, c_featured) VALUES (?, ?, ?, ?, ?)");
                    $stmt -> bind_param("sssss", $ct_name, $ct_title, $image, $ct_active, $ct_featured);

                    if ($stmt->execute()) {
                        $message = "Category Added Successfilly!";
                    }
                    else{
                        $message = "Unable to Add the Category!". $stmt->error ;
                    }
                    $stmt->close();
                }
                
            }
        }
    }
?>

	<div class="container">
		<div class="header">
			<h2>Add Category</h2>
		</div>
		<div class="form-controller">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="form-data">
					<input type="text" name="ct_name" placeholder="Enter Category Name" required>
				</div>
				<div class="form-data">
					<input type="text" name="ct_title" placeholder="Enter Category Title" required>
				</div>
				<div class="form-data">
                    <label for="ct_active">Category Status: </label>
                    <select name="ct_active" id="ct_active" required>
                        <option>Active</option>
                        <option>Unactive</option>
                    </select>
					<!-- <input type="text" name="ct_active" placeholder="Category Status" required> -->
				</div>
				<div class="form-data">
                    <label for="ct_featured">Category Featured: </label>
                    <select name="ct_featured" id="ct_featured" required>
                        <option>Display</option>
                        <option>Non Display</option>
                    </select>
					<!-- <input type="text" name="ct_featured" placeholder="Category Featured" required> -->
				</div>
				<div class="form-image">
					<label>Choose a file:</label>
					<input type="file" name="ct_image" id="category-image" style="display: none;" required>
					<label for="category-image" class="btn btn-primary">Upload Image</label>
				</div>
                <?php if (!empty($message)) : ?>
                    <p style="color: green;"><?php echo $message;?></p>
                <?php endif; ?>
				<div class="form-btn">
					<button type="submit" name="category-btn">Submit</button>
				</div>
			</form>
		</div>
	</div>
    </div>
    </div>

	<!--Style for add category start-->
	<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            top: 0px;
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
            flex: 1;
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

        .btn-primary {
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