<?php
    include('includes/header.php');
    include('includes/sidebar.php');
?>


	<div class="container">
		<div class="header">
		<h1>Admin Details</h1>
		</div>
        <?php if (!empty($message)) : ?>
            <p style="color: green;"> <?php echo $message;?></p>
        <?php endif; ?>
		<div class="table">
			<table class="ctable">
				<thead>
					<tr>
						<th>SL</th>
						<th>Name</th>
						<th>Contact</th>
						<th>Email</th>
						<th>Current Address</th>
						<th>Permanent Address</th>
						<th>Date of Birth</th>
						<th>NID or Passport</th>
						<th>Image</th>
						<th>Super Admin</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                    <?php
                    $sql = "SELECT * FROM admin";
                    $stmt = $conn->prepare($sql);
                    $stmt -> execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        $sl = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$sl}</td>";    
                            echo "<td>".$row['a_name']."</td>";
                            echo "<td>".$row['a_contact']."</td>";
                            echo "<td>".$row['a_email']."</td>";
                            echo "<td>".$row['a_caddress']."</td>";
                            echo "<td>".$row['a_paddress']."</td>";
                            echo "<td>".$row['a_dob']."</td>";
                            echo "<td>".$row['a_nid']."</td>";
                            echo "<td>
                                    <img class='form-image' src='". $row['a_img']."' alt='adminImage'>
                                  </td>";
                            echo "<td>".$row['s_admin']."</td>";
                            echo "<td>";
                            echo "<form action='forgotpass.php' method='GET' style='display:inline-block;'>";
                            echo '<input type="hidden" name="id" value='.$row["a_id"].'>';
                            echo "<button type='submit' class='icon-edit'>";
                            echo "<i class='fa-solid fa-key fas_key'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo "<form action='' method='POST' style='display:inline-block;'>";
                            echo '<input type="hidden" name="a_id" value='.$row['a_id'].'>';
                            echo "<button class='del-btn' name='delete_btn'>";
                            echo "<i class='fa-solid fa-trash-can'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                            $sl++;
                        }
                    }
                    else{
                        echo "<p style='text-align: center;color: red;'>No Catagory Found!</p>";
                    }
                    ?>
					
				</tbody>
			</table>
		</div>
	</div>
	<div class="icon">
		<a href="admin-add.php"><i class="fa-solid fa-square-plus"></i></a>
	</div>
    <?php
    if (isset($_POST['delete_btn']) && isset($_POST['a_id'])) {
        $a_id = $_POST['a_id'];
        $sql = "DELETE FROM admin WHERE a_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $a_id);
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
            max-width: 88%;
            margin: 50px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-left: 100px;
        }

        .header {
            background-color: #d7f7f7;
            color: #070808;
            text-align: center;
            padding: 5px;
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
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
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
        	height: 35px;
        	width: 35px;
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
            bottom: 50px;
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
        .fas_key{
            font-size: 12px;
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