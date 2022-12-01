<?php
  session_start();
  if($_SESSION['Authenticated']==false)
  {
	  header("Location: index.html");
	  exit();
  }
  $dbservername='localhost';
  $dbname='obereat';
  $dbusername='root';
  $dbpassword='';
  try
  {
    if (!isset($_POST['latitude']) || !isset($_POST['longitude']))
    {
      header("Location: index.html");
      exit();
    }
    if (empty($_POST['latitude']) || empty($_POST['longitude']))
      throw new Exception('Please input latitude and longitude.');

    $latitude=$_POST['latitude'];
    $longitude=$_POST['longitude'];
    
	if(!is_numeric($latitude))
	    throw new Exception('Please enter a vaild number.');

	if(!is_numeric($longitude))
	    throw new Exception('Please enter a vaild number.');
	
    if ($latitude>90 || $latitude<-90)
        throw new Exception('經度範圍為 -90~90');
    if ($longitude>180 || $longitude<-180)
        throw new Exception('緯度範圍為 -180~180');

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE user SET latitude=:latitude,longitude=:longitude WHERE uid=:uid";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array('latitude'=>$latitude, 'longitude'=>$longitude, 'uid'=>$_SESSION["uid"]));
    header("Location: nav.html");
    exit();

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
       window.location.replace("nav.html");
     </script>
     </body>
     </html>
     EOT;
  }
?>