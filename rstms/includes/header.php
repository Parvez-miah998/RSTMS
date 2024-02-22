<?php
include('includes/dbconnection.php');

// Check if the user is logged in
if (isset($_SESSION['u_email'])) {
    $email = $_SESSION['u_email'];

    // Fetch u_name and u_email from the users table
    $sql_fetch_user_info = "SELECT u_name, u_email, u_img FROM users WHERE u_email = ?";
    $stmt_fetch_user_info = mysqli_prepare($conn, $sql_fetch_user_info);
    mysqli_stmt_bind_param($stmt_fetch_user_info, "s", $email);
    mysqli_stmt_execute($stmt_fetch_user_info);
    $result_user_info = mysqli_stmt_get_result($stmt_fetch_user_info);

    if ($result_user_info && mysqli_num_rows($result_user_info) > 0) {
        $user_info = mysqli_fetch_assoc($result_user_info);
        $u_name = $user_info['u_name'];
        $u_email = $user_info['u_email'];
        $u_img = $user_info['u_img'];
        // Now $u_name and $u_email contain the user's name and email
    }
}
?>

<!DOCTYPE html>
<html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KING Restaurant</title>
    <link rel="icon" type="image/x-icon" href="assets/icons/icon1.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <!--This link is responsible for show the icon on mozilaFireFox-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">


    <!--This is responsible for stop resubmission -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>
<body>
    <nav class="navbar">
        <div class="navbar-container container">
            <input type="checkbox" name="" id="">
            <div class="hamburger-lines">
                <span class="line line1"></span>
                <span class="line line2"></span>
                <span class="line line3"></span>
            </div>
            <ul class="menu-items">
                <li><a aria-current="page" href="index.php" style="font-size: 16px!important;">Home</a></li>
                <li><a href="#about" style="font-size: 16px!important;">About</a></li>
                <li><a href="#food" style="font-size: 16px!important;">Category</a></li>
                <li><a href="#food-menu" style="font-size: 16px!important;">Menu</a></li>
                <li><a href="#BookedTable" style="font-size: 16px!important;">Table Booked</a></li>
                <li><a href="#testimonials" style="font-size: 16px!important;">Testimonial</a></li>
                <li><a href="#contact" style="font-size: 16px!important;">Contact</a></li>
                <li><a href="allcart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <?php if(isset($u_img) && !empty($u_img) && file_exists("assets/user_images/{$u_img}")): ?>
                            <img src="assets/user_images/<?php echo $u_img; ?>" alt="">
                        <?php else: ?>
                            <img src="assets/icons/users.svg" alt="">
                        <?php endif; ?>
                        <div class="user-details">
                            <!-- Your existing user details -->
                            <span class="user-name"><?php echo isset($u_name) ? $u_name : ''; ?></span>
                            <span class="user-email"><?php echo isset($u_email) ? $u_email : ''; ?></span>
                        </div>
                    </a>

                    <div class="dropdown-content">
                        <a href="users/myprofile.php"><i class="fa-solid fa-id-card-clip"></i> My Profile</a>
                        <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
                    </div>
                </li>
            </ul>
            <h1 class="logo">K<i class="fa-solid fa-crown"></i>NG</h1>
        </div>
    </nav>
    <section class="showcase-area" id="showcase">
        <div class="showcase-container">
            <h1 class="main-title" id="home">Eat Right Food</h1>
            <p>Eat Healty, it is good for our health.</p>
            <a href="#food-menu" class="btn btn-primary">Menu</a>
        </div>
    </section>


    <style type="text/css">
       /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .dropbtn img {
            width: 35px; /* Adjust size as needed */
            height: 35px; /* Adjust size as needed */
            border-radius: 50%;
            border: 3px solid #03fc8c;
            padding: .8px;
            margin-right: 6px; /* Spacing between icon and user details */
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .active-point {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .user-name,
        .user-email {
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

    </style>