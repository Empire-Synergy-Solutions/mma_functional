
<div class="box">
  <div class="box-body">
    <?php
    $rss = query("SELECT * FROM faculty");
    while($row = mysqli_fetch_array($rss)){
      $faculty = $row['id'];
      $name1 =$row['name'];
         $rs = query("SELECT * FROM department WHERE faculty='$faculty'");
         while($row = mysqli_fetch_array($rs)){
           $idd = $row['id'];
           $name = $row['dep_name'];
    ?>

<table class="table">
<thead>
  <tr>
    <th>
      Faculty of  <?php echo $name1; ?>
    </th>
    <th>
      <h3>Department of   <?php echo $name; ?></h3>
    </th>
  </tr>
  <tr>
    <th>S/N</th>
    <th>UserId</th>
    <th>Employee No</th>
    <th>FullName</th>
    <th>Monthly Amount</th>
    <th>Balance to Pay</th>
    <th>Total Loan</th>
  </tr>
</thead>
<tbody>
<?php
$result = query("SELECT * FROM loan LEFT JOIN customer ON loan.emp_no=customer.id WHERE dept='$idd' AND flag='0'");
$i = 0;
$sum = 0;
while($row = mysqli_fetch_array($result)){
  //$rs = query("SELECT * FROM payment WHERE emp_id = '$id'");
$id = $row['emp_no'];
$fullname = $row['firstname'] . $row['surname'];
$amount = $row['amort'];
$no = $row['employee_no'];
$paid = $row['paid'];
$total = $row['total'];
$charge = $row['admin_charge'];
$bal = $total - $paid;
$bal -= $amount;
$sum += $amount;
$amount += $charge;
?>
<tr>
  <td><?php echo ++$i;?></td>
  <td><?php echo $id;?></td>
  <td><?php echo $no; ?></td>
  <td><?php echo $fullname;?></td>
  <td><?php echo $amount;?></td>
  <td><?php echo $bal;?></td>
  <td><?php echo $total;?></td>
</tr>
<?php
}
?>
<tr>
  <th>Sum Total</th>
  <td></td>
  <td></td>
  <td></td>
  <th><?php echo $sum; ?></th>
</tr>
</tbody>
</table>
<?php
}
}
 ?>
</div>
</div>
<a href="../print_loan.php" class="btn btn-primary">Print</a>
