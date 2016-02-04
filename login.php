<?php

$host="localhost"; 
$username="";  
$password=""; 
$db_name="pro"; 
$tbl_name="users"; 

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");


$username=$_POST['username']; 
$pwd=$_POST['pwd']; 


$username = stripslashes($username);
$pwd = stripslashes($pwd);
$username = mysql_real_escape_string($username);
$pwd = mysql_real_escape_string($pwd);
$sql="SELECT * FROM $tbl_name WHERE username='$username' and password='$pwd'";
$result=mysql_query($sql);

$count=mysql_num_rows($result);


if($count==1){


session_register("username");
session_register("pwd"); 
header("location:success.php");
}
else {
echo "Wrong Username or Password";
}
?>