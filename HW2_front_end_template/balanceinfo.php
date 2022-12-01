<!DOCTYPE html>
<html>
<body>

<?php
session_start();
if(!isset($_SESSION['Authenticated']))
  {
     echo <<<EOT
     <!DOCTYPE html>
     <html>
     <body>
     <script>
     alert("Please log in.");
     </script>
     </body>
     </html>
     EOT;
	 echo "<script>window.top.location.href = \"toIndex.php\";</script>";
	 exit();
  }
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "obereat";

// Create connection
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// Check connection

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare( "SELECT balance FROM user where uid =:uid ");
$stmt->execute(array('uid' => $_SESSION["uid"]));
$row = $stmt->fetch();
$balance = $row['balance'];
$conn = NULL;
?>

<p><?php echo "Wallet balance: ", $balance?></p>
</body>
</html>
