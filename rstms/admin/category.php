<?php
    include('includes/header.php');
    include('includes/sidebar.php');
?>


	<div class="container">
		<div class="header">
		<h1>Food Category</h1>
		</div>
        <?php if (!empty($message)) : ?>
            <p style="color: green;"> <?php echo $message;?></p>
        <?php endif; ?>
		<div class="search-btn">
			<form id="searchForm" action="" method="GET">
				<input type="search" id="searchInput" name="search" placeholder="Find Category">
				<button type="submit" name="src-btn"><i class="fas fa-search"></i></button>
			</form>
		</div>
		<div class="table">
			<table class="ctable">
				<thead>
					<tr>
						<th>SL</th>
						<th>Category Name</th>
						<th>Category Title</th>
						<th>Category Status</th>
						<th>Category Featured</th>
						<th>Category Image</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                    <?php
                    $rowsPerPage = 25;
                    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                        $currentPage = $_GET['page'];
                    }
                    else{
                        $currentPage = 1;
                    }
                    $offset = ($currentPage - 1) * $rowsPerPage;
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $searchCondition = '';
                    if (!empty($search)) {
                        $searchCondition = "WHERE c_name LIKE '%$search%'";
                    }
                    $sql = "SELECT * FROM tbl_catagory $searchCondition LIMIT $rowsPerPage OFFSET $offset";
                    $stmt = $conn->prepare($sql);
                    $stmt -> execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        $starting_sl = ($currentPage - 1) * $rowsPerPage + 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$starting_sl}</td>";    
                            echo "<td>".$row['c_name']."</td>";
                            echo "<td>".$row['c_title']."</td>";
                            echo "<td>".$row['c_active']."</td>";
                            echo "<td>".$row['c_featured']."</td>";
                            echo "<td>
                                    <img class='form-image' src='../assets/image/{$row['c_image']}' alt='categoryImage'>
                                  </td>";
                            echo "<td>";
                            echo "<form action='editcategory.php' method='GET' style='display:inline-block;'>";
                            echo '<input type="hidden" name="id" value='.$row["c_id"].'>';
                            echo "<button type='submit' class='icon-edit'>";
                            echo "<i class='fa-solid fa-pen-to-square'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo "<form action='' method='POST' style='display:inline-block;'>";
                            echo '<input type="hidden" name="c_id" value='.$row['c_id'].'>';
                            echo "<button class='del-btn' name='delete_btn'>";
                            echo "<i class='fa-solid fa-trash-can'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                            $starting_sl++;
                        }
                    }
                    else{
                        echo "<p style='text-align: center;color: red;'>No Catagory Found!</p>";
                    }
                    // Calculate total number of pages
                    $sqlCount = "SELECT COUNT(*) AS total FROM tbl_catagory";
                    $resultCount = $conn->query($sqlCount);
                    $rowCount = $resultCount->fetch_assoc()['total'];
                    $totalPages = ceil($rowCount / $rowsPerPage);
                    ?>
					
				</tbody>
			</table>
            <div class="pagination" style="margin-bottom: 15px;">
                <?php if ($totalPages > 1): ?>
                    <ul>
                        <li><a href="?page=1">&laquo;</a></li> <!-- First page -->
                        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                            <li <?php if ($page == $currentPage) echo "class='active'"; ?>>
                                <a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li><a href="?page=<?php echo $totalPages; ?>">&raquo;</a></li> <!-- Last page -->
                    </ul>
                <?php endif; ?>
            </div>
		</div>
	</div>
	<div class="icon">
		<a href="addcategory.php"><i class="fa-solid fa-square-plus"></i></a>
	</div>
    <?php
    if (isset($_POST['delete_btn']) && isset($_POST['c_id'])) {
        $c_id = $_POST['c_id'];
        $sql = "DELETE FROM tbl_catagory WHERE c_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $c_id);
        if ($stmt->execute()) {
            echo "<script>window.location.reload();</script>";
        }
        else{
            $message = "Unable to Delete!";
        }
    }
    ?>

	<!--Style for category page start-->
	<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 86%;
            margin: 50px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-left: 100px;
        }

        .header {
            background-color: #d7f7f7;
            color: #070808;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .ctable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .ctable th, .ctable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .ctable th {
            background-color: #d7f7f7;
            color: #070808;
        }

        .ctable tbody tr:hover {
            background-color: #f5f5f5;
        }

        .ctable button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .ctable button:hover {
            background-color: #2980b9;
        }

        .add-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .add-link:hover {
            background-color: #2980b9;
        }
        .form-image{
        	height: 30px;
        	width: 30px;
        	object-fit: cover;
        }
        .icon-edit{
        	margin: 5px;
		  	display: inline-block;
		  	background-color: #178ce6;
		    border: none;
		    border-radius: 5px;
		    text-decoration: none;
		    color: #fff;
		    display: inline-block;
		    padding: 5px 9px;
        }
        .del-btn{
        	background-color: #fa1b32 !important;
        	border-radius: 5px;
        	display: inline-block;
        }
        .icon {
            position: fixed;
            bottom: 50px;
            right: 40px;
            width: 50px;
            height: 50px;
/*            border-radius: 50%;*/
/*            background-color: #3498db;*/
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            cursor: pointer;
        }

        .icon a {
            text-decoration: none;
        }
        .icon a .fa-square-plus{
        	height: 40px!important;
        	color: #61eded;
        }
        .icon a .fa-square-plus:hover{
            color: #06d6be;
        }
        .search-btn{
        	margin-bottom: 20px;
        	margin-left: 80%;

        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination ul li {
            display: inline-block;
            margin-right: 5px;
        }

        .pagination ul li a {
            display: block;
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 3px;
            color: #333;
        }

        .pagination ul li.active a {
            background-color: #3498db;
            color: #fff;
        }

        .pagination ul li a:hover {
            background-color: #f0f0f0;
        }
    </style>
	<!--Style for category page end-->

	<!-- Script for icon -->
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" integrity="sha512-..."></script>
	<script>
	    document.addEventListener('DOMContentLoaded', function() {
	        // Initialize Font Awesome
	        FontAwesome.dom.i2svg();
	    });
	</script>
    <script>
document.getElementById("searchForm").addEventListener("submit", function(event) {
    var searchInput = document.getElementById("searchInput").value.trim();
    if (searchInput === "") {
        event.preventDefault(); // Cancel form submission
        alert("Please enter foodcategory name!");
    }
});
</script>

<?php
    include('includes/footer.php');
?>