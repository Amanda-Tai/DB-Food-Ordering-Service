<?php
session_start();
$_SESSION['Authenticated']=false;
$dbservername='localhost';
$dbname='obereat';
$dbusername='root';
$dbpassword=NULL;
try 
{
    if (!isset($_POST['uname']) || !isset($_POST['phonenumber']) || !isset($_POST['account']) || !isset($_POST['password']) || !isset($_POST['re-password']) || !isset($_POST['latitude']) || !isset($_POST['longitude']))
    {
        header("Location: index.html");
        exit();
    }
    if (strlen($_POST['uname']) == 0 || strlen($_POST['phonenumber']) == 0 || strlen($_POST['account']) == 0 || strlen($_POST['password']) == 0 || strlen($_POST['re-password']) == 0|| strlen($_POST['latitude']) == 0|| strlen($_POST['longitude']) == 0)
        throw new Exception('欄位不可為空');
    $uname=$_POST['uname'];
    $phonenumber=$_POST['phonenumber'];
    $account=$_POST['account'];
    $pwd=$_POST['password'];
    $re_pwd=$_POST['re-password'];
    $latitude=$_POST['latitude'];
    $longitude=$_POST['longitude'];

    for ($i = 0; $i < strlen($account); $i++){
	$char = $account[$i];
	if(!preg_match('/\d/', $char) && !preg_match('/[a-zA-Z]/', $char))
	    throw new Exception(' Account must contain only numbers and letters');
    }

    for ($i = 0; $i < strlen($pwd); $i++){
	$char = $pwd[$i];
	if(!preg_match('/\d/', $char) && !preg_match('/[a-zA-Z]/', $char))
	    throw new Exception(' Password must contain only numbers and letters');
    }

    for ($i = 0; $i < strlen($phonenumber); $i++){
	$char = $phonenumber[$i];
	if(!preg_match('/\d/', $char) || strlen($phonenumber) != 10)
	    throw new Exception(' Invaild phonenumber format');
    }

    for ($i = 0; $i < strlen($uname); $i++){
	$char = $uname[$i];
	if(!preg_match('/[a-zA-Z]/', $char) && !ctype_space($char))
	    throw new Exception(' Please enter a correct name');
    }

    if(!is_numeric($latitude))
	    throw new Exception('Please enter a vaild latitude.');

	if(!is_numeric($longitude))
	    throw new Exception('Please enter a vaild longitude.');
	
    if ($pwd != $re_pwd)
        throw new Exception(' 密碼驗證 ≠ 密碼');
    if ($latitude>90 || $latitude<-90)
        throw new Exception('經度範圍為 -90~90');
    if ($longitude>180 || $longitude<-180)
        throw new Exception('緯度範圍為 -180~180');

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare("select uname from user where account=:account");
    $stmt->execute(array('account' => $account));
    if ($stmt->rowCount()!=0)
        throw new Exception('帳號已被註冊');
    if ($stmt->rowCount()==0)
    {
        $salt=strval(rand(1000,9999));
        $hashvalue=hash('sha256', $salt.$pwd);
        $stmt=$conn->prepare("insert into user (uname, phonenumber, account, pwd, salt, latitude, longitude, balance) values (:uname, :phonenumber, :account, :pwd, :salt, :latitude, :longitude, 0)");
        $stmt->execute(array(':uname' => $uname, ':phonenumber' => $phonenumber, ':account' => $account, ':pwd' => $hashvalue, ':salt' => $salt, ':latitude' => $latitude, ':longitude' => $longitude));
        $_SESSION['Authenticated']=true;
        $_SESSION['username']=$uname;
		$_SESSION['sid']=-1;
		$stmt = $conn->prepare( "SELECT uid FROM user where account =:account");
		$stmt->execute(array('account' => $account));
		$row = $stmt->fetch();
		$_SESSION['uid']=$row['uid'];
		$_SESSION['identity']=0;
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("註冊成功.");
        window.location.replace("nav.html");
        </script> </body> </html>
        EOT;
        exit();
    }
    else
        throw new Exception("Login failed.");
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