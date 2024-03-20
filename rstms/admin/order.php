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
        <h3>Order Details</h3>
    </div>
    <div class="table">
        <table class="stable">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Order ID</th>
                    <th>Name</th>
                    <th>Food Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total Unit Price</th>
                    <th>VAT &#37;</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                    <th>Payment Status</th>
                    <th>Table Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowsPerPage = 5;
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $currentPage = $_GET['page'];
                }
                else{
                    $currentPage = 1;
                }
                $offset = ($currentPage - 1) * $rowsPerPage;

                $sql_o = $conn->prepare("SELECT * FROM tbl_payment ORDER BY p_id DESC LIMIT $rowsPerPage OFFSET $offset");
                $sql_o -> execute();
                $result_o = $sql_o->get_result();
                if ($result_o && $result_o->num_rows>0) {
                    $starting_sl = ($currentPage - 1) * $rowsPerPage + 1;
                    while ($row = $result_o->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$starting_sl}</td>";
                        echo "<td>".$row['order_id']."</td>";
                        echo "<td>".$row['u_name']."</td>";
                        echo "<td>".$row['f_title']."</td>";
                        echo "<td>".$row['f_disctprice']."</td>";
                        echo "<td>".$row['o_quantity']."</td>";
                        echo "<td>".$row['total_amount']."</td>";
                        echo "<td>".$row['f_vat']."</td>";
                        echo "<td>".$row['amount']."</td>";
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
                        $starting_sl++;
                    }
                }
                $sqlCount = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_payment");
                $sqlCount -> execute();
                $resultCount = $sqlCount->get_result();
                $rowCount = $resultCount->fetch_assoc()['total'];
                $totalPages = ceil($rowCount/$rowsPerPage);
                ?>
            </tbody>
        </table>
        <div class="pagination" style="margin-bottom: 15px;">
            <?php if ($totalPages > 1): ?>
                <ul>
                    <li><a href="?page=1">&laquo;</a></li> <!-- First page -->
                    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                        <li <?php if ($page == $currentPage) echo "class='active'"; ?>>
                            <a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li><a href="?page=<?php echo $totalPages; ?>">&raquo;</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div> 
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

<?php
    include('includes/footer.php');
?>