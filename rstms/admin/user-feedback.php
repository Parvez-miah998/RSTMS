<?php
    include('includes/header.php');
    include('includes/sidebar.php');
    if (!isset($_SESSION['admin_email'])) {
        header("Location: ../users/login.php");
        exit();
    }
?>
<div class="container">
	<div class="header">
		<h3>User's Feed Back</h3>
	</div>
	<div class="table">
		<table class="ftable">
			<thead>
				<tr>
					<th>SL</th>
					<th>Name</th>
					<th>Contact</th>
					<th>U_ID</th>
					<th>Email</th>
					<th>Feed Back</th>
					<th>Rate</th>
				</tr>
			</thead>
			<tbody>
				<?php
                $rowsPerPage = 3;
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $currentPage = $_GET['page'];
                }
                else{
                    $currentPage = 1;
                }
                $offset = ($currentPage - 1) * $rowsPerPage;
				$sql = $conn->prepare("SELECT f.*, u.u_name, u.u_contact, u.u_email FROM tbl_feedback f INNER JOIN users u ON f.u_id = u.u_id LIMIT $rowsPerPage OFFSET $offset");
				$sql ->execute();
				$result = $sql->get_result();
				if ($result && $result->num_rows>0) {
					$starting_sl = ($currentPage - 1) * $rowsPerPage + 1;
					while ($row = $result->fetch_assoc()) {
						echo "<tr>";
						echo "<td>{$starting_sl}</td>";
						echo "<td>".htmlspecialchars($row['u_name'])."</td>";
						echo "<td>".htmlspecialchars($row['u_contact'])."</td>";
						echo "<td>".htmlspecialchars($row['u_id'])."</td>";
						echo "<td>".htmlspecialchars($row['u_email'])."</td>";
						echo "<td>".htmlspecialchars($row['f_content'])."</td>";
						echo "<td class='rating'>".htmlspecialchars($row['f_rate'])."</td>";
						echo "</tr>";
						$starting_sl ++;
					}
				}
                $sqlCount = "SELECT COUNT(*) AS total FROM tbl_feedback";
                $resultCount = $conn->query($sqlCount);
                $rowCount = $resultCount->fetch_assoc()['total'];
                $totalPages = ceil($rowCount/$rowsPerPage);
				?>
			</tbody>
		</table>
        <div class="pagination">
            <?php if ($totalPages>1) : ?>
                <ul>
                    <li> <a href="?page=1">&laquo;</a> </li>
                    <?php for ($page=1; $page <=$totalPages ; $page++) : ?>
                        <li <?php if($page == $currentPage) echo "class='active'"; ?>>
                            <a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li> <a href="?page=<?php echo $totalPages; ?>">&raquo;</a> </li>
                </ul>
            <?php endif; ?>
        </div>
	</div>
</div>


<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f7f0fc;
}

.container {
    max-width: 85%;
    margin: 20px auto;
    padding: 20px;
/*    background-color: #fff;*/
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 30px;
    background-color: #c6f5f4;
    padding: 5px;
    border-radius: 10px;
}

.table {
    overflow-x: auto;
}

.ftable {
    width: 100%;
    border-collapse: collapse;
}

.ftable th,
.ftable td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.ftable th {
    background-color: #f2f2f2;
}

.rating {
    text-align: center;
}

/* Star rating styles */
.star-rating {
    unicode-bidi: bidi-override;
    color: #ffcc00;
    font-size: 20px;
    display: inline-block;
    position: relative;
    padding: 0;
    margin: 0;
}
.star-rating li {
    display: inline-block;
    margin-right: -2px;
    list-style-type: none;
    cursor: pointer;
}
.star-rating input[type="radio"] {
    display: none;
}
.star-rating label {
    font-size: 20px;
    color: #f200ff;
    display: inline-block;
    width: 30px;
    text-align: center;
}
/*.star-rating label:before {
    content: '\2605';
    color: #ddd;
}*/
.star-rating input[type="radio"]:checked ~ label:before,
.star-rating input[type="radio"]:checked ~ label:hover ~ label:before {
    color: #ffcc00;
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
    // Function to generate stars based on rating
    $(".rating").each(function() {
        var rating = parseInt($(this).text());
        var stars = '';
        for (var i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<label>&#x2605;</label>';
            } else {
                stars += '<label>&#x2606;</label>';
            }
        }
        $(this).html('<div class="star-rating">' + stars + '</div>');
    });
});
</script>


<?php
    include('includes/footer.php');
?>