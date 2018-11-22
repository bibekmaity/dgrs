<?php
header("X-XSS-Protection: 1;mode = block");
include("../inc/dblib.inc.php");
$conn = OpenDB();
$tag = isset($_POST['tag']) ? $_POST['tag'] : '';
?>
<?php
if(($tag=='SHOW-BLOCK-PS'))
{
	$id = $_POST['id'];
	
	
	$stmt ="SELECT pm.ps_id,pm.ps_nm ";
	$stmt.="FROM ps_mas pm,block_mas bm ";
	$stmt.="where pm.sub_div_id=bm.sub_div_id ";
	if(!empty($id))
	{
	$stmt.="and bm.block_id=:block_id ";
	}
	$stmt.="order by pm.ps_nm ";
	//echo "$stmt-->$id";
	$sth = $conn->prepare($stmt);
	if(!empty($id))
	{
	$sth->bindParam(':block_id', $id);
	}
	$sth->execute();
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$row = $sth->fetchAll();
	
	
	?>
         <select name="ps" id="ps" class="form-control select2"  style="width: 100%;" tabindex="3">
           <option value="">Police Station</option>
           <?php
            foreach ($row as $key => $row) 
            {
                $e_ps_id=$row['ps_id'];
                $e_ps_nm=$row['ps_nm'];
                ?>	
               <option value="<?php echo $e_ps_id; ?>" <?php /*if($e_ps_id==$e_sub_div_id) { echo "SELECTED"; }*/ ?>><?php echo $e_ps_nm; ?></option>
                <?php
            }
            ?>
         </select>
       <script>

  $(function () {
    $('.select2').select2()
  });
</script>          
         <?php
}

?>

<?php
if(($tag=='UPDATE-USER'))
{
		
	 $hid_token= isset($_POST['hid_token'])? $_POST['hid_token']: '';
	 $hid_uid= isset($_POST['hid_uid'])? $_POST['hid_uid']: '';
	 $dept= isset($_POST['dept'])? $_POST['dept']: '';
	 $hid_log_user= isset($_POST['hid_log_user'])? $_POST['hid_log_user']: '';
	 
	 $comp_type= isset($_POST['comp_type'])? $_POST['comp_type']: '';
	 $user_nm= test_input(isset($_POST['user_nm'])? $_POST['user_nm']: '');
	 $user_id= test_input(isset($_POST['user_id'])? $_POST['user_id']: '');
	 $pwd= test_input($_POST['pwd']);
	 $user_status = isset($_POST['user_status']) ? $_POST['user_status'] : '';
 	 $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
 	 $district = isset($_POST['district']) ? $_POST['district'] : '';
	 $sub_division = isset($_POST['sub_division']) ? $_POST['sub_division'] : '';
	 $block = isset($_POST['block']) ? $_POST['block'] : '';
	 $ps = isset($_POST['ps']) ? $_POST['ps'] : '';
	 $comp_type=implode(',',$comp_type);

	 $sql=" select count(*) as log_count from user_log_mas ";
	 $sql.=" where uid=:user_id and token=:token ";
	 $sth = $conn->prepare($sql);
	 $sth->bindParam(':token', $hid_token);
	 $sth->bindParam(':user_id', $hid_log_user);
	 $sth->execute();
	 $ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
	 $row = $sth->fetch();
	 $log_count=$row['log_count'];
	// echo "Ct: $log_count";
	 if($log_count>0)
	 {
		$sql_ins ="update user_mas set ";
		$sql_ins.="user_nm=upper(:user_nm),user_status=:user_status,user_type=:user_type, ";
		$sql_ins.="dept_id=:dept_id,comp_type_id=:comp_type,dist_id=:district, ";
		$sql_ins.="sub_div_id=:sub_division,block_id=:block_id";
		$sql_ins.=",ps_id=:ps_id ";
		if(!empty($pwd))
		{
			$sql_ins.=",pwd=md5(:pwd) ";
		}
				
		$sql_ins.=" where md5(uid)=:hid_id ";
		
		$sthI = $conn->prepare($sql_ins);
		$sthI->bindParam(':hid_id', $hid_id);
		$sthI->bindParam(':user_nm', $user_nm);
		$sthI->bindParam(':user_status', $user_status);
		$sthI->bindParam(':user_type', $user_type);
		$sthI->bindParam(':dept_id', $dept);
		$sthI->bindParam(':comp_type', $comp_type);
		$sthI->bindParam(':district', $district);
		$sthI->bindParam(':sub_division', $sub_division);
		$sthI->bindParam(':block_id', $block);
		$sthI->bindParam(':ps_id', $ps);
		/*echo "$hid_id->$user_nm->$user_status->$user_type->$user_type";
		echo "->$dept->$comp_type->$district->$sub_division->$block";
		echo "->$ps->$hid_uid";*/
		if(!empty($pwd))
		{
		$sthI->bindParam(':pwd', $pwd);
		}

		$sthI->bindParam(':hid_id', $hid_uid);
		$sthI->execute();
		//var_dump($sthI->debugDumpParams());
	 
	 
		
		?>
        
      <script src="./js/alertify.min.js"></script>
      <link rel="stylesheet" href="./css/alertify.core.css" />
      <link rel="stylesheet" href="./css/alertify.default.css" />
		
		<script>
	     alertify.success('User updated Successfully');
	   </script>
        
	<?php	
		 
	 }
	
}
?>
<?php
if(($tag=="INSERT-USER"))
{
	 $hid_token= isset($_POST['hid_token'])? $_POST['hid_token']: '';
	 $hid_uid= isset($_POST['hid_uid'])? $_POST['hid_uid']: '';
	 $dept= isset($_POST['dept'])? $_POST['dept']: '';
	 $hid_log_user= isset($_POST['hid_log_user'])? $_POST['hid_log_user']: '';
	 
	 $comp_type= isset($_POST['comp_type'])? $_POST['comp_type']: '';
	 $user_nm= test_input(isset($_POST['user_nm'])? $_POST['user_nm']: '');
	 $user_id= test_input(isset($_POST['user_id'])? $_POST['user_id']: '');
	 $pwd= test_input($_POST['pwd']);
	 $user_status = isset($_POST['user_status']) ? $_POST['user_status'] : '';
 	 $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
 	 $district = isset($_POST['district']) ? $_POST['district'] : '';
	 $sub_division = isset($_POST['sub_division']) ? $_POST['sub_division'] : '';
	 $block = isset($_POST['block']) ? $_POST['block'] : '';
	 $ps = isset($_POST['ps']) ? $_POST['ps'] : '';
     $comp_type=implode(',',$comp_type);
	 $page_assign="8,9,10,11,12,17";

	 $sql=" select count(*) as log_count from user_log_mas ";
	 $sql.=" where uid=:user_id and token=:token ";
	 $sth = $conn->prepare($sql);
	 $sth->bindParam(':token', $hid_token);
	 $sth->bindParam(':user_id', $hid_log_user);
	 $sth->execute();
	 $ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
	 $row = $sth->fetch();
	 $log_count=$row['log_count'];
	// echo "Ct: $log_count";
	 if($log_count>0)
	 {
		
		$sql2=" select count(*) as user_count from user_mas ";
		$sql2.=" where user_id=:user_id  ";
		$sth2 = $conn->prepare($sql2);
		$sth2->bindParam(':user_id', $user_id);
		$sth2->execute();
		$sth2->setFetchMode(PDO::FETCH_ASSOC);
		$row2 = $sth2->fetch();
		$user_count=$row2['user_count'];
		
		if($user_count<=0)
		{
			$sql_ins ="insert into user_mas(user_nm,user_status,user_type ";
			$sql_ins.=",dept_id,comp_type_id,dist_id,sub_div_id,block_id";
			$sql_ins.=",ps_id,pwd,user_id,page_assign) ";
			$sql_ins.="values(upper(:user_nm),:user_status,:user_type ";
			$sql_ins.=",:dept_id,:comp_type,:district ";
			$sql_ins.=",:sub_division,:block_id ";
			$sql_ins.=",:ps_id,md5(:pwd),:user_id,:page_assign) ";
			
			$sthI = $conn->prepare($sql_ins);
			$sthI->bindParam(':user_nm', $user_nm);
			$sthI->bindParam(':user_status', $user_status);
			$sthI->bindParam(':user_type', $user_type);
			$sthI->bindParam(':dept_id', $dept);
			$sthI->bindParam(':comp_type', $comp_type);
			$sthI->bindParam(':district', $district);
			$sthI->bindParam(':sub_division', $sub_division);
			$sthI->bindParam(':block_id', $block);
			$sthI->bindParam(':ps_id', $ps);
			$sthI->bindParam(':pwd', $pwd);
			$sthI->bindParam(':user_id', $user_id);
			$sthI->bindParam(':page_assign', $page_assign);
			
			/*echo "$user_nm->$user_status->$user_type->$user_type";
			echo "->$dept->$comp_type->$district->$sub_division->$block";
			echo "->$ps->$hid_uid";*/
			$sthI->execute();
			//var_dump($sthI->debugDumpParams());
			?>
            <script src="./js/alertify.min.js"></script>
            <link rel="stylesheet" href="./css/alertify.core.css" />
            <link rel="stylesheet" href="./css/alertify.default.css" />
            <script>
             alertify.alert("User Created Successfully", function(){
                window.location.href='./user-master.php';
              });
            </script>
            <?php
			
			
		}else
		{
			?>
            <script src="./js/alertify.min.js"></script>
            <link rel="stylesheet" href="./css/alertify.core.css" />
            <link rel="stylesheet" href="./css/alertify.default.css" />
            <script>
             alertify.alert("User already exists with this user name", function(){
//             window.location.href='./user-master.php';
              });
            </script>
            <?php
			
		}

		?>
        
      	
        
	<?php	
	 }
}
?>
<?php
if(($tag=='REGISTER-PROFILE'))
{
	 $hid_uid=$_POST['hid_uid'];
	 $user_nm=test_input($_POST['user_nm']);
	 $contact_no=test_input($_POST['contact_no']);
	 $email=test_input($_POST['email']);
	 $addr=test_input(addslashes($_POST['addr']));
	 $base=test_input($_POST['base']);
	 $password=test_input($_POST['password']);
	 $csrftoken=test_input($_POST['csrftoken']);
	 $hid_id=test_input($_POST['hid_id']);
	 $addr = preg_replace("/<script.*?\/script>/s", "", $addr) ? : $addr;
	 
	$sqlTK="SELECT count(id) as tk from user_log_mas where token=:csrftoken and id=:hid_id ";
	$sthTK = $conn->prepare($sqlTK);
	$sthTK->bindParam(':csrftoken', $csrftoken);
	$sthTK->bindParam(':hid_id', $hid_id);
	$sthTK->execute();
	$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
	$rowTK = $sthTK->fetch();
	$tk=$rowTK['tk'];
	if($tk>0)
	{
	// $addr = htmlspecialchars($addr, ENT_QUOTES);
	 
		$sql_ins=" update user_mas set ";
		$sql_ins.=" user_nm=upper(:user_nm),user_addr=:addr, ";
		$sql_ins.="  user_cont_no=trim(:contact_no),mail_id=trim(:email) ";
		if(!empty($password))
		{
			$sql_ins.="  ,pwd=md5(:password) ";
		}
		if(!empty($base))
		{
			$sql_ins.="  ,user_photo= :base ";
		}
		
		$sql_ins.=" where uid=:hid_uid ";
		$sthI = $conn->prepare($sql_ins);
		$sthI->bindParam(':user_nm', $user_nm);
		$sthI->bindParam(':addr', $addr);
		$sthI->bindParam(':contact_no', $contact_no);
		$sthI->bindParam(':email', $email);
		if(!empty($base))
		{
		$sthI->bindParam(':base', $base);
		}
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