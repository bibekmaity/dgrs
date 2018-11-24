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
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
<th>Department</th>
<th>Officer</th>
<th class="one">Contact No</th>
<th class="one">Schemes</th>
<th><i class="fa fa-info-circle"></i></th>
</tr>
  
</thead>
<tbody>
<?php
$sl_no=0;

$sqle ="select id,department,officer_in_chrg,mobile_no,schemes ";
$sqle.="  from medinipur_data ";
$sthc = $conn->prepare($sqle);
$sthc->execute();
$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
$rowc = $sthc->fetchAll();
foreach ($rowc as $keyc => $rowe) 
{
  $id=$rowe['id'];
  $department=$rowe['department'];
  $officer_in_chrg=$rowe['officer_in_chrg'];
  $mobile_no=$rowe['mobile_no'];
  $schemes=$rowe['schemes'];

  ?>
  
  <tr>
  <td style="width:10%;"><?php echo "$department"; ?></td>
  <td><?php echo $officer_in_chrg; ?></td>
  
  <td class="one"><?php echo $mobile_no; ?></td>
  <td class="one" style="width:25%;"><?php echo $schemes; //substr($schemes,0,50); ?></td>
  <td>
    <a href="other-info-dtls.php?hr_id=<?php echo md5($id);?>"><i class="fa fa-info-circle"></i></a> &nbsp;
  </td>

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
<style>
@media only screen and (max-width: 800px) {
  .one
  {     
	 display: none;
  }
  
}
</style>        
<?php
include('./footer.php');
?>