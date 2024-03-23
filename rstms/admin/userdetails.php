<?php
    include('includes/header.php');
    include('includes/sidebar.php');
?>
	<div class="container">
     <div class="header">
         <h3>All Users</h3>
     </div> 
     <div class="table">
         <table class="utable">
             <thead>
                 <tr>
                     <th>SL</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Contact</th>
                     <th>Address</th>
                     <th>Occuption</th>
                     <th>Date of Birth</th>
                     <th>Image</th>
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
                $sql = $conn->prepare("SELECT * FROM users LIMIT $rowsPerPage OFFSET $offset");
                $sql -> execute();
                $result = $sql->get_result();
                if ($result && $result->num_rows>0) {
                    $starting_sl = ($currentPage - 1) * $rowsPerPage + 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$starting_sl}</td>";
                        echo "<td>".$row['u_name']."</td>";
                        echo "<td>".$row['u_email']."</td>";
                        echo "<td>".$row['u_contact']."</td>";
                        echo "<td>".$row['u_paddress']."</td>";
                        echo "<td>".$row['u_occ']."</td>";
                        echo "<td>".$row['u_dob']."</td>";
                        echo "<td><img class='user-img' src='../assets/user_images/{$row['u_img']}' alt='User_Image'></td>";
                        echo "</tr>";

                        $starting_sl++;
                    }
                }
                $sqlCount = "SELECT COUNT(*) AS total FROM users";
                $resultCount = $conn->query($sqlCount);
                $rowCount = $resultCount->fetch_assoc()['total'];
                $totalPages = ceil($rowCount/$rowsPerPage);
                ?>
             </tbody>
         </table>
         <div class="pagination" style="margin-bottom: 15px;">
            <?php if($totalPages>1) : ?>
                <ul>
                    <li> <a href="?page=1">&laquo;</a> </li>
                    <?php for ($page=1; $page <= $totalPages ; $page++) : ?>
                        <li <?php if($page == $currentPage) echo "class='active'"; ?>>
                            <a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li> <a href="?page=<?php echo $totalPages; ?>">&raquo;</a> </li>
                </ul>
            <?php endif;?>
         </div>
     </div>  
    </div>


    <style>
    .container {
        width: 88%;
        margin: 40px auto;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        background-color: #c6f5f4;
        padding: 5px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px 25px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        color: #333;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .user-img {
        max-width: 50px;
        border-radius: 50%;
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