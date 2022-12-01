<?php
	//連先資料庫
  session_start();
  $_SESSION['Authenticated']=false;
  $dbservername='localhost';
  $dbname='project';
  $dbusername='dbproject';
  $dbpassword='dbproject';
	require_once('conn.php');
	
	//查詢距離小於1000m的地點，並由遠及近排序
	$sql_distance_near = "select name.shop(POINT(latitude.user, location.shop) as distant from shop, user 
		where store_distance(POINT(latitude.user, location.shop) < 1000 order by distant";

	//查詢距離小於2000m且大於1001m的地點，並由遠及近排序
	$sql_distance_middle = "select name.shop(POINT(latitude.user, location.shop) as distant from shop, user 
		where store_distance(POINT(latitude.user, location.shop) < 2000 
		and store_distance(POINT(latitude.user, location.shop) < 1001 order by distant";

	//查詢距離小於4000m且大於2001m的地點，並由遠及近排序
	$sql_distance_far = "select name.shop(POINT(latitude.user, location.shop) as distant from shop, user
		where store_distance(POINT(latitude.user, location.shop) < 4000 
		and store_distance(POINT(latitude.user, location.shop) < 2000 order by distant";


  <button onclick="mysql_query($sql_distance_near)">近距離(小於1km)</button>
  <button onclick="mysql_query($sql_distance_middle)">中等距離(小於2km，大於1km)</button>
  <button onclick="mysql_query($sql_distance_far)">遠距離(小於4km，大於2km)</button>
	
$conn = null;
?>
	