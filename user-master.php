<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); 
$block = isset($_POST['block']) ? $_POST['block'] : ''; 
$submit = isset($_POST['submit']) ? $_POST['submit'] : ''; 
?>
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
<div class="box-header  with-border">
<h3 class="box-title">User</h3>
</div>
<form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()">


</form>
</div>
</div>
</div>
<?php
//if($submit=="Submit")
//{
	?>       
	<div class="row">
	<div class="col-xs-12">
	<div class="box">
	<div class="box-header">
	<h3 class="box-title">User List </h3>
	</div>
	<div class="box-body" id="user_list">
	<table id="example1" class="table table-bordered table-striped">
	<thead>
	<tr>
	<th>User ID</th>
	<th>User Name</th>
	<th>Status</th>
	<th>Permission</th>
	<th><a href="<?php echo $full_url; ?>/user-insert.php"><i class="fa fa-plus" aria-hidden="true"></i></a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$sl_no=0;
	$sqle ="select uid,user_id,user_nm,user_status ";
	$sqle.=" from user_mas ";
	$sqle.=" where user_type!='A' ";
	$sthc = $conn->prepare($sqle);
	$sthc->execute();
	$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
	$rowc = $sthc->fetchAll();
	$status_desc='';
	foreach ($rowc as $keyc => $rowe) 
	{
		$uid=$rowe['uid'];
		$user_id=$rowe['user_id'];
		$user_nm=$rowe['user_nm'];
		$status=$rowe['user_status'];
		if($status=="A")
		$status_desc="Active";
		else	
		$status_desc="Deactive";
		?>		
		<tr>
		<td><?php echo $user_id; ?></td>
		<td><?php echo $user_nm; ?></td>
		<td><?php echo $status_desc; ?></td>
		<td>
		<a href="<?php echo $full_url; ?>/user-permission.php?hr_id=<?php echo md5($uid);?>">
		<i class="fa fa-unlock" aria-hidden="true"></i>
		</a>
		</td>		
		<td>
		<a href="<?php echo $full_url; ?>/user-edit.php?hr_id=<?php echo md5($uid);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>
		</td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
	<th>User ID</th>
	<th>User Name</th>
	<th>Status</th>
	<th>Permission</th>
	<th><a href="<?php echo $full_url; ?>/user-insert.php"><i class="fa fa-plus" aria-hidden="true"></i></a></th>
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
	<?php
//}
?>
<?php include('./footer.php'); ?>