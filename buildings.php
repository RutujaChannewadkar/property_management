<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Owners List</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

	<style>
		body {
			background-color: #f8f9fa;
		}
		.container {
			margin-top: 40px;
		}
		.owner-card {
			background: white;
			border: 1px solid #ddd;
			border-radius: 5px;
			padding: 11px;
			margin-bottom: 15px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.1);
			transition: 0.2s;
		}
		.owner-card:hover {
			background-color: #f1f1f1;
			cursor: pointer;
		}
		.owner-name {
			font-size: 17px;
			color: black;
			text-decoration: none;
		}
		.owner-name:hover {
			text-decoration: none;
		}
	</style>
</head>
<body>

<div class="container">
	<h2 class="text-center">Building</h2>
	<hr>

	<?php
	include("db_config.php");

	$result = mysqli_query($conn, "SELECT b_no, building_name FROM building_info");

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$building_id = $row['b_no'];
			$name_b = $row['building_name'];
			echo "
				<div class='owner-card'>
					<a class='owner-name' href='update_owner.php?id=$building_id'>
						$name_b
					</a>
				</div>
			";
		}
	} else {
		echo "<p>No owners found.</p>";
	}
	?>
</div>

</body>
</html>
