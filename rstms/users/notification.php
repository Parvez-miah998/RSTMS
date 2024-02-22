<!--Header area start-->
<?php 
    session_start();
    include 'includes/header.php';
    include ('includes/dbconnection.php');
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    } 

?>
<!--Header area End-->

<!--Notification page body area start-->
<div class="container">
    <div class="notification">
        <div class="not-icon">
            <i class="fa fa-info-circle"></i>
        </div>
        <div class="not-text">
            <h4>Information!</h4>
            <p>This is an informational message.</p>
        </div>
        <div class="close-btn">
            <span class="close">&times;</span>
        </div>
    </div>
    
    <div class="notification success">
        <div class="not-icon">
            <i class="fa fa-check"></i>
        </div>
        <div class="not-text">
            <h4>Success!</h4>
            <p>Changes have been saved successfully!</p>
        </div>
        <div class="close-btn">
            <span class="close">&times;</span>
        </div>
    </div>
    
    <div class="notification warning">
        <div class="not-icon">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <div class="not-text">
            <h4>Warning!</h4>
            <p>Please be cautious!</p>
        </div>
        <div class="close-btn">
            <span class="close">&times;</span>
        </div>
    </div>
    
    <div class="notification danger">
        <div class="not-icon">
            <i class="fa fa-times-circle"></i>
        </div>
        <div class="not-text">
            <h4>Error!</h4>
            <p>Something went wrong!</p>
        </div>
        <div class="close-btn">
            <span class="close">&times;</span>
        </div>
    </div>
</div>
<!--Notification page body area end-->

<!--Footer area-->
<?php include 'includes/footer.php'; ?>
