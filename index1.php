<?php
header("X-XSS-Protection: 1;mode = block");
include('./header1.php');
//$ses_hid_lang='B'; 
if($ses_hid_lang=="B")
{  
  $box_title=" তথ্য / পরামর্শ দেওয়া / অভিযোগ দায়ের ";
	$mob_label="রেজিস্টার্ড মোবাইল নং";
	$comp_label="অভিযোগের ধরণ নির্বাচন করুন";
	$comp_sel="অভিযোগ টাইপ নির্বাচন করুন";
	$addr_label="ঠিকানা";
	$email_label="আপনার ইমেইল আইডি দিন";
	$email_place="আপনার ইমেইল আইডি দিন";
	$imag_label="অভিযোগের ইমেজ";
	$name_label="আপনার নাম";
	$police_label="থানা নির্বাচন করুন";
	$police_sel="থানা নির্বাচন করুন";
	$pin_label="পিনকোড টাইপ করুন";
	$pin_place="পিনকোড টাইপ করুন";
	$land_label="ল্যান্ড মার্ক (নিকটতম অবস্থান)";
	$land_place="ল্যান্ড মার্ক (নিকটতম অবস্থান)";
	$desc_label="অভিযোগের বিবরণ টাইপ করুন";
	$can_label="বাতিল";
	$sub_label="সাবমিট করুন";
	$block_label="ব্লক নির্বাচন করুন";
	$block_sel="ব্লক নির্বাচন করুন";
  $authorities_label="যে কতৃপক্ষের কাছে অভিযোগ করতে চান";
  $road_label='রাস্তা';
  $area_label="পাড়া/এলাকা";
  $village_label="গ্রাম/শহর";
  $dist_label="জেলা";
  $valid="অক্ষর কোন অনুমতি নেই";

  $book_type_label='তথ্য / পরামর্শ দেওয়া ';
  $book_type_label2='অভিযোগ দায়ের';
}
else
{   
  $box_title=" Information / Suggestion / Complaint Details";
	$mob_label="Registered Mobile No";
	$comp_label="Complaint Type";
	$comp_sel="Select Type of Complaint";
	$addr_label="Address";
	$email_label="Email ID";
	$email_place="Give Your Email ID";
	$imag_label="Complaint Image";
	$name_label="Your Name";
	$police_label="Police Station";
	$police_sel="Select Police Station";
	$pin_label="Pincode";
	$pin_place="Type Pincode";
	$land_label="Land Mark";
	$land_place="Land Mark (Nearest Location)";
	$desc_label="Complaint Details";
	$can_label="Cancel";
	$sub_label="Submit";
	$block_label="Block";
	$block_sel=" Select Block";
  $authorities_label="Complainting Authority";
  $road_label='Road';
  $area_label="Para/Area";
  $village_label="Village /City";
  $dist_label="District";
  $valid="Characters are no permission";

  $book_type_label='For Information/ Suggestion ';
  $book_type_label2='For Complaint';

}

$sql="select * from district_mas ";
$sql.="limit 1  ";
$sth = $conn->prepare($sql);
$sth->execute();
$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
$row2 = $sth->fetch();
$dist_id2=$row2['dist_id'];

if($ses_hid_lang=="B")
{ 
  $dist=$row2['dist_nm'];
}
else
{
  $dist=$row2['dist_nm_eng'];
}

$csrftoken = isset($_POST['csrftoken']) ? $_POST['csrftoken'] : '';
$mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : '';
$com_type = isset($_POST['com_type']) ? $_POST['com_type'] : '';
$block = isset($_POST['block']) ? $_POST['block'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$village = isset($_POST['village']) ? $_POST['village'] : '';
$pin_code = isset($_POST['pin_code']) ? $_POST['pin_code'] : '';
$email_id = isset($_POST['login']) ? $_POST['email_id'] : '';
$base = isset($_POST['base']) ? $_POST['base'] : '';
$department = isset($_POST['department']) ? $_POST['department'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$police = isset($_POST['police']) ? $_POST['police'] : '';
$road = isset($_POST['road']) ? $_POST['road'] : '';
$hid_dist = isset($_POST['hid_dist']) ? $_POST['hid_dist'] : '';
$area = isset($_POST['area']) ? $_POST['area'] : '';
$landmark = isset($_POST['landmark']) ? $_POST['landmark'] : '';
$comp_desc = isset($_POST['comp_desc']) ? $_POST['comp_desc'] : '';
$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
$book_type = isset($_POST['book_type']) ? $_POST['book_type'] : '';

if(($submit=="Submit") or ($submit=="সাবমিট করুন"))
{
  $stmt2 =" select count(*) as ct ";
  $stmt2.="FROM citizen_mas ";
  $stmt2.="where token=:csrftoken  ";
  $sth2 = $conn->prepare($stmt2);
  $sth2->bindParam(':csrftoken', $csrftoken);
  $sth2->execute();
  $sth2->setFetchMode(PDO::FETCH_ASSOC);
  $row2 = $sth2->fetch();
  $ct=$row2['ct'];
  if($ct>0)
  {
      //----------------- get sub divisin----------------------
      
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
      
      
      
      
      //---------------- get complaint serial --------------------
      
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
      
      $stmt ="select comp_type_id,comp_pfx,lpad(comp_srl,5,'0') as comp_srl,dept_id  ";
      $stmt.="FROM compl_type_mas ";
      $stmt.="where comp_pfx='S' ";
      $sth = $conn->prepare($stmt);
      $sth->execute();
      $sth->setFetchMode(PDO::FETCH_ASSOC);
      $row = $sth->fetch();
      $comp_pfx=$row['comp_pfx'];
      $comp_srl=$row['comp_srl'];
      $dept_id=$row['dept_id'];
      $com_type=$row['comp_type_id'];
        
      $dkt_no=null;
      $dkt_no="$comp_pfx$comp_srl";
    }
      
    
      $sql ="insert into flt_mas(rmn,dkt_no,dkt_date,comp_type_id,comp_desc"; 
      $sql.=",comp_img,dist_id,dept_id,sub_div_id,block_id,ps_id";
      $sql.=",addr,street,para,village,pin,landmark,flt_type) ";
      $sql.="values(:mobile_no,:dkt_no,current_timestamp,:com_type,:comp_desc";
      $sql.=",:comp_img,:dist_id,:department,:sub_div_id,:block_id,:ps_id";
      $sql.=",:addr,:street,:para,:village,:pin,:landmark,:book_type";
      $sql.=")";
      $sth = $conn->prepare($sql);
      $sth->bindParam(':mobile_no', $mobile_no);
      $sth->bindParam(':dkt_no', $dkt_no);
      $sth->bindParam(':com_type', $com_type);
      $sth->bindParam(':comp_desc', addslashes($comp_desc));
      $sth->bindParam(':comp_img', $base);
      $sth->bindParam(':dist_id', $hid_dist);
      $sth->bindParam(':department', $dept_id);
      $sth->bindParam(':sub_div_id', $sub_div_id);
      $sth->bindParam(':block_id', $block);
      $sth->bindParam(':ps_id', $police);
      $sth->bindParam(':addr', addslashes($address));
      $sth->bindParam(':street', addslashes($road));
      $sth->bindParam(':para', addslashes($area));
      $sth->bindParam(':village', addslashes($village));
      $sth->bindParam(':pin', $pin_code);
      $sth->bindParam(':landmark', addslashes($landmark));
      $sth->bindParam(':book_type', $book_type);
      $sth->execute();

      $sql_upd =" update citizen_mas set email_id=:email_id  ";
      $sql_upd.=" where rmn=:mobile_no ";
      $sth_upd = $conn->prepare($sql_upd);
      $sth_upd->bindParam(':email_id', $email_id);
      $sth_upd->bindParam(':mobile_no', $mobile_no);
      $sth_upd->execute();

     
      if($book_type=='C')
      {

          $stmt ="update compl_type_mas set comp_srl=comp_srl+1 ";
          $stmt.="where comp_type_id=:com_type ";
          $sth = $conn->prepare($stmt);
          $sth->bindParam(':com_type', $com_type);
          $sth->execute();
         
        $complaint_desc="Your complaint has been registered. Complaint No: ";
        send_sms($mobile_no,$complaint_desc."".$dkt_no);
      
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

          $complaint_desc="Your suggestion has been registered. ID : ";
          send_sms($mobile_no,$complaint_desc."".$dkt_no);
        ?>
         <script>
          alert('Your suggestion has been registered. ID: <?php echo $dkt_no; ?>. We will contact you shortly.');
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
                    <h3 class="box-title"><?php echo $box_title; ?> </h3>
                </div>
                <form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()">
                  <input type="hidden" id="hid_dist" name="hid_dist" value="<?php echo $dist_id2; ?>"/>
                  <input type="hidden" name="hid_lang" id="hid_lang" value="<?php echo $ses_hid_lang; ?>" />
                  <input type="hidden" name="csrftoken" id="csrftoken" value="<?php echo $ses_token; ?>" />
                    <div class="box-body">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="<?php echo $mob_label; ?>" class="col-sm-4"><?php echo $mob_label; ?></label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="mobile_no"  name="mobile_no" placeholder="<?php echo $mob_label; ?>" readonly="readonly" value="<?php echo $ses_rmn; ?>" />
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-1">
                            <input type="radio" id="book_type" name="book_type"  value="S" tabindex="3" checked="checked" tabindex="1" autofocus="autofocus" />
                          </div>
                            <label for="Booking Type" class="col-sm-6"><?php echo $book_type_label; ?></label>
                            <div class="col-sm-1">
                            <input type="radio" id="book_type" name="book_type"  value="C" tabindex="2"  />
                          </div>
                            <label for="Booking Type" class="col-sm-4"> <?php echo $book_type_label2; ?></label>
                        </div>
                        
                        <div class="form-group">
                          <label for="<?php echo $block_label; ?>" class="col-sm-4"><?php echo $block_label; ?>
                          <font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                            <select name="block" id="block" class="form-control select2" style="width:100%;"  tabindex="4" >
                              <option value=""><?php echo $block_sel; ?></option>     
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
                										if($ses_hid_lang=='B')
                										{
                											$block_name=$block_nm_ben;
                										}
                										else
                										{
                											$block_name=$block_nm;
                										}
                                    ?>
                                     <option value="<?php echo $block_id; ?>"><?php echo $block_name; ?></option>
                                     <?php
                                }
                                    ?>
                               </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="<?php echo $addr_label; ?>" class="col-sm-4"><?php echo $addr_label; ?>
                          <font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                              <textarea name="address" id="address" class="form-control" rows="4" tabindex="6"></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="<?php echo $village_label; ?>" class="col-sm-4"><?php echo $village_label; ?></label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" id="village" name="village" placeholder="<?php echo $village_label; ?>" maxlength="35" tabindex="9" />
                          </div>
                        </div> 
                         <div class="form-group">
                          <label for="<?php echo $pin_label; ?>" class="col-sm-4"><?php echo $pin_label; ?></label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="<?php echo $pin_place; ?>" maxlength="6" tabindex="11" />
                          </div>
                        </div>                         
                        
                        <div class="form-group">
                          <label for="<?php echo $desc_label; ?>" class="col-sm-4"><?php echo $desc_label; ?></label>
                          <div class="col-sm-8">
                              <textarea name="comp_desc" id="comp_desc" class="form-control" rows="4" tabindex="13"></textarea>
                          </div>
                        </div>
                        
                      </div>
                      <div class="col-md-6"> 
                        <div class="form-group">
                          <label for="<?php echo $name_label; ?>" class="col-sm-4"><?php echo $name_label; ?></label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="name"  name="name" placeholder="<?php echo $name_label; ?>" value="<?php echo $ses_citizen_nm; ?>" readonly />
                          </div>
                        </div>
                        <div class="form-group" id="div_comp">
                          <label for="Complaint Type" class="col-sm-4"></label>
                          <div class="col-sm-8">
                            <div class="form-control" id="com_type" style="border:none !important;"></div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="<?php echo $police_label; ?>" class="col-sm-4"><?php echo $police_label; ?> <font color="#FF0000">*</font></label>
                          <div class="col-sm-8" id="div_ps">
                              <select name="police" id="police" class="form-control select2" style="width:100%;"  tabindex="5" >   
                                <option value=""><?php echo $police_sel; ?></option>
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
                                    if($ses_hid_lang=='B')
                                    {
                                      $ps=$ps_nm_ben;
                                    }
                                    else
                                    {
                                      $ps=$ps_nm;
                                    }
                                    ?>
                                     <option value="<?php echo $ps_id; ?>"><?php echo $ps; ?></option>
                                     <?php
                                    }
                                  ?>
                               </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="<?php echo $road_label; ?>" class="col-sm-4"><?php echo $road_label; ?></label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="road" name="road" placeholder="<?php echo $road_label; ?>" maxlength="45" tabindex="7" />
                          </div>
                        </div> 
                        <div class="form-group">
                          <label for="<?php echo $area_label; ?>" class="col-sm-4"><?php echo $area_label; ?></label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="area" name="area" placeholder="<?php echo $area_label; ?>" tabindex="8"  maxlength="35" />
                          </div>
                        </div> 
                        <div class="form-group">
                          <label for="<?php echo $land_label; ?>" class="col-sm-4"><?php echo $land_label; ?> <font color="#FF0000">*</font> </label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="landmark" name="landmark" placeholder="<?php echo $land_place; ?>" tabindex="10" />
                          </div>
                        </div>  
                        <div class="form-group">
                          <label for="<?php echo $email_label; ?>" class="col-sm-4"><?php echo $email_label; ?></label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" id="email_id"  name="email_id" placeholder="<?php echo $email_place; ?>" tabindex="12"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="<?php echo $imag_label; ?>" class="col-sm-4"><?php echo $imag_label; ?> <font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                            <input  id="photo" type="file" accept="image/gif, image/jpeg, image/png" onchange="readURL(this);" class="form-control" tabindex="14">
                            <input id="base" name="base" type="text" class="form-control" readonly="readonly" style="visibility:hidden;" />
                          </div>
                        </div>    
                      </div>
                    </div>
                  <div class="box-footer">
                   <a href="<?php echo $full_url; ?>/index1.php"  class="btn btn-default"><?php echo $can_label; ?></a>
                    <input type="submit" name="submit" id="submit" class="btn btn-primary pull-right" value="<?php echo $sub_label; ?>" tabindex="15">
                  </div>
                </form>
            </div>
         </div>
       </div>
<script> 
$('input[type="radio"]').click(function() {
   var book_type=$("input[name='book_type']:checked").val() 
   var hid_lang=$('#hid_lang').val();
   var request = $.ajax({
      url: "./back/index1_back.php",
      method: "POST",
      data: { book_type:book_type,hid_lang:hid_lang,tag:'B-TYPE' },
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
jQuery('#submit').click( function() 
{
    var book_type=$("input[name='book_type']:checked").val(); 
    var com_type=$('#com_type').val();
    var block=$('#block').val();
    var police=$('#police').val();
    var address=$('#address').val();
    var road=$('#road').val();
    var area=$('#area').val();
    var village=$('#village').val();
    var pin_code=$('#pin_code').val();
    var landmark=$('#landmark').val();
    var email_id=$('#email_id').val();
    var comp_desc=$('#comp_desc').val();
    var photo=$('#photo').val();
    var hid_dist=$('#hid_dist').val();
    var csrftoken=$('#csrftoken').val();
    var base=$('#base').val();

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
      alertify.error("<?php echo $block_label; ?>");
      $('#block').css("border-color","#FF0000");
      $('#block').focus();
          return false;                  
    }
    if(police=="")
    {
      alertify.error("<?php echo $police_label; ?>");
      $('#police').css("border-color","#FF0000");
      $('#police').focus();
          return false;                  
    }
    if(address=="")
    {
      alertify.error("<?php echo $addr_label; ?>");
      $('#address').css("border-color","#FF0000");
      $('#address').focus();
      return false;                  
    }
    if(address!="")
    {
       if(/^[/!<>]+$/.test(address))
       {
       
      alertify.error("  <?php echo $valid; ?> (/!()<>)");
      $('#address').css("border-color","#FF0000");
       $('#address').focus();
       return false;
       }
    }
    if(road!="")
    {
       if(/^[/!<>]+$/.test(road))
       {
       
      alertify.error("  <?php echo $valid; ?> (/!()<>)");
      $('#road').css("border-color","#FF0000");
       $('#road').focus();
       return false;
       }
    }
    if(area!="")
    {
       if(/^[/!<>]+$/.test(area))
       {
       
      alertify.error("  <?php echo $valid; ?> (/!()<>)");
      $('#area').css("border-color","#FF0000");
       $('#area').focus();
       return false;
       }
    }
    if(village!="")
    {
       if(/^[/!<>]+$/.test(village))
       {
       
      alertify.error("  <?php echo $valid; ?> (/!()<>)");
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
      alertify.error("<?php echo $land_place; ?>");
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
      var hid_lang =$('#hid_lang').val();
      var block =$('#block').val();
      var request = $.ajax({
      url: "./back/index1_back.php",
      method: "POST",
      data: { block:block,hid_lang:hid_lang,tag:'POLICE' },
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