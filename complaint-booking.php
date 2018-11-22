<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php');


$mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$com_type = isset($_POST['com_type']) ? $_POST['com_type'] : '';
$block = isset($_POST['block']) ? $_POST['block'] : '';
$police = isset($_POST['police']) ? $_POST['police'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$road = isset($_POST['road']) ? $_POST['road'] : '';
$area = isset($_POST['area']) ? $_POST['area'] : '';
$village = isset($_POST['village']) ? $_POST['village'] : '';
$landmark = isset($_POST['landmark']) ? $_POST['landmark'] : '';
$pin_code = isset($_POST['pin_code']) ? $_POST['pin_code'] : '';
$email_id = isset($_POST['email_id']) ? $_POST['email_id'] : '';
$comp_desc = isset($_POST['comp_desc']) ? $_POST['comp_desc'] : '';
$hid_dist = isset($_POST['hid_dist']) ? $_POST['hid_dist'] : '';
$csrftoken = isset($_POST['csrftoken']) ? $_POST['csrftoken'] : '';
$base = isset($_POST['base']) ? $_POST['base'] : '';
$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
$book_type = isset($_POST['book_type']) ? $_POST['book_type'] : '';

 
if($submit=="Submit")
{
	
  $stmt2 =" select count(*) as ct ";
  $stmt2.="FROM user_mas ";
  $stmt2.="where token=:csrftoken and uid=:ses_uid ";
  $sth2 = $conn->prepare($stmt2);
  $sth2->bindParam(':csrftoken', $csrftoken);
  $sth2->bindParam(':ses_uid', $ses_uid);
  $sth2->execute();
  $sth2->setFetchMode(PDO::FETCH_ASSOC);
  $row2 = $sth2->fetch();
  $ct=$row2['ct'];
  if($ct>0)
  {
    
    
    //------------- check for duplicate fault ---------------------
    $stmt3 ="select count(*) as ctzen ";
    $stmt3.="FROM citizen_mas ";
    $stmt3.="where 1=1 ";
    $stmt3.="and rmn=:mobile_no ";
    $sth3 = $conn->prepare($stmt3);
    $sth3->bindParam(':mobile_no', $mobile_no);
    $sth3->execute();
    $sth3->setFetchMode(PDO::FETCH_ASSOC);
    $row3 = $sth3->fetch();
    $ctzen=$row3['ctzen'];
    if($ctzen<=0)
    {
        
      $sqlC ="insert into citizen_mas(rmn,citizen_nm,addr,land_mark,ps_id"; 
      $sqlC.=",block_no,email_id) ";
      $sqlC.="values(:mobile_no,:name,:address,:landmark,:ps_id,:block_id";
      $sqlC.=",:email_id ";
      $sqlC.=")";
      $sth = $conn->prepare($sqlC);
      $sth->bindParam(':mobile_no', $mobile_no);
      $sth->bindParam(':name', $name);
      $sth->bindParam(':address', addslashes($address));
      $sth->bindParam(':landmark', addslashes($landmark));
      $sth->bindParam(':block_id', $block);
      $sth->bindParam(':ps_id', $police);
      $sth->bindParam(':email_id', $email_id);
      $sth->execute();
    }
    else
    {
    	$sql ="update citizen_mas set citizen_nm=:name,addr=:address ";
    	$sql.=",land_mark=:landmark,ps_id=:ps_id"; 
      	$sql.=",block_no=:block_id,email_id=:email_id ";
        $sql.="where rmn=:mobile_no ";
        $sth = $conn->prepare($sql);
      	$sth->bindParam(':mobile_no', $mobile_no);
      	$sth->bindParam(':name', $name);
      	$sth->bindParam(':address', addslashes($address));
      	$sth->bindParam(':landmark', addslashes($landmark));
      	$sth->bindParam(':block_id', $block);
      	$sth->bindParam(':ps_id', $police);
      	$sth->bindParam(':email_id', $email_id);
      	$sth->execute();
    }
    if($book_type=='C')
    {

      $stmt ="select comp_pfx,lpad(comp_srl,5,'0') as comp_srl,dept_id ";
	    $stmt.="FROM compl_type_mas ";
	    $stmt.="where comp_type_id=:com_type ";
	    $sth = $conn->prepare($stmt);
	    $sth->bindParam(':com_type', $com_type);
	    $sth->execute();
	    $sth->setFetchMode(PDO::FETCH_ASSOC);
	    $row = $sth->fetch();
	    $comp_pfx=$row['comp_pfx'];
	    $comp_srl=$row['comp_srl'];
      $dept_id=$row['dept_id'];
	      
	    $dkt_no=null;
	    $dkt_no="$comp_pfx$comp_srl";

    }
    else
    {
      $com_type='4';
		  $stmt ="select comp_pfx,lpad(comp_srl,5,'0') as comp_srl,dept_id  ";
	    $stmt.="FROM compl_type_mas ";
	    $stmt.="where comp_pfx='S' ";
	    $sth = $conn->prepare($stmt);
	    $sth->execute();
	    $sth->setFetchMode(PDO::FETCH_ASSOC);
	    $row = $sth->fetch();
	    $comp_pfx=$row['comp_pfx'];
	    $comp_srl=$row['comp_srl'];
      $dept_id=$row['dept_id'];
	      
	    $dkt_no=null;
	    $dkt_no="$comp_pfx$comp_srl";
    }
	$stmt =" select pm.sub_div_id ";
	$stmt.="FROM ps_mas pm,block_mas bm ";
	$stmt.="where pm.sub_div_id=bm.sub_div_id ";
	if(!empty($block_id))
	{
	$stmt.="and bm.block_id=:block ";
	}
	$sth = $conn->prepare($stmt);
	if(!empty($block_id))
	{
	$sth->bindParam(':block', $block);
	}
	$sth->execute();
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$row = $sth->fetch();
	$sub_div_id=$row['sub_div_id'];

    $sql ="insert into flt_mas(rmn,dkt_no,dkt_date,comp_type_id,comp_desc"; 
	$sql.=",comp_img,dist_id,dept_id,sub_div_id,block_id,ps_id";
	$sql.=",addr,street,para,village,pin,landmark,flt_type) ";
	$sql.="values(:mobile_no,:dkt_no,current_timestamp,:com_type,:comp_desc";
	$sql.=",:comp_img,:dist_id,:dept_id,:sub_div_id,:block_id,:ps_id";
	$sql.=",:addr,:street,:para,:village,:pin,:landmark,:book_type";
	$sql.=")";
	$sth = $conn->prepare($sql);
	$sth->bindParam(':mobile_no', $mobile_no);
	$sth->bindParam(':dkt_no', $dkt_no);
	$sth->bindParam(':com_type', $com_type);
	$sth->bindParam(':comp_desc', addslashes($comp_desc));
	$sth->bindParam(':comp_img', $base);
	$sth->bindParam(':dist_id', $hid_dist);
	$sth->bindParam(':dept_id', $dept_id);
	$sth->bindParam(':sub_div_id', $sub_div_id);
	$sth->bindParam(':block_id', $block);
	$sth->bindParam(':ps_id', $police);
	$sth->bindParam(':addr', addslashes($address));
	$sth->bindParam(':street', addslashes($road));
	$sth->bindParam(':para', addslashes($area));
	$sth->bindParam(':village', addslashes($village));
	$sth->bindParam(':pin', $pin_code);
	$sth->bindParam(':landmark', addslashes($landmark));
	$sth->bindParam(':book_type', addslashes($book_type));
	$sth->execute();

	if($book_type=='C')
  {

    	$stmt ="update compl_type_mas set comp_srl=comp_srl+1 ";
    	$stmt.="where comp_type_id=:com_type ";
    	$sth = $conn->prepare($stmt);
    	$sth->bindParam(':com_type', $com_type);
   	$sth->execute();
   	?>
   	 <script>
      alert('Your complaint has been registered. Complaint No: <?php echo $dkt_no; ?>. We will contact you shortly.');
      
      </script>
      <?php
  }
  else
  {
  	$stmt ="update compl_type_mas set comp_srl=comp_srl+1 ";
    	$stmt.="where comp_pfx='S' ";
    	$sth = $conn->prepare($stmt);
   	$sth->execute();
   	?>
   	 <script>
      alert('Your Advice has been registered. Referance No: <?php echo $dkt_no; ?>. We will contact you shortly.');
      </script>
      <?php
  }
     
}
else
{
      ?>
      <script>
        alert('You are unauthorized.');
      </script>
      <?php
  }
}
?>

<div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header  with-border">
                    <h3 class="box-title">Information / Advice / Complaint Details</h3>
                </div>
                <form name="form1"  method="post" id="myForm" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()">
                  <input type="hidden" id="hid_dist" name="hid_dist" value="<?php echo $ses_dist_id; ?>"/>
                  <input type="hidden" name="csrftoken" id="csrftoken" value="<?php echo $ses_token; ?>" />
                    <div class="box-body">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="Mobile No" class="col-sm-4">Mobile No</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="mobile_no"  name="mobile_no" placeholder="Mobile No" value="" maxlength="10" tabindex="1" autofocus="" />
                          </div>
                        </div>
                        <div class="form-group">
                        	<div class="col-sm-1">
                        		<input type="radio" id="book_type" name="book_type"  value="S" tabindex="3" checked="checked" />
                        	</div>
                            <label for="Booking Type" class="col-sm-4">For Information / Suggestion</label>
                            <div class="col-sm-1">
                        		<input type="radio" id="book_type" name="book_type"  value="C" tabindex="4"  />
                        	</div>
                            <label for="Booking Type" class="col-sm-4"> For Complaint</label>
                        </div>
                         <div class="form-group">
                          <label for="Block" class="col-sm-4">Block<font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                            <select name="block" id="block" class="form-control select2" style="width:100%;"  tabindex="7" >
                              <option value="">-- Selct Block --</option>     
                <?php
                                $sqlf=" select block_id,block_nm,block_nm_ben ";
                  $sqlf.="from block_mas order by sub_div_id,block_id ";
                                $sthf = $conn->prepare($sqlf);
                                $sthf->execute();
                                $ssf=$sthf->setFetchMode(PDO::FETCH_ASSOC);
                                $rowf = $sthf->fetchAll();
                                foreach ($rowf as $keyf => $rowd) 
                                {
                                    $block_id=$rowd['block_id'];
                                    $block_nm=$rowd['block_nm'];
                          $block_nm_ben=$rowd['block_nm_ben'];
                                    ?>
                                    <option value="<?php echo $block_id; ?>"><?php echo $block_nm; ?></option>
                                     <?php
                                }
                                ?>
                               </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="Address" class="col-sm-4">Address<font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                              <textarea name="address" id="address" class="form-control" rows="3" tabindex="9"></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="Village/City" class="col-sm-4">Village/City</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" id="village" name="village" placeholder="Type Village/City Name" maxlength="35" tabindex="12" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="Pin Code" class="col-sm-4">Pin Code</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="Type Pin Code" maxlength="6" tabindex="14" />
                          </div>
                        </div>      
                        
                        <div class="form-group">
                          <label for="Describtion" class="col-sm-4">Describtion</label>
                          <div class="col-sm-8">
                              <textarea name="comp_desc" id="comp_desc" class="form-control" rows="3" tabindex="16"></textarea>
                          </div>
                        </div>  
                      </div>
                      <div class="col-md-6"> 
                        <div class="form-group">
                          <label for="Name" class="col-sm-4">Name</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="name"  name="name" placeholder="Name" value="" tabindex="2" />
                          </div>
                        </div>
                        <div class="form-group" id="div_comp">
                          <label for="Complaint Type" class="col-sm-4"></label>
  						  <div class="col-sm-8"><div class="form-control" id="com_type" style="border:none !important;"></div></div>
                        </div>
                        <div class="form-group">
                          <label for="Police Station" class="col-sm-4">Police Station<font color="#FF0000">*</font></label>
                          <div class="col-sm-8" id="div_ps">
                              <select name="police" id="police" class="form-control select2" style="width:100%;"  tabindex="8" >   
                                <option value="">-- Select Police Station --</option>
                                  <?php
                                  $sqlf=" select ps_id,ps_nm,ps_nm_ben ";
                                  $sqlf.="from ps_mas order by ps_id ";
                                  $sthf = $conn->prepare($sqlf);
                                  $sthf->execute();
                                  $ssf=$sthf->setFetchMode(PDO::FETCH_ASSOC);
                                  $rowf = $sthf->fetchAll();
                                  foreach ($rowf as $keyf => $rowd) 
                                  {
                                    $ps_id=$rowd['ps_id'];
                                    $ps_nm=$rowd['ps_nm'];
                                    $ps_nm_ben=$rowd['ps_nm_ben'];       
                                    ?>
                                     <option value="<?php echo $ps_id; ?>"><?php echo $ps_nm; ?></option>
                                     <?php
                                    }
                                  ?>
                               </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="Road" class="col-sm-4">Road</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="road" name="road" placeholder="Type Road Name" maxlength="45" tabindex="10" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="Para/Area" class="col-sm-4">Para/Area</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="area" name="area" placeholder="Type Para/Area" tabindex="11"  maxlength="35" />
                          </div>
                        </div>  
                        <div class="form-group">
                          <label for="Landmark" class="col-sm-4">Landmark<font color="#FF0000">*</font> </label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Please Type Landmark" tabindex="13" />
                          </div>
                        </div>  
                                             
                        <div class="form-group">
                          <label for="Email ID" class="col-sm-4">Email ID</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="email_id"  name="email_id" placeholder="Type Email ID" tabindex="15"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="Photo" class="col-sm-4">Photo</label>
                          <div class="col-sm-8">
                            <input  id="photo" type="file" accept="image/gif, image/jpeg, image/png" onchange="readURL(this);" class="form-control" tabindex="17" >
                            <input id="base" name="base" type="text" class="form-control" readonly="readonly" style="visibility:hidden;" />
                          </div>
                        </div>  
                       
                        
                      </div>
                    </div>
                  <div class="box-footer">
                   <a href="<?php echo $full_url; ?>/complaint-booking.phpp"  class="btn btn-default">Cancel</a>
                    <input type="submit" name="submit" id="submit" class="btn btn-primary pull-right" value="Submit" tabindex="18">
                  </div>
                </form>
            </div>
         </div>
       </div>
<script>  
$('input[type="radio"]').click(function() {

   var book_type=$("input[name='book_type']:checked").val();
	
   var request = $.ajax({
      url: "./back/complaint-booking-back.php",
      method: "POST",
      data: { book_type:book_type,tag:'B-TYPE' },
      dataType: "html",
      success:function( msg ) {
      $( "#div_comp" ).html( msg);
      
    }
  });
})
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
function ValidateSize(file) 
{
    var FileSize = file.files[0].size / 1024 / 1024; // in MB
    if (FileSize > 2) {
        alert('File size exceeds 2 MB');
       // $(file).val(''); //for clearing with Jquery
    } else {

    }
}
jQuery('#submit').click( function() 
{
	
	var mobile_no=$('#mobile_no').val();
	var name=$('#name').val();
	var book_type=$("input[name='book_type']:checked").val();	
    var com_type=$('#com_type').val();  
    var block=$('#block').val();
    var police=$('#police').val();    
    var address=$('#address').val();
    var road=$('#road').val();
    var area=$('#area').val();
    var village=$('#village').val();
    var landmark=$('#landmark').val();
    var pin_code=$('#pin_code').val();    
    var email_id=$('#email_id').val();
    var comp_desc=$('#comp_desc').val();
    var photo=$('#photo').val();
    var hid_dist=$('#hid_dist').val();
    var csrftoken=$('#csrftoken').val();
    var base=$('#base').val();

    if(mobile_no=="")
    {
      alertify.error("Please input Mobile No");
      $('#mobile_no').css("border-color","#FF0000");
      $('#mobile_no').focus();
          return false;                  
    }
    if(mobile_no!="")
   {
   	  if(mobile_no.length!="10")
	  {
		  alertify.error('Please input a Valid Mobile No');
		  $('#mobile_no').focus();
		  return false;
	  }
	  if(!/^[0-9]+$/.test(mobile_no))
	   {
         alertify.error('Please input a Valid Mobile No');
         $('#mobile_no').focus();
	     return false;
       }
   }
    if(name=="")
    {
      alertify.error("Please Input Name");
      $('#name').css("border-color","#FF0000");
      $('#name').focus();
          return false;                  
    }
    if(name!="")
    {
       if(/^[/!<>]+$/.test(name))
       {
       
      alertify.error("/!()<> are not supported");
      $('#name').css("border-color","#FF0000");
       $('#name').focus();
       return false;
       }
    }
   if(book_type=="C")
    {
    	if(com_type=="")
	    {
	      alertify.error("Please Select Complaint Type");
	      $('#com_type').css("border-color","#FF0000");
	      $('#com_type').focus();
	          return false;                  
	    }
    }
    
    
    if(block=="")
    {
      alertify.error("Please Select Block");
      $('#block').css("border-color","#FF0000");
      $('#block').focus();
          return false;                  
    }
    if(police=="")
    {
      alertify.error("Please Select Police Station");
      $('#police').css("border-color","#FF0000");
      $('#police').focus();
          return false;                  
    }
   if(address=="")
    {
      alertify.error("Please Input Address");
      $('#address').css("border-color","#FF0000");
      $('#address').focus();
      return false;                  
    }
    if(address!="")
    {
       if(/^[/!<>]+$/.test(address))
       {
       
      alertify.error("/!()<> are not supported");
      $('#address').css("border-color","#FF0000");
       $('#address').focus();
       return false;
       }
    }
     if(road!="")
    {
       if(/^[/!<>]+$/.test(road))
       {
       
      alertify.error("  </!()<> are not supported");
      $('#road').css("border-color","#FF0000");
       $('#road').focus();
       return false;
       }
    }
    if(area!="")
    {
       if(/^[/!<>]+$/.test(area))
       {
       
       alertify.error("  </!()<> are not supported");
      $('#area').css("border-color","#FF0000");
       $('#area').focus();
       return false;
       }
    }
    if(village!="")
    {
       if(/^[/!<>]+$/.test(village))
       {
       
	       alertify.error("  </!()<> are not supported");
	       $('#village').css("border-color","#FF0000");
	       $('#village').focus();
	       return false;
       }
    }
    if(pin_code!="")
    {
      if(!/^[0-9]+$/.test(pin_code))
      {
       
        alertify.error("only number supported");
        $('#pin_code').css("border-color","#FF0000");
        $('#pin_code').focus();
        return false;
      }
    }
     if(landmark=="")
    {
      alertify.error("Please input Landmark");
      $('#landmark').css("border-color","#FF0000");
      $('#landmark').focus();
      return false;                  
    }
    if (email_id != "") {
     if(!validateEmail('email_id')){
      alertify.error(" Invalid Email");
      $('#email_id').css("border-color","#FF0000");
      $('#email_id').focus();
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
	        var allowedExtensions = ['jpg', 'jpeg', 'png'];
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
 });

function readURL(input) {
  var FileSize = input.files[0].size / 1024 / 1024; // in MB
  if (FileSize > 2) 
  {
        alert('File size exceeds 2 MB');
        $(input).val('');
  } 
  else 
  {      
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#photo').attr('src', e.target.result);
        
        $('#base').val(e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
}
$(document).ajaxStart(function ()
{
  $('body').addClass('wait');
   
})
.ajaxComplete(function () {

  $('body').removeClass('wait');

});
$("#block").change(function() {
      var block =$('#block').val();
     
      var request = $.ajax({
      url: "./back/complaint-booking-back.php",
      method: "POST",
      data: { block:block,tag:'POLICE' },
      dataType: "html",
      success:function( msg ) {
      $( "#div_ps" ).html( msg);
      
    }
  });
});
</script>  
<?php 
include('./footer.php'); 
?>