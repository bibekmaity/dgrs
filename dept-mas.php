<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); 
?>
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">Department Master </h3>
</div>
<div class="box-body">
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
<th> Sl</th>
<th>Department Name </th>
<th>বিভাগের নাম</th>
<?php
if($ses_user_type=="A")
{
	?>
	<th><a href="<?php echo $full_url; ?>/dept-insert.php"><i class="fa fa-plus" aria-hidden="true"></i></a></th>
	<?php
}
?>
</tr>
</thead>
<tbody>
<?php
$sl_no=0;
$sqle ="select dept_id,de_eng,dept_nm from dept_mas ";
$sqle.=" order by dept_id " ;
$sthc = $conn->prepare($sqle);
$sthc->execute();
$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
$rowc = $sthc->fetchAll();
foreach ($rowc as $keyc => $rowe) 
{
	$sl_no++;
	$dept_id=$rowe['dept_id'];
	$de_eng=$rowe['de_eng'];
	$dept_nm=$rowe['dept_nm'];
	?>
	
	<tr>
	<td><?php echo $sl_no; ?></td>
	<td><?php echo $de_eng; ?></td>
	<td><?php echo $dept_nm; ?></td>
   <?php
   if($ses_user_type=="A")
   {
   	?>
   
	<td>
	<a href="dept-edit.php?hr_id=<?php echo md5($dept_id);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a> &nbsp;
   	</td>
   	<?php
    }
	else
	{
   	?>
    <td>&nbsp;</td>
    <?php }?>
	</tr>
	<?php
}
?>
</tbody>
<tfoot>
<tr>
<th> Sl</th>
<th>Sub Division </th>
<th>উপ বিভাগের নাম</th>
<?php
if($ses_user_type=="A")
{
	?>
	<th><a href="<?php echo $full_url; ?>/mouza-insert.php"><i class="fa fa-plus" aria-hidden="true"></i></a></th>
	<?php
}
else
{
	?>
    <td>&nbsp;</td>
    <?php }
    
?>
</tr>
</tfoot>
</table>
</div>
<!-- /.box-body -->
</div>
<!-- /.box -->
</div>

<!-- /.col -->
</div>      
<?php include('./footer.php'); ?>     
