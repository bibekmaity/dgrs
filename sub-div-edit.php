<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); ?>
<?php
$hid_token = isset($_POST['hid_token']) ? $_POST['hid_token'] : '';
$hid_log_user = isset($_POST['hid_log_user']) ? $_POST['hid_log_user'] : '';
$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
$dept_nm = isset($_POST['dept_nm']) ? $_POST['dept_nm'] : '';
$dept_nm_ben = isset($_POST['dept_nm_ben']) ? $_POST['dept_nm_ben'] : '';
$hr_id = isset($_REQUEST['hr_id']) ? $_REQUEST['hr_id'] : '';
$hid_dept_id = isset($_POST['hid_dept_id']) ? $_POST['hid_dept_id'] : '';

if($submit=="Submit")
{
  
  $stmt2 =" select count(*) as ct ";
  $stmt2.="FROM user_mas ";
  $stmt2.="where token=:hid_token and uid=:hid_log_user ";
  $sth2 = $conn->prepare($stmt2);
  $sth2->bindParam(':hid_token', $hid_token);
  $sth2->bindParam(':hid_log_user', $hid_log_user);
  $sth2->execute();
  $sth2->setFetchMode(PDO::FETCH_ASSOC);
  $row2 = $sth2->fetch();
  $ct=$row2['ct'];
  if($ct>0)
  {
     $stmt3 ="select count(sub_div_id) as total ";
     $stmt3.="FROM sub_div_mas ";
     $stmt3.="where sub_div_nm=:dept_nm and md5(sub_div_id)!=:hid_dept_id ";
     $sth3 = $conn->prepare($stmt3);
     $sth3->bindParam(':dept_nm', strtoupper($dept_nm));
     $sth3->bindParam(':hid_dept_id', $hid_dept_id);
     $sth3->execute();
     $sth3->setFetchMode(PDO::FETCH_ASSOC);
     $row3 = $sth3->fetch();
     $total=$row3['total'];
     if($total<=0)
     {
        $sqlC =" update sub_div_mas set sub_div_nm_ben=:dept_nm_ben, "; 
        $sqlC.=" sub_div_nm=:dept_nm where md5(sub_div_id)=:hid_dept_id ";
        $sth = $conn->prepare($sqlC);
        $sth->bindParam(':hid_dept_id', $hid_dept_id);
        $sth->bindParam(':dept_nm', strtoupper($dept_nm));
        $sth->bindParam(':dept_nm_ben', $dept_nm_ben);
        $sth->execute();
		?>
   	 	<script>
      		alert('Sub Division Update Successfully.'); 
			window.location.href='<?php echo $full_url; ?>/sub-div-mas.php'  
      	</script>
     	<?Php
     }
  }
}


$sql ="select sub_div_nm,sub_div_nm_ben ";
$sql.="FROM sub_div_mas ";
$sql.="where md5(sub_div_id)=:hr_id ";
$sth = $conn->prepare($sql);
$sth->bindParam(':hr_id', $hr_id);
$sth->execute();
$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetch();
$e_dept_nm=$row['sub_div_nm_ben'];
$e_de_eng=$row['sub_div_nm'];
?>
<div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header  with-border">
                    <h3 class="box-title">Sub Division Details</h3>
                </div>
                <form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()">
                  <input type="hidden" id="hid_token" name="hid_token"value="<?php echo $ses_token; ?>"/>
                  <input type="hidden" id="hid_log_user" name="hid_log_user" value="<?php echo $ses_uid; ?>"/>
					<input type="hidden" name="hid_dept_id" value="<?php echo $hr_id; ?>"/>
                    <div class="box-body">
                      <div class="col-md-6">
                        <div class="form-group">
                           <label for="Department Name" class="col-sm-4">Name (English) <font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                             <input type="text" name="dept_nm" id="dept_nm" class="form-control" placeholder="Name (English)" tabindex="1" value="<?php echo $e_de_eng; ?>"  maxlength="40">
                             
                           </div>
                        </div>
                      </div>
                      <div class="col-md-6"> 
                        <div class="form-group">
                           <label for="Department Name" class="col-sm-4">Name (Bengali) <font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                             <input type="text" name="dept_nm_ben" id="dept_nm_ben" class="form-control" placeholder="Name (Bengali)" tabindex="1"  value="<?php echo $e_dept_nm; ?>"  maxlength="40">
                             
                           </div>
                        </div>
                      </div>
                      <div id="info"></div>
                  </div>
                  <div class="box-footer">
                   <a href="<?php echo $full_url; ?>/index.php"  class="btn btn-default">Cancel</a>
                    <input type="submit" name="submit" id="submit" class="btn btn-primary pull-right" value="Submit" tabindex="3">
                  </div>
                </form>
            </div>
         </div>
       </div>   
 <script> 
function validateEmail(txtEmail){
   var a = document.getElementById(txtEmail).value;
   var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
    if(filter.test(a)){
        return true;
    }
    else{
        return false;
    }
}  
jQuery('#submit').click( function() 
{
  var dept_nm=$('#dept_nm').val();
  var dept_nm_ben=$('#dept_nm_ben').val();
  if(dept_nm=="")
  {
    alertify.error("Please Input Name (English)");
    $('#dept_nm').css("border-color","#FF0000");
    $('#dept_nm').focus();
    return false;                  
  }
  if(dept_nm!="")
  {
     if(/^[/!<>]+$/.test(dept_nm))
     {     
		 alertify.error("/!()<> are not supported");
		 $('#dept_nm').css("border-color","#FF0000");
		 $('#dept_nm').focus();
		 return false;
     }
  }
  if(dept_nm_ben=="")
  {
    alertify.error("Please Input Name (Bengali) ");
    $('#dept_nm_ben').css("border-color","#FF0000");
    $('#dept_nm_ben').focus();
    return false;                  
  }
  if(dept_nm_ben!="")
  {
     if(/^[/!<>]+$/.test(dept_nm_ben))
     {     
		alertify.error("/!()<> are not supported");
		$('#dept_nm_ben').css("border-color","#FF0000");
		$('#dept_nm_ben').focus();
     	return false;
     }
  } 
 });
</script>     
   
<?php 

include('./footer.php'); ?>     
