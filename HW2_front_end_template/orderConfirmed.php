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
	$conn2 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	try{
	$stmt = $conn->prepare( "select balance from user where uid = ?");
	$stmt->bind_param("i", $_SESSION['uid']);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$balance = $row['balance'];
	if($balance < $_POST['total'])
		throw new Exception('insufficient balance');
	
	$noStock = 0;
	$noStockMessage = "";
	
	$stmt = $conn->prepare( "SELECT pid, image, img_datatype, product_name, price, quantity FROM product");
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
				$name=$row['product_name'];
				$quantity=$row['quantity'];
				$pid=$row['pid'];
				if(!empty($_POST[$pid]))
				{
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
			if(is_int($key) && !isset(${$key."checked"}) && $value != "")
			{
				echo 'a';
				$deleted = 1;
				$deletedMessage = $deletedMessage."Product ".$_POST[$key."name"]. " is deleted by the stored. \\n";
			}
			}
			if($noStock)
				throw new Exception($noStockMessage);
			
			if($deleted)
				throw new Exception($deletedMessage);
	
	$stmt=$conn->prepare("UPDATE user SET balance = balance - ? WHERE uid=?");
	$stmt->bind_param("ii",$_POST['total'] ,$_SESSION['uid']);
	$stmt->execute();
	
	$stmt=$conn->prepare("UPDATE user SET balance = balance + ? WHERE uid in
						  (SELECT uid FROM shop WHERE sid = ?)");
	$stmt->bind_param("ii",$_POST['total'] , $_POST['sid']);
	$stmt->execute();
	
	$stmt=$conn->prepare("SELECT uid FROM shop WHERE sid = ?");
	$stmt->bind_param("i",$_POST['sid']);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$ownerUid = $row['uid'];
	
	$one = 1;
	$two = 2;
	
	$today = date("Y-m-d H:i:s");
	$stmt = $conn->prepare("insert into tradeoff (uid, ouid, amount, time, type) values (?, ?, ?, ?, ?)");
	$stmt->bind_param("iiisi", $_SESSION['uid'], $ownerUid, $_POST['total'], $today, $one);
	$stmt->execute();
	
	$stmt = $conn->prepare("insert into tradeoff (uid, ouid, amount, time, type) values (?, ?, ?, ?, ?)");
	$stmt->bind_param("iiisi", $ownerUid, $_SESSION['uid'], $_POST['total'], $today, $two);
	$stmt->execute();
	
	$zero = 0;
	$blank = "";
	$today = date("Y-m-d H:i:s");
	$stmt=$conn->prepare("insert into ord  (sid, uid, state, start_time, end_time, distance, price, type) values (?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("iiissdii", $_POST['sid'], $_SESSION['uid'], $zero, $today, $blank, $_POST['distance'], $_POST['total'] ,$_POST['type']);
	$stmt->execute();
	
	$stmt=$conn->prepare("SELECT LAST_INSERT_ID() as oid");
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$last_id = $row['oid'];
	
	$stmt = $conn->prepare( "SELECT pid, image, img_datatype, product_name, price, quantity FROM product");
			$stmt->execute();
			$result = $stmt->get_result();
			while($row = $result->fetch_assoc()) {
				$quantity=$row['quantity'];
				$pid=$row['pid'];
				$price = $row['price'];
				$image = $row['image'];
				$img_datatype = $row['img_datatype'];
				$product_name = $row['product_name'];
				if(!empty($_POST[$pid]))
				{
					$stmt=$conn2->prepare("insert into order_num (oid, pid, number, image, img_datatype, product_name, price) values (:oid, :pid, :number, :image, :img_datatype, :product_name, :price)");
					$stmt->execute(array('oid' => $last_id, 'pid' => $pid, 'number' => $_POST[$pid], 'image' => $image, 'img_datatype' => $img_datatype, 'product_name' => $product_name, 'price' => $price,));


					
					$stmt=$conn->prepare("UPDATE product SET quantity = quantity - ? WHERE pid=?");
					$stmt->bind_param("ii",$_POST[$pid] ,$pid);
					$stmt->execute();
				}
			}
	echo <<<EOT
	<!DOCTYPE html>
	<html>
	<body>
	<script>
	alert('Order Success!');
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
       window.location.replace("search.php");
     </script>
     </body>
     </html>
     EOT;
  }

	exit();
			