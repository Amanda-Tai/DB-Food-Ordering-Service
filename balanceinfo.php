balanceinfo.php
今年稍早
6月16日

̇和何承原編輯了 1 個項目
HTML
balanceinfo.php
6月14日

̇共用了 1 個項目
HTML
balanceinfo.php
可以編輯
任何知道這個連結的網際網路使用者
6月14日

̇上傳了 1 個項目
HTML
balanceinfo.php
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
