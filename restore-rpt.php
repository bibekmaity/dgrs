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
<form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()" action="<?php echo $full_url; ?>/restore-rpt-out.php" target="_blank">
<input type="hidden" id="hid_uid" value="<?php echo $ses_uid; ?>"/>
<input type="hidden" name="csrftoken" id="csrftoken" value="<?php echo $ses_token; ?>" />

<div class="box-body">
<div class="col-md-6">
<div class="form-group">
<label for="Period" class="col-sm-2">Period</label>
<div class="col-sm-5">
  <input type="text" name="reservation" id="reservation" class="form-control"  autocomplete="off"  placeholder="Period" required />
</div>
</div>

</div>

</div>
<div class="box-footer">
<a href="<?php echo $full_url; ?>/index.php"  class="btn btn-default">Cancel</a>
<input type="submit" name="submit" id="submit" class="btn btn-primary pull-right" value="Submit">
</div>
<input type="hidden" name="hid_user_type" id="hid_user_type" value="<?php echo $ses_user_type;?>" />
</form>
</div>
</div>
</div>  
<script src="<?php echo $full_url; ?>/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
	$("#submit").click(function(){
		
		var user_type=$("#hid_user_type").val();
		var block=$("#block").val();
		var reservation=$("#reservation").val();
		
		if(reservation=="")
		{
			alertify.error("Please input date range");
			return false;
		}
	
		if(user_type=="B" && block=="")
		{
			alertify.error("Please select Block");
			return false;
		}
		
	});
	$('#from_date1').datepicker({autoclose: true});
$('#to_date1').datepicker({autoclose: true});

</script>
<?php 
include('./footer.php'); 
?>