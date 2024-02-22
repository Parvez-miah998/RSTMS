
<h2 class="testimonial-title">What Our Customers Say</h2>
        <div class="testimonial-container container owl-carousel owl-theme">
            <?php
                include ('includes/dbconnection.php');

                $sql = "SELECT tbl_feedback.f_content, tbl_feedback.f_rate, users.u_name, users.u_img FROM tbl_feedback INNER JOIN users ON tbl_feedback.u_id = users.u_id";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $f_content = $row['f_content'];
                        $f_rate = $row['f_rate'];
                        $u_name = $row['u_name'];
                        $u_img = $row['u_img'];

                        ?>

                        <div class="testimonial-box">
                    <div class="customer-detail">
                        <div class="customer-photo">
                            <img src="assets/user_images/<?php echo $u_img; ?>" alt="" />
                            <p class="customer-name"><?php echo $u_name; ?></p>
                        </div>
                    </div>
                    <div class="star-rating">
                        <?php 
                        // for ($i=0; $i < $f_rate; $i++) { 
                        //     echo '<span class="fa fa-star checked"></span>';
                        // }
                        ?>
                        <?php 
                        for ($i = 0; $i < $f_rate; $i++) { 
                            switch ($f_rate) {
                                case 1:
                                    echo '<span class="emoji">&#128542;</span>'; 
                                    break;
                                case 2:
                                    echo '<span class="emoji">&#128577;</span>'; 
                                    break;
                                case 3:
                                    echo '<span class="emoji">&#128528;</span>'; 
                                    break;
                                case 4:
                                    echo '<span class="emoji">&#128578;</span>'; 
                                    break;
                                case 5:
                                    echo '<span class="emoji">&#128513;</span>'; // Very happy emoji
                                    break;
                                default:
                                    echo '<span class="emoji">&#128577;</span>'; // Default emoji (e.g., very sad) for any other value
                                    break;
                            }
                        }
                        ?>

                        </div>
                            <p class="testimonial-text">
                                <?php echo $f_content; ?>
                            </p>
                        </div>
                <?php
                    }
            }
            else{
                echo "Error executing the query: " . mysqli_connect($conn);
            }

            mysqli_close($conn);
             ?>
        </div>


    <style>
        /* CSS for individual elements within the slider */
        .testimonial-box {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
        }
        .customer-photo img {
            max-width: 150px;
            border-radius: 50%;
        }
        .star-rating {
            margin-bottom: 10px;
        }
        .testimonial-text {
            font-style: italic;
            color: #555;
        }
    </style>