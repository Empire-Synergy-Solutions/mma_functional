<?php
function date_splitter(){
  $date = date('Y-m-d');
  $tmp_date = preg_split("~-~", $date);
  $tmp_date = $tmp_date[0] . "-" . $tmp_date[1];
  return $tmp_date;
}
function charge_total(){
  $amount = 0;
  $arr = [0,0,0];
  $tmp_date = date_splitter();
  $result = query("SELECT * FROM charge WHERE type = '1' AND date LIKE '$tmp_date-%'");
  while($row= mysqli_fetch_array($result)){
    $date1 = $row['date'];
    $date = strtotime($date1);
          $amount += $row['amount'];
      }
  $arr[0] = $amount;

  $amount = 0;
  $result = query("SELECT * FROM charge WHERE type = '2' AND date LIKE '$tmp_date-%'");
  while($row= mysqli_fetch_array($result)){
    $date1 = $row['date'];
    $date = strtotime($date1);
          $amount += $row['amount'];
      }
  $arr[1] = $amount;
  $amount = 0;

  $result = query("SELECT * FROM income WHERE income_type = '2' AND date LIKE '$tmp_date-%'");
  while($row= mysqli_fetch_array($result)){
    $date1 = $row['date'];
    $date = strtotime($date1);
      $amount += $row['amount'];
    if(($date >= $start) && ($date <= $end)){
    }
  }
  $arr[2] = $amount;


return $arr;
}
function total_withdrawn(){
  $tmp_date = date_splitter();
  $result = query("SELECT * FROM withdraw WHERE date LIKE '$tmp_date-%'");
  $total = 0;
  $count = 0;
  while($row=mysqli_fetch_array($result)){
    $amount = $row['total_deduction'];
    $total += $amount;
    $count += 1;
  }
  $tm = [$count, $total];
  return $tm;
}

function total_deposit(){
$tmp_date = date_splitter();
$result = query("SELECT * FROM deposit WHERE date LIKE '$tmp_date-%'");
$total = 0;
$count = 0;
while($row=mysqli_fetch_array($result)){
  $amount = $row['amount'];
  $total += $amount;
  $count += 1;
}
$tm = [$count, $total];
return $tm;
}
function total_no_users(){
  $result = query("SELECT * FROM customer WHERE priviledge ='2'");
  $count = mysqli_num_rows($result);
  return $count;
}
function no_faculty(){
  $result = query("SELECT * FROM faculty");
  $count = mysqli_num_rows($result);
  return $count;
}

function no_dept(){
  $result = query("SELECT * FROM department");
  $count = mysqli_num_rows($result);
  return $count;
}

function no_accounts(){
$tmp_date = date_splitter();
$result = query("SELECT * FROM account WHERE d_opened LIKE '$tmp_date-%'");
$thrift_total = 0;
$special_total = 0;
$count = 0;
while($row=mysqli_fetch_array($result)){
  $type = $row['acct_type'];
  if($type == 1){
    $thrift_total += 1;
  }
  else{
    $special_total += 1;
  }

  $count += 1;
}
$tm = [$count, $thrift_total, $special_total];
return $tm;
}
function ts_calc($i){
  $date = date("y-m-d");
  $date =split('-', $date);
  $date = $date[0] . "-" . $date['1'];
  $count = 0;
  $result = query("SELECT * FROM account WHERE d_opened LIKE '$date-%' AND acct_type = '$i'");
  while($row = mysqli_fetch_array($result)){
    ++$count;
  }
  $rss = query("SELECT * FROM account WHERE acct_type = '$i'");
while($row= mysqli_fetch_array($rss)){
  ++$count;
}

}
function expected(){
  $arr = [0,0];
  $loan_int = 0;
  $loan_rec = 0;
  $rs = query("SELECT * FROM loan");
  while($row = mysqli_fetch_array($rs)){
    $loan_int += $row['amort_interest'];
    $loan_rec += $row['amort_loan'];
  }
  $rs = query("SELECT * FROM icas");
  while($row = mysqli_fetch_array($rs)){
    $loan_int += $row['interest'];
    $loan_rec += $row['amount'];
  }
  $arr = [$loan_int, $loan_rec];
  return $arr;
}

function income_report($start, $end){
  $start = strtotime($start);
  $end = strtotime($end);
  $arr = [0,0,0,0,0,0,0];
  $loan_int = 0;
  $loan_rec = 0;
  $rs = query("SELECT * FROM payment");
  while($row = mysqli_fetch_array($rs)){
    $date1 = $row['date'];
    $date = strtotime($date1);
    if(($date >= $start) && ($date <= $end)){
    $loan_int += $row['amort_interest'];
    $loan_rec += $row['amort_loan'];
  }
}
$arr[0] = $loan_int;
$arr[1] = $loan_rec;
$result = query("SELECT * FROM transaction WHERE description='deposit'");
$amount = 0;
$count = 0;
while($row = mysqli_fetch_array($result)){
  $date1 = $row['date'];
  $date = strtotime($date1);
  if(($date >= $start) && ($date <= $end)){
    $amount +=$row['credit'];
    ++$count;
}
  }
  $arr[2] = $amount;
  $arr[3] = $count;
  $amount = 0;
  $result = query("SELECT * FROM income WHERE income_type = '2'");
  while($row= mysqli_fetch_array($result)){
    $date1 = $row['date'];
    $date = strtotime($date1);
    if(($date >= $start) && ($date <= $end)){
      $amount += $row['amount'];
    }
  }
$arr[4] = $amount;
$result = query("SELECT * FROM charge WHERE type = '1'");
while($row= mysqli_fetch_array($result)){
  $date1 = $row['date'];
  $date = strtotime($date1);
  if(($date >= $start) && ($date <= $end)){
    $amount += $row['amount'];
  }
}
$arr[5] = $amount;

$amount = 0;
$result = query("SELECT * FROM charge WHERE type = '2'");
while($row= mysqli_fetch_array($result)){
  $date1 = $row['date'];
  $date = strtotime($date1);
  if(($date >= $start) && ($date <= $end)){
    $amount += $row['amount'];
  }
}
$arr[6] = $amount;

return $arr;
}


?>
