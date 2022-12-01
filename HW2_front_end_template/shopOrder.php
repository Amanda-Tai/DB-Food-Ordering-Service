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
			if(!isset($_POST['sType']))
				$_POST['sType'] = "All";
		
			if($_POST['sType'] == "All")
				$sType=3;
			else if($_POST['sType'] == "Finished")
				$sType=1;
			else if($_POST['sType'] == "Canceled")
				$sType=2;
			else
				$sType=0;
			if($sType==3)
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "shopOrder.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option selected>All</option>
	<option>Finished</option>
	<option>Not Finished</option>
	<option>Canceled</option>
	</select>
	<noscript><input type="submit" value="Submit"></noscript>
	</form>
	EOT;
			}
			else if($sType==2)
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "shopOrder.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option>All</option>
	<option>Finished</option>
	<option>Not Finished</option>
	<option selected>Canceled</option>
	</select>
	<noscript><input type="submit" value="Submit"></noscript>
	</form>
	EOT;
			}
			else if($sType==1)
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "shopOrder.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option>All</option>
	<option selected>Finished</option>
	<option>Not Finished</option>
	<option>Canceled</option>
	</select>
	<noscript><input type="submit" value="Submit"></noscript>
	</form>
	EOT;
			}
			else
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "shopOrder.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option>All</option>
	<option>Finished</option>
	<option selected>Not Finished</option>
	<option>Canceled</option>
	</select>
	<noscript><input type="submit" value="Submit"></noscript>
	</form>
	EOT;
			}
			echo <<<EOT
			<!DOCTYPE html>
			<html>
			<div class="row">
			<div class="  col-xs-8">
			<table class="table" style=" margin-top: 15px;">
				<thead>
					<tr>
					<th scope="col">Order ID</th>
					<th scope="col">Status</th>
					<th scope="col">Start</th>
					<th scope="col">End</th>
					<th scope="col">Shop name</th>
					<th scope="col">Total Price</th>
					<th scope="col">Order Datails</th>
					<th scope="col">Action</th>
					<th scope="col"></th>
					</tr>
				</thead>		
			EOT;
			
			if($sType == 3)
			{
				$stmt = $conn->prepare("select oid, state, start_time, end_time,sid, price from ord where sid = ?");
				$stmt->bind_param("i", $_SESSION['sid']);
			}
			else
			{
				$stmt = $conn->prepare("select oid, state, start_time, end_time,sid, price from ord where sid = ? and state = ?");
				$stmt->bind_param("ii", $_SESSION['sid'], $sType);
			}
			$stmt->execute();
			$result = $stmt->get_result();
			$count = 0;
			while($row = $result -> fetch_assoc())
			{
				$oid=$row['oid'];
				$state = $row['state'];
				if($state == 0)
				{
					$status="Not Finished";
				}
				else if($state == 1)
				{
					$status="Finished";
				}
				else
				{
					$status="Canceled";
				}
				$sTime = $row['start_time'];
				$eTime = $row['end_time'];
				$price = $row['price'];
				$stmt = $conn->prepare("select name from shop where sid = ?");
				$stmt->bind_param("i", $row['sid']);
				$stmt->execute();
				$result2 = $stmt->get_result();
				$row2 = $result2 -> fetch_assoc();
				$shop = $row2['name'];
				echo <<< EOT
					<!DOCTYPE html>
					<html>
					<tbody>
					<td>{$oid}</td>
					<td>{$status}</td>
					<td>{$sTime}</td>
					<td>{$eTime}</td>
					<td>{$shop}</td>
					<td>{$price}</td>
					<form action = myOrderDetailShop.php method = "POST">
					<input type = "hidden" name = "sType" id = "sType" value = {$_POST['sType']}>
					<input type = "hidden" name = "oid" id = "oid" value = {$oid}>
					<td><input type="submit" align="left" class="btn btn-info" value="order details"></td>
					</form>
					<form action = done.php method = "POST">
					<input type = "hidden" name = "oid" id = "oid" value = {$oid}>
					EOT;
					if($state == 0)
					{
					echo <<< EOT
					<td><input type="submit" align="right" class="btn btn-success" value="Done"></td>
					</form>
					<form action = cancel.php method = "POST">
					<input type = "hidden" name = "oid" id = "oid" value = {$oid}>
					<td><input type="submit" class="btn btn-danger" value="Cancel"></td>
					</form>
					EOT;
					}	
				$count = $count + 1;
			}
			echo<<< EOT
				</table>
				EOT;
			if($_SESSION['identity'] == 0)
			{
				echo <<< EOT
				<p>Open your own shop to see the records.</p>
				EOT;
			}
			else
			{
			if($_POST['sType'] == "All")
				$message = "No order record";
			else
				$message = "No ".strtolower($_POST['sType'])." record";
			if($count == 0)
			{
				echo <<< EOT
				<p>{$message}</p>
				EOT;
			}
			}
?>
</body>