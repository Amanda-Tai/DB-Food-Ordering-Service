<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oberEat";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // sql to create table
  $sql = "CREATE TABLE user (
  uid INT AUTO_INCREMENT,
  uname VARCHAR(256) NOT NULL,
  account VARCHAR(256) NOT NULL,
  phonenumber VARCHAR(10) NOT NULL,
  pwd VARCHAR(64) NOT NULL,
  salt INT(4) NOT NULL,
  latitude FLOAT(25) NOT NULL,
  longitude FLOAT(25) NOT NULL,
  balance int NOT NULL,
  identity int NOT NULL, 

  PRIMARY KEY(uid)
  )";

  $conn->exec($sql);

  $sql = "CREATE TABLE distance(
  sid INT AUTO_INCREMENT,
  distance INT NOT NULL,

  FOREIGN KEY(sid) REFERENCES shop(sid)
  )";

  $sql = "CREATE TABLE shop (
  sid INT AUTO_INCREMENT,
  uid INT NOT NULL,
  name VARCHAR(256) NOT NULL,
  latitude FLOAT(25) NOT NULL,
  longitude FLOAT(25) NOT NULL,
  location geometry NOT NULL,
  phonenumber VARCHAR(10) NOT NULL,
  category VARCHAR(256) NOT NULL,

  PRIMARY KEY(sid),
  FOREIGN KEY(uid) REFERENCES user(uid)
  )";

  $conn->exec($sql);

  $sql = "CREATE TABLE tradeoff (
  tid INT AUTO_INCREMENT,
  uid INT NOT NULL,
  ouid INT NOT NULL,
  amount INT NOT NULL,
  time VARCHAR(256) NOT NULL,
  type INT NOT NULL,

  PRIMARY KEY(tid),
  FOREIGN KEY(ouid) REFERENCES user(uid),
  FOREIGN KEY(uid) REFERENCES user(uid)
  )";

  $conn->exec($sql);

  $sql = "CREATE TABLE ord (
  oid INT AUTO_INCREMENT,
  sid INT NOT NULL,
  uid INT NOT NULL,
  state INT NOT NULL,
  start_time VARCHAR(256) NOT NULL,
  end_time VARCHAR(256) NOT NULL,
  distance INT NOT NULL,
  price INT NOT NULL,
  type INT NOT NULL,
  
  PRIMARY KEY(oid),
  FOREIGN KEY(sid) REFERENCES shop(sid),
  FOREIGN KEY(uid) REFERENCES user(uid)
  )";

  $conn->exec($sql);

  $sql = "CREATE TABLE product (
  pid INT AUTO_INCREMENT,
  sid INT NOT NULL,
  product_name VARCHAR(256) NOT NULL,
  price INT NOT NULL,
  quantity INT NOT NULL,
  image MEDIUMBLOB NOT NULL,
  img_datatype VARCHAR(256) NOT NULL,

  PRIMARY KEY(pid),
  FOREIGN KEY(sid) REFERENCES shop(sid)
  )";

  $conn->exec($sql);
 
  $sql = "CREATE TABLE order_num (
  oid INT NOT NULL,
  pid INT NOT NULL,
  number INT NOT NULL,
  price INT NOT NULL,
  image MEDIUMBLOB NOT NULL,
  img_datatype VARCHAR(256) NOT NULL,
  product_name VARCHAR(256) NOT NULL,

  FOREIGN KEY(oid) REFERENCES ord(oid)
  
  )";

  $conn->exec($sql);
 
  echo "Table obereat created successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>