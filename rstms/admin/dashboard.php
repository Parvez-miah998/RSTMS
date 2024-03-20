<?php
	include('includes/header.php');
	include('includes/sidebar.php');
	if (!isset($_SESSION['admin_email'])) {
	    header("Location: ../users/login.php");
	    exit();
	}
?>

<div class="dashboard">
	<div class="content">
        <div class="total-order">
            <div class="order-head">
                <h4>Total Order</h4>
            </div>
            <div class="order-body">
                <?php
                $sql_o = $conn->prepare("SELECT COUNT(*) AS total_o FROM tbl_payment");
                $sql_o -> execute();
                $result_o = $sql_o->get_result();
                if($result_o && $result_o->num_rows>0){
                    $row = $result_o->fetch_assoc();
                    $total_o = $row['total_o'];
                    echo "<h4>". $row['total_o']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                <a href="order.php">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total Category</h4>
            </div>
            <div class="order-body">
                <?php
                $sql_c = $conn->prepare("SELECT COUNT(*) AS total_c FROM tbl_catagory");
                $sql_c -> execute();
                $result_c = $sql_c->get_result();
                if ($result_c && $result_c->num_rows>0) {
                    $row = $result_c->fetch_assoc();
                    $total_c = $row['total_c'];
                    echo "<h4>".$row['total_c']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                
                <a href="category.php">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total Food</h4>
            </div>
            <div class="order-body">
                <?php
                $sql_f = $conn->prepare("SELECT COUNT(*) AS total_f FROM tbl_food");
                $sql_f -> execute();
                $result_f = $sql_f->get_result();
                if($result_f && $result_f->num_rows>0){
                    $row = $result_f->fetch_assoc();
                    $total_f = $row['total_f'];
                    echo "<h4>". $row['total_f']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                <a href="foodmenu.php">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Booked Table Request</h4>
            </div>
            <div class="order-body">
            	<?php
                $sql_bt = $conn->prepare("SELECT COUNT(*) AS total_bt FROM tbl_bookedtable WHERE t_status = ''");
                $sql_bt -> execute();
                $result_bt = $sql_bt->get_result();
                if($result_bt && $result_bt->num_rows>0){
                    $row = $result_bt->fetch_assoc();
                    $total_bt = $row['total_bt'];
                    echo "<h4>". $row['total_bt']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                <a href="bookedtable.php">View</a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="total-order">
            <div class="order-head">
                <h4>Feed Back</h4>
            </div>
            <div class="order-body">
                <?php
                $sql_fb = $conn->prepare("SELECT COUNT(*) AS total_fb FROM tbl_feedback");
                $sql_fb -> execute();
                $result_fb = $sql_fb->get_result();
                if($result_fb && $result_fb->num_rows>0){
                    $row = $result_fb->fetch_assoc();
                    $total_fb = $row['total_fb'];
                    echo "<h4>". $row['total_fb']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                <a href="user-feedback.php">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total User</h4>
            </div>
            <div class="order-body">
                <?php
                $sql_u = $conn->prepare("SELECT COUNT(*) AS total_u FROM users");
                $sql_u -> execute();
                $result_u = $sql_u->get_result();
                if($result_u && $result_u->num_rows>0){
                    $row = $result_u->fetch_assoc();
                    $total_u = $row['total_u'];
                    echo "<h4>". $row['total_u']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                <a href="userdetails.php">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Approved Booked Table</h4>
            </div>
            <div class="order-body">
                <?php
                $sql_bta = $conn->prepare("SELECT COUNT(*) AS total_bta FROM tbl_bookedtable WHERE t_status = 'Accept'");
                $sql_bta -> execute();
                $result_bta = $sql_bta->get_result();
                if($result_bta && $result_bta->num_rows>0){
                    $row = $result_bta->fetch_assoc();
                    $total_bta = $row['total_bta'];
                    echo "<h4>". $row['total_bta']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                <a href="approvedlbooked-table.php">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Cancel Booked Table</h4>
            </div>
            <div class="order-body">
                <?php
                $sql_btc = $conn->prepare("SELECT COUNT(*) AS total_btc FROM tbl_bookedtable WHERE t_status = 'Cancel'");
                $sql_btc -> execute();
                $result_btc = $sql_btc->get_result();
                if($result_btc && $result_btc->num_rows>0){
                    $row = $result_btc->fetch_assoc();
                    $total_btc = $row['total_btc'];
                    echo "<h4>". $row['total_btc']."</h4>";
                }else { echo "<h4>0</h4>"; }
                ?>
                <a href="cancelbooked-table.php">View</a>
            </div>
        </div>
    </div>
</div>
<!--Code for Cost and Profit start-->
<?php
// Fetch total cost from the 'account' table
$sqlCost = "SELECT SUM(ac_amount) AS total_cost FROM account";
$resultCost = mysqli_query($conn, $sqlCost);

$totalCost = 0;
if ($resultCost && mysqli_num_rows($resultCost) > 0) {
    $rowCost = mysqli_fetch_assoc($resultCost);
    $totalCost = $rowCost['total_cost'];
}

// Fetch total sales amount from the 'tbl_payment' table
$sqlSales = "SELECT SUM(amount) AS total_sales FROM tbl_payment";
$resultSales = mysqli_query($conn, $sqlSales);

$totalSales = 0;
if ($resultSales && mysqli_num_rows($resultSales) > 0) {
    $rowSales = mysqli_fetch_assoc($resultSales);
    $totalSales = $rowSales['total_sales'];
}

// Calculate total profit
$totalProfit = $totalSales - $totalCost;

// Determine color based on profit or loss
$profitColor = $totalProfit >= 0 ? 'rgba(54, 162, 235, 0.5)' : 'rgba(255, 99, 132, 0.5)';
$costColor = 'rgba(255, 206, 86, 0.5)';
$salesColor = 'rgba(75, 192, 192, 0.5)';
?>
<div class="container-es">
    <div class="bar-graph">
        <div class="header">
            <h2>Cost & Profit</h2>
        </div>
        <div class="line-graph">
            <canvas class="line-graph" id="pieChart"></canvas>
        </div>
    </div>
    <!--Code for Cost and Profit end-->
    <!--Code for Daily sales and cost start-->
    <?php
    $costData = [];
    $salesData = [];

    // Fetching cost data from the database
    $costQuery = "SELECT DATE(date) AS day, SUM(ac_amount) AS total_cost FROM account WHERE DATE(date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE() GROUP BY DATE(date)";
    $costResult = mysqli_query($conn, $costQuery);
    if (!$costResult) {
        die("Error fetching cost data: " . mysqli_error($conn));
    }
    while ($row = mysqli_fetch_assoc($costResult)) {
        $costData[$row['day']] = $row['total_cost'];
    }

    // Fetching sales data from the database
    $salesQuery = "SELECT DATE(o_date) AS day, SUM(amount) AS total_sales FROM tbl_payment WHERE DATE(o_date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE() GROUP BY DATE(o_date)";
    $salesResult = mysqli_query($conn, $salesQuery);
    if (!$salesResult) {
        die("Error fetching sales data: " .mysqli_error($conn));
    }
    while ($row = mysqli_fetch_assoc($salesResult)) {
        $salesData[$row['day']] = $row['total_sales'];
    }

    // Fill in missing days with zero values
    $days = [];
    for ($i = 0; $i < 7; $i++) {
        $days[date('Y-m-d', strtotime("-$i days"))] = 0;
    }

    $costData = array_merge($days, $costData);
    $salesData = array_merge($days, $salesData);

    // Sort data by date
    ksort($costData);
    ksort($salesData);

    $costData = array_values($costData);
    $salesData = array_values($salesData);
    ?>
    
    <div class="area-chart">
        <div class="header">
            <h2>Daily Sales & Cost</h2>
        </div>
        <div class="graph">
            <canvas id="myChart"></canvas>
        </div>
    </div>
<!--Code for Daily sales report end-->
</div>




<!--JS Code for Cost and Profit start-->
<script>
    var totalCost = <?php echo $totalCost; ?>;
    var totalSales = <?php echo $totalSales; ?>;
    var totalProfit = <?php echo $totalProfit; ?>;

    var remainingProfit = totalProfit > 0 ? totalProfit : 0;

    var ctx = document.getElementById('pieChart').getContext('2d');

    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Cost', 'Sales', 'Profit'],
            datasets: [{
                // label: 'Cost, Sales, and Profit',
                data: [totalCost, totalSales, remainingProfit],
                backgroundColor: [
                    '<?php echo $costColor; ?>',
                    '<?php echo $salesColor; ?>',
                    '<?php echo $profitColor; ?>'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
        }
    });
</script>
<!--JS Code for Cost and Profit end-->
<!--JS Code for Daily Cost and sales start-->
<script>
    var costData = <?php echo json_encode($costData); ?>;
    var salesData = <?php echo json_encode($salesData); ?>;

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Changed type to 'bar'
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Cost',
                data: costData,
                backgroundColor: 'rgba(247, 10, 10, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Sales',
                data: salesData,
                backgroundColor: 'rgba(10, 247, 113, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>


<!--JS Code for Daily Cost and sales end-->


<?php include('includes/footer.php'); ?>