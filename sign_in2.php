<?php
session_start();
include_once('conf/db_connect.php');
connect();
// Define $myusername and $mypassword
$uname=$_POST['username'];
$pword=$_POST['password'];
// To protect MySQL injection (more detail about MySQL injection)
$uname=stripslashes($uname);
$pword=stripslashes($pword);
$uname=mysqli_real_escape_string($link, $uname);
$pword=mysqli_real_escape_string($link, $pword);

$result=query("SELECT * FROM customer WHERE uname='$uname' AND pwd='$pword'");
$count=mysqli_num_rows($result);
$row = mysqli_fetch_array($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
  $_SESSION['logged_in'] = 1;
  $_SESSION['id'] = $row['id'];
$priv = $row['priviledge'];
  $_SESSION['priv'] = $priv;
switch ($priv) {
  case '0':
    redirect('admin/index.php');
    break;
  case '1':
    redirect('teller/index.php');
    break;
  default:
    redirect("index.php");
    break;
}

}
else {
echo "Wrong Username or Password";
    echo $uname; echo $pword;
}

?>
