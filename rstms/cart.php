<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'];
    $quantity = $_POST['quantity'];

    // Get the current product details
    $selectSql = $conn->prepare("SELECT f_disctprice, f_vat FROM tbl_order WHERE order_id = ?");
    $selectSql->bind_param("i", $orderId);
    $selectSql->execute();
    $result = $selectSql->get_result();

    if ($row = $result->fetch_assoc()) {
        $productPrice = $row['f_disctprice'];
        $vat = $row['f_vat'];

        // Calculate new total amount including VAT
        $totalAmount = ($productPrice * $quantity) + ($productPrice * $quantity * $vat / 100);

        // Update quantity and total amount in the database
        $updateSql = $conn->prepare("UPDATE tbl_order SET o_quantity = ?, total_amount = ? WHERE order_id = ?");
        $updateSql->bind_param("idi", $quantity, $totalAmount, $orderId);

        if ($updateSql->execute()) {
            if ($quantity == 0) {
                // Delete row if quantity becomes 0
                $deleteSql = $conn->prepare("DELETE FROM tbl_order WHERE order_id = ?");
                $deleteSql->bind_param("i", $orderId);
                $deleteSql->execute();
            }
            echo $totalAmount; // Return the new total amount for updating in the UI
        } else {
            http_response_code(500);
            echo "Error updating quantity and total amount";
        }
    } else {
        http_response_code(404);
        echo "Product not found";
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?>
