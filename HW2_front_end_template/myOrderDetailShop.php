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

        // Create connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Check connection
        $conn2 = new mysqli($servername, $username, $password, $dbname);
        if ($conn2->connect_error) {
            die("Connection failed: " . $conn->connect_error);
	    }

        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $oid = $_POST['oid'];
        try{
            $stmt=$conn->prepare("SELECT price FROM ord WHERE oid = :oid");
            $stmt->execute(array('oid' => $oid));
            $row = $stmt->fetch();
            $total_price = $row['price'];
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
						<th scope="col">Order Quantity</th>
						</tr>
					</thead>
					
			EOT;
            $stmt=$conn2->prepare("SELECT oid, pid, number, image, img_datatype, product_name, price FROM order_num WHERE oid = ?");
            $stmt->bind_param("i",$oid);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = 1;
            $subtotal = 0;
            while($row = $result->fetch_assoc())
            {
                $product_name = $row['product_name'];
                $image = $row['image'];
                $price = $row['price'];
                $quantity = $row['number'];
                $subtotal = $subtotal + $price * $quantity;
                echo <<<EOT
                    <!DOCTYPE html>
                    <html>
                    <tbody>
                        <tr>
                        <th scope="row">$count</th>
                        <td><img width="128" height="128" src="data:{$row['img_datatype']};base64,$image" /></td>
                        <td>{$product_name}</td>
                        <td>{$price} </td>
                        <td>{$quantity} </td>
                    EOT;
                $count = $count +1;
            }
            $Delivery_fee = $total_price - $subtotal;
            echo <<<EOT
            </tbody>
            <td><p>Subtotal  $subtotal</p></td>
            <td><p>Delivery fee  $Delivery_fee</p></td>
            <td><p>Total  $total_price</p></td>
            <form action="myOrder.php" method="post">
            <input type = "hidden" name = "sType" id = "sType" value = {$_POST['sType']}>
            <td><input type="submit" class="btn btn-info" value="Return"></td>
            </form>
            EOT;
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
            window.location.replace("shopOrder.php");
            </script>
            </body>
            </html>
            EOT;
        }
        ?>
