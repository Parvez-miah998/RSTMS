<?php 
  session_start(); 
  include ('includes/dbconnection.php');
  if (!isset($_SESSION['user'])) {
    header('Location: users/login.php');
    exit();
   }
   $message = ''; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KING Restaurant</title>
  <link rel="icon" type="image/x-icon" href="assets/icons/icon1.svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript">
  if (window.history.replaceState) {
    window.history.replaceState(null,null,window.location.href);
  }
</script>
  
</head>
<body>
	<div class="container-fluid">
	    <div class="cart-details">
	        <div class="header">
	            <h2>All the Cart</h2>
                <?php if (!empty($message)) : ?>
                    <p style="color: green;"> <?php echo $message;?></p>
                <?php endif; ?>
	        </div>
	        <table class="all-cart">
	            <thead>
	                <tr>
	                    <th>SL</th>
	                    <th>Product Title</th>
	                    <th>Unit Price</th>
	                    <th>Product Quantity</th>
	                    <th>VAT</th>
	                    <th>Total Price</th>
                        <th>Update Quantity</th>
	                    <th>Action</th>
	                </tr>
	            </thead>
	            <tbody>
                  <?php
                    if (isset($_SESSION['user'])) {
                        $email = $_SESSION['user'];
                        $user_sql = $conn->prepare("SELECT u_id FROM users WHERE u_email = ?");
                        $user_sql->bind_param("s", $email);
                        $user_sql->execute();
                        $user_result = $user_sql->get_result();
                        if ($user_row = $user_result->fetch_assoc()) {
                            $user_id = $user_row['u_id'];
                            $sql = $conn->prepare("SELECT * FROM tbl_order WHERE u_id = ?");
                            $sql->bind_param("i", $user_id);
                            $sql->execute();
                            $result = $sql->get_result();

                            $totalAmount = 0;
                            if ($result && $result->num_rows > 0) {
                                $sl = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr data-order-id='{$row['order_id']}'>";
                                    echo "<td>{$sl}</td>";
                                    echo "<td>{$row['f_title']}</td>";
                                    echo "<td>&#36; {$row['f_disctprice']}</td>";
                                    echo "<td class='quantity'>{$row['o_quantity']}</td>";
                                    echo "<td>{$row['f_vat']} &percnt;</td>";
                                    echo "<td>&#36; {$row['total_amount']}</td>";
                                    echo '<td>
                                            <div class="buttons" style="display: inline-block;">
                                              <button class="increase-btn" style="background: #03fc8c;padding:4px;">+</button>
                                              <button class="decrease-btn" style="background: #fc031c;padding:4px;">-</button>
                                            </div>
                                          </td>';
                                    echo "<td>
                                          <form action='' method='POST'>
                                              <input type='hidden' name='delete' value='{$row['f_title']}' />
                                              <button type='submit' style='background-color: #f74134;' name='btn-delete'>
                                                  <i class='fa-solid fa-xmark'></i>
                                              </button>
                                          </form>
                                      </td>";
                                    echo "</tr>";

                                    $totalAmount += $row['total_amount'];

                                    $sl++;
                                }
                                // Store totalAmount in session after the calculation
                                $_SESSION['totalAmount'] = $totalAmount;
                            } else {
                                $message = "No orders found for this user.";
                            }
                        }
                    } else {
                        header("Location: users/login.php");
                        exit;
                    }
                    ?>

                </tbody>
                <tfoot>
                  <tr>
                    <th style="color: #080807; background-color: #56f52a">Select Your Table</th>

<?php
if (isset($_SESSION['user'])) {
    $email = $_SESSION['user'];
    $usersql = $conn->prepare("SELECT u_id, u_name FROM users WHERE u_email = ?");
    $usersql->bind_param("s", $email);
    $usersql->execute();
    $userresult = $usersql->get_result();

    if ($userresult && $userresult->num_rows > 0) {
        $userrow = $userresult->fetch_assoc();
        $user_id = $userrow['u_id'];
        $user_name = $userrow['u_name'];

        if (isset($_POST['submit'])) {
            $selected_table = $_POST['table'];
            $_SESSION['selected_table'] = $selected_table;

            $fetch_sql = $conn->prepare("SELECT ta_id FROM tbl_table WHERE t_name = ?");
            $fetch_sql->bind_param("s", $selected_table);
            $fetch_sql->execute();
            $fetch_result = $fetch_sql->get_result();
            $fetch_row = $fetch_result->fetch_assoc();
            $ta_id = $fetch_row['ta_id'];

            // Check if the user already has an entry in tbl_order
            $existing_order_sql = $conn->prepare("SELECT * FROM tbl_order WHERE u_id = ?");
            $existing_order_sql->bind_param("i", $user_id);
            $existing_order_sql->execute();
            $existing_order_result = $existing_order_sql->get_result();

            if ($existing_order_result && $existing_order_result->num_rows > 0) {
                // If user already has an order, update the existing row
                $update_order_sql = $conn->prepare("UPDATE tbl_order SET t_name = ?, ta_id = ? WHERE u_id = ?");
                $update_order_sql->bind_param("ssi", $selected_table, $ta_id, $user_id);
                if (!$update_order_sql->execute()) {
                    echo "Error: " . $update_order_sql->error;
                } else {
                    // Set the status of the selected table to 'Disable'
                    $update_table_sql = $conn->prepare("UPDATE tbl_table SET t_status = 'Disable' WHERE t_name = ?");
                    $update_table_sql->bind_param("s", $selected_table);
                    if (!$update_table_sql->execute()) {
                        echo "Error: " . $update_table_sql->error;
                    }
                }
            } else {
                // If user does not have an existing order, insert a new row
                $insert_order_sql = $conn->prepare("INSERT INTO tbl_order (u_id, u_name, t_name, ta_id) VALUES (?, ?, ?, ?)");
                $insert_order_sql->bind_param("isss", $user_id, $user_name, $selected_table, $ta_id);
                if (!$insert_order_sql->execute()) {
                    echo "Error: " . $insert_order_sql->error;
                } else {
                    $update_table_sql = $conn->prepare("UPDATE tbl_table SET t_status = 'Disable' WHERE t_name = ?");
                    $update_table_sql->bind_param("s", $selected_table);
                    if (!$update_table_sql->execute()) {
                        echo "Error: " . $update_table_sql->error;
                    }
                }
            }
            $_SESSION['table_selected'] = true;
        }

        $tblb_sql = $conn->prepare("SELECT COUNT(*) AS count FROM tbl_table WHERE u_name = ? AND t_category = 'Booked'");
        $tblb_sql->bind_param("s", $user_name);
        $tblb_sql->execute();
        $tblb_result = $tblb_sql->get_result();
        $tblb_row = $tblb_result->fetch_assoc();
        $tblb = $tblb_row['count'];
        if ($tblb > 0) {
            $tbl_sql = $conn->prepare("SELECT * FROM tbl_table WHERE u_name = ? AND t_category = 'Booked'");
            $tbl_sql->bind_param("s", $user_name);
        } else {
            $tbl_sql = $conn->prepare("SELECT * FROM tbl_table WHERE (u_name IS NULL OR t_category = 'Regular') AND t_status != 'Disable'");
        }

        $tbl_sql->execute();
        $tbl_result = $tbl_sql->get_result();

        if ($tbl_result && $tbl_result->num_rows > 0) {
            echo "<td>";
            echo "<form method='POST' id='tableForm'>";
            $tableOptions = "";
            $userHasSelectedTable = false;
            
            $selected_table = isset($_POST['table']) ? $_POST['table'] : '';

            if (!empty($selected_table)) {
                $userHasSelectedTable = true;
            }

            $fetch_order_sql = $conn->prepare("SELECT SUM(o_quantity) as o_quantity FROM tbl_order WHERE u_id = ? AND o_quantity > 0");
            $fetch_order_sql -> bind_param("s", $user_id);
            $fetch_order_sql -> execute();
            $fetch_order_result = $fetch_order_sql->get_result();
            $has_food = $fetch_order_result->fetch_assoc()['o_quantity'] > 0;

            if ($has_food) {
                // Check if a table has been selected
                $userHasSelectedTable = isset($_SESSION['selected_table']);

                // Display table selection field and selected table message accordingly
                echo "<form method='post'>";
                echo "<select name='table' id='table' onchange='submitForm()' " . ($userHasSelectedTable ? "style='display:none;'" : "") . ">";
                while ($row = $tbl_result->fetch_assoc()) {
                    if (!$userHasSelectedTable && $row['t_status'] == 'Enable') {
                        $selected = ""; 
                        echo "<option value='" . $row['t_name'] . "' $selected>" . $row['t_name'] . "</option>";
                    } elseif ($userHasSelectedTable && $row['t_name'] == $_SESSION['selected_table']) {
                        $selected = "selected";
                        echo "<option value='" . $row['t_name'] . "' $selected>" . $row['t_name'] . "</option>";
                    }
                }
                echo "</select>";

                if (!$userHasSelectedTable) {
                    echo "<button type='submit' name='submit' id='submitButton' style='margin-top: 10px; margin-left: 5px; padding: 2px;'>Submit</button>";
                }

                echo "</form>";
                if (!empty($_SESSION['selected_table']) || $userHasSelectedTable) {
                    echo "<p id='selectedTableMessage'>Selected Table: {$_SESSION['selected_table']}</p>";
                }

            }
            else {
                // No food in order, hide the table selection field
                echo "<p style='padding-left: 20px;text-align:center;color:#32ed86;'>Please select your food first  &#128512;!</p>";
            }
            echo "</td>";
        } else {
            echo "<p style='padding-left: 20px;text-align:center;color:green;'>No tables found for the user.</p>";
        }
    } else {
        echo "<p style='padding-left: 20px;text-align:center;color:green;'>User Not Found!</p>";
    }
} else {
    echo "User Not Logged In!";
}
?>

                    <!--Total Amount-->           
                    <th>Total Amount</th>
                    <?php
                        echo "<td> &#36; ".number_format($totalAmount, 2)."</td>";
                    ?>
                  </tr>                    
                </tfoot>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-delete'])) {
                    $food_title = $_POST['delete'];
                    $del_sql = $conn->prepare("DELETE FROM tbl_order WHERE f_title = ?");
                    $del_sql -> bind_param("s", $food_title);
                    if ($del_sql->execute()) {
                        $message = "Food item deleted successfully!";
                    }
                    else {
                        header('HTTP/1.1 500 Internal Server Error');
                        exit("Error deleting food item.");
                    }
                }
                ?>
	        </table>
            <!--Code for Confirm button start-->
            <?php
            if (isset($_SESSION['user'])) {
                $email = $_SESSION['user'];
                $user_sql = $conn->prepare("SELECT u_id FROM users WHERE u_email = ?");
                $user_sql -> bind_param("s", $email);
                $user_sql -> execute();
                $user_result = $user_sql->get_result();
                if ($user_row = $user_result->fetch_assoc()) {
                    $user_id = $user_row['u_id'];
                    $table_sql = $conn->prepare("SELECT COUNT(*) AS count FROM tbl_order WHERE u_id = ? AND ta_id IS NOT NULL AND t_name IS NOT NULL");
                    $table_sql -> bind_param("i", $user_id);
                    $table_sql->execute();
                    $table_result = $table_sql->get_result();
                    $table_count = $table_result->fetch_assoc()['count'];
                    if (($table_count > 0 || isset($_SESSION['selected_table'])) && !empty($_SESSION['selected_table']) && $totalAmount > 0) {
                        echo "<div class='btn-order'>";
                        echo "<form action='checkout.php' method='POST'>";
                        echo "<input type='hidden' name='table' value='" . (isset($_POST['table']) ? $_POST['table'] : '') . "'>";
                        echo "<button type='submit' name='order_confirm'>Confirm Order</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    else{
                        echo "<p style='text-align: center; color:#080807;font-family:Arial Rounded MT Bold;font-size:19px;font-weight:bolder;'>Please select your table or food &#128512;!</p>";
                    }
                }
            }
            ?>
            <!--Code for Confirm button end-->
	    </div>
        <!--Cart area End-->
	</div>
    


	<style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container-fluid{
        	margin-bottom: 60px;
        }
        /* Styles for the cart tables */
        .cart-details,
        .action-cart {
/*            background-color: #fff;*/
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        .header {
            text-align: center;
            font-family: sans-serif;
            color: #098aed;
            padding: 10px 0;
            background-color: #f2f2f2;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        table {
            width: 1200px;
            border-collapse: collapse;
            margin: auto;
            margin-bottom: 40px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        .payment-table {
            margin: 0 auto;
            max-width: 250px;
        }

        .payment-table td:first-child {
            text-align: right;
        }

        .payment-table td:last-child {
            text-align: center;
        }

        button {
            padding: 8px 16px;
            border: none;
            background-color: #098aed;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #065aa0;
        }
        .btn-order button{
            background-color: #03fcd7;
            margin-left: 47%;
            border-radius: 10px;
        }
        .btn-order button:hover{
            background-color: #5cf7e0;
        }
        
        
    </style>

    <script>
        $(document).ready(function() {
    $('.increase-btn').click(function() {
      var row = $(this).closest('tr');
      var orderId = row.data('order-id');
      var quantityElement = row.find('.quantity');
      var currentValue = parseInt(quantityElement.text()) || 0;
      currentValue++;
      quantityElement.text(currentValue);
      updateQuantity(orderId, currentValue, row);
    });

    $('.decrease-btn').click(function() {
      var row = $(this).closest('tr');
      var orderId = row.data('order-id');
      var quantityElement = row.find('.quantity');
      var currentValue = parseInt(quantityElement.text()) || 0;
      currentValue = Math.max(0, currentValue - 1);
      quantityElement.text(currentValue);
      updateQuantity(orderId, currentValue, row);
    });

    function updateQuantity(orderId, quantity, row) {
      $.ajax({
        url: 'cart.php',
        method: 'POST',
        data: {
          orderId: orderId,
          quantity: quantity
        },
        success: function(response) {
          if (response !== '') {
            // Update total amount in the row
            var totalAmountElement = row.find('.total-amount');
            totalAmountElement.text(response);

            // Update total amount fields
            updateTotalAmountFields();
          } else {
            // Handle error response
            console.error("Error updating quantity and total amount.");
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    }

    function updateTotalAmountFields() {
      // Calculate total amount from all rows
      var totalAmount = 0;
      $('.total-amount').each(function() {
        totalAmount += parseFloat($(this).text());
      });

      // Update total amount fields
      $('.total-amount-display').text('$' + totalAmount.toFixed(2));
    }
  });
    // Function to hide the button and show the message if a table has been selected
    // function adjustDisplay() {
    //     var selectedTable = "<?php echo isset($_POST['table']) ? $_POST['table'] : ''; ?>";
    //     if (selectedTable !== '') {
    //         document.getElementById("submitButton").style.display = "none";
    //         document.getElementById("table").style.display = "none";
    //         document.getElementById("selectedTableMessage").style.display = "block";
    //     }
    // }
    
    // // Call the function when the page loads
    // window.onload = adjustDisplay;
    
    // // Function to submit the form
    // function submitForm() {
    //     document.getElementById("tableForm").submit();
    // }
</script>


</body>

</html>