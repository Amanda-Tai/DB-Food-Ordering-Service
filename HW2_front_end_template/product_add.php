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
$sid=$_SESSION['sid'];
try 
{
	if(!$_SESSION['identity'])
		throw new Exception('You register a shop first!');
	
    if (!isset($_POST['product_name']) || !isset($_POST['price']) || !isset($_POST['quantity']) || !isset($_FILES["myFile"]["tmp_name"]))
    {
        header("Location: index.html");
        exit();
    }
    if (strlen($_POST['product_name']) == 0 || strlen($_POST['price']) == 0 || strlen($_POST['quantity']) == 0 || strlen($_FILES["myFile"]["tmp_name"]) == 0)
        throw new Exception('欄位不可空白');
	
	if ( $_POST['price'] < 0 || $_POST['quantity'] < 0)
		throw new Exception("price or quantity can't be negative");
	
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    //開啟圖片檔
    $file = fopen($_FILES["myFile"]["tmp_name"], "rb");
    // 讀入圖片檔資料
    $fileContents = fread($file, filesize($_FILES["myFile"]["tmp_name"])); 
    //關閉圖片檔
    $file = fclose($file);
    //讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
    $fileContents = base64_encode($fileContents);
    $img_datatype=$_FILES["myFile"]["type"];
	
    $product_name=$_POST['product_name'];
    $price=$_POST['price'];
    $quantity=$_POST['quantity'];
    $stmt=$conn->prepare("select * from product where product_name=:product_name and sid=:sid");
    $stmt->execute(array(':product_name' => $product_name, ':sid' => $sid));

    if ($stmt->rowCount()!=0)
        throw new Exception('商店已有此商品');

    $stmt=$conn->prepare("insert into product (sid, product_name, price, image, img_datatype, quantity) values (:sid, :product_name, :price, :image, :img_datatype, :quantity)");
    $stmt->execute(array(':sid' => $sid, ':product_name' => $product_name, ':price' => $price, ':image' => $fileContents, ':img_datatype' => $img_datatype, ':quantity' => $quantity));
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("成功加入商品.");
        window.location.replace("nav.html");
        </script> </body> </html>
        EOT;
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
</body>
</html