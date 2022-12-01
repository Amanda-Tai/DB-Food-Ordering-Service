<?php
  session_start();
  $_SESSION['Authenticated']=false;
  $dbservername='localhost';
  $dbname='obereat';
  $dbusername='root';
  $dbpassword='';
  try
  {
    if (!isset($_POST['uname']) || !isset($_POST['pwd']))
    {
      header("Location: index.html");
      exit();
    }
    if (strlen($_POST['uname']) == 0 || strlen($_POST['pwd']) == 0)
      throw new Exception('Please input user name and password.');

    $uname=$_POST['uname'];
    $pwd=$_POST['pwd'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);

    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare("select uid, uname, pwd, identity, salt from user where account=:account ");
    $stmt->execute(array(':account' => $uname));
    if($stmt->rowCount()==1)
    {
      $row = $stmt->fetch();
      if($row['pwd']==hash('sha256',$row['salt'].$_POST['pwd']))
      {
        $_SESSION['Authenticated']=true;
	    $_SESSION['uid'] = $row['uid'];
		$_SESSION['identity'] = $row['identity'];
		
        if($row['identity']==1)
		{
			$stmt=$conn->prepare("select sid from shop where uid=:uid");
			$stmt->execute(array(':uid' => $_SESSION['uid']));
			if($stmt->rowCount()==1){
				$row = $stmt->fetch();
				$_SESSION['sid']=$row['sid'];
			}
		}
        header("Location: nav.html");
        exit();
      }
      else
        throw new Exception('The account name or password that you have entered is incorrect.');
    }
    else
      throw new Exception('The account name or password that you have entered is incorrect.');
    }
   catch(Exception $e)
   {
     $msg=$e->getMessage();
     session_unset();
     session_destroy();
     echo <<<EOT
     <!DOCTYPE html>
     <html>
     <body>
     <script>
     alert("$msg");
       window.location.replace("index.html");
     </script>
     </body>
     </html>
     EOT;
   }
?>