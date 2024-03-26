<?php 
  session_start(); 
  include ('includes/dbconnection.php');
  if (!isset($_SESSION['user'])) {
    header('Location: users/login.php');
    exit();
   } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KING Restaurant</title>
  <link rel="icon" type="image/x-icon" href="assets/icons/icon1.svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- This links for feedback star statrt-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- This links for feedback star end -->
  <script type="text/javascript">
    if (window.history.replaceState) {
      window.history.replaceState(null,null, window.location.href);
    }
  </script>
</head>
<body>
  <div class="container">
    <div class="food-menu-container container">
      <?php
      $message = '';

      // Check if the rating form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_rating'])) {
  if (isset($_POST['form_type'])) {
    // Sanitize and validate input data
    $f_id = $_POST['food_id'];
    $rating = $_POST['rating'];
    $f_content = htmlspecialchars($_POST['f_content']); // Sanitize user input
    $user_email = $_SESSION['user'];

    // Get user ID from the session
    $user_query = $conn->prepare("SELECT u_id FROM users WHERE u_email = ?");
    $user_query->bind_param("s", $user_email);
    $user_query->execute();
    $user_result = $user_query->get_result();

    if ($user_result && $user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['u_id'];

        // Insert rating into the database
        $insert_rating_query = $conn->prepare("INSERT INTO f_rating (f_id, u_id, fd_rate, rating_desc) VALUES (?, ?, ?, ?)");
        $insert_rating_query->bind_param("iiis", $f_id, $user_id, $rating, $f_content);
        
        // Execute the query
        if ($insert_rating_query->execute()) {
            // Rating submitted successfully
            $message = "Rating submitted successfully!";
        } else {
            // Error while submitting rating
            $message = "Error submitting rating. Please try again.";
        }
    } else {
        // Error retrieving user data
        $message = "Error retrieving user data. Please try again.";
    }
  }
}

      if (isset($_GET['f_id'])) {
        $f_id = $_GET['f_id'];
        $sql = $conn->prepare("SELECT * FROM tbl_food WHERE f_id = ?");
        $sql -> bind_param("i", $f_id);
        $sql->execute();
        $result = $sql->get_result();
        if ($result && $result->num_rows>0) {
          $row = $result->fetch_assoc();
        }
        else{
          $message = "No data Found!";
        }
      }
      ?>
      <div class="food-menu-item">
        <div class="food-box" style="margin: 10px;">
          <div class="food-img">
            <img src="<?php echo isset($row['f_image']) ? 'assets/image/'.$row['f_image'] : ''; ?>" alt="" />
          </div>
          <div class="food-description">
            <h2 class="food-title"><?php echo isset($row['f_title']) ? $row['f_title'] : ''; ?></h2>
            <p><?php echo isset($row['f_desc']) ? $row['f_desc'] : ''; ?> </p>
            <?php
            $f_price = isset($row['f_price']) ? $row['f_price'] : '';
            $f_disctprice = isset($row['f_disctprice']) ? $row['f_disctprice'] : '';
            if ($f_price != 0) {
              $f_discount = (($f_price - $f_disctprice) / $f_price) * 100;
            }
            else{
              $f_discount = 0;
            }
            ?>
            <p class="food-price"><strong>Price:</strong> &#36;<del> <?php echo $f_price; ?></del></p>
            <p class="food-price"><strong>Discount Price:</strong> &#36; <?php echo $f_disctprice; ?></p>
            <p class="discountprice"><strong>Discount:</strong><br> <?php echo number_format($f_discount,2) ?> &#37; OFF</p>
            <!-- <p class="food-price"><strong>Price:</strong> &#36;<del> <?php echo isset($row['f_price']) ? $row['f_price'] : ''; ?></del></p>
            <p class="food-price"><strong>Discount Price:</strong> &#36; <?php echo isset($row['f_disctprice']) ? $row['f_disctprice'] : ''; ?></p> -->
            <!-- <p class="food-price">Up to: 30 &percnt;</p> -->
            <?php
            // Assuming $db is your database connection
            $food_id = isset($row['f_id']) ? $row['f_id'] : '';
            $query = "SELECT SUM(fd_rate) AS total_rating, COUNT(fd_rate) AS num_ratings FROM f_rating WHERE f_id = $food_id";
            $result = mysqli_query($conn, $query);
            $row_rating = mysqli_fetch_assoc($result);
            $total_rating = $row_rating['total_rating'];
            $num_ratings = $row_rating['num_ratings'];
            $average_rating = $num_ratings != 0 ? round($total_rating / $num_ratings, 1) : 0;
            $sql_rate = $conn->prepare("SELECT f.rating_desc, u.u_name FROM f_rating f INNER JOIN users u ON f.u_id = u.u_id WHERE f.f_id = $food_id ORDER BY fr_id DESC");
            $sql_rate -> execute();
            $result_rate = $sql_rate->get_result();
            $all_rating = array();
            while ($row_rate = $result_rate->fetch_assoc()) {
              $all_rating[] = $row_rate;
            }
            ?>
            <p class="food-price" style="margin-bottom: 5px;"><strong>Rating:</strong></p>
            <div id="avg_rating" data-avg_rating="<?php echo $average_rating; ?>" style="margin-left: 37%;margin-bottom: 15px;"></div>
            <h6 class="food-price">Additional vat: <?php echo isset($row['f_vat']) ? $row['f_vat'] : ''; ?> &percnt;</h6>
            <div class="food-buttons">
              <div class="food-desc">
                <form action='' method='POST' style='display:inline-block;margin-left:0px;margin-right: 5px;'>
                  <input type='hidden' name='food_id' id='food_id' value="<?php echo isset($row['f_id']) ? $row['f_id'] : ''; ?>">
                  <input type='hidden' name='food_title' id='food_title' value="<?php echo isset($row['f_title']) ? $row['f_title'] : ''; ?>">
                  <input type='hidden' name='food_price' id='food_price' value="<?php echo isset($row['f_disctprice']) ? $row['f_disctprice'] : ''; ?>">
                  <input type='hidden' name='food_vat' id='food_vat' value="<?php echo isset($row['f_vat']) ? $row['f_vat'] : ''; ?>">
                  <input type='hidden' name='quantity' id='quantity' value="0">
                  

                  <button type="submit" name="add_to_cart" style='background:transparent; border:none;cursor:pointer;color:#1164d9' href='#'>
                    <i class='fa-solid fa-cart-shopping'></i>
                  </button>
                </form>
              </div>
              <div class="product-box">
                <div class="quantity-box">
                  <span class="quantity">0</span>
                  <div class="buttons">
                    <button class="increase-btn" style="background: #03fc8c;">+</button>
                    <button class="decrease-btn" style="background: #fc031c;">-</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="rating">
            <form action="" method="POST">
              <input type="hidden" name="form_type" value="rating_form">
              <input type='hidden' name='food_id' id='food_id' value="<?php echo isset($row['f_id']) ? $row['f_id'] : ''; ?>">
              <input type="hidden" name="food_title" value="<?php echo isset($row['f_title']) ? $row['f_title'] : ''; ?>">
              <input type="hidden" name="food_price" value="<?php echo isset($row['f_disctprice']) ? $row['f_disctprice'] : ''; ?>">
              <input type="hidden" name="food_vat" value="<?php echo isset($row['f_vat']) ? $row['f_vat'] : ''; ?>">
              <div class="rate_desc">
                <div id="rating"></div>
                <!-- Hidden input to store the selected rating -->
                <input type="hidden" name="rating" id="rating_input">
              </div>
              <div class="rate_desc">
                <textarea class="form-control" id="f_content" name="f_content" rows="2" placeholder="What's on your mind!" required></textarea>
                <button type="submit" name="submit_rating" class="btn btn-secondary" style=""><i class="fa-solid fa-arrow-up"></i></button>
              </div>
            </form>
          </div>     
      </div>
        <?php foreach ($all_rating as $rating) : ?>
          <div class="cust_com">
          <div class="user-name"><?php echo $rating['u_name']; ?></div>
          <p class="review-text">&#x1F449; <?php echo $rating['rating_desc']; ?></p>
          </div>
        <?php endforeach ; ?>
    </div>
  </div>
</div>

  <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['food_id']) && isset($_POST['add_to_cart'])) {
        // Get data from the form
        $food_id = $_POST['food_id'];
        $food_title = $_POST['food_title'];
        $food_price = $_POST['food_price'];
        $food_vat = $_POST['food_vat'];
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; 
        $quantity = max(1, $quantity);

        // Get user data from the session
        $user_email = $_SESSION['user'];
        $user_query = $conn->prepare("SELECT u_id, u_name FROM users WHERE u_email = ?");
        $user_query->bind_param("s", $user_email);
        $user_query->execute();
        $user_result = $user_query->get_result();

        if ($user_result && $user_result->num_rows > 0) {
            $user_row = $user_result->fetch_assoc();
            $user_id = $user_row['u_id'];
            $user_name = $user_row['u_name'];

            // Check if the same food has already been ordered by the user
            $existing_sql = $conn->prepare("SELECT * FROM tbl_order WHERE f_title = ? AND u_id = ?");
            $existing_sql->bind_param("si", $food_title, $user_id);
            $existing_sql->execute();
            $existing_result = $existing_sql->get_result();

            if ($existing_result && $existing_result->num_rows > 0) {
                // If existing order found, update the quantity
                $existing_row = $existing_result->fetch_assoc();
                $existing_id = $existing_row['o_id'];
                $existing_quantity = $existing_row['o_quantity'];
                $new_quantity = $existing_quantity + $quantity;

                $total_amount = ($food_price * $new_quantity) + ($food_price * $new_quantity * $food_vat / 100);

                $update_order_sql = $conn->prepare("UPDATE tbl_order SET o_quantity = ?, total_amount = ? WHERE o_id = ? AND u_id = ?");
                $update_order_sql->bind_param("idii", $new_quantity, $total_amount, $existing_id, $user_id);

                if ($update_order_sql->execute()) {
                    $message = "Quantity updated successfully!";
                } else {
                    $message = "Error updating quantity. Please try again.";
                }
            } else {
                // If no existing order found, insert a new order
                $order_id = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
                $total_amount = ($food_price * $quantity) + ($food_price * $quantity * $food_vat / 100);

                $insert_order_query = $conn->prepare("INSERT INTO tbl_order (order_id, f_title, f_disctprice, total_amount, o_quantity, f_vat, o_date, u_id, u_name, ta_id, t_name) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, NULL, NULL)");
                $insert_order_query->bind_param("ssddddss", $order_id, $food_title, $food_price, $total_amount, $quantity, $food_vat, $user_id, $user_name);
                if ($insert_order_query->execute()) {
                    $message = "Order placed successfully!";
                } else {
                    $message = "Error placing order. Please try again.";
                }
            }
        } else {
            $message = "Error retrieving user data. Please try again.";
        }
    }
}

?>

  <style>
    body{
      background-color: #b5fff6;
      font-family: Arial;
    }
    .container {
      text-align: center;
      margin-top: 20px;
    }

    .food-menu-heading {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .food-menu-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
    }

    .food-menu-item {
      width: 40%; /* Adjust as needed */
      margin: 10px;
    }

    .food-box {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 5px;
      margin: 10px;
    }

    .food-img img {
      width: 100%;
      height: 350px;
      border-radius: 8px;
    }

    .food-title {
      font-size: 24px;
      margin-top: 10px;
    }

    .food-price {
      font-weight: bold;
      margin-top: 5px;
    }

    .food-buttons {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 10px;
    }

    .food-desc,
    .product-box {
      margin: 0 5px; 
    }
    .quantity-box {
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 5px;
      margin-left: 10px;
    }
    .quantity {
      font-size: 18px;
      font-weight: bold;
      margin-right: 5px;
    }
    .buttons{
    	display: inline-block;
    }
    .buttons button {
      margin: 0px;
      border: none;
      border-radius: 4px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      padding: 5px 10px;
    }

    /* Additional styles for the buttons */
    .increase-btn {
      background: #03fc8c;
    }

    .decrease-btn {
      background: #fc031c;
    }

    .buttons button:hover {
      background-color: #f0f0f0;
    }
    .discountprice{
       height:auto;
       width: auto;
       background-color: #37f230;
       text-align: center;
       position: absolute;
       margin-top: -260px;
       margin-left: 5px;
       padding: 15px;
       transform: rotate(-15deg);
       transform-origin: top left;
       font-family: Arial;
       font-size: 28px;
       border-radius: 50%;
    }
    .rating {
      margin-top: 10px;
    }
    .rate_desc #rating{
      margin-left: 190px;
    }
    .form-control {
      width: 60%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 30px;
      resize: none;
      background: transparent;
      margin-top: 10px;
    }
    .rate_desc .btn-secondary{
      background: none; 
      border: none;
      position: absolute;
      margin-top: 15px;
    }
    .fa-arrow-up {
      font-size: 20px;
      padding: 10px;
      border: none;
      background: #65c7f7;
      border-radius: 50%;
      cursor: pointer;
    }
    .cust_com {
        margin-bottom: 10px;
        border-bottom: 1px solid #ccc;
        padding: 10px;
        width: auto;
        height: auto;
        text-align: left;
        background-color: #b8fccf;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .cust_com p{
      margin-top: 0px;
      font-size: 12px;
    }
    .user-name {
      margin-bottom: 4px;
      color: #666;
      font-size: 16px;
      font-weight: bolder;
    }
  </style>

  
  <script>
    const quantitySpan = document.querySelector('.quantity');
    const increaseBtn = document.querySelector('.increase-btn');
    const decreaseBtn = document.querySelector('.decrease-btn');
    let quantityValue = 0;

    increaseBtn.onclick = function() {
        quantityValue++;
        updateQuantity();
    };

    decreaseBtn.onclick = function() {
        if (quantityValue > 0) {
            quantityValue--;
            updateQuantity();
        }
    };

    function updateQuantity() {
        quantitySpan.textContent = quantityValue;
        // Ensure that the hidden input value is not negative
        document.getElementById('quantity').value = Math.max(0, quantityValue);
    }
</script>
<script>
    $(function () {
        $("#rating").rateYo({
            starWidth: "25px",
            rating: 0,
            fullStar: true,
            onSet: function (rating, rateYoInstance) {
                // Store the selected rating in a hidden input field
                $("#rating_input").val(rating);
            }
        });
    });

    $(function () {
    // Get the average rating from the data attribute
    var averageRating = parseFloat($('#avg_rating').data('avg_rating'));

    $("#avg_rating").rateYo({
        starWidth: "25px",
        rating: averageRating, // Set the initial rating to the average rating
        readOnly: true, // Make the stars read-only since it's for display purpose only
        fullStar: true
    });
});
</script>


</body>
</html>
