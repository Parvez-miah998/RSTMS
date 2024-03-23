<?php
session_start();
include ('dbconnection.php');
if (!isset($_SESSION['admin_email'])) {
        header('Location: ../users/login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/gauge-high-solid.svg">
    <link rel="stylesheet" type="text/css" href="../assets/icons/icon2.svg">
    <link rel="stylesheet" type="text/css" href="../assets/css/admin.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!--This link is responsible for show the icon on mozilaFireFox-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!--script for calender start-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!--script for pie chart-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script type="text/javascript">
    	if (window.history.replaceState) {
    		window.history.replaceState(null, null, window.location.href);
    	}
    </script>
    <!-- Style for Profile active green dot start -->
<style type="text/css">
    .active-indicator {
    width: 10px;
    height: 10px;
    background-color: green;
    border-radius: 50%;
    margin-right: -5px;
    margin-top: -20px;
    animation: pulse 0.5s infinite alternate;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.2);
    }
}

</style>
<!-- Style for Profile active green dot end -->
</head>
<body>