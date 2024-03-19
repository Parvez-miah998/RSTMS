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
		<h3>All the Issues</h3>
	</div>
	<div class="table">
		<table class="contact-table">
			<thead>
				<tr>
					<th>SL</th>
					<th>Name</th>
					<th>Email</th>
					<th>Issue</th>
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
				$sql = $conn->prepare("SELECT * FROM tbl_contactus LIMIT $rowsPerPage OFFSET $offset");
				$sql -> execute();
				$result = $sql->get_result();
				if ($result && $result->num_rows>0) {
					$starting_sl = ($currentPage - 1) * $rowsPerPage + 1;
					while ($row = $result->fetch_assoc()) {
						echo "<tr>";
						echo "<td>{$starting_sl}</td>";
						echo "<td>".htmlspecialchars($row['ctus_name'])."</td>";
						echo "<td>".htmlspecialchars($row['ctus_email'])."</td>";
						echo "<td>".htmlspecialchars($row['ctus_desc'])."</td>";
						echo "<td><a href='mailto:".urlencode(htmlspecialchars($row['ctus_email']))."' class='btn-sugst'>Suggestion</a></td>";
						echo "</tr>";

						$starting_sl++;
					}
				}
				$sqlCount = "SELECT COUNT(*) AS total FROM tbl_contactus";
				$resultCount = $conn->query($sqlCount);
				$rowCount = $resultCount->fetch_assoc()['total'];
				$totalPages = ceil($rowCount/$rowsPerPage);
				?>
			</tbody>
		</table>
		<div class="pagination" style="margin-bottom:15px;">
			<?php if($totalPages>1) : ?>
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
    background-color: #f7f0fc;
}

.container {
    max-width: 80%;
    margin: 30px auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 20px;
    background-color: #c6f5f4;
    padding: 5px;
    border-radius: 10px;
}

.contact-table {
    width: 100%;
    border-collapse: collapse;
}

.contact-table th,
.contact-table td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.contact-table th {
    background-color: #f2f2f2;
}

.contact-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.contact-table tbody tr:hover {
    background-color: #e6f7ff;
}

.btn-sugst {
    padding: 8px 16px;
    border: none;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
    text-decoration: none;
}

.btn-sugst:hover {
    background-color: #07f0d8;
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

<!--Script for Send an email after cilck the suggestion button-->
<script>
    document.querySelectorAll('.btn-sugst').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            var href = this.getAttribute('href').split(':')[1]; 
            window.open('mailto:' + href);
        });
    });
</script>




<?php
    include('includes/footer.php');
?>