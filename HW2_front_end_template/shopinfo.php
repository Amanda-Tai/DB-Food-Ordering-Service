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
$stmt = $conn->prepare( "SELECT name, category, latitude, longitude FROM shop WHERE sid =:sid ");
$stmt->execute(array('sid' => $_SESSION["sid"]));
$row = $stmt->fetch();
if($_SESSION['identity']){
$name = $row['name'];
$category = $row['category'];
$latitude = $row['latitude'];
$longitude = $row['longitude'];
$conn = NULL;
}

if($_SESSION['identity'])
  <p><?php echo"Shop name: ", $name, " , category: ", $category,", " ,$phonenumber," " ,"location: " ,number_format((float) $latitude, 14, '.', '') ,", ", number_format((float) $longitude, 14, '.', '') ?></p>

?>


</body>
</html>
