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
    if (!isset($_POST['value']))
    {
      header("Location: index.html");
      exit();
    }
    if (empty($_POST['value']))
      throw new Exception('Please enter add value.');

    $value=$_POST['value'];
    
    for ($i = 0; $i < strlen($value); $i++){
	$char = $value[$i];
	if(!preg_match('/\d/', $char))
	    throw new Exception('Please enter a vaild number.');
    }
    $value = (int)$value;

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE user SET balance = balance + :value WHERE uid=:uid";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array('value'=>$value, 'uid'=>$_SESSION["uid"]));
	
	$zero = 0;
	$today = date("Y-m-d H:i:s");
	$sql = "insert into tradeoff (uid, ouid, amount, time, type) values (:uid, :ouid, :amount, :time, :type)";
    $stmt = $conn->prepare($sql);
	$stmt->execute(array('uid'=>$_SESSION['uid'], 'ouid'=>$_SESSION['uid'], 'amount'=>$value, 'time'=>$today, 'type'=>$zero));
	
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