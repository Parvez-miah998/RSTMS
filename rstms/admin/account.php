<?php
    include('includes/header.php');
    include('includes/sidebar.php');
?>
<div class="container">
    <div class="header">
        <h1>Cost Details</h1>
    </div>
    <?php if (!empty($message)) : ?>
        <p style="color: green;"> <?php echo $message;?></p>
    <?php endif; ?>
    <div class="table">
        <table class="ctable">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Admin Email</th>
                    <th>Description</th>
                    <th>Cost Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowsPerPage = 25;
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $currentPage = $_GET['page'];
                }
                else{
                    $currentPage = 1;
                }
                $offset = ($currentPage - 1) * $rowsPerPage;

                $sql = "SELECT * FROM account ORDER BY date DESC LIMIT $rowsPerPage OFFSET $offset";
                $result = $conn->query($sql);
                $total_cost = 0;

                if ($result && $result->num_rows > 0) {
                    $starting_sl = ($currentPage - 1) * $rowsPerPage + 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$starting_sl}</td>";    
                        echo "<td>".$row['a_email']."</td>";
                        echo "<td>".$row['ac_desc']."</td>";
                        echo "<td>&dollar; ".$row['ac_amount']."</td>";
                        echo "<td>".$row['date']."</td>";
                        echo "<td>";
                        echo "<form action='edit-account.php' method='GET' style='display:inline-block;'>";
                        echo '<input type="hidden" name="id" value='.$row["ac_id"].'>';
                        echo "<button type='submit' class='icon-edit'>";
                        echo "<i class='fa-solid fa-pen-to-square'></i>";
                        echo "</button>";
                        echo "</form>";
                        // echo "<form action='' method='POST' style='display:inline-block;'>";
                        // echo '<input type="hidden" name="ac_id" value='.$row['ac_id'].'>';
                        // echo "<button class='del-btn' name='delete_btn'>";
                        // echo "<i class='fa-solid fa-trash-can'></i>";
                        // echo "</button>";
                        // echo "</form>";
                        echo "</td>";
                        echo "</tr>";

                        $total_cost += $row['ac_amount'];

                        $starting_sl++;
                    }
                }
                else{
                    echo "<p style='text-align: center;color: red;'>No Category Found!</p>";
                }

                // Calculate total number of pages
                $sqlCount = "SELECT COUNT(*) AS total FROM account";
                $resultCount = $conn->query($sqlCount);
                $rowCount = $resultCount->fetch_assoc()['total'];
                $totalPages = ceil($rowCount / $rowsPerPage);

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
                    <li><a href="?page=<?php echo $totalPages; ?>">&raquo;</a></li> <!-- Last page -->
                </ul>
            <?php endif; ?>
        </div>
        <?Php
        $sql_cost = "SELECT SUM(ac_amount) AS total_cost  FROM account";
        $result_cost = $conn->query($sql_cost);
        $row_cost = $result_cost->fetch_assoc();
        $total_cost = $row_cost['total_cost'];

        ?>
        <table class="ctable">
            <tr>
                <td style="padding-left: -50px; margin-left: -10px;background-color:#b5f5dd;border-radius: 5px;font-weight: 600;">Total Cost Amount</td>
                <td style="background-color: #4df7b9;border-radius: 5px;font-weight: 600;">&dollar; <?php echo $total_cost; ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="icon">
    <a href="add-account.php"><i class="fa-solid fa-square-plus"></i></a>
</div>

    <?php
    // if (isset($_POST['delete_btn']) && isset($_POST['ac_id'])) {
    //     $ac_id = $_POST['ac_id'];
    //     $sql = "DELETE FROM account WHERE ac_id = ?";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("i", $ac_id);
    //     if ($stmt->execute()) {
    //         echo "<script>window.location.reload();</script>";
    //     }
    //     else{
    //         $message = "Unable to Delete!";
    //     }
    // }
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
            max-width: 86%;
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
            padding: 10px;
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
            height: 30px;
            width: 30px;
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