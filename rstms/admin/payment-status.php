<?php
    include('includes/header.php');
    include('includes/sidebar.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
?>

<div class="container">
	<div class="header">
		<h3>Payment Status</h3>
	</div>
    <div class="search-form">
        <form action="" method="POST">
            <div class="search-box">
                <label>Enter Order ID</label>
                <input type="text" name="search" id="search">
            </div>
            <div class="search-btn">
                <button type="submit" name="src-btn" id="src-btn">Submit</button>
            </div>
        </form>
    </div>
    
        <?php
        $order_id = "";
        $payment_details = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = mysqli_real_escape_string($conn, $_POST['search']);
            $sql = "SELECT * FROM tbl_payment WHERE order_id = '$order_id'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                if (mysqli_num_rows($result)>0) {
                    echo '<div class="table">';
                    echo '<div class="bottom-header">';
                    echo '<h3>Payment Details</h3>';
                    echo '</div>';
                    echo '<table class="stable">';
                    echo '<thead>';
                    echo '<tr>';
                    echo "<th>Order ID</th>
                            <th>Name</th>
                            <th>Food Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>VAT &#37;</th>
                            <th>Total Unit Price</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Payment Status</th>
                            <th>Table Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>";
                    echo "<tbody>";
                while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        echo "<td>".$row['order_id']."</td>";
                        echo "<td>".$row['u_name']."</td>";
                        echo "<td>".$row['f_title']."</td>";
                        echo "<td>&#36; ".$row['f_disctprice']."</td>";
                        echo "<td>".$row['o_quantity']."</td>";
                        echo "<td>".$row['f_vat']."</td>";
                        echo "<td>&#36; ".$row['total_amount']."</td>";
                        echo "<td>&#36; ".$row['amount']."</td>";
                        echo "<td>".$row['o_date']."</td>";
                        echo "<td>";
                        if (empty($row['payment_status']) || is_null($row['payment_status'])) {
                            echo "<span style='color:red;'>Customer Does not Payment Yet!</span>";
                        }
                        else{
                            echo $row['payment_status'];
                        }
                        echo "</td>";
                        echo "<td>".$row['t_name']."</td>";
                        echo '<td>
                                <form action="pdf_print.php" method="POST" target="_blank">
                                    <input type="hidden" name="order_id" value="' . $row["order_id"] . '">
                                    <button type="submit" name="pdf_btn"><i class="fa-solid fa-print"></i></button>
                                </form>
                              </td>';
                        echo "</tr>"; 
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                }
                else {
                    echo "No payment details found for the provided order ID.";
                }
            }
            else {
                echo "Error: " . mysqli_error($conn);
            }
            mysqli_close($conn);         
        }
        ?>
    </div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #e8faf5;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 85%;
        margin: 20px auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-left: 100px;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
        background-color: #c6f5f4;
        padding: 5px;
        border-radius: 10px;
    }
    .search-form {
        margin-bottom: 20px;
        text-align: left;
    }
    .search-box {
        margin-bottom: 10px;
    }
    .search-box label {
        font-weight: bold;
    }
    .search-box input[type="text"] {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .search-btn button {
        padding: 8px 20px;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
    }
    .search-btn button:hover {
        background-color: #0056b3;
    }
    .table {
        overflow-x: auto;
    }
    .stable {
        width: 100%;
        border-collapse: collapse;
    }
    .stable th, .stable td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .stable th {
        background-color: #007bff;
        color: #fff;
    }
    .stable tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .stable tbody tr:hover {
        background-color: #ddd;
    }
    .stable td button {
        padding: 10px 15px;
        border-radius: 5px;
        background-color: #28a745;
        color: #fff;
        border: none;
        cursor: pointer;
    }
    .stable td button:hover {
        background-color: #0af2ac;
    }
    .bottom-header{
        text-align: center;
    }
    .fa-print{
        font-size: 24px;
    }
</style>

<?php
    include('includes/footer.php');
?>