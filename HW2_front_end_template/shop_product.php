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
          if($_SESSION['identity']){
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "obereat";
			$count = 0;
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			 if (!isset($_POST['shop_name']) || !isset($_POST['distance']) || !isset($_POST['priceLow']) || !isset($_POST['priceHigh']) || !isset($_POST['Meal']))
			{
				header("Location: index.html");
				exit();
			}
			
			$stmt = $conn->prepare( "SELECT image, img_datatype, product_name, price, quantity FROM product WHERE sid=? ");
			$stmt->bind_param("s", $_SESSION['sid']);
			$stmt->execute();
			$result = $stmt->get_result();
			while($row = $result->fetch_assoc()) {
				$img=$row["image"];
				$name=$row['product_name'];
				$price=$row['price'];
				$quantity=$row['quantity'];
				echo '<img width="128" height="128" src="data:'.$row['img_datatype'].';base64,' . $img. '" />';
				echo $name,' ', $price,' ', $quantity;
				echo '<br>';
			}
		}
		?>
		<?php if(!$_SESSION['identity']) {?>
			<p style="font-size:50px;font-family:verdana;text-align:center;">Register and add some food right here</p> 
		<?php }?>
  </body>
</html>
