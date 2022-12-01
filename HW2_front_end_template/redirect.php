<?php
	session_start();
	$_SESSION['price_up'] = $_POST['price_up'];
	$_SESSION['price_down'] = $_POST['price_down'];
	$_SESSION['distance'] = $_POST['distance'];
	$_SESSION['category'] = $_POST['category'];
	$_SESSION['s_meal'] = $_POST['s_meal'];
	$_SESSION['s_shop'] = $_POST['s_shop'];
	header("Location: nav.html");
	exit();
?>