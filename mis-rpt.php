<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); 
?>
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
<div class="box-header  with-border">
<h3 class="box-title">Monthly Progress Report </h3>
</div>
<form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()" action="<?php echo $full_url; ?>/mis-rpt-out.php" target="_blank">
<input type="hidden" id="hid_uid" value="<?php echo $ses_uid; ?>"/>
<input type="hidden" name="csrftoken" value="<?php echo $ses_token; ?>" />
<div class="box-body">

<div class="col-md-6">
<div class="form-group">
<label for="Period" class="col-sm-4">Select Month</label>
<div class="col-sm-6">
<select name="month" id="month" class="form-control select2">
<option value="">Select Month</option>
<?php
$sqle=" select mon_cd,mon_desc from month_mas WHERE 1=1 ";
$sthc = $conn->prepare($sqle);
$sthc->execute();
$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
$rowc = $sthc->fetchAll();
foreach ($rowc as $keyc => $rowe) 
{
	$mon_cd=$rowe['mon_cd'];
	$mon_desc=$rowe['mon_desc'];									
	?>
	<option value="<?php echo $mon_cd; ?>"><?php echo $mon_desc; ?></option>
	<?php
}
?>
</select>
</div>
</div>
<div class="form-group">
<label for="Memo No" class="col-sm-4">Memo No</label>
<div class="col-sm-6">
  <input type="text" name="memo_no" id="memo_no" class="form-control"  autocomplete="off"  placeholder="Memo No" required />
</div>
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label for="Period" class="col-sm-2">Year</label>
<div class="col-sm-3">
  <input type="text" name="year" id="year" class="form-control"  autocomplete="off"  placeholder="Year" required  maxlength="4" minlength="4" />
</div>
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label for="Period" class="col-sm-2">Date</label>
<div class="col-sm-3">
  <input type="text" name="date" id="date" class="form-control"  autocomplete="off"  placeholder="Date"  data-inputmask="'alias': 'dd/mm/yyyy'" data-mask required />
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
		var month=$("#month").val();
		var reservation=$("#year").val();
		var memo_no=$("#memo_no").val();
		var date=$("#date").val();
		
		if(year=="")
		{
			alertify.error("Please input year");
			$('#year').focus();
			return false;
		}
		if(memo_no=="")
		{
			alertify.error("Please input Memo No");
			$('#memo_no').focus();
			return false;
		}
	/*	if(memo_no!="")
	    {
	   	    if(!/^[A-Za-z0-9_@./#&+-,()' ]*$/.test(memo_no))
		    {
	        	alertify.error("Please input valid memo_no")
	        	$('#memo_no').focus();
		    	return false;
	        }
	    }*/
		if(date=="")
	    {
			alertify.error('Please input  a  Date');
			$('#date').focus();
			return false;
	    }

	   if(date!="")
	   {
	   	    if(!/^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/.test(date))
		    {
	        	alertify.error("Please input valid  Date")
	            $('#date').focus();
		       return false;
	        }
	   } 
	});

</script>

<?php 
include('./footer.php'); 
?>