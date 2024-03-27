<?php
    session_start();
    include 'includes/header.php';
    require('../config.php');
    include ('includes/dbconnection.php');
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }
?>

<div class="container-fluid">
    <div class="view-details">
        <div class="header">
            <h2>Food Order Table</h2>
        </div>
        <div class="booked-table">
                <table class="all-booked">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>User Name</th>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>VAT</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Table</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalAmount = 0;
                    if (isset($_SESSION['user'])) {
                        $email = $_SESSION['user'];
                        $user_sql = $conn->prepare("SELECT u_id FROM users WHERE u_email = ?");
                        $user_sql->bind_param("s", $email);
                        $user_sql->execute();
                        $user_result = $user_sql->get_result();

                        if ($user_row = $user_result->fetch_assoc()) {
                            $user_id = $user_row['u_id'];
                            $user_result->close();
                            $rowsPerPage = 10;
                            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                                $currentPage = $_GET['page'];
                            }
                            else{
                                $currentPage = 1;
                            }
                            $offset = ($currentPage - 1) * $rowsPerPage; 

                            $order_sql = $conn->prepare("SELECT * FROM tbl_payment WHERE u_id = ? ORDER BY p_id DESC LIMIT $rowsPerPage OFFSET $offset");
                            $order_sql->bind_param("i", $user_id);
                            $order_sql->execute();
                            $order_result = $order_sql->get_result();
                        while ($row = mysqli_fetch_assoc($order_result)) {
                            echo "<tr>";
                            echo "<td>{$row['order_id']}</td>";
                            echo "<td>{$row['u_name']}</td>";
                            echo "<td>{$row['f_title']}</td>";
                            echo "<td>{$row['o_quantity']}</td>";
                            echo "<td>&#36; {$row['f_disctprice']}</td>";
                            echo "<td>{$row['f_vat']} &percnt;</td>";
                            echo "<td>&#36; {$row['total_amount']}</td>";
                            echo "<td>{$row['o_date']}</td>";
                            echo "<td>{$row['t_name']}</td>";
                            if (empty($row['payment_status'])) {
                                $order_id = $row['order_id'];
                                $totalAmount_sql = $conn->prepare("SELECT amount FROM tbl_payment WHERE order_id = ?");
                                $totalAmount_sql->bind_param("s", $order_id);
                                $totalAmount_sql->execute();
                                $totalAmount_result = $totalAmount_sql->get_result();

                                if ($totalAmount_row = $totalAmount_result->fetch_assoc()) {
                                    $amount = number_format($totalAmount_row['amount'], 2);
                                    echo "<td>";
                                    echo "Payable Amount: $amount";
                                    echo '<form action="../thankyou.php" method="POST">';
                                    echo '<input type="hidden" name="total_amount" value="' . $amount . '">';
                                    echo '<script 
                                            src="https://checkout.stripe.com/checkout.js"
                                            class="stripe-button"
                                            data-key="' . $publishableKey . '"
                                            data-amount="' . $amount * 100 . '"
                                            data-name="KING RESTAURANT"
                                            data-description="A1 Restaurent"
                                            data-image="https://img.freepik.com/premium-vector/cute-panda-paws-up-wall-panda-face-cartoon-icon_42750-498.jpg"
                                            data-currency="usd">
                                         </script>';
                                    echo '</form>';
                                    echo "</td>";
                                }
                            } 
                            else {
                                echo "<td>{$row['payment_status']}</td>";
                            }  
                               
                            echo "</td>";
                            echo "</tr>";
                          }
                          $sql_page = "SELECT COUNT(*) AS total FROM tbl_payment";
                          $result_page = $conn->query($sql_page);
                          $row_page = $result_page->fetch_assoc()['total'];
                          $totalPages = ceil($row_page/$rowsPerPage);
                      }
                    ?>   
                </tbody>
            </table>
        </div>
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
<?php }
else{
    echo "User Not Found!";
}
?>
<!--Table Food order End-->
<!--Table Booked start-->
<?php  

    $userEmail = $_SESSION['user'];
    $usersql = "SELECT u_id FROM users WHERE u_email = '$userEmail'";
    $userresult = mysqli_query($conn, $usersql);

    if ($userrow = mysqli_fetch_assoc($userresult)) {
        $userId = $userrow['u_id'];
        $sql = "SELECT * FROM tbl_bookedtable WHERE u_id = '$userId' ORDER BY t_id DESC";
        $result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
	<div class="view-details">
		<div class="header">
			<h2>Booked Table</h2>
		</div>
		<div class="booked-table">
				<table class="all-booked">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Table Category</th>
                        <th>Table Seats</th>
                        <th>Booked Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$sl}</td>";
                        echo "<td>{$row['u_name']}</td>";
                        echo "<td>{$row['u_email']}</td>";
                        echo "<td>{$row['u_contact']}</td>";
                        echo "<td>{$row['t_category']}</td>";
                        echo "<td>{$row['t_seat']}</td>";
                        echo "<td>{$row['t_bookeddate']}</td>";
                        echo "<td>";
                        if ($row['t_status'] === 0 || $row['t_status'] === '') {
                            echo "<span style='background-color: #2da3f7; color: #fff;'>Pending</span>";
                        }
                        elseif ($row['t_status'] === 'Accept') {
                            echo "<span style='background-color: #02f5a4; color: #fff;'>Accepted</span>";
                        }
                        elseif ($row['t_status'] === 'Cancel') {
                            echo "<span style='background-color: #f72d33; color: #fff;'>Canceled</span>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if ($row['t_status'] === 0 || $row['t_status'] === '') {
                            echo "<form action='' method='POST'>
                                <input type='hidden' name='delete_id' value='{$row['t_id']}'>
                                <button type='submit' name='delete_btn'>Delete</button>
                              </form>";
                        }
                        elseif ($row['t_status'] === 'Accept') {
                            echo "<span style='background-color: #02f5a4; color: #fff;'>Approved</span>";
                        }
                        elseif ($row['t_status'] === 'Cancel') {
                            echo "<span style='background-color: #f72d33; color: #fff;'>Canceled</span>";
                        }
                              
                        echo "</td>";
                        echo "</tr>";

                        $sl++;
                      }
                    ?>  
                </tbody>
            </table>
		</div>
	</div>
</div>
<?php
}
else{
    echo "User Not Found!";
}

?>

<!--Delete Button controller code start-->

                    <?php
                        if (isset($_POST['delete_btn'])) {
                            $delete_id = $_POST['delete_id'];
                            $deleteResult = mysqli_query($conn, "DELETE FROM tbl_bookedtable WHERE t_id = $delete_id");

                            if ($deleteResult) {
                                // echo "Record deleted successfully!";
                                echo "<script>window.location.reload();</script>";
                            } else {
                                echo "Error deleting record: " . mysqli_error($conn);
                            }
                        }
                    ?>
<!--Delete Button controller code End-->


<!--Style area for Viewall Page Start-->

<style type="text/css">
	.container-fluid {
      max-width: 90%;
      margin: 0 auto;
      margin-top: 40px;
      padding: 20px;
      font-family: Arial, sans-serif;
    }

    .view-details {
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 10px;
      border-radius: 8px;
    }

    .header {
      text-align: center;
    }

    h2 {
      color: #333;
    }

    .booked-table {
      margin-top: 20px;
    }

    .all-booked {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .all-booked th, .all-booked td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }

    .all-booked th {
      background-color: #f2f2f2;
    }

    .all-booked tr:hover {
      background-color: #f5f5f5;
    }

    button {
      background-color: #ff5c5c;
      color: #fff;
      border: none;
      padding: 8px 12px;
      cursor: pointer;
      border-radius: 4px;
    }

    button:hover {
      background-color: #e74c3c;
    }
    .btn-payment{
        background-color: #03fc88;
        margin-left: 48%;
        margin-bottom: 15px;
        margin-top: 25px;
    }
    .btn-payment:hover{
        background-color: #25f594;
    }
    .tbl-pay{
        width: 100%;
        border-collapse: collapse;
        margin-top: 0px!important;
    }
    .tbl-pay th{
        text-align: right;
        padding-left: 45%;
        padding-right: 13.6px;
    }
    .tbl-pay td{
        text-align: left;
        padding-left: 10px;
    }
    .tbl-pay th, .tbl-pay td{
        border: 1px solid #ddd;
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

<!--Style area for Viewall Page End-->


<!--Footer area-->
<?php include 'includes/footer.php'; ?>
