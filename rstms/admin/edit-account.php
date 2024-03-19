<?php
    include('includes/header.php');
    include('includes/sidebar.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }

    $message = '';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql_show = $conn->prepare("SELECT * FROM account WHERE ac_id = ?");
        $sql_show -> bind_param("i", $id);
        $sql_show -> execute();
        $resuls_show = $sql_show->get_result();
        if ($resuls_show && $resuls_show->num_rows>0) {
            $row = $resuls_show->fetch_assoc();
        }
        else{
            $message = "<div style='color: red;'>No Data Found! </div>";
        }
        $sql_show->close();

    }

    if(isset($_SESSION['admin_email'])){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $a_email = $_SESSION['admin_email'];

            $ac_desc = trim($_POST['ac_desc']);
            $ac_amount = trim($_POST['ac_amount']);

            if ($a_email === $row['a_email']) {
                $sql_update = $conn->prepare("UPDATE account SET ac_desc = ?, ac_amount = ? WHERE ac_id = ? AND a_email = ? ");
                $sql_update -> bind_param("ssid", $ac_desc, $ac_amount, $id, $a_email);
                
                if ($sql_update -> execute()) {
                    $message = "<div style='color: green;'>Today's Cost Update successfully! </div>";
                }
                else{
                    $message = "<div style='color: red;'>Unable to Update this cost! </div>";
                }
            }
            else{
                $message = "<div style='color: red;'>You are not authorized to update this data.</div>";
            }            
        }
    }
    
?>

<div class="container">
    <div class="header">
        <h2>Add Today's Cost</h2>
    </div>
    <?php if(!empty($message)) : ?>
        <p> <?php echo $message; ?> </p>
    <?php endif; ?>
    <div class="form_cost">
        <form action="" method="POST">
            <div class="form-data">
                <label>Email</label>
                <input type="text" name="a_emai" id="a_emai" value="<?php echo (isset($row['a_email'])) ? $row['a_email'] : '' ; ?>" readonly>
            </div>
            <div class="form-data">
                <label>Add Description</label>
                <textarea type="message" name="ac_desc" id="ac_desc" required><?php echo isset($row['ac_desc']) ? $row['ac_desc'] : ''; ?></textarea>
            </div>
            <div class="form-data">
                <label>Add Amount</label>
                <input type="text" name="ac_amount" id="ac_amount" value=" <?php echo (isset($row['ac_amount'])) ? $row['ac_amount'] : '' ; ?>" required>
            </div>
            <div class="form-btn">
                <button type="submit" name="acc_btn">Update</button>
            </div>
        </form>
    </div>
</div>


<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #e4f7ec;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 450px;
        margin: 50px auto;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 40px;
        box-sizing: border-box;
        overflow: hidden;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
        background-color: #a5fae7;
        border-radius: 10px 10px 0 0;
        padding: 5px;
    }

    .header h2 {
        margin: 0;
        color: #333;
    }

    .form-data {
        margin-bottom: 20px;
    }
    .form-data label{
        font-weight: 700;
        display: block;
        margin-bottom: 10px;
    }

    .form-data textarea,
    .form-data input[type="text"] {
        width: 100%; 
        min-height: 30px; 
        max-height: 300px;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #605d66;
        box-sizing: border-box;
        font-size: 16px;
        resize: vertical;
        background-color: transparent;
    }

    .form-btn button {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
    }

    .form-btn button:hover {
        background-color: #05f7c3;
    }
</style>


<?php
    include('includes/footer.php');
?>