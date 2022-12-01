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
    if(!isset($_POST['price'])||!isset($_POST['quantity'])||!isset($_POST['pid']))
    {
      header("Location: index.html");
      exit();
    }
    if (strlen($_POST['price']) == 0 && strlen($_POST['quantity']) == 0)
      throw new Exception('Please enter at least a number.');
	$pid=$_POST['pid'];
	$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT price,quantity FROM product WHERE pid=:pid";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array('pid'=>$pid));
	$row = $stmt->fetch();
	$quantity = $row['quantity'];
	$price=$row['price'];
	
	
	if(strlen($_POST['price']) == 0)
	{
		$_POST['price'] = (int)$row['price'];
		$_POST['price'] = (string)$_POST['price'];
	}
	
	if(strlen($_POST['quantity']) == 0)
	{
		$_POST['quantity'] = (int)$row['quantity'];
		$_POST['quantity'] = (string)$_POST['quantity'];
	}
	
  
	if ( $_POST['price'] < 0 || $_POST['quantity'] < 0)
		throw new Exception("price or quantity can't be negative");

    $price=$_POST['price'];
	$quantity=$_POST['quantity'];
    
    for ($i = 0; $i < strlen($price); $i++){
	$char = $price[$i];
	if(!preg_match('/\d/', $char))
	    throw new Exception('Please enter a vaild number.');
    }
	
	for ($i = 0; $i < strlen($quantity); $i++){
	$char = $quantity[$i];
	if(!preg_match('/\d/', $char))
	    throw new Exception('Please enter a vaild number.');
    }
	
    $price = (int)$price;
	$quantity = (int) $quantity;
	$pid = (int) $pid;

    $sql = "UPDATE product SET price=:price,quantity=:quantity WHERE pid=:pid";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array('price'=>$price, 'quantity'=>$quantity, 'pid'=>$pid));
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