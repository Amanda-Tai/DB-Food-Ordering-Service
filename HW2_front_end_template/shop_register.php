<?php
session_start();
if($_SESSION['Authenticated']==false)
  {
	  header("Location: index.html");
	  exit();
  }
$uid=$_SESSION['uid'];
$dbservername='localhost';
$dbname='obereat';
$dbusername='root';
$dbpassword=NULL;
try 
{
    if (!isset($_POST['shop_name']) || !isset($_POST['shop_category']) || !isset($_POST['latitude']) || !isset($_POST['longitude']))
    {
        header("Location: nav.html");
        exit();
    }
    if (strlen($_POST['shop_name']) == 0 || strlen($_POST['shop_category']) == 0 || strlen($_POST['latitude']) == 0 || strlen($_POST['longitude']) == 0)
        throw new Exception('欄位不可空白');

    $shop_name=$_POST['shop_name'];
    $shop_category=$_POST['shop_category'];
    $latitude=$_POST['latitude'];
    $longitude=$_POST['longitude'];

	if(!is_numeric($latitude))
	    throw new Exception('Please enter a vaild number.');

	if(!is_numeric($latitude))
	    throw new Exception('Please enter a vaild number.');

    if ($latitude>90 || $latitude<-90)
        throw new Exception('經度範圍為 -90~90');
    if ($longitude>180 || $longitude<-180)
        throw new Exception('緯度範圍為 -180~180');
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare("select name from shop where name=:shop_name");
    $stmt->execute(array(':shop_name' => $shop_name));
    if ($stmt->rowCount()!=0)
        throw new Exception('店名已被註冊');
    if ($stmt->rowCount()==0)
    {
        $stmt=$conn->prepare("insert into shop (name, uid, category, longitude, latitude, location) values (:name, :uid, :category, :longitude, :latitude, ST_GeomFromText(:point))");
        $stmt->execute(array(':name' => $shop_name, ':uid' => $uid, ':category' => $shop_category, ':latitude' => $latitude, ':longitude' => $longitude, ':point' => 'POINT(' . $longitude . ' ' . $latitude . ')'));
        $stmt=$conn->prepare("update user set identity = 1 where uid = :uid");
        $stmt->execute(array(':uid' => $uid));
		$_SESSION['identity']=1;
		$stmt = $conn->prepare( "SELECT sid FROM shop where uid =:uid ");
		$stmt->execute(array(':uid' => $uid));
		$row = $stmt->fetch();
        $_SESSION['sid']=$row['sid'];
		
        
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("店家新增成功.");
        window.location.replace("registerCheck.php");
        </script> </body> </html>
        EOT;
        exit();
    }
    else
        throw new Exception("店家新增失敗.");
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
    window.location.replace("registerCheck.php");
    </script>
    </body>
    </html>
    EOT;
}
?>