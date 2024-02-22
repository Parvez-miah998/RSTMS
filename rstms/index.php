<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: users/login.php");
        exit();
    }
?>

    <!--Start Header area-->
    <?php include('includes/header.php')?>
    <!--End Header area-->

    <!--Start about area-->
    <section id="about">
        <?php include('includes/about.php')?>
    </section>
    <!--End About area-->

    <!--Start Food Category-->
    <section id="food" style="margin-bottom: 0px!important;padding-bottom: 0px!important;">
        <?php include('includes/foodcategory.php')?>
    </section>
    <!--End Food Category-->

    <!--Start Food Menu-->
    <section id="food-menu" style="margin-top: 0px!important;padding-top: 0px!important;">
         <?php include('includes/foodmenu.php')?>
    </section>
    <!--End Food Menu-->

    <!--Booked Table Start-->
    <section id="BookedTable" style="margin-top: 40px;">
        <?php include('includes/bookedtable.php')?>
    </section>
    <!--Booked Table End-->

    <!--Testimonial area start-->
    <section id="testimonials">
        <?php include('includes/testimonial.php')?>
    </section>
    <!--Testimonial area end-->

    <!--Contact area end-->
    <section id="contact">
        <?php include('includes/contactus.php')?>
    </section>
    <!--Contact area end-->
    

    <!--Footer area start-->
    <?php include('includes/footer.php')?>
    <!--Footer area End-->