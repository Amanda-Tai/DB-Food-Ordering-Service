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
			else if($_POST['sType'] == "Payment")
				$sType=1;
			else if($_POST['sType'] == "Recieve")
				$sType=2;
			else
				$sType=0;
			if($sType==3)
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "transaction.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option selected>All</option>
	<option>Payment</option>
	<option>Recieve</option>
	<option>Recharge</option>
	</select>
	<noscript><input type="submit" value="Submit"></noscript>
	</form>
	EOT;
			}
			else if($sType==2)
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "transaction.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option>All</option>
	<option>Payment</option>
	<option selected>Recieve</option>
	<option>Recharge</option>
	</select>
	<noscript><input type="submit" value="Submit"></noscript>
	</form>
	EOT;
			}
			else if($sType==1)
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "transaction.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option>All</option>
	<option selected>Payment</option>
	<option>Recieve</option>
	<option>Recharge</option>
	</select>
	<noscript><input type="submit" value="Submit"></noscript>
	</form>
	EOT;
			}
			else
			{
			echo<<<EOT
	<p>Action</p>
	<form action = "transaction.php" method = "post">
	<select name='sType',id='sType' onchange='this.form.submit()'>
	<option>All</option>
	<option>Payment</option>
	<option>Recieve</option>
	<option selected>Recharge</option>
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
					<th scope="col">Record ID</th>
					<th scope="col">Action</th>
					<th scope="col">Time</th>
					<th scope="col">Trader</th>
					<th scope="col">Amount change</th>
					</tr>
				</thead>		
			EOT;
			$zero = 0;
			$one = 1;
			$two = 2;
			$three = 3;
			$four = 4;
			if($sType == 3)
			{
				$stmt = $conn->prepare("select tid, ouid, type, time, amount from tradeoff where uid = ?");
				$stmt->bind_param("i", $_SESSION['uid']);
			}
			else
			{
				if($sType == 1)
				{
					$stmt = $conn->prepare("select tid, ouid, type, time, amount from tradeoff where uid = ? and (type = ? or type = ?)");
					$stmt->bind_param("iii", $_SESSION['uid'], $one, $three);
				}
				else if($sType == 2)
				{
					$stmt = $conn->prepare("select tid, ouid, type, time, amount from tradeoff where uid = ? and (type = ? or type = ?)");
					$stmt->bind_param("iii", $_SESSION['uid'], $two, $four);
				}
				else
				{
					$stmt = $conn->prepare("select tid, ouid, type, time, amount from tradeoff where uid = ? and type = ?");
					$stmt->bind_param("ii", $_SESSION['uid'], $zero);
				}
				
			}
			$stmt->execute();
			$result = $stmt->get_result();
			$count = 0;
			while($row = $result -> fetch_assoc())
			{
				$tid=$row['tid'];
				$type = $row['type'];
				if($type == 0)
				{
					$action="Recharge";
					$amount="+".$row['amount'];
				}
				else if($type == 1 || $type == 3)
				{
					$action="Payment";
					$amount="-".$row['amount'];
				}
				else
				{
					$action="Recieve";
					$amount="+".$row['amount'];
				}
				$time = $row['time'];
				if($type == 1 || $type == 4)
				{
					$stmt = $conn->prepare("select name from shop where uid = ?");
					$stmt->bind_param("i", $row['ouid']);
				}
				else if($type == 2 || $type == 3)
				{
					$stmt = $conn->prepare("select account as name from user where uid = ?");
					$stmt->bind_param("i", $row['ouid']);
				}
				else
				{
					$stmt = $conn->prepare("select account as name from user where uid = ?");
					$stmt->bind_param("i", $_SESSION['uid']);
				}
					
				$stmt->execute();
				$result2 = $stmt->get_result();
				$row2 = $result2 -> fetch_assoc();
				$trader = $row2['name'];
				echo <<< EOT
					<!DOCTYPE html>
					<html>
					<tbody>
					<td>{$tid}</td>
					<td>{$action}</td>
					<td>{$time}</td>
					<td>{$trader}</td>
					<td>{$amount}</td>
				EOT;	
				$count = $count + 1;
			}
			echo<<< EOT
				</table>
				EOT;
			if($_POST['sType'] == "All")
				$message = "No transaction record";
			else
				$message = "No ".strtolower($_POST['sType'])." record";
			if($count == 0)
			{
				echo <<< EOT
				<p>{$message}</p>
				EOT;
			}
?>
</body>