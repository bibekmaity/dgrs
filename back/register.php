<?php
header("X-XSS-Protection: 1;mode = block");
include("../inc/dblib.inc.php");
$conn = OpenDB();
?>

<?php
$tag = isset($_POST['tag']) ? $_POST['tag'] : '';

if(($tag=='DTLS'))
{
	$id = $_POST['id'];
	if($id!='D')
	{
	?>
    <html>
        
        <div class="form-group">
           <label for="Block" class="col-sm-2">Block<font color="#FF0000">*</font></label>
           <div class="col-sm-4"  id="div_block">
             <select name="block" id="block" class="form-control select2"  style="width: 100%;" tabindex="3">
               <option value="">Block</option>
               <?php
                $sqle=" select * ";
                $sqle.=" from block_mas WHERE status='A'  ";
                $sthc = $conn->prepare($sqle);
                $sthc->execute();
                $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                $rowc = $sthc->fetchAll();
                foreach ($rowc as $keyc => $rowe) 
                {
                    $e_block_cd=$rowe['block_cd'];
                    $e_block_nm=$rowe['block_nm'];
                    ?>	
                    <option value="<?php echo $e_block_cd; ?>"><?php echo $e_block_nm; ?></option>
                    <?php
                }
                ?>
             </select>
           </div> 
         <?php
		 if($id=='B')
	     {
         ?>  
          <div id="lessee"></div> 
          <label for="OFFICE" class="col-sm-2">OFFICE<font color="#FF0000">*</font></label>
          <div class="col-sm-4" id="div_office">
             <select name="office" id="office" class="form-control select2"  style="width: 100%;" tabindex="4">
               <option value="">OFFICE</option>
               <?php
                $sqle=" select * ";
                $sqle.=" from comp_mas  ";
                $sthc = $conn->prepare($sqle);
                $sthc->execute();
                $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                $rowc = $sthc->fetchAll();
                foreach ($rowc as $keyc => $rowe) 
                {
                    $e_comp_id=$rowe['comp_id'];
                    $e_comp_nm=$rowe['comp_nm'];
                    ?>	
                    <option value="<?php echo $e_comp_id; ?>"><?php echo $e_comp_nm; ?></option>
                    <?php
                }
                ?>
             </select>
           </div>
           <?php
		 }
		 ?>
         <?php
		 if($id=='L')
	     {
         ?>  
           <div id="office"></div>
 
          <label for="Lessee" class="col-sm-2">Lessee<font color="#FF0000">*</font></label>
          <div class="col-sm-4" id="div_office">
             <select name="lessee" id="lessee" class="form-control select2"  style="width: 100%;" tabindex="4">
               <option value="">Lessee</option>
               <?php
                $sqle=" select les_id,les_nm ";
                $sqle.=" from lessee_mas where status='A'   ";
                $sthc = $conn->prepare($sqle);
                $sthc->execute();
                $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                $rowc = $sthc->fetchAll();
                foreach ($rowc as $keyc => $rowe) 
                {
                    $les_id=$rowe['les_id'];
                    $les_nm=$rowe['les_nm'];
                    ?>	
                    <option value="<?php echo $les_id; ?>"><?php echo $les_nm; ?></option>
                    <?php
                }
                ?>
             </select>
           </div>
           <?php
		 }
		 ?>
        </div>
     </html>     
     
     <script>
		$( "#district" ).change(function() {
		  var ID = $(this).val();
		  var request = $.ajax({
		  url: "./back/register.php",
		  method: "POST",
		  data: { id:ID,tag:'BLOCK' },
		  dataType: "html",
		  success:function( msg ) {
		  $( "#div_block" ).html( msg);
		  
		}
		});
		});
		$( "#block" ).change(function() {
		var ID = $(this).val();
		var user_type =$('#user_type').val();

		var request = $.ajax({
		url: "./back/register.php",
		method: "POST",
		data: { id:ID,user_type:user_type,tag:'OFFICE' },
		dataType: "html",
		success:function( msg ) {
		$( "#div_office" ).html( msg);
		
		}
		});
		});

  $(function () {
       $(".select2").select2();
  });
</script>
     <?php
	}
}
?>
<?php
if(($tag=='BLOCK'))
{
	$id = $_POST['id'];
	?>
         <select name="block" id="block" class="form-control select2"  style="width: 100%;" tabindex="3">
           <option value="">Block</option>
           <?php
            $sqle=" select * ";
            $sqle.=" from block_mas where dist_id=:id ";
            $sthc = $conn->prepare($sqle);
			$sthc->bindParam(':id', $id);
            $sthc->execute();
            $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
            $rowc = $sthc->fetchAll();
            foreach ($rowc as $keyc => $rowe) 
            {
                $e_block_cd=$rowe['block_cd'];
                $e_block_nm=$rowe['block_nm'];
                ?>	
                <option value="<?php echo $e_block_cd; ?>"><?php echo $e_block_nm; ?></option>
                <?php
            }
            ?>
         </select>
        <script>
		$( "#district" ).change(function() {
		  var ID = $(this).val();
		  var request = $.ajax({
		  url: "./back/register.php",
		  method: "POST",
		  data: { id:ID,tag:'BLOCK' },
		  dataType: "html",
		  success:function( msg ) {
		  $( "#div_block" ).html( msg);
		  
		}
		});
		});
		$( "#block" ).change(function() {
		var ID = $(this).val();
		var user_type =$('#user_type').val();
		var request = $.ajax({
		url: "./back/register.php",
		method: "POST",
		data: { id:ID,user_type:user_type,tag:'OFFICE' },
		dataType: "html",
		success:function( msg ) {
		$( "#div_office" ).html( msg);
		
		}
		});
		});

  $(function () {
       $(".select2").select2();
  });
</script>
         <?php
}

?>
<?php
if(($tag=='OFFICE'))
{
	$id=$_POST['id'];
	$user_type=$_POST['user_type'];
   if($user_type=='B')
   {
	?> 
    <div id="lessee"></div> 
         <select name="office" id="office" class="form-control select2"  style="width: 100%;" tabindex="4">
           <option value="">OFFICE</option>
           <?php
            $sqle=" select * ";
            $sqle.=" from comp_mas where block_id=:id ";
            $sthc = $conn->prepare($sqle);
			$sthc->bindParam(':id', $id);
            $sthc->execute();
            $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
            $rowc = $sthc->fetchAll();
            foreach ($rowc as $keyc => $rowe) 
            {
                $e_comp_id=$rowe['comp_id'];
                $e_comp_nm=$rowe['comp_nm'];
                ?>	
                <option value="<?php echo $e_comp_id; ?>"><?php echo $e_comp_nm; ?></option>
                <?php
            }
            ?>
         </select>
        <?php
   }
      if($user_type=='L')
   {

	?> 
      <div id="office"></div>

    <select name="lessee" id="lessee" class="form-control select2"  style="width: 100%;" tabindex="4">
       <option value="">Lessee</option>
       <?php
        $sqle=" select les_id,les_nm ";
        $sqle.=" from lessee_mas where status='A' and block_code=:id   ";
        $sthc = $conn->prepare($sqle);
        $sthc->bindParam(':id', $id);
        $sthc->execute();
        $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
        $rowc = $sthc->fetchAll();
        foreach ($rowc as $keyc => $rowe) 
        {
            $les_id=$rowe['les_id'];
            $les_nm=$rowe['les_nm'];
            ?>	
            <option value="<?php echo $les_id; ?>"><?php echo $les_nm; ?></option>
            <?php
        }
        ?>
     </select>
        <?php
}
?>
         <script>
		  $(function () {
			   $(".select2").select2();
		  });
		</script>
         <?php
}

?>
<?php
if(($tag=='REGISTER'))
{
	 $hid_uid= test_input($_POST['hid_uid']);
	 $user_type= test_input($_POST['user_type']);
	 $user_nm= test_input($_POST['user_nm']);
	 $user_id= test_input($_POST['user_id']);
	 $password= test_input($_POST['password']);
	 $contact_no= test_input($_POST['contact_no']);
	 $email= test_input($_POST['email']);
	 $addr= test_input($_POST['addr']);
	 $addr = addslashes(($addr));
	 $district = isset($_POST['district']) ? $_POST['district'] : '';
	 $block = isset($_POST['block']) ? $_POST['block'] : '';
	 $office = isset($_POST['office']) ? $_POST['office'] : '';
	 $lessee = isset($_POST['lessee']) ? $_POST['lessee'] : '';
	 $base=$_POST['base'];
	 $sql=" select count(*) as avail from user_mas ";
	 $sql.=" where user_id=:user_id ";
	 $sth = $conn->prepare($sql);
	 $sth->bindParam(':user_id', $user_id);
	 $sth->execute();
	 $ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
	 $row = $sth->fetch();
	 $avail=$row['avail'];
	 $my_like="%$user_type%";
	 
	 $sqlU="select mid from  menu_master where acces_tag LIKE (:my_like) ORDER BY mid ";
	 $sthU = $conn->prepare($sqlU);
	 $sthU->bindParam(':my_like', $my_like);
	 $sthU->execute();
	 $ssU=$sthU->setFetchMode(PDO::FETCH_ASSOC);
	 $rowU = $sthU->fetchAll();
	 $access="";
	 foreach ($rowU as $keyU => $rowe) 
	 {
		 $mid=$rowe['mid'];
		 $access.="$mid,";
	 }
 	 $access.="0";
	 
	 if($avail<=0)
	 {
		$sql_ins=" insert into user_mas ( ";
		$sql_ins.=" page_assign,user_id ,user_nm,pwd,user_type,";
		$sql_ins.="  user_addr,user_cont_no,mail_id ";
		$sql_ins.=" ,user_photo,dist_id,status,approved_by,approved_on ";
		if($user_type=='B')
		{
			$sql_ins.="  ,block_id,comp_id ";
		}
		if($user_type=='L')
		{
			$sql_ins.="  ,block_id,les_id ";
		}
		$sql_ins.=" ) values ( ";
		$sql_ins.=" trim(:access),trim(:user_id), upper(:user_nm),md5(:password) ";
		$sql_ins.=" ,:user_type,:addr ";
		$sql_ins.=" ,trim(:contact_no),trim(:email) ";
		$sql_ins.=" ,:base,:district,'A',:hid_uid,current_date ";
		if($user_type=='B')
		{
			$sql_ins.="  ,:block,:office ";
		}
		if($user_type=='L')
		{
			$sql_ins.="  ,:block,:lessee ";
		}
		
		$sql_ins.=" ) ";
		$sthI = $conn->prepare($sql_ins);
		if($user_type=='B')
		{
		$sthI->bindParam(':block', $block);
		$sthI->bindParam(':office', $office);
		}
		if($user_type=='L')
		{
			$sthI->bindParam(':block', $block);
			$sthI->bindParam(':lessee', $lessee);
		}
		$sthI->bindParam(':user_id', $user_id);
		$sthI->bindParam(':user_nm', $user_nm);
		$sthI->bindParam(':password', $password);
		$sthI->bindParam(':user_type', $user_type);
		$sthI->bindParam(':addr', $addr);
		$sthI->bindParam(':contact_no', $contact_no);
		$sthI->bindParam(':email', $email);
		$sthI->bindParam(':base', $base);
		$sthI->bindParam(':district', $district);
		$sthI->bindParam(':hid_uid', $hid_uid);
		$sthI->bindParam(':access', $access);
		$sthI->execute();
		?>
        
      <script src="./js/alertify.min.js"></script>
      <link rel="stylesheet" href="./css/alertify.core.css" />
      <link rel="stylesheet" href="./css/alertify.default.css" />
		
		<script>
	     alertify.success('User Added Successfully');
	   </script>
        
	<?php	
		 
	 }
	 else
	 {
		 ?>  
      <script src="./js/alertify.min.js"></script>
      <link rel="stylesheet" href="./css/alertify.core.css" />
      <link rel="stylesheet" href="./css/alertify.default.css" />
	 <script>
		 alertify.error('User already Added');
	 </script>
	<?php	
	 }
}
?>
<?php
if(($tag=='REGISTER-OLD'))
{
	 $hid_id=test_input($_POST['hid_id']);
	 $hid_uid=test_input($_POST['hid_uid']);
	 $user_type=test_input($_POST['user_type']);
	 $user_nm=test_input($_POST['user_nm']);
	 $user_id=test_input($_POST['user_id']);
	 $contact_no=test_input($_POST['contact_no']);
	 $email=test_input($_POST['email']);
	 $addr=addslashes($_POST['addr']);
	 $district = isset($_POST['district']) ? $_POST['district'] : '';
	 $block = isset($_POST['block']) ? $_POST['block'] : '';
	 $office = isset($_POST['office']) ? $_POST['office'] : '';
	 $lessee = isset($_POST['lessee']) ? $_POST['lessee'] : '';
	 $base=$_POST['base'];
	 $user_status=$_POST['user_status'];
	 
	 
		$sql_ins=" update user_mas set ";
		$sql_ins.=" user_nm=upper(:user_nm),user_type=:user_type,user_addr=:addr, ";
		$sql_ins.="  user_cont_no=trim(:contact_no),mail_id=trim(:email),dist_id=:district, ";
		$sql_ins.=" status=:user_status,approved_by=trim(:hid_uid),approved_on=current_date ";
		if($user_type=='B')
		{
			$sql_ins.="  ,block_id=:block,comp_id=:office ";
		}
		if($user_type=='L')
		{
			$sql_ins.="  ,block_id=:block,les_id=:lessee ";
		}
		if(!empty($base))
		{
			$sql_ins.="  ,user_photo= :base ";
		}
		
		$sql_ins.=" where uid=:hid_id ";
		$sthI = $conn->prepare($sql_ins);
		if($user_type=='B')
		{
		$sthI->bindParam(':block', $block);
		$sthI->bindParam(':office', $office);
		}
		if($user_type=='L')
		{
			$sthI->bindParam(':block', $block);
			$sthI->bindParam(':lessee', $lessee);
		}
		$sthI->bindParam(':hid_id', $hid_id);
		$sthI->bindParam(':user_nm', $user_nm);
		
		$sthI->bindParam(':user_type', $user_type);
		$sthI->bindParam(':addr', $addr);
		$sthI->bindParam(':contact_no', $contact_no);
		$sthI->bindParam(':email', $email);
		$sthI->bindParam(':user_status', $user_status);
		if(!empty($base))
		{
		$sthI->bindParam(':base', $base);
		}
		$sthI->bindParam(':district', $district);
		$sthI->bindParam(':hid_uid', $hid_uid);
		$sthI->execute();
		?>
        
      <script src="./js/alertify.min.js"></script>
      <link rel="stylesheet" href="./css/alertify.core.css" />
      <link rel="stylesheet" href="./css/alertify.default.css" />
		
		<script>
		 alertify.alert("User Modification Successfully", function(){
			window.location.href='./user-master.php';
		  });
	   </script>
        
	<?php	
}
?>
<?php
if(($tag=='REGISTER-PROFILE'))
{
	 $hid_uid=$_POST['hid_uid'];
	 $user_nm=test_input($_POST['user_nm']);
	 $password=test_input($_POST['password']);
	 $csrftoken=$_POST['csrftoken'];
	 $hid_id=test_input($_POST['hid_id']);
	 
	$sqlTK=" SELECT count(id) as tk from user_log_mas where token=:csrftoken and id=:hid_id ";
	$sthTK = $conn->prepare($sqlTK);
	$sthTK->bindParam(':csrftoken', $csrftoken);
	$sthTK->bindParam(':hid_id', $hid_id);
	$sthTK->execute();
	$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
	$rowTK = $sthTK->fetch();
	$tk=$rowTK['tk'];
	//echo "$sqlTK  $csrftoken  $hid_id";
	if($tk>0)
	{
	// $addr = htmlspecialchars($addr, ENT_QUOTES);
	 
		$sql_ins=" update user_mas set ";
		$sql_ins.=" user_nm=upper(:user_nm) ";
		if(!empty($password))
		{
			$sql_ins.="  ,pwd=md5(:password) ";
		}
		
		$sql_ins.=" where uid=:hid_uid ";
		//echo "<br>$sql_ins";
		$sthI = $conn->prepare($sql_ins);
		$sthI->bindParam(':user_nm', $user_nm);
		if(!empty($password))
		{
		$sthI->bindParam(':password', $password);
		}
		$sthI->bindParam(':hid_uid', $hid_uid);
		$sthI->execute();
		?>
        
      <script src="./js/alertify.min.js"></script>
      <link rel="stylesheet" href="./css/alertify.core.css" />
      <link rel="stylesheet" href="./css/alertify.default.css" />
		
		<script>
		 alertify.alert("Profile Modification Successfully", function(){
			window.location.href='./my-account.php';
		  });
	   </script>
        
	<?php
	}	
}
?>
<?php
$conn=null;
?>