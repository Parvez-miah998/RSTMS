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
         <h3>Sales Report</h3>
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
                 <button type="submit" name="sales-search">Search</button>
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

            $sql = "SELECT * FROM tbl_payment WHERE o_date BETWEEN '$from_date' AND '$to_date'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result)>0) {
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
                        <th>Total Unit Price</th>
                        <th>VAT</th>
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
                    echo "<td>".$row['f_disctprice']."</td>";
                    echo "<td>".$row['o_quantity']."</td>";
                    echo "<td>".$row['total_amount']."</td>";
                    echo "<td>".$row['f_vat']."</td>";
                    echo "<td>".$row['amount']."</td>";
                    echo "<td>".$row['o_date']."</td>";
                    echo "<td>".$row['payment_status']."</td>";
                    echo "<td>".$row['t_name']."</td>";
                    echo "</tr>"; 
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
            }
            else {
                echo "<span style='margin-left:35%;background-color:#30f073;padding:4px;border-radius:5px;font-family: Bahnschrift Light;font-weight:500;'>No payment details found for the provided date range.</span>";
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