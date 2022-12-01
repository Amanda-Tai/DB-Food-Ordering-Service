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
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "obereat";
			$count = 0;
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error){
				die("Connection failed: " . $conn->connect_error);
			}
			
			$price_up;
    		$price_down;
			$s_meal = '_';
			$s_shop = '_';
			$s_category = '_';
			$check = 0;
			
			if(!isset($_SESSION['distance']))
			{
				header("Location: blank.php");
				exit();
			}
			
    		if(strlen($_SESSION['price_up']) == 0)
       			$price_up = 2147483646;
			else
				$price_up = $_SESSION['price_up'];
			
			if(strlen($_SESSION['price_down']) == 0)
				$price_down = 0;
			else
				$price_down = $_SESSION['price_down'];
			
			if(strlen($_SESSION['s_shop']) != 0) $s_shop = $_SESSION['s_shop'];
			if(strlen($_SESSION['s_meal']) != 0) $s_meal = $_SESSION['s_meal'];
			if(strlen($_SESSION['category']) != 0) $s_category = $_SESSION['category'];
			
			$s_shop = "%{$s_shop}%";
			$s_meal = "%{$s_meal}%";
			$s_category = "%{$s_category}%";
			$distance = $_SESSION['distance'];
			
			if($_SESSION['distance'] == 'near')
			{
				$distance_min = 0;
				$distance_max = 1000;
			}
			else if($_SESSION['distance'] == 'medium')
			{
				$distance_min = 1001;
				$distance_max = 2000;
			}
			else if($_SESSION['distance'] == 'far')
			{
				$distance_min = 2001;
				$distance_max = 4000;
			}
			else
			{
				$distance_min = 0;
				$distance_max = 100000;
			}
			$stmt = $conn->prepare( "select latitude, longitude from user where uid = ?");
			$stmt->bind_param("i", $_SESSION['uid']);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$latitude = $row['latitude'];
			$longitude = $row['longitude'];
			
			
			$stmt = $conn->prepare( "select sid, name, category from shop
									 where sid in (select sid from product
									 where product.price <= ?
									 and product.price >= ?
									 and product.product_name like ?
									 and product.sid in (select sid from shop where name like ? 
									 and shop.category like ?
									 and ST_Distance_Sphere(POINT(?, ?), location) <= ?
									 and ST_Distance_Sphere(POINT(?, ?), location) >= ? ))");
			$stmt->bind_param("iisssddiddi", $price_up, $price_down, $s_meal, $s_shop, $s_category, $longitude, $latitude, $distance_max, $longitude, $latitude, $distance_min);
			$stmt->execute();
			$result = $stmt->get_result();
			echo <<<EOT
			<!DOCTYPE html>
			<html>
			<div class="row">
			<div class="  col-xs-8">
			<table class="table" style=" margin-top: 15px;">
				<thead>
					<tr>
					<th scope="col">#</th>
					
					<th scope="col">shop name</th>
					<th scope="col">shop category</th>
					<th scope="col">Distance</th>
				
					</tr>
				</thead>
				

				
			
			EOT;
			
			
			$count = 0;
			while($row = $result -> fetch_assoc())
			{
				
				$count = $count +1;
				$name=$row['name'];
				$category=$row['category'];
				$sid = $row['sid'];
				$_SESSION["chosen_shop"] = $sid;
				echo <<< EOT
					<!DOCTYPE html>
					<html>
					<tbody>
					<td>{$count}</td>
					<td>{$name}</td>
					<td>{$category}</td>
					<td>{$distance}</td>
					<form action = menu.php method = "post">
					<input type="hidden" name="sid" id="sid" value={$sid}>
					<td><input type="submit" class="btn btn-info" value="open menu"></td>
					</form>
					
				EOT;		
				
				
		
					
			}
		?>
  </body>
</html>