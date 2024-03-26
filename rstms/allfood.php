<?php
    session_start();
    include('includes/dbconnection.php');
    if (!isset($_SESSION['user'])) {
        header("Location: users/login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KING Restaurant</title>
    <link rel="icon" type="image/x-icon" href="assets/icons/icon1.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <!--This link is responsible for show the icon on mozilaFireFox-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">


    <!--This is responsible for stop resubmission -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>
<body>
<body>
  <div class="container">
    <h2 class="food-menu-heading">All Dishes are here</h2>
    <div class="search-box" style="margin-left: 550px;">
        <form id="searchForm" action="includes/search.php" method="GET" target="_blank">
            <input type="search" id="searchInput" name="search" placeholder="Find your favourite dish">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <?php
    $rowsPerPage = 40;
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $currentPage = $_GET['page'];
    }
    else{
        $currentPage = 1;
    }
    $offset = ($currentPage - 1) * $rowsPerPage;
$sql = "SELECT * FROM tbl_food LIMIT $rowsPerPage OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows>0) {
    $count = 0;
    echo "<div class ='row' style='display: flex;margin-top:20px;margin-bottom:20px;'>";
    while ($row = $result->fetch_assoc()) {
            if ($row['f_featured'] === 'Display') {
            if ($count % 4 === 0 && $count != 0) {
                echo "</div><div class ='row' style='display: flex;margin-top:20px;margin-bottom:20px;'>";
            }
            echo "<div class='col-md-3' style='display:inline-block;margin-left:4%;border:1px solid #ddd;padding:5px;border-radius:10px;'>";
            echo "<div class='food-desc'>";
            echo "<form action='' method='POST' style='display:inline-block;margin-left:40%;'>";
            echo '<input type="hidden" name="food_id" value="' . $row['f_id'] . '">';
            echo '<input type="hidden" name="food_title" value="' . $row['f_title'] . '">';
            echo '<input type="hidden" name="food_disctprice" value="' . $row['f_disctprice'] . '">';
            echo '<input type="hidden" name="food_vat" value="' . $row['f_vat'] . '">';
            echo "<input type='button' value='-' onclick='decrementValue(\"quantity_$count\")' style='border:none;padding:5px;background-color:red;border-radius:4px;' />";
            echo "<input type='text' name='quantity' id='quantity_$count' value='0' style='border:none;width:30px;text-align:center;font-size:16px;' onkeypress='return event.charCode >= 48 && event.charCode <= 57' />";
            echo "<input type='button' value='+' onclick='incrementValue(\"quantity_$count\")' style='border:none;padding:5px;background-color:#39e667;margin-right:30px;border-radius:4px;' />";
            echo "<button type='submit' style='background:transparent; border:none;cursor:pointer;color:#1164d9'><i class='fa-solid fa-cart-shopping'></i></button></form>";
            echo "<form action='quickview.php' method='GET' style='display:inline-block;'>";
            echo "<input type='hidden' name='f_id' value='".$row['f_id']."'>";
            echo "<button style='background:transparent; border:none;cursor:pointer;color:#1164d9' href='quickview.php'><i class='fa-regular fa-eye' style='margin-left: 5px;'></i></button></form>";
            echo "</div>";
            echo "<div class='food-menu-item'>";
            echo "<div class='food-img'>";
            echo "<img src='assets/image/" . $row['f_image'] . "' alt='' style='border-radius:50%;height:100px;width:100px;object-fit:cover;' />";
            echo "</div>";
            echo "<div class='food-description'>";
            echo "<h6 class='food-title'>" . $row['f_title'] . "</h6>";
            $f_price = isset($row['f_price']) ? $row['f_price'] : '';
            $f_disctprice = isset($row['f_disctprice']) ? $row['f_disctprice'] : '';
            if ($f_price != 0) {
                $f_discount = (($f_price - $f_disctprice) / $f_price) * 100;
            } else {
                $f_discount = 0;
            }
            echo "<p class='food-price' style='font-size:18px;color:#06bd27;'>Price: &#36; " . number_format($f_disctprice, 2) . "</p>";
            echo "<div class='discountprice'>"; 
            echo number_format($f_discount, 2) . " &#37;<br> OFF"; 
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            $count++;
        }
    }
    echo "</div>";
        $sqlCount = "SELECT COUNT(*) AS total FROM tbl_food";
        $resultCount = $conn->query($sqlCount);
        $rowCount = $resultCount->fetch_assoc()['total'];
        $totalPages = ceil($rowCount / $rowsPerPage);
        // $totalPages = ceil($result->num_rows/$rowsPerPage);
        echo '<div class="pagination" style="margin-bottom: 15px;">';
        if ($totalPages > 1): 
            echo '<ul>';
            echo '<li><a href="?page=1">&laquo;</a></li>';
            for ($page = 1; $page <= $totalPages; $page++):
                $class = ($page == $currentPage) ? 'class="active"' : '';
                echo '<li ' . $class . '>';
                echo '<a href="?page=' . $page . '">' . $page . '</a>';
                echo '</li>';
            endfor;
            echo '<li><a href="?page=' . $totalPages . '">&raquo;</a></li>';
            echo '</ul>';
        endif;
        echo '</div>';
}else{
    echo "No Found Food Item!";
}

?>
        
            
        </div>
        </div>

        <?php
        $message = '';

           if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               if (isset($_POST['food_id'])) {
                   $food_id = $_POST['food_id'];
                   $food_title = $_POST['food_title'];
                   $food_disctprice = $_POST['food_disctprice'];
                   $food_vat = $_POST['food_vat'];
                   $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; 
                   $quantity = max(1, $quantity);

                   $user_email = $_SESSION['user'];
                   $user_sql = $conn->prepare("SELECT * FROM users WHERE u_email = ?");
                   $user_sql -> bind_param("s", $user_email);
                   $user_sql->execute();
                   $user_result = $user_sql->get_result();

                   if ($user_result && $user_result->num_rows>0) {
                       $user_row = $user_result->fetch_assoc();
                       $user_id = $user_row['u_id'];
                       $user_name = $user_row['u_name'];

                       $existing_sql = $conn->prepare("SELECT * FROM tbl_order WHERE f_title = ? AND u_id = ?");
                       $existing_sql -> bind_param("si", $food_title, $user_id);
                       $existing_sql -> execute();
                       $existing_result = $existing_sql->get_result();

                       if ($existing_result && $existing_result->num_rows>0) {
                           $existing_row = $existing_result->fetch_assoc();
                           $existing_id = $existing_row['o_id'];
                           $existing_quantity = $existing_row['o_quantity'];
                           $new_quantity = $existing_quantity + $quantity;

                           $total_amount = ($food_disctprice * $new_quantity) + ($food_disctprice * $new_quantity * $food_vat / 100);
                           $update_sql = $conn->prepare("UPDATE tbl_order SET o_quantity = ?, total_amount = ? WHERE o_id = ? AND u_id = ?");
                           $update_sql -> bind_param("idii", $new_quantity, $total_amount, $existing_id, $user_id);

                           if ($update_sql->execute()) {
                               $message = "Quantity updated successfully!";
                           }
                           else{
                            $message = "Error updating quantity. Please try again.";
                           }
                       }
                       else{
                            $order_id = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
                            $total_amount = ($food_disctprice * $quantity) + ($food_disctprice * $quantity * $food_vat / 100);
                            $insert_sql = $conn->prepare("INSERT INTO tbl_order (order_id, f_title, f_disctprice, total_amount, o_quantity, f_vat, o_date, u_id, u_name, ta_id, t_name) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, null, null)");
                            $insert_sql -> bind_param("ssddddss", $order_id, $food_title, $food_disctprice, $total_amount, $quantity, $food_vat, $user_id, $user_name);
                            if ($insert_sql->execute()) {
                                $message = "Order placed successfully!";
                            }
                            else{
                                $message = "Error placing order. Please try again.";
                            }
                       }
                   }
                   else{
                     $message = "Error retrieving user data. Please try again.";
                   }
               }
           }
        ?>
        <style type="text/css">
            .discountprice{
               height:70px;
               width: 70px;
               background-color: #12f50a;
               text-align: center;
               position: absolute;
               margin-top: -15px;
               margin-bottom: 0px;
               margin-left: -100px;
               padding: 15px;
               transform: rotate(-15deg);
               transform-origin: top left;
               font-family: Arial;
               font-size: 14px!important;
               border-radius: 50%;
               color: black!important;
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

        <!--Script for control the quantity message end-->
        <script>
            function incrementValue(id) {
                var value = parseInt(document.getElementById(id).value, 10);
                value = isNaN(value) ? 0 : value;
                value++;
                document.getElementById(id).value = value >= 0 ? value : 0;
            }

            function decrementValue(id) {
                var value = parseInt(document.getElementById(id).value, 10);
                value = isNaN(value) ? 0 : value;
                value--;
                document.getElementById(id).value = value >= 0 ? value : 0;
            }

        </script>


        <!--Script for control the quantity message end-->
        <script>
        document.getElementById("searchForm").addEventListener("submit", function(event) {
            var searchInput = document.getElementById("searchInput").value.trim();
            if (searchInput === "") {
                event.preventDefault(); // Cancel form submission
                alert("Please enter your favourite food!");
            }
        });
        </script>


<!--Footer area start-->
    <?php include('includes/footer.php')?>
    <!--Footer area End-->
    