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
                $sql = $conn->prepare("SELECT * FROM users");
                $sql -> execute();
                $result = $sql->get_result();
                if ($result && $result->num_rows>0) {
                    $sl = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$sl}</td>";
                        echo "<td>".$row['u_name']."</td>";
                        echo "<td>".$row['u_email']."</td>";
                        echo "<td>".$row['u_contact']."</td>";
                        echo "<td>".$row['u_paddress']."</td>";
                        echo "<td>".$row['u_occ']."</td>";
                        echo "<td>".$row['u_dob']."</td>";
                        echo "<td><img class='user-img' src='../assets/user_images/{$row['u_img']}' alt='User_Image'></td>";
                        echo "</tr>";

                        $sl++;
                    }
                }
                ?>
             </tbody>
         </table>
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
</style>

<?php
    include('includes/footer.php');
?>