<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); 
?>
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">Polie Station Master </h3>
</div>
<div class="box-body">
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
<th> Sl</th>
<th>Sub Division </th>
<th>উপ বিভাগের নাম</th>
<th>Police Station Name</th>
<th>থানার নাম</th>
<?php
if($ses_user_type=="A")
{
	?>
	<th><a href="<?php echo $full_url; ?>/ps-mas-insert.php"><i class="fa fa-plus" aria-hidden="true"></i></a></th>
	<?php
}
?>
</tr>
</thead>
<tbody>
<?php
$sl_no=0;
$sqle ="select b.ps_id,b.ps_nm,b.ps_nm_ben,s.sub_div_nm,s.sub_div_nm_ben from sub_div_mas s, ps_mas b ";
$sqle.="where b.sub_div_id=s.sub_div_id ";
if($ses_user_type!='D')
{
//$sqle.=" and b.block_id=:ses_block_id ";
}
$sqle.=" order by b.sub_div_id,b.ps_nm  " ;
$sthc = $conn->prepare($sqle);
if($ses_user_type!='D')
{
//$sthc->bindParam(':ses_block_id', $ses_block_id);
}
$sthc->execute();
$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
$rowc = $sthc->fetchAll();
foreach ($rowc as $keyc => $rowe) 
{
	$sl_no++;
	$ps_id=$rowe['ps_id'];
	$ps_nm=$rowe['ps_nm'];
	$ps_nm_ben=$rowe['ps_nm_ben'];
	$sub_div_nm=$rowe['sub_div_nm'];
	$sub_div_nm_ben=$rowe['sub_div_nm_ben'];
	?>
	
	<tr>
	<td><?php echo $sl_no; ?></td>
	<td><?php echo $sub_div_nm; ?></td>
	<td><?php echo $sub_div_nm_ben; ?></td>
	<td><?php echo $ps_nm; ?></td>
	<td><?php echo $ps_nm_ben; ?></td>
   <?php
   if($ses_user_type=="A")
   {
   	?>
   
	<td>
	<a href="ps-mas-edit.php?hr_id=<?php echo md5($ps_id);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a> &nbsp;
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
<th>Block Name</th>
<th>ব্লক নাম</th>
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
