<?php
    include('includes/dbconnection.php');
    include('includes/header.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
?>


	<div class="container">
		<div class="header">
			<h2>Food Menu</h2>
		</div>
        <?php if(!empty($message)) : ?>
            <p style="color: green;"> <?php echo $message; ?> </p>
        <?php endif; ?>
		<div class="table">
			<table class="ctable">
				<thead>
					<tr>
						<th>SL</th>
						<th>Title</th>
						<th>Description</th>
						<th>Price</th>
						<th>Discount Price</th>
                        <th>VAT</th>
						<th>Image</th>
						<th>Category Name</th>
						<th>Status</th>
						<th>Featured</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                    <?php 
                    $sql = "SELECT * FROM tbl_food";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        $sl = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo "<td>{$sl}</td>";
                            echo '<td>'.$row["f_title"].'</td>';
                            echo '<td>'.$row["f_desc"].'</td>';
                            echo '<td>&#36;'.$row["f_price"].'</td>';
                            echo '<td>&#36;'.$row["f_disctprice"].'</td>';
                            echo '<td>'.$row["f_vat"].'&#37;</td>';
                            echo "<td><img class='form-image' src='../assets/image/{$row["f_image"]}' alt='food-image'></td>";
                            echo '<td>'.$row['c_name'].'</td>';
                            echo '<td>'.$row['f_active'].'</td>';
                            echo '<td>'.$row['f_featured'].'</td>';
                            echo '<td>';
                            echo "<form action='editfoodmenu.php' method='GET' style='display:inline-block;'>";
                            echo '<input type="hidden" name="id" value='.$row["f_id"].'>';
                            echo "<button type='submit' class='icon-edit'>";
                            echo "<i class='fa-solid fa-pen-to-square'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo "<form action='' method='POST' style='display: inline-block;'>";
                            echo '<input type="hidden" name="f_id" value='.$row["f_id"].'>';
                            echo "<button name='delete_btn' class='del-btn'>";
                            echo "<i class='fa-solid fa-trash'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo '</td>';
                            echo '</tr>';
                            $sl++;
                        }
                    }
                    ?>

				</tbody>
			</table>
		</div>
	</div>

	<div class="icon">
		<a href="addfoodmenu.php"><i class="fa-solid fa-square-plus"></i></a>
	</div>
    <?php
    if (isset($_POST['delete_btn']) && isset($_POST['f_id'])) {
        $f_id = $_POST['f_id'];
        $sql = "DELETE FROM tbl_food WHERE f_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $f_id);
        if ($stmt->execute()) {
            echo "<script>window.location.reload();</script>";
        }
        else{
            $message = "Unable to Delete!";
        }
    }
    ?>

	<!--Style for category page start-->
	<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1250px;
            margin: 50px auto;
/*            background-color: #fff;*/
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .header {
            background-color: #d7f7f7;
            color: #070808;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .ctable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .ctable th, .ctable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .ctable th {
            background-color: #d7f7f7;
            color: #070808;
        }

        .ctable tbody tr:hover {
            background-color: #f5f5f5;
        }

        .ctable button {
        	display: inline-block;
            background-color: #3498db;
            color: #fff;
            border: none;
            margin-top: 2px;
            padding: 5px 9px;
            cursor: pointer;
            border-radius: 5px;
        }

        .ctable button:hover {
            background-color: #2980b9;
        }
        .add-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .add-link:hover {
            background-color: #2980b9;
        }
        .form-image{
        	height: 30px;
        	width: 30px;
        	object-fit: cover;
        }
        .icon-edit{
        	margin: 5px;
		  	display: inline-block;
		  	background-color: #178ce6;
		    border: none;
		    border-radius: 5px;
		    text-decoration: none;
		    color: #fff;
		    display: inline-block;
		    padding: 5px 9px;
        }
        .del-btn{
        	background-color: #fa1b32 !important;
        	border-radius: 5px;
        	display: inline-block;
        }
        .icon {
            position: fixed;
            bottom: 60px;
            right: 40px;
            width: 50px;
            height: 50px;
/*            border-radius: 50%;*/
/*            background-color: #3498db;*/
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            cursor: pointer;
        }

        .icon a {
            text-decoration: none;
        }
        .icon a .fa-square-plus{
        	height: 40px!important;
        	color: #61eded;
        }
        .icon a .fa-square-plus:hover{
            color: #06d6be;
        }
        .search-btn{
        	margin-bottom: 20px;
        	margin-left: 82%;

        }
    </style>
	<!--Style for category page end-->

	<!-- Script for icon -->
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" integrity="sha512-..."></script>
	<script>
	    document.addEventListener('DOMContentLoaded', function() {
	        // Initialize Font Awesome
	        FontAwesome.dom.i2svg();
	    });
	</script>

<?php
    include('includes/footer.php');
?>