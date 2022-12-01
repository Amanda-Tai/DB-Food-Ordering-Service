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
    <form action="shop_register.php" method="post">
        <div class="form-group ">
          <div class="row">
            <div class="col-xs-2">
              <label for="shop_name">shop name</label>
              <input name="shop_name" class="form-control" id="shop_name" placeholder="macdonald" type="text" >
            </div>
            <div class="col-xs-2">
              <label for="shop_category">shop category</label>
              <input name="shop_category" class="form-control" id="shop_category" placeholder="fast food" type="text" >
            </div>
            <div class="col-xs-2">
              <label for="latitude">latitude</label>
              <input name="latitude" class="form-control" id="latitude" placeholder="24.78472733371133" type="text" >
            </div>
            <div class="col-xs-2">
              <label for="longitude">longitude</label>
              <input name="longitude" class="form-control" id="longitude" placeholder="121.00028167648875" type="text" >
            </div>
          </div>
        </div>
		<?php session_start();
		if($_SESSION['Authenticated']==false)
  {
	  header("Location: index.html");
	  exit();
  }
		  if($_SESSION['identity']){
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "obereat";
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare( "SELECT name, category, latitude, longitude FROM shop WHERE sid =:sid ");
			$stmt->execute(array('sid' => $_SESSION["sid"]));
			$row = $stmt->fetch();
			$name = $row['name'];
			$category = $row['category'];
			$latitude = $row['latitude'];
			$longitude = $row['longitude'];
		  }
		?>

        <div class=" row" style=" margin-top: 25px;">
          <div class=" col-xs-3">
		  
		  <?php if($_SESSION['identity']) {?>
		    <input type="submit" class="btn btn-primary" value="Register" disabled></input>
			<br><br>
			<p><?php echo "Shop name: ", $name, " , category: ", $category?> </p> 
			<p><?php echo "location: " ,number_format((float) $latitude, 14, '.', '') ,", ", number_format((float) $longitude, 14, '.', '')?></p>
		  <?php } else { ?>
			<input type="submit" class="btn btn-primary" value="Register"></input>
		  <?php } ?>
          </div>
        </div>
	</form>
  </body>
</html>