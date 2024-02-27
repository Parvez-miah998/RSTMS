<?php
    include('includes/dbconnection.php');
    include('includes/header.php');
    include('includes/sidebar.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }

?>
	
		<div class="container">
			<div class="header">
				<h1>Table & Seats</h1>
			</div>
			<div class="form-data">
				<div class="table">
					<table class="ctable">
						<thead>
							<tr>
								<th>Table SL</th>
								<th>Table Name</th>
								<th>Table Seats</th>
								<th>Table Status</th>
								<th>Table Category</th>
								<th>User Name</th>
								<th>Table Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT * FROM tbl_table";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$result = $stmt->get_result();
							if ($result && $result->num_rows>0) {
								$sl = 1;
								while($row = $result->fetch_assoc()){
									echo '<tr>';
									echo "<td>{$sl}</td>";
									echo '<td>'.$row['t_name'].'</td>';
									echo '<td>'.$row['t_seat'].'</td>';
									echo '<td>'.$row['t_status'].'</td>';
									echo '<td>'.$row['t_category'].'</td>';
									echo '<td>'.$row['u_name'].'</td>';
									echo '<td>';
									echo '<form action="edittable.php" method="GET" style="display:inline-block;">';
									echo "<input type='hidden' id='id' name='id' value='".$row['ta_id']."'>";
									echo '<button type="submit" class="tbl-edit"><i class="fa-solid fa-pen-to-square"></i></button>';
									echo '</form>';
									echo '<form action="" method="POST" style="display:inline-block;">';
									echo "<input type='hidden' id='ta_id' name='ta_id' value='".$row['ta_id']."'>";
									echo '<button type="submit" class="tbl-del" name="delete_btn"><i class="fa-solid fa-trash"></i></button>';
									echo '</form>';
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
				<a href="addtable.php"><i class="fa-solid fa-square-plus"></i></a>
			</div>
		</div>
	</div>
	<?php
	if (isset($_POST['delete_btn']) && isset($_POST['ta_id'])) {
		$ta_id = $_POST['ta_id'];
		$sql = "DELETE FROM tbl_table WHERE ta_id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $ta_id);
		if($stmt->execute()){
			echo "<script>window.location.reload();</script>";
		}
		else{
			$message = "Unable to Delete!";
		}
	}
	?>
	

	<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 89%;
            margin: auto;
/*            background-color: #fff;*/
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-left: 100px;
            margin-top: 20px;
            margin-bottom: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .ctable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .ctable th, .ctable td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .ctable th {
            background-color: #f2f2f2;
        }

        .tbl-actions {
            display: flex;
            justify-content: space-around;
        }

        .tbl-edit {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 5px 9px;
            cursor: pointer;
            border-radius: 5px;
        }
        .tbl-del{
        	background-color: #f24033;
            color: #fff;
            border: none;
            padding: 5px 9px;
            margin-left: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .tbl-del {
            background-color: #e74c3c;
        }

        .tbl-edit:hover {
            background-color: #2980b9;
        }
        .tbl-del:hover {
            background-color: #f51505;
        }
        .icon {
	        position: fixed;
	        bottom: 60px;
	        right: 30px;
	        width: 50px;
	        height: 50px!important;
	        /*background-color: #3498db;
	        border-radius: 50%;*/
	        display: flex;
	        justify-content: center;
	        align-items: center;
	        color: #fff;
	        cursor: pointer;
	        text-decoration: none;
	    }
	    
	    .icon a .fa-square-plus {
	        height: 40px !important;
	        color: #61eded;
	    }

	    .icon a .fa-square-plus:hover {
	        color: #06d6be;
	    }
    </style>


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