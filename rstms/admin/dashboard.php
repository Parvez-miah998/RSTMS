<?php
	include('includes/header.php');
	include('includes/sidebar.php');
	if (!isset($_SESSION['admin_email'])) {
	    header("Location: ../users/login.php");
	    exit();
	}
?>

<div class="dashboard">
	<div class="content">
        <div class="total-order">
            <div class="order-head">
                <h4>Total User</h4>
            </div>
            <div class="order-body">
            	<h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total User</h4>
            </div>
            <div class="order-body">
                <h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total Order</h4>
            </div>
            <div class="order-body">
            	<h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total Booked table</h4>
            </div>
            <div class="order-body">
            	<h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="total-order">
            <div class="order-head">
                <h4>Feed Back</h4>
            </div>
            <div class="order-body">
                <h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Contact Us</h4>
            </div>
            <div class="order-body">
                <h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total Cost</h4>
            </div>
            <div class="order-body">
                <h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
        <div class="total-order">
            <div class="order-head">
                <h4>Total Cost</h4>
            </div>
            <div class="order-body">
                <h4>5</h4>
                <a href="#">View</a>
            </div>
        </div>
    </div>
</div>

<!--Style for dasboard start-->

<style type="text/css">
.content {
        display: flex;
        justify-content: space-between;
        max-width: 90%;
        margin: 0 auto;
        margin-top: 35px;
    }

    .total-order {
        flex-basis: calc(25% - 20px);
        height: 150px;
        border-radius: 10px;
        background-color: gray;
        margin-bottom: 20px;
    }

    .order-head {
        text-align: center;
/*        padding-top: 5px;*/
    }

    .order-body {
        text-align: center;
/*        padding-top: 5px;*/
    }

    .order-body a {
        text-decoration: none;
        color: #000;
    }
    .total-order:nth-child(1) {
        background-color: #34a1fa;
    }

    .total-order:nth-child(2) {
        background-color: #34fab5;
    }

    .total-order:nth-child(3) {
        background-color: #fa3462;
    }
</style>

<!--Style for dasboard end-->
	


<?php include('includes/footer.php'); ?>