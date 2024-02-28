<?php
session_start();
include ('dbconnection.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/gauge-high-solid.svg">
    <link rel="stylesheet" type="text/css" href="../assets/icons/icon2.svg">
    <link rel="stylesheet" type="text/css" href="../assets/css/admindashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!--This link is responsible for show the icon on mozilaFireFox-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script type="text/javascript">
    	if (window.history.replaceState) {
    		window.history.replaceState(null, null, window.location.href);
    	}
    </script>
</head>
<body>
    
    
