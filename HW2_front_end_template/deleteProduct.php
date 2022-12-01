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
    if(!isset($_POST['pid']))
    {
      header("Location: index.html");
      exit();
    }
    if (strlen($_POST['pid']) == 0)
      throw new Exception('Please enter the numbers.');

	$pid=$_POST['pid'];
    
	$pid = (int)$pid;

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM product WHERE pid=:pid";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array('pid'=>$pid));
    header("Location: productinfo.php");
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
       window.location.replace("productinfo.php");
     </script>
     </body>
     </html>
     EOT;
  }
?>