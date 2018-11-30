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

.modal-header,.modal-body,.modal-footer,.modal-content,.modal-dialog
{
	
	width:605px !important;
}
.modal-body .select2
{
	margin-bottom: 5px;
}
.form-group
{
	margin-top:10px;
}
.modal-body
{
	height:155px !important;
}

@media only screen and (max-width: 800px) {
  .modal-header,.modal-body,.modal-footer,.modal-content,.modal-dialog
  {     
	 width:99.5% !important;
  }
  .modal-dialog
  {
	  margin:2px;
  }
  .modal-body
  {
	height:205px !important;
  }
  
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
               <div class="col-md-12">
                  <div class="form-group">
	                <label for="Remarks" class="col-sm-4" >Remarks <font color="#FF0000">*</font></label>
	                <div class="col-sm-8">
	                	<textarea name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" tabindex="2" rows="3"></textarea>
	                </div>
          		  </div> 
                  </div>
                   <div class="col-md-12">
                  <div class="form-group">
	                <label for="Document Upload" class="col-sm-4" >Document Upload</label>
	                <div class="col-sm-8">
                       <input type="file" class="form-control" id="photo" name="photo[]" placeholder="Enter photo"  tabindex="3">
	                </div>
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
		var photo = $("#photo").val();

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
		var fileinput =$('#photo').val();
   
    if(fileinput!= "")
    {
	    var filearr=fileinput.split('.');
	    if(filearr.length>2)
	     {
	      alert('Double extension files are not allowed.'); 
	      $('#photo').focus();
	      return false;    
	    }
	    if(fileinput!="")
	    {
	        var extension = fileinput.substr(fileinput.lastIndexOf('.') + 1).toLowerCase(); 
	        var allowedExtensions = ["txt","doc","pdf","jpg","jpeg","gif","docx","png","xls","xlsx","odt","ods"];
	        if (fileinput.length > 0) 
	        { 
	          if (allowedExtensions.indexOf(extension) === -1) 
	          { 
	            alert('Invalid file Format. Only ' + allowedExtensions.join(', ') + ' are allowed.'); 
	            $('#photo').focus();
	            return false; 
	          } 
	        }
	    }
	} 
	var formData = new FormData(document.getElementById("fileUploadForm"));
	var request = $.ajax({
	url: "./back/restore_entry_back.php",
	method: "POST",
	data: formData,
	enctype: 'multipart/form-data',
	processData: false,  
	contentType: false,  
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
$conn=null;
?>