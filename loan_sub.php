<?php
session_start();
$id = $_SESSION['id'];
$priv = $_SESSION['priv'];
include('conf/db_connect.php');
include('conf/misc.php');
connect();
$new_ans = '';
$insert_id = "";
$sure1 = $_POST['sure1'];
$sure2 = $_POST['sure2'];
//$result = query("SELECT * FROM customer WHERE")
$p1 = $_POST['amt'];
$duration = $_POST['duration'];
$sm_amt = '';
$p = '';
$emp_id = $_POST['emp_id'];
$digit = update_loan($emp_id);
$rs = query("SELECT * FROM percent WHERE id='2'");
$row = mysqli_fetch_array($rs);
$tmp = $row['value'];
if($digit[2] != 0){
$duration += $digit[1];
$sm_amt = simple_interest($p1, $tmp, $duration);
$sm_amt = round($sm_amt);
$p = $sm_amt + $p1 + $digit[0];
}else{
$sm_amt = simple_interest($p1, $tmp, $duration);
$sm_amt = round($sm_amt);
$p = $sm_amt + $p1;
}
/*$i = $tmp /100;
$d = $duration;
$j = $i/12;
$num = (1+$j);
$s = pow($num, $d);
$new_ans = $j/($s - 1);
if(1 >$s){
$new_ans = $j/(1-$s);
}
*/

$loan_amount1 = ($p1/$duration);
$loan_amount2 = ($sm_amt/$duration);
//$loan_amount1 = $new_ans * $p1;
//$loan_amount2 = $new_ans * $sm_amt;
$loan_amount = $loan_amount1 + $loan_amount2;
//$loan_amount1 = round($loan_amount1,2);
//$loan_amount2 = round($loan_amount2,2);
//$loan_amount = floor($loan_amount);
$loan_amount = round($loan_amount, 2);



$date = date('Y-m-d');
$charge = (1/100) * $p;

if($digit[2] != 0){
  query("UPDATE loan SET flag='1', status='1' WHERE emp_no='$emp_id'");
}
query("INSERT INTO loan(sure1, sure2, admin_charge, p_no, duration, interest_amount, emp_no, date_incured, amount, amort, interest, total) VALUES('$sure1', '$sure2', '$charge', '0', '$duration', '$sm_amt', '$emp_id', '$date', '$p1', '$loan_amount', '$tmp', '$p')");
$insert_id = mysqli_insert_id();


$rs = query("SELECT * FROM ad_income");
$row = mysqli_fetch_array($rs);
$bal = $row['balance'];
$int1 = $bal - $p1;
query("INSERT INTO income(income_type, amount, balance) VALUES('4', '$new_amt1', '$int1')");
query("UPDATE ad_income SET balance='$int1'");
folders($priv, "page=../view_amort&amort=$loan_amount&$insert_id");

 ?>
