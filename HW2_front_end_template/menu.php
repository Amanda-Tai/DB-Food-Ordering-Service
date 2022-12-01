<!DOCTYPE html>
  <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
  <body>
		<?php session_start();
		if($_SESSION['Authenticated']==false)
  {
	  header("Location: index.html");
	  exit();
  }
          if($_SESSION['chosen_shop']){
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "obereat";
			$count = 0;
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			$sid = $_POST['sid'];
			echo <<<EOT
			<!DOCTYPE html>
			<html>
			<div class="row">
				<div class="  col-xs-8">
					<table class="table" style=" margin-top: 15px;">
					<thead>
						<tr>
						<th scope="col">#</th>
						<th scope="col">Picture</th>
						<th scope="col">meal name</th>
					
						<th scope="col">price</th>
						<th scope="col">Quantity</th>
						<th scope="col">Order</th>
						</tr>
					</thead>
					<form action="orderCheck.php" method="post">
			EOT;
			
			$stmt = $conn->prepare( "SELECT pid, image, img_datatype, product_name, price, quantity FROM product WHERE sid=? ");
			$stmt->bind_param("s", $_POST['sid']);
			$stmt->execute();
			$result = $stmt->get_result();
			$count = 1;
			while($row = $result->fetch_assoc()) {
				$img=$row["image"];
				$img_type = $row["img_datatype"];
				$name=$row['product_name'];
				$price=$row['price'];
				$quantity=$row['quantity'];
				$pid=$row['pid'];
				$pidn = $pid."name";
				echo <<<EOT
					<!DOCTYPE html>
					<html>
					<tbody>
						<tr>
						<th scope="row">$count</th>
						<td><img width="128" height="128" src="data:{$row['img_datatype']};base64,$img" /></td>
						<td>{$name}</td>
						<td>{$price} </td>
						<td>{$quantity} </td>
						<td><input type="text" name={$pid} id={$pid} placeholder = "0" size="3" ></td>
						<input type="hidden" name={$pidn} id={$pidn} value = $name >
					EOT;
					$count = $count +1;
			}
			echo <<<EOT
			</tbody>
			<td>type</td>
			<div class="col-sm-5">
			<td><select name="type" class="form-control" id="type">
			<option>Delievery</option>
			<option>Pick-up</option>
			</select></td>
			</div>
			<input type="hidden" name="sid" id="sid" value=$sid>
			<div class="form-group">
			<td><input type="submit" class="btn btn-info" value="Calculate the price"></td>
			</form>
			<form action="search.php" method="post">
			<td><input type="submit" class="btn btn-info" value="Return"></td>
			</form>
			<div class="form-group">
			EOT;
		}
		?>
  </body>
</html>