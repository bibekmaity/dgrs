<?php
header("X-XSS-Protection: 1;mode = block");
include("../inc/dblib.inc.php");
$conn = OpenDB();

$tag = isset($_POST['tag']) ? $_POST['tag'] : '';
?>


<?php
/*-------------- insert into trans mas --------------------*/
if(($tag=='REST-NOTE'))
{	
	$id = $_POST['myid'];
	$myid = isset($_POST['myid']) ? $_POST['myid'] : '';
	$hid_uid = isset($_POST['hid_uid']) ? $_POST['hid_uid'] : '';
	
    $sql_search=" select flt_id,rmn,dist_id,dkt_no from flt_mas where ";
	$sql_search.=" md5(flt_id)=:myid ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':myid', $myid);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$rmn=$row_search['rmn'];
	$dkt_no=$row_search['dkt_no'];

?>
 <link rel="stylesheet" href="./plugins/select2/select2.min.css">

<style>
.modal-body
{
	height:95px !important;
}
.modal-header,.modal-body,.modal-footer,.modal-content,.modal-dialog
{
	
	width:605px !important;
}
.modal-body .select2
{
	margin-bottom: 5px;
}
</style>
<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="./plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="./plugins/timepicker/bootstrap-timepicker.min.css">
<form method="POST" enctype="multipart/form-data" id="fileUploadForm">
    <div class="example-modal">
        <div class="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="batch-close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Docket Update of Mobile No: <?php echo $rmn; ?> & Docket No: <?php echo $dkt_no; ?></h4>
                <input type="hidden" id="hid_fault" name="hid_fault" value="<?php echo $myid; ?>">
                <input type="hidden" id="hid_uid"  name="hid_uid" value="<?php echo $hid_uid; ?>">
              </div>
              <div class="modal-body">
                  <div class="form-group">
	                <label for="Remarks" class="col-sm-3" >Remarks <font color="#FF0000">*</font></label>
	                <div class="col-sm-9">
	                	<textarea name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" tabindex="2" rows="3"></textarea>
	                </div>
          		  </div>  
              </div>
              <div class="modal-footer">
               <input type="button" name="doc_submit" id="doc_submit" class="btn btn-primary pull-right" value="Submit" tabindex="4">
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        </div>
      </div>
      </form>    
 <script type="text/javascript" src="./lib/jquery-1.11.1.js"></script>
 <script src="./bootstrap/js/bootstrap.min.js"></script>
 <script src="./plugins/select2/select2.full.min.js"></script>
<script src="./plugins/input-mask/jquery.inputmask.js"></script>
<script src="./plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="./plugins/input-mask/jquery.inputmask.extensions.js"></script>  
<script src="./js/alertify.min.js"></script>
<link rel="stylesheet" href="./css/alertify.core.css" />
<link rel="stylesheet" href="./css/alertify.default.css" />
<script>
$(function () {
$(".close").click(function(){
	modal.style.display = "none";
});
});
$(function () {
    $('.select2').select2()
  });

$( "#doc_submit").click(function() {

	alertify.confirm('Confirm Docket Solve', function (e) {
    if (e) 
    {
        var remarks = $("#remarks").val();	
		var hid_fault = $("#hid_fault").val();
		var hid_uid = $("#hid_uid").val();

		if(remarks=="")
	    {    
	       alertify.error("Remarks can't be blank");
	       $('#remarks').css("border-color","#FF0000");
	       $('#remarks').focus();
		   return false;
	    }  	
	
		if(remarks!="")
	    {
	       if(/^[/!<>]+$/.test(remarks))
	       {
	       
		       alertify.error("  </!()<> are not supported");
		       $('#remarks').css("border-color","#FF0000");
		       $('#remarks').focus();
		       return false;
	       }
	    }  

		var request = $.ajax({
	    url: "./back/restore_back.php",
	    method: "POST",
	    data: {remarks:remarks, hid_fault:hid_fault,hid_uid:hid_uid,tag: 'RES-TRAN'  },
	    dataType: "html",
	    success:function(msg) {
	    //	alert(msg);
	    alert('Docket Solve Successfully');
	    location.reload();

	  },
	  error: function(xhr, status, error) {
	            alert(status);
	            alert(xhr.responseText);
	        },
	  });
	   modal.style.display = "none";
	   
    } 
    else 
    {
       alertify.error("Docket not resolve");
       modal.style.display = "none";
    }
    });

	
	
	 
  });	
</script>
<?php
}
?>
<?php
/*-------------- insert into trans mas --------------------*/
if(($tag=='RES-TRAN'))
{
	$remarks= $_POST['remarks'];
	$hid_fault= $_POST['hid_fault']; 
	$hid_uid= $_POST['hid_uid'];
	
	$sqlU=" update  flt_mas set refer_rmk=:remarks,close_by=trim(:hid_uid)";
    $sqlU.=" ,close_date=current_timestamp where md5(flt_id)=:flt_id ";
    $sthU = $conn->prepare($sqlU);
    $sthU->bindParam(':remarks', addslashes($remarks));
    $sthU->bindParam(':flt_id', $hid_fault);
    $sthU->bindParam(':hid_uid', $hid_uid);
    $sthU->execute();

    $sql=" insert into flt_his (flt_id,rmn,dkt_no,dkt_date,flt_type,comp_type_id,comp_desc ";
    $sql.=" ,comp_img,dist_id,dept_id,sub_div_id,block_id,ps_id,status,close_date,close_by ";
    $sql.=" ,refer_to,refer_date,refer_rmk,addr,street,para,village,pin,landmark ) ";
    $sql.=" select flt_id,rmn,dkt_no,dkt_date,flt_type,comp_type_id,comp_desc ";
    $sql.=" ,comp_img,dist_id,dept_id,sub_div_id,block_id,ps_id,status,close_date,close_by ";
    $sql.=" ,refer_to,refer_date,refer_rmk,addr,street,para,village,pin,landmark ";
    $sql.=" from flt_mas where  md5(flt_id)=:flt_id ";
    //echo $sql;
    $sth = $conn->prepare($sql);
	$sth->bindParam(':flt_id', $hid_fault);
	$sth->execute();

	$sqlD=" delete from flt_mas where md5(flt_id)=:flt_id ";
	$sthD = $conn->prepare($sqlD);
	$sthD->bindParam(':flt_id', $hid_fault);
	$sthD->execute();
		
}
?>

<?php
$conn=null;
?>