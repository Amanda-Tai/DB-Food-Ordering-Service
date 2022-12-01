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
  $oid = $_POST['oid'];
  try{
		$four = 4;
		$three = 3;
		$two = 2;
		$stmt=$conn->prepare("SELECT state, oid, uid, price FROM ord WHERE oid=?");
		$stmt->bind_param("i", $oid);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		if($row['state'] == 2)
		{
		echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("The order is already canceled by user.");
        window.location.replace("reload.php");
        </script>
        </body>
        </html>
        EOT;
	}
		
		$customerUid = $row['uid'];
		$price = $row['price'];
		$stmt=$conn->prepare("UPDATE user SET balance = balance + ?  WHERE uid=?");
		$stmt->bind_param("ii",$price, $customerUid);
		$stmt->execute();
		
		$stmt=$conn->prepare("UPDATE user SET balance = balance - ?  WHERE uid=?");
		$stmt->bind_param("ii",$price, $_SESSION['uid']);
		$stmt->execute();
		
		$stmt = $conn->prepare( "SELECT number , pid FROM order_num WHERE oid = ?");
		$stmt->bind_param("i",$oid);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			$pid = $row['pid'];
			$number=$row['number'];
			$stmt=$conn->prepare("UPDATE product SET quantity = quantity + ? WHERE pid = ?");
			$stmt->bind_param("ii",$number ,$pid);
			$stmt->execute();
		}
		
		$today = date("Y-m-d H:i:s");
		
		$stmt = $conn->prepare("insert into tradeoff (uid, ouid, amount, time, type) values (?, ?, ?, ?, ?)");
		$stmt->bind_param("iiisi", $customerUid,  $_SESSION['uid'], $price, $today, $four);
		$stmt->execute();
	
		$stmt = $conn->prepare("insert into tradeoff (uid, ouid, amount, time, type) values (?, ?, ?, ?, ?)");
		$stmt->bind_param("iiisi",  $_SESSION['uid'], $customerUid, $price, $today, $three);
		$stmt->execute();
		
		$stmt=$conn->prepare("UPDATE ord SET state = ?, end_time = ? WHERE oid=?");
		$stmt->bind_param("isi",$two , $today, $oid);
		$stmt->execute();
		echo <<<EOT
	<!DOCTYPE html>
	<html>
	<body>
	<script>
	alert('Order canceled!');
	window.location.replace("reload.php");
	</script>
	</body>
	</html>
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
</body>