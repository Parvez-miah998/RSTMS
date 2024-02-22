<?php
include('includes/dbconnection.php');

if (!isset($_SESSION['user'])) {
    header('Location: users/login.php');
    exit();
   }
?>


        <h2 class="food-menu-heading">Food Menu</h2>
        <div class="search-box" style="margin-left: 550px;margin-bottom: 10px;">
            <form action="includes/search.php" method="GET" target="_blank">
                <input type="search" id="searchInput" name="search" placeholder="Find your favourite dish">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <?php
$sql = "SELECT * FROM tbl_food";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows>0) {
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        if ($row['f_featured'] === 'Display') {
            if ($count % 2 === 0) {
                echo "<div class ='row' style='display: flex;margin-top:20px;margin-bottom:20px;'>";
            }
            echo "<div class='col-md-6' style='display:inline-block;width:40%;margin-left:4%;border:1px solid #ddd;padding:10px;border-radius:10px;'>";
            echo "<div class='food-desc'>";
            echo "<form action='' method='POST' style='display:inline-block;margin-left:70%;'>";
            echo '<input type="hidden" name="food_id" value="' . $row['f_id'] . '">';
            echo '<input type="hidden" name="food_title" value="' . $row['f_title'] . '">';
            echo '<input type="hidden" name="food_disctprice" value="' . $row['f_disctprice'] . '">';
            echo '<input type="hidden" name="food_vat" value="' . $row['f_vat'] . '">';
            // echo "<input type='number' name='quantity' value='0' min='1' style='border:none;width:30px;'>";
            echo "<input type='button' value='-' onclick='decrementValue(\"quantity_$count\")' style='border:none;padding:5px;background-color:red;border-radius:4px;' />";
            echo "<input type='text' name='quantity' id='quantity_$count' value='0' style='border:none;width:30px;text-align:center;font-size:16px;' onkeypress='return event.charCode >= 48 && event.charCode <= 57' />";
            echo "<input type='button' value='+' onclick='incrementValue(\"quantity_$count\")' style='border:none;padding:5px;background-color:#39e667;margin-right:30px;border-radius:4px;' />"; 
            echo "<button type='submit' style='background:transparent; border:none;cursor:pointer;color:#1164d9'><i class='fa-solid fa-cart-shopping'></i></button></form>";

            echo " <form action='quickview.php' method='GET' style='display:inline-block;'>";
            echo " <input type='hidden' name='f_id' value='".$row['f_id']."'>";
            echo " <button style='background:transparent; border:none;cursor:pointer;color:#1164d9' href='quickview.php'><i class='fa-regular fa-eye' style='margin-left: 5px;'></i></button></form>";
            echo " </div>";
            echo "<div class='food-menu-item'>";
            echo "<div class='food-img'>";
            echo "<img src='assets/image/" . $row['f_image'] . "' alt='' style='border-radius:50%;' />";
            echo "</div>";
            echo "<div class='food-description'>";
            echo "<h3 class='food-title'>" . $row['f_title'] . "</h3>";
            $truncatedDesc = substr($row['f_desc'], 0, 100);
            echo "<p class='truncatedDesc' style='font-size:14px;'>".$truncatedDesc."<span class='see-more'style='color:#3dd961;'>...</span></p>";
            // echo "<p>" . $row['f_desc'] . "</p>";
            echo "<p class='food-price' style='font-size:18px;color:#32fa57;'>Price: <del>&#36; " . $row['f_price'] . "</del></p>";
            echo "<p class='food-price' style='font-size:18px;color:#06bd27;'>Discount Price: &#36; " . $row['f_disctprice'] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

            if ($count % 2 !== 0 || $count === $result->num_rows - 1) {
                echo "</div>";
            }
            $count++;
        }

    }

}else{
    echo "No Found Food Item!";
}

?>
        <div class="button" style="margin-top: 10px;padding: 0px;">
            <button type="submit" style="margin-left: 560px;margin-top: 0px; padding: 10px; border-radius: 20px;background: #57ded7;font-size: 18px;border: none;"><a href="allfood.php" style="text-decoration: none;font-weight: bolder;">See All</a></button>
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
        