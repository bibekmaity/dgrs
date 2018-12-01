<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); 
?>
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
<div class="box-header  with-border">
<h3 class="box-title">Search</h3>
</div>
<form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()" action="<?php echo $full_url; ?>/type-wise-comp-out.php" target="_blank">
<input type="hidden" id="hid_uid" value="<?php echo $ses_uid; ?>"/>
<input type="hidden" name="csrftoken" value="<?php echo $ses_token; ?>" />
<div class="box-body">
<div class="col-md-6">
<div class="form-group">
<label for="Period" class="col-sm-2">Period</label>
<div class="col-sm-5">
  <input type="text" name="reservation" id="reservation" class="form-control"  autocomplete="off"  placeholder="Period" required />
</div>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label for="Period" class="col-sm-4">Complaint Type</label>
<div class="col-sm-8">
<select name="block" id="block" class="form-control select2">
<option value="">Complaint Type</option>
<?php
$sqle=" select comp_type_id,comp_type from compl_type_mas WHERE 1=1 ";
$sthc = $conn->prepare($sqle);
$sthc->execute();
$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
$rowc = $sthc->fetchAll();
foreach ($rowc as $keyc => $rowe) 
{
	$comp_type_id=$rowe['comp_type_id'];
	$comp_type=$rowe['comp_type'];									
	?>
	<option value="<?php echo $comp_type_id; ?>"><?php echo $comp_type; ?></option>
	<?php
}
?>
</select>
</div>
</div>
</div>
</div>
<div class="box-footer">
<a href="<?php echo $full_url; ?>/index.php"  class="btn btn-default">Cancel</a>
<input type="submit" name="submit" id="submit" class="btn btn-primary pull-right" value="Submit">
</div>
</form>
</div>
</div>
</div>  

<script>
	$("#submit").click(function(){
		var block=$("#block").val();
		var reservation=$("#reservation").val();
		
		if(reservation=="")
		{
			alertify.error("Please input date range");
			return false;
		}
	});

</script>

<?php 
include('./footer.php'); 
?>