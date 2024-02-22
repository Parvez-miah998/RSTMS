<?php
include('includes/dbconnection.php');
if (!isset($_SESSION['user'])) {
    header('Location: users/login.php');
    exit();
}
?>

<h2>Types of Food</h2>
<div class="food-container container">
    <?php
    $sql = $conn->prepare("SELECT * FROM tbl_catagory");
    $sql->execute();
    $result = $sql->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='food-type'>";
            echo "<div class='food-image'>";
            echo "<img src='assets/image/".$row['c_image']."' alt='".$row['c_name']."' />";
            echo "</div>";
            echo "<div class='food-details'>";
            echo "<h3>".(isset($row['c_name']) ? $row['c_name'] : '')."</h3>";
            echo "<p>".(isset($row['c_description']) ? $row['c_description'] : '')."</p>";
            echo "<a href='allcategory.php?category=".urlencode($row['c_name'])."' class='btn btn-primary' target='_blank'>".(isset($row['c_title']) ? $row['c_title'] : '')."</a>";
            echo "</div>";
            echo "</div>";
        }
    }
    ?>
</div>

<style type="text/css">
    .food-container.container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .food-type {
        flex-basis: calc(25% - 20px);
        margin-bottom: 10px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .food-image img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }

    .food-details {
        padding: 5px;
    }

    .food-details h3 {
        margin-top: 0;
        font-size: 1.2em;
    }

    .food-details p {
        color: #666666;
    }

    .btn {
        display: inline-block;
        padding: 4px 5px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
    }

    .btn:hover {
        background-color: #45a049;
    }
</style>

            
                
                    
                    
                        
                        
                    
                
            <!-- 
            <div class="food-type fruite">
                <div class="img-container">
                    <img src="https://i.postimg.cc/yxThVPXk/food1.jpg" alt="error" />
                    <div class="img-content">
                        <h3>Salad</h3>
                        <a href="salad.php" class="btn btn-primary" target="blank">Salad</a>
                    </div>
                </div>
            </div>
            <div class="food-type vegetable">
                <div class="img-container">
                    <img src="assets/image/image9.jpg" alt="error" />
                    <div class="img-content">
                        <h3>Lunch</h3>
                        <a href="lunch.php" class="btn btn-primary" target="blank">Lunch</a>
                    </div>
                </div>
            </div>
            <div class="food-type grin">
                <div class="img-container">
                    <img src="assets/image/image10.jpg" alt="error" />
                    <div class="img-content">
                        <h3>Dinner</h3>
                        <a href="dinner.php" class="btn btn-primary" target="blank">Dinner</a>
                    </div>
                </div>
            </div>
        
        <div class="food-container container" style="margin-top: 10px;">
            <div class="food-type grin">
                <div class="img-container">
                    <img src="assets/image/image12.jpg" alt="error" />
                    <div class="img-content">
                        <h3>Snacks</h3>
                        <a href="snacks.php" class="btn btn-primary" target="blank">Snacks</a>
                    </div>
                </div>
            </div>
            <div class="food-type grin" style="height: 450px;width: 450px; object-fit: cover;">
                <div class="img-container">
                    <img src="assets/image/image11.jpg" alt="error" />
                    <div class="img-content">
                        <h3>SoftDrinks</h3>
                        <a href="softdrinks.php" class="btn btn-primary" target="blank">Soft Drinks</a>
                    </div>
                </div>
            </div>
            <div class="food-type grin">
                <div class="img-container">
                    <img src="assets/image/image13.jpg" alt="error" />
                    <div class="img-content">
                        <h3>Juice</h3>
                        <a href="juice.php" class="btn btn-primary" target="blank">Juice</a>
                    </div>
                </div>
            </div>
            <div class="food-type grin">
                <div class="img-container">
                    <img src="assets/image/image7.jpg" alt="error" />
                    <div class="img-content">
                        <h3>Desserts</h3>
                        <a href="dessert.php" class="btn btn-primary" target="blank">Desserts</a>
                    </div>
                </div>
            </div>
        </div> -->