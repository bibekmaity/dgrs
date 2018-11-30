<?php
header("X-XSS-Protection: 1;mode = block");
include('./header1.php');
//$ses_hid_lang=''; 

?>
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title"></h3>
</div>
<div class="box-body">
<table  class="table table-bordered table-striped">
<thead>
 <?php
  if($ses_hid_lang=='B')
  {
    ?> 
    <tr>
    <th>অভিযোগ নং</th>
<!--    <th>অভিযোগের তারিখ</th>-->
    <th>অভিযোগের ধরন</th>
    <th>অবস্থান</th>
    <th>সমাধানের তারিখ</th>
    </tr>
    
    <?php 
  }
  else
  {
    ?> 
    <tr>
    <th>Ticket No</th>
<!--    <th>Ticket Date</th> -->
    <th>Complaint Type</th>
    <th>Status</th>
    <th>Restored On</th>
    </tr>
    <?php 
  }
  ?>
</thead>
<tbody>
<?php
$sl_no=0;

$sqle ="select fm.flt_id,fm.dkt_no,fm.dkt_date,fm.status,fm.close_date ";
$sqle.=",cm.comp_type,cm.comp_type_eng from flt_mas fm ";
$sqle.=" ,compl_type_mas cm where fm.rmn=:ses_rmn and ";
$sqle.=" fm.comp_type_id=cm.comp_type_id ";
$sthc = $conn->prepare($sqle);
$sthc->bindParam(':ses_rmn', $ses_rmn);
$sthc->execute();
$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
$rowc = $sthc->fetchAll();
foreach ($rowc as $keyc => $rowe) 
{
  $sl_no++;
  $dkt_no=$rowe['dkt_no'];
  $dkt_date1=$rowe['dkt_date'];
  $close_date1=$rowe['close_date'];
  $status=$rowe['status'];
  $comp_type=$rowe['comp_type'];
  $comp_type_eng=$rowe['comp_type_eng'];

  $close_date2=british_to_ansi(substr($close_date1,0,10));
  $dkt_date2=british_to_ansi(substr($dkt_date1,0,10));
  $dkt_date=substr($dkt_date2,0,6).''.substr($dkt_date2,8,2);
  $dkt_time=substr($dkt_date1,11,5);
  
  $close_date=substr($close_date2,0,6).''.substr($close_date2,8,2);

  if($close_date=='00-00-00')
  $close_date="";
  
  if($status=='P')
  {
    $status1="Pndg.";
  }
  else
  {
    $status1="Close";
  }
  ?>
  
  <tr>
  <td><?php echo "$dkt_no <br>$dkt_date<br>$dkt_time"; ?></td>
  <?php
  if($ses_hid_lang=='B')
  {
    ?>
    <td><?php echo $comp_type; ?></td>
    <?php
  }
  else
  {
    ?>
    <td><?php echo $comp_type_eng; ?></td>
    <?php
  }
  ?>
  
  <td><?php echo $status1; ?></td>
  <td><?php echo $close_date; ?></td>
  </tr>
  <?php
}


$sqle=" select fm.flt_id,fm.dkt_no,fm.dkt_date,fm.status,fm.close_date ";
$sqle.=",cm.comp_type,cm.comp_type_eng from flt_his fm ";
$sqle.=" ,compl_type_mas cm where fm.rmn=:ses_rmn and ";
$sqle.=" fm.comp_type_id=cm.comp_type_id ";
$sqle.=" order by status DESC ,flt_id DESC LIMIT 0,10 ";
$sthc = $conn->prepare($sqle);
$sthc->bindParam(':ses_rmn', $ses_rmn);
$sthc->execute();
$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
$rowc = $sthc->fetchAll();
foreach ($rowc as $keyc => $rowe) 
{
  $sl_no++;
  $dkt_no=$rowe['dkt_no'];
  $dkt_date1=$rowe['dkt_date'];
  $close_date1=$rowe['close_date'];
  $status=$rowe['status'];
  $comp_type=$rowe['comp_type'];
  $comp_type_eng=$rowe['comp_type_eng'];

  $close_date2=british_to_ansi(substr($close_date1,0,10));
  $dkt_date2=british_to_ansi(substr($dkt_date1,0,10));
  $dkt_date=substr($dkt_date2,0,6).''.substr($dkt_date2,8,2);
  $dkt_time=substr($dkt_date1,11,5);
  
  $close_date=substr($close_date2,0,6).''.substr($close_date2,8,2);

  if($close_date=='00-00-00')
  $close_date="";
  
  $status1="Closed";
  ?>
  
  <tr>
  <td><?php echo "$dkt_no <br>$dkt_date<br>$dkt_time"; ?></td>
  <?php
  if($ses_hid_lang=='B')
  {
    ?>
    <td><?php echo $comp_type; ?></td>
    <?php
  }
  else
  {
    ?>
    <td><?php echo $comp_type_eng; ?></td>
    <?php
  }
  ?>
  
  <td><?php echo $status1; ?></td>
  <td><?php echo $close_date; ?></td>
  </tr>
  <?php
}
?>


</tbody>
</table>
</div>
<!-- /.box-body -->
</div>
<!-- /.box -->
</div>

<!-- /.col -->
</div>        
<?php
include('./footer.php');
?>