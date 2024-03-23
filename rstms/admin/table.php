<?php
    include('includes/header.php');
    include('includes/sidebar.php');

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
							$rowsPerPage = 2;
							if (isset($_GET['page']) && is_numeric($_GET['page'])) {
								$currentPage = $_GET['page'];
							}
							else{
								$currentPage = 1;
							}
							$offset = ($currentPage - 1) * $rowsPerPage;
							$sql = "SELECT * FROM tbl_table LIMIT $rowsPerPage OFFSET $offset";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$result = $stmt->get_result();
							if ($result && $result->num_rows>0) {
								$sl = ($currentPage - 1) * $rowsPerPage + 1;
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
							$sql = "SELECT COUNT(*) AS total FROM tbl_table";
							$result = $conn->query($sql);
							$row = $result->fetch_assoc()['total'];
							$totalPages = ceil($row/$rowsPerPage);
							?>
						</tbody>
					</table>
					<div class="pagination" style="margin-bottom:15px;">
						<?php if($totalPages>1) : ?>
							<ul>
								<li> <a href="?page=1">&laquo;</a></li>
								<?php for($page=1; $page<=$totalPages; $page++) : ?>
									<li <?php if($page == $currentPage) echo "class='active'"; ?>>
										<a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
									</li>
								<?php endfor;?>
								<li> <a href="?page=<?php echo $totalPages; ?>">&raquo;</a></li>
							</ul>
						<?php endif; ?>
					</div>
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
            background-color: #97fcf7;
            padding: 5px;
            border-radius: 10px 10px 0 0;
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
	    .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination ul li {
            display: inline-block;
            margin-right: 5px;
        }

        .pagination ul li a {
            display: block;
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 3px;
            color: #333;
        }

        .pagination ul li.active a {
            background-color: #3498db;
            color: #fff;
        }

        .pagination ul li a:hover {
            background-color: #f0f0f0;
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