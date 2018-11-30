<?php
header("X-XSS-Protection: 1;mode = block");
//header("X-Frame-Options content='SAMEORIGIN'");

include("./inc/operator_class.php");
include("./inc/dblib.inc.php");
include("./inc/datelib.inc.php");
$conn = OpenDB(); 
/*
$base_dir  = __DIR__; 
$doc_root  = preg_replace("!${_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']); 
$base_url  = preg_replace("!^${doc_root}!", '', $base_dir);
$protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$port      = $_SERVER['SERVER_PORT'];
$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
$domain    = $_SERVER['SERVER_NAME'];*/
//$full_url  = "${protocol}://${domain}${disp_port}${base_url}";
$full_url=".";

$login = isset($_POST['login']) ? $_POST['login'] : '';
$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
$user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$csrftoken = isset($_POST['csrftoken']) ? $_POST['csrftoken'] : '';
/**************** citizen login ************************/
$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$eotp = isset($_POST['eotp']) ? $_POST['eotp'] : '';
$hid_lang = isset($_POST['hid_lang']) ? $_POST['hid_lang'] : '';

$randomtoken = md5(uniqid(rand(), true));

$Session = new Session('Script');
$protect = $Session->Get('protect');
if(!$protect)
{
	$Session->Set('protect','0');
}

if($protect>2)
{
	die('You Are Blocked! Contact Portal Admin');
}

if(($submit=="Submit") or ($submit=="সাবমিট করুন"))
{
	$protect = $Session->Get('protect');	
	$protect++;
		
	$Session->Set('protect',$protect);
	if(!empty($randomtoken))
	{
		$sql_search=" select count(*) as cnt from otp_mas where ";
		$sql_search.=" cell_no=:mobile and otp_no=:eotp";
		$sth_search = $conn->prepare($sql_search);
		$sth_search->bindParam(':mobile', $mobile);
		$sth_search->bindParam(':eotp', $eotp);
		$sth_search->execute();
		$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
		$row_search = $sth_search->fetch();
		$cnt=$row_search['cnt'];

		if($cnt>0)
		{
			    $sql="select * ";
				$sql.="from citizen_mas ";
				$sql.=" where rmn=:mobile ";
				$sth = $conn->prepare($sql);
				$sth->bindParam(':mobile',  $mobile);
				$sth->execute();
				$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
				$row2 = $sth->fetch();
				$citizen_id=$row2['citizen_id'];
				$rmn=$row2['rmn'];
				$citizen_nm=$row2['citizen_nm'];
				$addr=$row2['addr'];
				$alter_no=$row2['alter_no'];
				$land_mark=$row2['land_mark'];
				$ps_id=$row2['ps_id'];
				$email_id=$row2['email_id'];
				$photo_path=$row2['photo_path'];
				
				$sql_his=" insert into otp_his  select * from otp_mas where cell_no=:mobile ";
				$sth_his = $conn->prepare($sql_his);
				$sth_his->bindParam(':mobile', $mobile);
				$sth_his->execute();
				
				$sql_upd="  update citizen_mas set token=:csrftoken ";
				$sql_upd.=" where rmn=:mobile";
				$sth_upd = $conn->prepare($sql_upd);
				$sth_upd->bindParam(':mobile', $mobile);
				$sth_upd->bindParam(':csrftoken', $csrftoken);
				$sth_upd->execute();

				$sql_del="  delete from otp_mas where cell_no=:mobile";
				$sth_del = $conn->prepare($sql_del);
				$sth_del->bindParam(':mobile', $mobile);
				$sth_del->execute();
				
				$Session = new Session('Script');
				$Session->Set('citizen_id',$citizen_id);
				$Session->Set('rmn',$rmn);
				$Session->Set('citizen_nm',$citizen_nm);
				$Session->Set('addr',$addr);
				$Session->Set('alter_no',$alter_no);
				$Session->Set('land_mark',$land_mark);
				$Session->Set('ps_id',$ps_id);
				$Session->Set('email_id',$email_id);
				$Session->Set('photo_path',$photo_path);
				$Session->Set('token',$csrftoken);
				$Session->Set('hid_lang',$hid_lang);
				?>
				<script>
				window.location.href='<?php echo $full_url; ?>/index1.php'
				</script>
	            <?php
		}
		else
		{
				?>
				<script>
	             alertify.error('check otp');  
	            </script>
	            <?php 
		}
	}
}



?>

<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>জন সহায়ক - পশ্চিম মেদিনীপুর জেলা প্রশাসন</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <meta http-equiv="Content-Security-Policy" content="default-src 'self';img-src * 'self' data: http:; connect-src 'self' 'unsafe-inline' 'unsafe-eval' *; child-src 'self' 'unsafe-inline' 'unsafe-eval' *; script-src 'self' 'unsafe-inline' 'unsafe-eval' *  ; style-src  'self' 'unsafe-inline' 'unsafe-eval' * data: http:">
 <link rel="icon"  href="./images/favicon.ico">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/select2/dist/css/select2.min.css">

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="./dist/html5shiv.min.js"></script>
  <script src="<?php echo $full_url; ?>/dist/respond.min.js"></script>
  <![endif]-->
<!-- <script src="https://code.jquery.com/jquery-2.1.4.min.js" integrity="sha384-R4/ztc4ZlRqWjqIuvf6RX5yb/v90qNGx6fS48N0tRxiGkqveZETq72KgDVJCp2TC" crossorigin="anonymous"></script>  -->
 <script src="<?php echo $full_url; ?>/js/jquery-2.1.4.min.js" integrity="sha384-R4/ztc4ZlRqWjqIuvf6RX5yb/v90qNGx6fS48N0tRxiGkqveZETq72KgDVJCp2TC" crossorigin="anonymous"></script>
<script src="<?php echo $full_url; ?>/js/alertify.min.js"></script>
<link rel="stylesheet" href="<?php echo $full_url; ?>/css/alertify.core.css" />
<link rel="stylesheet" href="<?php echo $full_url; ?>/css/alertify.default.css" />
</head>

<body class="hold-transition login-page">
<?php
/*************** Department Login ****************/
if($login=='Login')
{
	
	$protect = $Session->Get('protect');	
	$protect++;
		
	$Session->Set('protect',$protect);
	if(!empty($randomtoken))
	{
	
		$sql_ct="select count(uid) as ct from user_mas ";
		$sql_ct.="where user_id=:user_name and pwd=md5(:password) ";
		$sth_ct = $conn->prepare($sql_ct);
		$sth_ct->bindParam(':user_name', $user_name);
		$sth_ct->bindParam(':password', $password);
		$sth_ct->execute();
		$ss_ct=$sth_ct->setFetchMode(PDO::FETCH_ASSOC);
		$row_ct = $sth_ct->fetch();
		$total=$row_ct['ct'];
			

		if($total>0)
	    {
			$sql="select * ";
			$sql.="from user_mas ";
			$sql.=" where user_id=:user_name and pwd=md5(:password) ";
			$sth = $conn->prepare($sql);
			$sth->bindParam(':user_name',  $user_name);
			$sth->bindParam(':password', $password);
			$sth->execute();
			$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
			$row2 = $sth->fetch();
			$uid=$row2['uid'];
			$user_nm=$row2['user_nm'];
			$user_id=$row2['user_id'];
			$user_type=$row2['user_type'];
			$user_status=$row2['user_status'];
			$page_assign=$row2['page_assign'];
			$orgn_id=$row2['orgn_id'];
           	$comp_type_id=$row2['comp_type_id'];
			$dist_id=$row2['dist_id'];
			$sub_div_id=$row2['sub_div_id'];
			$block_id=$row2['block_id'];
			$ps_id=$row2['ps_id'];
			$access=$row2['access'];
			$restore_access=$row2['restore_access'];
				 

			if($user_status=='A')
			{
				$sql1 ="select current_timestamp as login_time ";
				$sth1 = $conn->prepare($sql1);;
				$sth1->execute();
			    $ss1=$sth1->setFetchMode(PDO::FETCH_ASSOC);
			    $rowt = $sth1->fetch();
			    $login_time=$rowt['login_time'];
				$current_status='A';
				
				$sqlI="insert into user_log_mas (uid,login_on,token) ";
				$sqlI.=" values (:uid,:login_time,:csrftoken) ";
				$sthI = $conn->prepare($sqlI);
				$sthI->bindParam(':uid', $uid);
		    	$sthI->bindParam(':login_time', $login_time);
		    	$sthI->bindParam(':csrftoken', $csrftoken);
				$sthI->execute();
				
				$sqlC="select max(id) as id from user_log_mas WHERE uid=:uid ";
				$sthr = $conn->prepare($sqlC);;
				$sthr->bindParam(':uid', $uid);
				$sthr->execute();
			    $ssr=$sthr->setFetchMode(PDO::FETCH_ASSOC);
			    $rowr = $sthr->fetch();
			    $chk_id=$rowr['id'];
				
					// Clean setting of the session data
			    $sql_upd="  update user_mas set token=:csrftoken ";
				$sql_upd.=" ,current_status='A' where uid=:uid";
				$sth_upd = $conn->prepare($sql_upd);
				$sth_upd->bindParam(':uid', $uid);
				$sth_upd->bindParam(':csrftoken', $csrftoken);
				$sth_upd->execute();
				
				$Session->Set('uid',$uid);
				$Session->Set('dist_id',$dist_id);
				$Session->Set('block_id',$block_id);
				$Session->Set('orgn_id',$orgn_id);
				$Session->Set('sub_div_id',$sub_div_id);
				$Session->Set('user_id',$user_id);
				$Session->Set('user_nm',$user_nm);
				$Session->Set('user_type',$user_type);
				$Session->Set('ps_id',$ps_id);
				$Session->Set('comp_type_id',$comp_type_id);
				$Session->Set('page_assign',$page_assign);
				$Session->Set('id',$chk_id);
				$Session->Set('token',$csrftoken);
				$Session->Set('full_url',$full_url);

				/************** new session data **********/
				 
				 $Session->Set('user_status',$user_status);
				 $Session->Set('current_status',$current_status);
				 $Session->Set('access',$access);
				 $Session->Set('restore_access',$restore_access);
				//header("location:./index.php");
				//exit();

				?>
	            <script>
				window.location.href='<?php echo $full_url; ?>/index.php'
				</script>
				<?php		
			}
			else
			{
				?>
				<script>
				 alertify.error('User ID Not Active. Please contact Administrator ....');  
				</script>
				<?php 
			}
		}
		else
		{
			?>
					<script>
					 alertify.error('Wrong User-Id/Password ....');  
					</script>
					<?php   
		}
	}
}
?>
<?php
$sqle =" select lang_id,english,bengali ";
$sqle.=" from language_mas ";
$sqle.=" order by lang_id  " ;
$sthe = $conn->prepare($sqle);
$sthe->execute();
$sse=$sthe->setFetchMode(PDO::FETCH_ASSOC);
$rowe = $sthe->fetchAll();
foreach ($rowe as $keye => $value) 
{
	$english[]=$value['english'];
	$bengali[]=$value['bengali'];
}
?>
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>User Login</b></a>
  </div>
  <div class="login-box-body">
    <form name="form1" method="post"  enctype="multipart/form-data" onSubmit="return validate()">
    	 <input type="hidden" name="csrftoken" value=" <?php echo $randomtoken; ?>" />
    	<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li ><a href="#citizen" data-toggle="tab">Citizen Login</a></li>
              <li class="active"><a href="#department" data-toggle="tab">Departmental Login</a></li>
            </ul>
            <div class="tab-content" style="padding:15px 0px !important; border:none;">
              <div class=" tab-pane" id="citizen">
                <div class="col-xs-12">
                  <div class="checkbox icheck">
                    <label style="padding-left:5px;">
                      <input type="checkbox" name="bengali" id="bengali" value="B" > বাংলা
                    </label>
                    <input type="hidden" id="hid_lang"  name="hid_lang">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <input type="user_id" name="mobile" id="mobile" class="form-control" placeholder="<?php echo $english[0]; ?>" tabindex="1" autocomplete="off">
                </div>
                <div class="form-group has-feedback">
                  <input type="text" name="name" id="name" class="form-control" placeholder="<?php echo $english[1]; ?>" tabindex="1" autocomplete="off">
                </div>
                <div id="div_otp">
                <div class="row">
                  <div class="col-xs-12">
                    <input type="button" name="otp" id="otp" value="<?php echo $english[3]; ?>"  class="btn btn-primary btn-block btn-flat">
                  </div>
                </div>
                </div>
              </div>
              <div class="tab-pane active" id="department">
                <div class="form-group has-feedback">
                  <input type="user_id" name="user_name" id="user_name" class="form-control" placeholder="Enter User Name" tabindex="1" autocomplete="off">
                </div>
                <div class="form-group has-feedback">
                  <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" tabindex="1" autocomplete="off">
                </div>
                <div class="row">
                 
                  <div class="col-xs-12">
                    <input type="submit" name="login" id="login" value="Login"  class="btn btn-primary btn-block btn-flat">
                  </div>
                </div>
              </div>
            </div>
          </div>
    </form>
  </div>
</div>


<script src="<?php echo $full_url; ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $full_url; ?>/bower_components/select2/dist/js/select2.full.min.js"></script>
<style>
         .alertify-log-custom {
            background: blue;
         }
      </style>
<script>
jQuery('#bengali').click( function() 
{
	$('[id="bengali"]').each(function() {
		if($(this).prop('checked'))
		{
	   		if(!$('#mobile').val())
	   		{ 
			    $('#mobile').attr("placeholder", "<?php echo $bengali[0]; ?>")
			}
			if(!$('#name').val())
	   		{ 
			    $('#name').attr("placeholder", "<?php echo $bengali[1]; ?>");
			}
			if(!$('#eotp').val())
	   		{ 
			    $('#eotp').attr("placeholder", "<?php echo $bengali[2]; ?>");
			}
			
			$('#otp').attr("value", "<?php echo $bengali[3]; ?>");
			$('#reotp').attr("value", "<?php echo $bengali[4]; ?>");
			$('#submit').attr("value", "<?php echo $bengali[5]; ?>");
			$('#hid_lang').attr("value", "B");
			
		}
		else
		{
	    	if(!$('#name').val())
	   		{ 
			    $('#name').attr("placeholder", "<?php echo $english[1]; ?>");
			}
			if(!$('#mobile').val())
	   		{ 
			    $('#mobile').attr("placeholder", "<?php echo $english[0]; ?>");
			}
			if(!$('#eotp').val())
	   		{ 
			    $('#eotp').attr("placeholder", "<?php echo $english[2]; ?>");
			}
			$('#otp').attr("value", "<?php echo $english[3]; ?>");
			$('#reotp').attr("value", "<?php echo $english[4]; ?>");
			$('#submit').attr("value", "<?php echo $english[5]; ?>");
			$('#hid_lang').attr("value", "");
	    }
	});
});
$("#otp").click(function(){
    var mobile=$('#mobile').val();
	var name=$('#name').val();
    var hid_lang=$('#hid_lang').val();
	
	if(mobile=="")
	{
		if(hid_lang=='B')
		{
			alertify.error("<?php echo $bengali[0]; ?>");
		}
		else
		{
			alertify.error("<?php echo $english[0]; ?>");
		}
		
		$('#mobile').css("border-color","#FF0000");
		$('#mobile').focus();
	      return false;								   
	}
	if(mobile!="")
    {
	   if(!/^[0-9]+$/.test(mobile))
	   {
		 if(hid_lang=='B')
		 {
			alertify.error("আপনার মোবাইল নম্বর চেক করুন");
		 }
		 else
		 {
			alertify.error("Please check your mobile no");
		 }
		 $('#mobile').focus();
		 return false;
	   }
    }
	if(name!="")
    {
	   if(/^[*/-`!@#$&*()<>]+$/.test(name))
	   {
		 if(hid_lang=='B')
		 {
			alertify.error("আপনার নাম চেক করুন");
		 }
		 else
		 {
			alertify.error("Please check your name");
		 }
		 $('#name').focus();
		 return false;
	   }
    }
	 var request = $.ajax({
	  url: "./back/login_back.php",
	  method: "POST",
	  data: { mobile: mobile,name:name,hid_lang:hid_lang, tag: 'OTP'  },
	  dataType: "html",
	  success:function( msg ) 
	  {
		var name=msg.trim();
		$('#div_otp').html(name);  	  
	  },
	  error: function(xhr, status, error) 
	  {
			alert(status);
			alert(xhr.responseText);
	  },
	});
});
  $(function () {
   $(".select2").select2();
   
  });
$("#mobile").keyup(function(){
	var myid=$('#mobile').val();
	var hid_lang=$('#hid_lang').val();
	if(myid!="")
	   {
	   	   if(!/^[0-9]+$/.test(myid))
		   {
			 if(hid_lang=='B')
			 {
				alertify.error("আপনার মোবাইল নম্বর চেক করুন");
			 }
			 else
			 {
				alertify.error("Please check your mobile no");
			 }
	         $('#mobile').focus();
		     return false;
	       }
	   }
	 if(myid.length>9)
	 {
		 var request = $.ajax({
		  url: "./back/login_back.php",
		  method: "POST",
		  data: { myid: myid, tag: 'CITIZEN'  },
		  dataType: "html",
		  success:function( msg ) {

		  var name=msg.trim();
			$('#name').val(name);  
		},
		error: function(xhr, status, error) {
	            alert(status);
	            alert(xhr.responseText);
	        },
		});
	 }
	 else
	 {
		 $('#name').val();  
	 }
});
jQuery('#login').click( function() {
var user_name=$('#user_name').val();
var mobile=$('#mobile').val();

if(user_name=="")
{
	alertify.error("User Name cannot be Blank");
	$('#user_name').css("border-color","#FF0000");
	$('#user_name').focus();
      return false;								   
}
if(user_name!="")
{
   if(/^[/!()<>]+$/.test(user_name))
   {
	 
	alertify.error("Please check User Name ('/!()<>' character not allow)");
	$('#user_name').css("border-color","#FF0000");
	 $('#name').focus();
	 return false;
   }
}
if ($('#password').val() == "") {
   alertify.error(" Password  cannot be Blank");
	$('#password').css("border-color","#FF0000");
	$('#password').focus();
      return false;								   
}
if(password!="")
{
   if(/^[/!()<>]+$/.test(password))
   {
	 
	alertify.error("Please check Password ('/!()<>' character not allow)");
	$('#password').css("border-color","#FF0000");
	 $('#password').focus();
	 return false;
   }
}
 });

</script>


</body>
</html>
<?php
$conn=null;
?>