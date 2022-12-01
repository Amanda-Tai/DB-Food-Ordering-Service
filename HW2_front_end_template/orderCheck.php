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
<h1>Order</h1>
<?php session_start();
  if($_SESSION['Authenticated']==false)
  {
	  header("Location: index.html");
	  exit();
  }
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
  try{
  if($_POST['type'] == "Delievery")
	  $type = 0;
  else
	  $type = 1;
  echo <<<EOT
			<!DOCTYPE html>
			<html>
			<div class="row">
				<div class="  col-xs-8">
					<table class="table" style=" margin-top: 15px;">
					<thead>
						<tr>
						<th scope="col">Picture</th>
						<th scope="col">meal name</th>
					
						<th scope="col">price</th>
						<th scope="col">Quantity</th>
						<th scope="col">Order</th>
						</tr>
					</thead>
					<form action="orderConfirmed.php" method="post">
			EOT;
			$stmt = $conn->prepare( "select latitude, longitude from user where uid = ?");
			$stmt->bind_param("i", $_SESSION['uid']);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$latitude = $row['latitude'];
			$longitude = $row['longitude'];
			
			$stmt = $conn->prepare( "SELECT (ST_Distance_Sphere(POINT(?, ?), location)) as distance FROM shop WHERE sid=? ");
			$stmt->bind_param("dds", $longitude, $latitude, $_POST['sid']);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$distance = $row['distance'];
			
			$stmt = $conn->prepare( "SELECT pid, image, img_datatype, product_name, price, quantity FROM product");
			$stmt->execute();
			$result = $stmt->get_result();
			$count=0;
			$subtotal=0;
			while($row = $result->fetch_assoc()) {
				$img=$row["image"];
				$img_type = $row["img_datatype"];
				$name=$row['product_name'];
				$price=$row['price'];
				$quantity=$row['quantity'];
				$pid=$row['pid'];
				if(!empty($_POST[$pid]))
				{
					$subtotal = $subtotal + $_POST[$pid]*$price;
					$count = $count+1;
				}
			}
			
			if($type)
				$delivery = 0;
			else
			{
				$delivery = round($distance / 100);
				if($delivery < 10)
					$delivery = 10;
			}
			
			$total = $subtotal+$delivery;
			$noStock = 0;
			$noStockMessage = "";
			
			$stmt = $conn->prepare( "SELECT pid, image, img_datatype, product_name, price, quantity FROM product");
			$stmt->execute();
			$result = $stmt->get_result();
			while($row = $result->fetch_assoc()) {
				$img=$row["image"];
				$img_type = $row["img_datatype"];
				$name=$row['product_name'];
				$price=$row['price'];
				$quantity=$row['quantity'];
				$pid=$row['pid'];
				$pidn = $pid."name";
				if(!empty($_POST[$pid]))
				{
					echo <<<EOT
					<input type="hidden" name={$pid} id={$pid} value = $_POST[$pid] >
					<input type="hidden" name={$pidn} id={$pidn} value = $name >
					<!DOCTYPE html>
					<html>
					<tbody>
						<tr>
						<td><img width="128" height="128" src="data:{$row['img_datatype']};base64,$img" /></td>
						<td>{$name}</td>
						<td>{$price} </td>
						<td>{$quantity} </td>
						<td>{$_POST[$pid]} </td>
					EOT;
					for ($i = 0; $i < strlen($_POST[$pid]); $i++){
					$char = $_POST[$pid][$i];
					if(!preg_match('/\d/', $char))
						throw new Exception('Please enter a vaild number.');
					}
					if($_POST[$pid] > $quantity)
					{
						$noStock = 1;
						$noStockMessage = $noStockMessage."Product ".$name. " is out of stock. \\n";
					}
					${$pid."checked"} = -1;
				}
			}
			
			$deleted = 0;
			$deletedMessage = "";
			
			foreach ($_POST as $key => $value) {
			if(is_int($key) && !isset(${$key."checked"}) && !empty($value))
			{
				$deleted = 1;
				$deletedMessage = $deletedMessage."Product ".$_POST[$key."name"]. " is deleted by the stored. \\n";
			}
			}
			
			if($noStock)
				throw new Exception($noStockMessage);
			
			if($deleted)
				throw new Exception($deletedMessage);
			
			echo<<<EOT
			<input type="hidden" name="subtotal" id="subtotal" value = $subtotal >
			<input type="hidden" name="sid" id="sid" value = $sid >
			<input type="hidden" name="distance" id="distance" value = $distance >
			<input type="hidden" name="total" id="total" value = $total >
			<input type="hidden" name="type" id="type" value = $type >
			<input type="hidden" name="count" id="count" value = $count >
			EOT;
			
	
	if($count != 0)
	{
		echo <<<EOT
		<td><input type="submit" class="btn btn-info" value="Order"></td>
		</form>
		<!DOCTYPE html>
		<html>
		<form action="menu.php" method="post">
		<input type="hidden" name="sid" id="sid" value=$sid>
		<td><input type="submit" class="btn btn-info" value="Return"></td>
		</form>
		<p>Subtotal  $$subtotal</p>
		<p>Delivery fee  $$delivery</p>
		<p>Total  $$total</p>
		EOT;
	}
	else
	{
		throw new Exception('Please order some food.');
	}
  }
  catch(Exception $e)
  {
     $msg=$e->getMessage();
     echo <<<EOT
     <!DOCTYPE html>
     <html>
     <body>
     <script>
     alert("$msg");
       window.location.replace("search.php");
     </script>
     </body>
     </html>
     EOT;
  }
?>
</body>