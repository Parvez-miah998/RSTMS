<?php
session_start();
include('includes/dbconnection.php');
if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }

date_default_timezone_set('Asia/Dhaka');
if (isset($_POST['pdf_btn'])) {
    if (isset($_SESSION['admin_email'])) {
        $admin_email = $_SESSION['admin_email'];
        $sql_admin = $conn->prepare("SELECT a_name FROM admin WHERE a_email = ?");
        $sql_admin -> bind_param("s", $admin_email);
        $sql_admin -> execute();
        $result_admin = $sql_admin->get_result();
        if ($result_admin->num_rows>0) {
        	$row_admin = $result_admin->fetch_assoc();
        	$admin_name = $row_admin['a_name'];
        }
        else{
        	$admin_name = "Unknown"; 
        }

        $order_id = mysqli_real_escape_string($conn, $_POST['order_id']); // Extract the order ID from the form submission
        $html = '<style>
                    .receipt {
                        border: 1px solid #ccc;
                        padding: 20px;
                        max-width: 400px;
                        margin: 0 auto;
                        font-family: Arial, sans-serif;
                    }
                    .receipt h2, .receipt h4 {
                        text-align: center;
                    }
                    .receipt p {
                        margin: 5px 0;
                    }
                    .receipt .order-details {
                        margin-top: 20px;
                        margin-bottom: 20px;
                    }
                    .receipt .order-details table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .receipt .order-details th, .receipt .order-details td {
                        padding: 8px;
                        border-bottom: 1px solid #ddd;
                    }
                </style>';
        $html .= '<div class="receipt">';
        $html .= '<h2><strong>BIGTree</strong></h2>';
        $html .= '<h4><strong>Bill</strong></h4>';
        $html .= '<p>Date Time: ' . date('Y-m-d (h:i A)') . '</p>';
        $html .= '<p>Bill Prepared By: ' . $admin_name . '</p>';

        $sql = $conn->prepare("SELECT * FROM tbl_payment WHERE order_id = ?");
        $sql->bind_param("s", $order_id); // Bind the order ID parameter
        $sql->execute();
        $result = $sql->get_result();

        if ($result && $result->num_rows > 0) {
            $html .= '<p>Order ID: ' . $order_id . '</p>'; // Display the order ID once
            
            // Initialize arrays to store food item details
            $food_names = array();
            $unit_prices = array();
            $quantities = array();
            $vats = array();
            $total_unit_prices = array();
            $total_amounts = array();
            $order_dates = array();
            $payment_statuses = array();
            $table_names = array();
			$row = $result->fetch_assoc();
			$html .= '<p>Name: ' . (!empty($row['u_name']) ? $row['u_name'] : '') . '</p>';

			// Reset the result pointer back to the beginning
			$result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
			    // Store food item details in arrays
			    $food_names[] = $row['f_title'];
			    $unit_prices[] = $row['f_disctprice'];
			    $quantities[] = $row['o_quantity'];
			    $vats[] = $row['f_vat'];
			    $total_unit_prices[] = $row['total_amount'];
			    $total_amounts[] = $row['amount'];
			    $order_dates[] = $row['o_date'];
			    $payment_statuses[] = $row['payment_status'];
			    $table_names[] = $row['t_name'];

			}


            // Output other details in parallel columns
            
            $html .= '<div class="order-details">';
            $html .= '<table>';
            $html .= '<tr><th>Food Name</th><th>Unit Price</th><th>Quantity</th><th>Total</th></tr>';
            foreach ($food_names as $key => $food_name) {
                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $food_name . '</td>';
                $html .= '<td style="text-align: center;">$' . $unit_prices[$key] . '</td>';
                $html .= '<td style="text-align: center;">' . $quantities[$key] . '</td>';
                $html .= '<td style="text-align: center;">$' . $total_unit_prices[$key] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            $html .= '</div>'; 

            // Other details
            // Sum up the individual item totals
			$total_amount = array_sum($total_unit_prices);
			$html .= '<p><strong>Total Amount:</strong> $' . number_format($total_amount, 2) . '</p>';
            $html .= '<p>Date: ' . $order_dates[0] . '</p>'; 
            $html .= '<p>Payment: ' . $payment_statuses[0] . '</p>'; 
            $html .= '<p>Table: ' . (!empty($table_names[0]) ? $table_names[0] : '') . '</p>'; 

            $html .= '</div>'; 

            // Add other details like total amount, date, etc. if needed
            require_once(__DIR__ . '/vendor/autoload.php');

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($html);
            $mpdf->Output('receipt.pdf', 'I'); // Output as a downloadable file
            exit;
        } else {
            echo "No data found for the provided order ID.";
        }
    }
}
?>
