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
	$count = 0;
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
  $oid = $_POST['oid'];
  
  try
  {	
	$four = 4;
	$three = 3;
	$two = 2;
	$one = 1;
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
	
	$today = date("Y-m-d H:i:s");
	
		
	$stmt=$conn->prepare("UPDATE ord SET state = ?, end_time = ? WHERE oid=?");
		$stmt->bind_param("isi",$one , $today, $oid);
		$stmt->execute();
	
	echo <<<EOT
	<!DOCTYPE html>
	<html>
	<body>
	<script>
	alert('Order is done!');
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