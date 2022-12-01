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
						<th scope="col">Edit</th>
						<th scope="col">Delete</th>
						</tr>
					</thead>
			
			EOT;
			
			$stmt = $conn->prepare( "SELECT pid, image, img_datatype, product_name, price, quantity FROM product WHERE sid=? ");
			$stmt->bind_param("s", $_SESSION['sid']);
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
						<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#{$pid}">
						Edit
						</button></td>
						<!-- Modal -->
							<div class="modal fade" id="{$pid}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
									<h5 class="modal-title" id="staticBackdropLabel">{$name} Edit</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									</div>
									<form action="updateProduct.php" method="post">
									<div class="modal-body">
									<div class="row" >
										<div class="col-xs-6">
										<label for="price">price</label>
										<input name="price" class="form-control" id="price" type="text">
										</div>
										<div class="col-xs-6">
										<label for="quantity">quantity</label>
										<input name="quantity" class="form-control" id="quantity" type="text">
										</div>
										<input type="hidden" name="pid" id="pid" value=$pid>
									</div>
							
									</div>
									<div class="modal-footer">
									<input type="submit" class="btn btn-secondary" value="Edit"></input>
									
									</div>
									</form>
								</div>
								</div>
							</div>
							<form action="deleteProduct.php" method="post">
							<input type="hidden" name="pid" id="pid" value=$pid>
						<td><input type="submit" class="btn btn-danger" data-dismiss="modal" value="Delete"></input></td>
						</form>
						</tr>
					EOT;
					$count = $count +1;
			}
		}
		?>
		<?php if(!$_SESSION['identity']) {?>
			<p style="font-size:50px;font-family:verdana;text-align:center;">Register and add some food right here</p> 
		<?php }?>
  </body>
</html>