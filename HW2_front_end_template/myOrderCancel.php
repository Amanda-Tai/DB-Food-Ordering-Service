<?php
session_start();
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
    
    $stmt=$conn->prepare("SELECT sid, uid, price, state FROM ord WHERE oid = :oid");
    $stmt->execute(array('oid' => $oid));
    $row = $stmt->fetch();
    $sid = $row['sid'];
    $buyuid = $row['uid'];
    $price = $row['price'];
    
	$stmt=$conn2->prepare("SELECT state, oid, uid, price FROM ord WHERE oid=?");
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
        alert("The order is already canceled by the shop owner.");
        window.location.replace("reload.php");
        </script>
        </body>
        </html>
        EOT;
	}
	else if($row['state'] == 1)
	{
		echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("The order is already done.");
        window.location.replace("reload.php");
        </script>
        </body>
        </html>
        EOT;
	}
	$today = date("Y-m-d H:i:s");
	
	$stmt = $conn->prepare( "UPDATE ord SET state = 2 , end_time = :today WHERE oid=:oid ");
    $stmt->execute(array('today' => $today ,'oid' => $oid));
	
	$stmt = $conn2->prepare( "SELECT number , pid FROM order_num WHERE oid = ?");
	$stmt->bind_param("i",$oid);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
		$pid = $row['pid'];
		$number=$row['number'];
		$stmt=$conn2->prepare("UPDATE product SET quantity = quantity + ? WHERE pid = ?");
		$stmt->bind_param("ii",$number ,$pid);
		$stmt->execute();
	}

    $stmt=$conn->prepare("SELECT uid FROM shop WHERE sid = :sid");
    $stmt->execute(array('sid' => $sid));
    $row = $stmt->fetch();
    $selluid = $row['uid'];

    $four = 4;
    $three = 3;
    $stmt = $conn->prepare( "UPDATE user SET balance = balance+$price WHERE uid=:buyuid ");
    $stmt->execute(array('buyuid' => $buyuid));
    $stmt = $conn->prepare( "UPDATE user SET balance = balance-$price WHERE uid=:selluid ");
    $stmt->execute(array('selluid' => $selluid));

    $stmt = $conn->prepare("INSERT INTO tradeoff (uid, ouid, amount, time, type) VALUES ($buyuid, $selluid, $price, :today, $four)");
    $stmt->execute(array('today' => $today));
    $stmt = $conn->prepare("INSERT INTO tradeoff (uid, ouid, amount, time, type) VALUES ($selluid, $buyuid, $price, :today, $three)");
    $stmt->execute(array('today' => $today));

    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert('Cancel Successfully!');
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
       window.location.replace("myOrder.php");
     </script>
     </body>
     </html>
     EOT;
  }
?>