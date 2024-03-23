<?php
session_start();
include('includes/dbconnection.php');
require_once('phpqrcode/qrlib.php');

// Set the default timezone to Dhaka
date_default_timezone_set('Asia/Dhaka');

if (isset($_POST['sale_pdf_btn'])) {
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
        $html = '
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f2f2f2;
                }
                h2, h4, p{
                    text-align: center;
                    color: #333;
                    font-family: Arial;
                    font-weight: bolder;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #8bfcf9;
                }
                .footer {
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                    text-align: right; /* Align right */
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                    font-size: 10px;
                }
            </style>';

        $html .= '<h2><strong>BIGTree</strong></h2>';
        $html .= '<h4><strong>Sales Report</strong></h4>';
        $html .= '<p><strong>Date and Time (Dhaka Time): </strong>' . date('Y-m-d (h:i A)') . '</p>';
        // $html .= '<p><strong>Logged-in User: </strong>' . $admin_email . '</p>';
        $html .= '<p><strong>User Name: </strong>' . $admin_name . '</p>';
        if (isset($_SESSION['from_date']) && isset($_SESSION['to_date']) && isset($_SESSION['total_sale'])) {
            $html .= '<p><strong>From Date: </strong>'.$_SESSION['from_date'].'</p>';
            $html .= '<p><strong>To Date: </strong>'.$_SESSION['to_date'].'</p>';
            $html .= '<p><strong>Total Amount: </strong> $'.$_SESSION['total_sale'].'</p>';
        }
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Order ID</th>
                    <th>Name</th>
                    <th>Food Name</th>
                    <th>Unit Price &#36;</th>
                    <th>Quantity</th>
                    <th>VAT &#37;</th>
                    <th>Total Unit Price &#36;</th>
                    <th>Total Amount &#36;</th>
                    <th>Order Date</th>
                    <th>Payment Status</th>
                    <th>Table Name</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $from_date = $_SESSION['from_date'];
        $to_date = $_SESSION['to_date'];
        $sql_spdf = $conn->prepare("SELECT * FROM tbl_payment WHERE o_date BETWEEN ? AND ?");
        $sql_spdf->bind_param("ss", $from_date, $to_date);
        $sql_spdf->execute();
        $result_spdf = $sql_spdf->get_result();
        if ($result_spdf && $result_spdf->num_rows>0) {
            while ($row_pdf = $result_spdf->fetch_assoc()) {
                $html .= '<tr>';
                $html .= '<td>'.$row_pdf['order_id'].'</td>';
                $html .= '<td>'.$row_pdf['u_name'].'</td>';
                $html .= '<td>'.$row_pdf['f_title'].'</td>';
                $html .= '<td>'.$row_pdf['f_disctprice'].'</td>';
                $html .= '<td>'.$row_pdf['o_quantity'].'</td>';
                $html .= '<td>'.$row_pdf['f_vat'].'</td>';
                $html .= '<td>'.$row_pdf['total_amount'].'</td>';
                $html .= '<td>'.$row_pdf['amount'].'</td>';
                $html .= '<td>'.$row_pdf['o_date'].'</td>';
                $html .= '<td>' . ($row_pdf['payment_status'] ? $row_pdf['payment_status'] : '<span style="color:red;">Customer Does not Payment Yet!</span>') . '</td>';
                $html .= '<td>'.$row_pdf['t_name'].'</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '<div class="footer">Page {PAGENO} of {nb}</div>';

            require_once(__DIR__.'/vendor/autoload.php');

            // Include mPDF library
            require_once(__DIR__ . '/vendor/autoload.php');

            // Create mPDF object
            $mpdf = new \Mpdf\Mpdf();

            // Write HTML content to PDF
            $mpdf->WriteHTML($html);

            // Output PDF
            $mpdf->Output('sales.pdf', 'I');
            exit; // Exit to prevent further execution
        } else {
            header('Location: ../users/login.php');
            exit();
        }
    } else {
        echo "Form Not Submitted";
    }
}
// Code for sales pdf end

// Code for cost pdf start
if (isset($_POST['cost_pdf_btn'])) {
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

        // Get the date range from session
        $from_date = $_SESSION['from_date'];
        $to_date = $_SESSION['to_date'];

        // Fetch data from the database within the date range
        $sql_pdf = $conn->prepare("SELECT ac_desc, ac_amount, a_email, date FROM account WHERE date BETWEEN ? AND ?");
        $sql_pdf->bind_param("ss", $from_date, $to_date);
        $sql_pdf->execute();
        $result_pdf = $sql_pdf->get_result();

        // Start HTML content
        $html = '<style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f2f2f2;
                    }
                    h2, h4, p{
                        text-align: center;
                        color: #333;
                        font-family: Arial;
                        font-weight: bolder;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    th, td {
                        padding: 8px;
                        text-align: center;
                        border-bottom: 1px solid #ddd;
                    }
                    th {
                        background-color: #8bfcf9;
                    }
                    .footer {
		                position: fixed;
		                bottom: 0;
		                width: 100%;
		                text-align: right; /* Align right */
		                border-top: 1px solid #ddd;
		                padding-top: 10px;
		                font-size: 10px;
		            }
                </style>';
        $html .= '<h2><strong>BIGTree</strong></h2>';
        $html .= '<h4><strong>Cost Report</strong></h4>';
        $html .= '<p><strong>Date and Time (Dhaka Time): </strong>' . date('Y-m-d (h:i A)') . '</p>';
        // $html .= '<p><strong>Logged-in User: </strong>' . $admin_email . '</p>';
        $html .= '<p><strong>User Name: </strong>' . $admin_name . '</p>';

        // Add from date, to date, and total cost amount
        if (isset($_SESSION['from_date']) && isset($_SESSION['to_date']) && isset($_SESSION['total_cost'])) {
            $html .= '<p><strong>From Date:</strong> ' . $_SESSION['from_date'] . '</p>';
            $html .= '<p><strong>To Date: </strong>' . $_SESSION['to_date'] . '</p>';
            $html .= '<p><strong>Total Cost:</strong> $' . $_SESSION['total_cost'] . '</p>';
        }

        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Cost Description</th>';
        $html .= '<th>Cost Amount</th>';
        $html .= '<th>Email</th>';
        $html .= '<th>Date</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Loop through the results and add them to HTML
        while ($row = $result_pdf->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . $row['ac_desc'] . '</td>';
            $html .= '<td>' . $row['ac_amount'] . '</td>';
            $html .= '<td>' . $row['a_email'] . '</td>';
            $html .= '<td>' . $row['date'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        $html .= '<div class="footer">Page {PAGENO} of {nb}</div>';

        // Include mPDF library
        require_once(__DIR__ . '/vendor/autoload.php');

        // Create mPDF object
        $mpdf = new \Mpdf\Mpdf();

        // Write HTML content to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        $mpdf->Output('cost.pdf', 'I');
        exit; // Exit to prevent further execution
    } else {
        // Redirect or handle if session is not set
        header("Location: ../users/login.php");
        exit;
    }
} else {
    // Handle if form is not submitted
    echo "Form not submitted.";
}

?>