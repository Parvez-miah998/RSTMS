<?php
	include('includes/header.php');
	if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
    $message = '';
?>
	<!--Booked table area start-->
	<div class="container">
		<div class="header">
			<h2>Customer's Booked Table</h2>
		</div>
        <?php if (!empty($message)) : ?>
            <p style="color: green;"><?php echo $message;?></p>
        <?php endif; ?>
		<div class="table">
			<div class="btable">
				<table class="table-data">
					<thead>
						<tr>
							<th>SL</th>
							<th>Table Category</th>
							<th>Table Seats</th>
							<th>User Name</th>
							<th>Email</th>
							<th>Contact</th>
							<th>Table Description</th>
                            <th>Booked Date</th>
							<th>Table Status</th>
						</tr>
					</thead>
					<tbody>
                        <?php
                        $sql = "SELECT * FROM tbl_bookedtable ORDER BY t_id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result && $result->num_rows>0) {
                            $sl = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$sl}</td>";
                                echo "<td>".$row['t_category']."</td>";
                                echo "<td>".$row['t_seat'] ."</td>";
                                echo "<td>".$row['u_name'] ."</td>";
                                echo "<td>".$row['u_email'] ."</td>";
                                echo "<td>".$row['u_contact'] ."</td>";
                                echo "<td>".$row['t_desc'] ."</td>";
                                echo "<td>".$row['t_bookeddate'] ."</td>";
                                
                                $t_status = $row['t_status'];
                                echo "<td>";
                                if ($t_status == '') {
                                    echo "<form action=''method='POST' style='display: inline-block;'>";
                                    echo "<input type='hidden' id='id' name='id' value='".$row['t_id']."'>";
                                    echo "<button name='accept_btn' class='accept-btn'><i class='fa-solid fa-square-check'></i>";
                                    echo "</button>";
                                    echo "</form>";
                                    echo "<form action=''method='POST' style='display: inline-block;'>";
                                    echo "<input type='hidden' id='t_id' name='t_id' value='".$row['t_id']."'>";
                                    echo "<button name='delete_btn' class='del-btn'><i class='fa-solid fa-xmark'></i>";
                                    echo "</button>";
                                    echo "</form>";
                                    
                                }
                                else if (strcasecmp($t_status, 'accept') === 0) {
                                    echo "<span style='background-color: #02f5a4; color: #fff;'>Accepted</span>";
                                }
                                else if (strcasecmp($t_status, 'cancel') === 0) {
                                    echo "<span style='background-color: #f72d33; color: #fff;'>Canceled</span>";
                                }
                                echo "</td>";
                                echo "</tr>";
                                $sl++;
                                
                            }
                        }
                        ?>
					</tbody>
				</table>
			</div>
		</div>
        <!--Code for delete the bookedtable requeest start-->
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['accept_btn'])) {
                $t_id = isset($_POST['id']) ? $_POST['id'] : null;
                updateStatus($t_id, "Accept");
            } elseif (isset($_POST['delete_btn'])) {
                $t_id = isset($_POST['t_id']) ? $_POST['t_id'] : null;
                updateStatus($t_id, "Cancel");
            }
        }

        function updateStatus($t_id, $status){
            global $conn;
            if ($t_id !== null) {
                // Add the closing single quote in the SQL query
                $sql = "UPDATE tbl_bookedtable SET t_status = '$status' WHERE t_id = '$t_id'";

                try {
                    if ($conn->query($sql) === TRUE) {
                        $message = "Record Update Successfully!";
                    } else {
                        $message = "Error updating record: " . $conn->error;
                    }
                } catch (mysqli_sql_exception $e) {
                    // Handle exceptions properly
                    $message = "Error: " . $e->getMessage();
                }
            } else {
                $message = "Error: t_id is not set";
            }
        }
        ?>
    <!--Code for delete the bookedtable requeest end-->
	</div>
	<!--Booked table area end-->

	<!--Booked table style area start-->
	<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            width: 90%;
            margin: auto;
/*            background-color: #fff;*/
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            overflow-x: auto;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-data th, .table-data td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table-data th {
            background-color: #f2f2f2;
        }

        .table-data tbody tr:hover {
            background-color: #f5f5f5;
        }

        .table-data button {
            padding: 5px 9px;
            margin-right: 5px;
            margin-bottom: 5px;
            cursor: pointer;
            border: none;
            outline: none;
            font-size: 14px;
            border-radius: 3px;
            background-color: #18f2bf;
            padding-left: 13px;
        }

        .table-data button i {
            margin-right: 5px;
        }

        .table-data button.accept {
            background-color: #5cb85c;
            color: #fff;
        }

        .table-data button.delete {
            background-color: #d9534f;
            color: #fff;
        }
        .del-btn {
            background-color: #f72c0c!important;
            color: #fff!important;
        }
    </style>
	<!--Booked table style area end-->

<?php include ('includes/footer.php'); ?>