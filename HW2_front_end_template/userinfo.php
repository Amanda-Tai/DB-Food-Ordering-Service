<!DOCTYPE html>
<html>
<body>

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

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare( "SELECT uname, account, identity, phonenumber, latitude, longitude, balance FROM user where uid =:uid ");
$stmt->execute(array('uid' => $_SESSION["uid"]));
$row = $stmt->fetch();
$uname = $row['uname'];
$account = $row['account'];
$identity = $row['identity'];
$phonenumber = $row['phonenumber'];
$latitude = $row['latitude'];
$longitude = $row['longitude'];
$balance = $row['balance'];
$conn = NULL;
?>

<p><?php echo "Accouont: ", $account, " , ", $uname,", " ,"PhoneNumber: " ,$phonenumber?></p>
<p><?php echo "location: " ,number_format((float) $latitude, 14, '.', '') ,", ", number_format((float) $longitude, 14, '.', '')?></p>

</body>
</html>
