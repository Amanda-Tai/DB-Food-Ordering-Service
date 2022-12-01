<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "obereat";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "drop table ord";

if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully". "<br>";
} else {
  echo "Error deleting record: " . $conn->error;
}


$sql = "drop table order_num";

if ($conn->query($sql) === TRUE) {
  echo "order_num deleted successfully". "<br>";
} else {
  echo "Error deleting record: ". $conn->error;
}

$sql = "drop table product";

if ($conn->query($sql) === TRUE) {
  echo "produc deleted successfully". "<br>";
} else {
  echo "Error deleting record: " . $conn->error;
}

$sql = "drop table shop";

if ($conn->query($sql) === TRUE) {
  echo "shop deleted successfully". "<br>";
} else {
  echo "Error deleting record: " . $conn->error;
}

$sql = "drop table tradeoff";

if ($conn->query($sql) === TRUE) {
  echo "tradeoff deleted successfully". "<br>";
} else {
  echo "Error deleting record: " . $conn->error;
}


$sql = "drop table user";

if ($conn->query($sql) === TRUE) {
  echo "user deleted successfully". "<br>";
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
