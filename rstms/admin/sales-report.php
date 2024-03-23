<?php
    include('includes/header.php');
    include('includes/sidebar.php');
?>
	<div class="container">
     <div class="header">
         <h3>Sales & Cost Report</h3>
     </div> 
     <div class="sales-form">
         <form action="" method="GET">
             <div class="from-date">
                 <label>Enter Start Date:</label>
                 <input type="date" name="search1" id="search1" class="custom-date-picker" placeholder="yyyy-mm-dd">
             </div>
             <div class="from-date">
                 <label>Enter End Date:</label>
                 <input type="date" name="search2" id="search2" class="custom-date-picker" placeholder="yyyy-mm-dd">
             </div>
             <div class="sales-btn">
                 <button type="submit" name="sales-search">Sales Report</button>
                 <button type="submit" name="cost-search">Cost Report</button>
             </div>
         </form>
     </div>
    <?Php
    if (isset($_GET['sales-search'])) {
        if (empty($_GET['search1']) || empty($_GET['search2'])) {
            echo "<span style='margin-left:35%;background-color:#fa0707;padding:4px;border-radius:5px;font-family: Bahnschrift Light;font-weight:500;'>Please select the date range first.</span>";
        }
        else{
            $from_date = $_GET['search1'];
            $to_date = $_GET['search2'];
            $sql_sale = $conn->prepare("SELECT SUM(amount) AS total_sale FROM tbl_payment WHERE DATE(o_date) BETWEEN '$from_date' AND '$to_date'");
            $sql_sale -> execute();
            $result_sale = $sql_sale->get_result();
            $total_sale = $result_sale->fetch_assoc()['total_sale'];


            $sql = "SELECT * FROM tbl_payment WHERE o_date BETWEEN '$from_date' AND '$to_date'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result)>0) {
                echo '<div class="table">';
                echo '<div class="bottom-header">';
                echo '<h3>Sales Details</h3>';
                echo '</div>';
                if ($total_sale !== null) {
                    $_SESSION['from_date'] = $from_date;
                    $_SESSION['to_date'] = $to_date;
                    $_SESSION['total_sale'] = $total_sale;
                    echo '<div>';
                    echo "<p style='background-color: #56f595;padding: 5px;border-radius:4px;font-weight: 700;'>Total Sale: &dollar; $total_sale</p>";
                    echo '</div>';
                }
                else{
                    echo "<span style='margin-left:35%;background-color:#30f073;padding:4px;border-radius:5px;font-family: Bahnschrift Light;font-weight:500;'>No cost details found for the provided date range.</span>";
                }
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
                    echo "</tr>"; 
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                $_SESSION['from_date'];
                $_SESSION['to_date'];
                $_SESSION['total_sale'];
                echo '<div class="pdf-btn">
                        <form action="pdf.php" method="post" target="_blank">
                            <button type="submit" name="sale_pdf_btn"><i class="fa-solid fa-print"></i></button> 
                        </form>
                    </div>';
                echo "</div>";
            }
            else {
                echo "<span style='margin-left:35%;background-color:#30f073;padding:4px;border-radius:5px;font-family: Bahnschrift Light;font-weight:500;'>No payment details found for the provided date range.</span>";
            }
        }
    }

    if (isset($_GET['cost-search'])) {
        if (empty($_GET['search1']) || empty($_GET['search2'])) {
            echo "<span style='margin-left:35%;background-color:#fa0707;padding:4px;border-radius:5px;font-family: Bahnschrift Light;font-weight:500;'>Please select the date range first.</span>";
        }
        else{
            $from_date = $_GET['search1'];
            $to_date = $_GET['search2'];
            $sqlTotalcost = $conn->prepare("SELECT SUM(ac_amount) AS total_cost FROM account WHERE DATE(date) BETWEEN '$from_date' AND '$to_date'");
            $sqlTotalcost -> execute();
            $resultTotalcost = $sqlTotalcost->get_result();
            $rowTotalcost = $resultTotalcost->fetch_assoc();
            $total_cost = $rowTotalcost['total_cost'];
            

            $sql_cost = $conn->prepare("SELECT * FROM account WHERE date BETWEEN '$from_date' AND '$to_date'");
            $sql_cost -> execute();
            $result_cost = $sql_cost->get_result();
            if ($result_cost && $result_cost->num_rows>0) {
                echo '<div class="table">';
                echo '<div class="bottom-header">';
                echo '<h3>Cost Details</h3>';
                echo '</div>';
                if ($total_cost !== null) {
                    echo '<div>';
                    echo "<p style='background-color: #56f595;padding: 5px;border-radius:4px;font-weight: 700;'>Total Cost: &dollar; $total_cost</p>";
                    echo '</div>';
                }
                else{
                    echo "<span style='margin-left:35%;background-color:#30f073;padding:4px;border-radius:5px;font-family: Bahnschrift Light;font-weight:500;'>No cost details found for the provided date range.</span>";
                }
                echo '<table class="stable">';
                echo '<thead>';
                echo '<tr>';
                echo "<th>Description</th>
                        <th>Amount</th>
                        <th>Email</th>
                        <th>Date</th>
                    </tr>
                </thead>";
                echo "<tbody>";
                while ($row_cost = $result_cost->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_cost['ac_desc'] . "</td>";
                    echo "<td>&dollar; " . $row_cost['ac_amount'] . "</td>";
                    echo "<td>" . $row_cost['a_email'] . "</td>";
                    echo "<td>" . $row_cost['date'] . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                // Set session variables before redirecting to pdf.php
                $_SESSION['from_date'] = $from_date;
                $_SESSION['to_date'] = $to_date;
                $_SESSION['total_cost'] = $total_cost;
                echo '<div class="pdf-btn">
                        <form action="pdf.php" method="post" target="_blank">
                            <button type="submit" name="cost_pdf_btn"><i class="fa-solid fa-print"></i></button> 
                        </form>
                    </div>';
                echo "</div>";
            }
            else{
                echo "<span style='margin-left:35%;background-color:#30f073;padding:4px;border-radius:5px;font-family: Bahnschrift Light;font-weight:500;'>No cost details found for the provided date range.</span>";
            }
        }
    }
    ?>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4ebf7;
        margin: 0;
        padding: 0;
    }
.container {
    max-width: 85%;
    margin: 40px 10px 40px 100px;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 25px;
    padding: 5px;
    background-color: #c1f5f0;
    border-radius: 10px;
}

/* Form Styles */
.sales-form {
    margin-bottom: 20px;
}

.sales-form form {
    display: inline-block;
    flex-wrap: wrap;
    justify-content: left;
}

.from-date {
    margin: 0 10px;
    text-align: center;
    padding: 10px;
}

.from-date label {
    display: inline-block;
    margin-bottom: 5px;
    font-weight: bolder;
    margin-right: 10px;
}

.from-date input[type="date"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    outline: none;
}

.sales-btn button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    outline: none;
    transition: background-color 0.3s ease;
    margin-left: 80px;
    margin-top: 10px;
}

.sales-btn button:hover {
    background-color: #0af5dd;
}

.table {
    overflow-x: auto;
    text-align: center;
}

.stable {
    width: 100%;
    border-collapse: collapse;
}

.stable th, .stable td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.stable th {
    background-color: #84d7f5;
}
/* Styling the calendar start */
.custom-date-picker {
  border: 2px solid #0f1212;
  border-radius: 5px;
  padding: 10px;
  font-size: 16px;
}
input[type="date"] {
  border: 2px solid #ccc;
  border-radius: 5px;
  padding: 5px;
}

input[type="date"]::-webkit-calendar-picker-indicator {
  color: #333;
  font-size: 18px;
  cursor: pointer;
}
.pdf-btn button {
    margin-top: 10px;
    padding: 10px 15px;
    border-radius: 5px;
    background-color: #28a745;
    color: #fff;
    border: none;
    cursor: pointer;
}

.pdf-btn button:hover {
    background-color: #0af2ac;
}

.pdf-btn {
    text-align: center;
}

.fa-print {
    font-size: 24px;
}

/* Styling the calendar end */
</style>

<!--script for calender start-->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
var yyyy = today.getFullYear();

today = yyyy + '-' + mm + '-' + dd;

// Set the max attribute of the date input to today
document.getElementById("search2").setAttribute("max", today);


flatpickr('#search1, #search2', {
    
    dateFormat: 'Y-m-d',
    maxDate: today
});
</script>
<!--script for calender end-->

<?php
    include('includes/footer.php');
?>