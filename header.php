<?php
header("X-XSS-Protection: 1;mode = block");
header("X-Content-Type-Options: nosniff");
//header("Header set X-Frame-Options "allow-from https://example.com/"");

include("./inc/operator_class.php");

function curPageName() 
{
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
$current_page=curPageName(); 

include("./inc/dblib.inc.php");
include("./inc/datelib.inc.php");
$conn = OpenDB();

$Session = new Session('Script');
$ses_uid = $Session->Get('uid');
$ses_orgn_id = $Session->Get('orgn_id');
$ses_user_nm = $Session->Get('user_nm');
$ses_user_id = $Session->Get('user_id');
$ses_user_type = $Session->Get('user_type');
$ses_page_per= $Session->Get('page_assign');
$ses_id= $Session->Get('id');
$ses_token= $Session->Get('token');
$full_url= $Session->Get('full_url');

$ses_dist_id = $Session->Get('dist_id');
$ses_sub_div_id = $Session->Get('sub_div_id');
$ses_block_id = $Session->Get('block_id');
$ses_ps_id = $Session->Get('ps_id');
$ses_comp_type_id = $Session->Get('comp_type_id');

/************** new session data **********/
$ses_user_status= $Session->Get('user_status');
$ses_current_status= $Session->Get('current_status');
$ses_access= $Session->Get('access');

if($ses_user_type=='D')
{
	$sql="select mid from menu_master ";
	$sql.="where murl=:current_page ";
	$sth = $conn->prepare($sql);
	$sth->bindParam(':current_page', $current_page);
	$sth->execute();
	$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
	$row2 = $sth->fetch();
	$mid_p=$row2['mid'];
}
if(empty($ses_uid))
{
	//header("location:./login.php");
	//exit();
	?>
    <script>
    window.location.href='./login.php'
	</script>
    <?php
}
//echo  "User: $ses_user_type<br>";
if($ses_user_type!="A")
{
if($current_page!='index.php' )
{
	if($current_page!='my-account.php')
	{		
		$sql="select mid from menu_master ";
		$sql.="where murl=:current_page ";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':current_page', $current_page);
		$sth->execute();
		$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row2 = $sth->fetch();
		$mid_p=$row2['mid'];
		
		//---------- search the current page if it has permission---------------------
		$arr_page_per=explode(",",$ses_page_per);
		$found=array_search($mid_p,$arr_page_per);
		
		if(strlen($found)<1)
		{
			?>
			<script language="javascript">
			window.location.href="./index.php";
			</script>	
			<?php
		}
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
  <link rel="stylesheet" href="<?php echo $full_url; ?>/dist/css/skins/_all-skins.min.css">
 <script src = "<?php echo $full_url; ?>/js/jquery-1.9.1.js" integrity="sha384-+GtXzQ3eTCAK6MNrGmy3TcOujpxp7MnMAi6nvlvbZlETUcZeCk7TDwvlCw9RiV6R" crossorigin="anonymous"></script>
<!--  <script src="https://code.jquery.com/jquery-1.9.1.js" integrity="sha384-+GtXzQ3eTCAK6MNrGmy3TcOujpxp7MnMAi6nvlvbZlETUcZeCk7TDwvlCw9RiV6R" crossorigin="anonymous"></script>-->
  <script src="<?php echo $full_url; ?>/js/alertify.min.js"></script>
  <link rel="stylesheet" href="<?php echo $full_url; ?>/css/alertify.core.css" />
  <link rel="stylesheet" href="<?php echo $full_url; ?>/css/alertify.default.css" />
  <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/select2/dist/css/select2.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="./dist/html5shiv.min.js"></script>
  <script src="./dist/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
 <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
--></head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo $full_url; ?>/index.php" class="logo">
     <img src="<?php echo $full_url; ?>/images/logo.png" alt="Citizen Complaint" title="Citizen Complaint" height="40px" width="25px" align="left" style="margin-top:8px;" />
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!--<span class="logo-mini"><b>T</b>P</span>
       logo for regular state and mobile devices -->
      <span class="logo-lg"><b>জন সহায়ক</b> &nbsp; </span>
    </a>
    
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php if(!empty($ses_photo_path))
			  {
				  ?>
                  <img src="<?php echo $ses_photo_path; ?>" class="user-image" alt="<?php echo $ses_user_nm; ?>">
                  <?php
			  }
			  else
			  {
				  ?>
				  <img src="<?php echo $full_url; ?>/images/user.png" class="user-image" alt="<?php echo $ses_user_nm; ?>">
                 <?php
			  }
			  ?>
              <span class="hidden-xs"><?php echo $ses_user_nm; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <?php if(!empty($ses_photo_path))
			   {
				    ?>
                    <img src="<?php echo $full_url; ?>/<?php echo $ses_photo_path; ?>" class="img-circle" alt="<?php echo $ses_user_nm; ?>" height="160px">
                    <?php
			  }
			  else
			  {
				  ?>
				  <img src="<?php echo $full_url; ?>/images/user.png" class="img-circle" alt="<?php echo $ses_user_nm; ?>"  height="160px">
                 <?php
			  }
			  ?>
              <p><?php echo $ses_user_nm; ?></p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo $full_url; ?>/my-account.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo $full_url; ?>/logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>        
          
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image" style="color:#FFF;">
          &nbsp;
        </div>
        <div class="pull-left info">
          <p>&nbsp;</p>
        </div>
      </div>
      <!-- search form -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li>
          <a href="<?php echo $full_url; ?>/index.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <?php
		$sql_p="select mid,parent_id,mbody,img_nm from menu_master ";
		$sql_p.="where murl=:current_page ";
		$sth_p = $conn->prepare($sql_p);
		$sth_p->bindParam(':current_page', $current_page);
		$sth_p->execute();
		$sscurrent_page=$sth_p->setFetchMode(PDO::FETCH_ASSOC);
		$row_p = $sth_p->fetch();
		$mid_p=$row_p['mid'];
		$parent_id_p=$row_p['parent_id'];
		$mbody_p=$row_p['mbody'];
		$img_nm_p=$row_p['img_nm'];


$sql="select * from menu_master ";
$sql.="where parent_id='0' and show_tag='T' ";
$sql.="order by srl";
$sth = $conn->prepare($sql);
$sth->execute();
$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetchAll();
foreach ($row as $key => $value) 
{
	$mbody=$value['mbody'];
	$murl=$value['murl']; 
	$mid=$value['mid']; 
	$img_nm=$value['img_nm'];  
	     
	$cnt=0;
	$right = isset($right) ? $right : '';
	//if(!empty($ses_page_per))
	//{
    $sqlc ="select count(mid) as cnt from menu_master ";
	$sqlc.="where 1=1 ";
	$sqlc.="and parent_id=:mid ";
	$sqlc.="and show_tag='T' ";	
	//$sqlc.="and mid in(".$ses_page_per.") ";
	//echo "$sqlc-->$mid<br>";
	$sthc = $conn->prepare($sqlc);
	$sthc->bindParam(':mid', $mid);
	$sthc->execute();
	$sthc->setFetchMode(PDO::FETCH_ASSOC);
	$rowc = $sthc->fetch();
	$cnt=$rowc['cnt'];
	//}
	if($cnt>=1){
		$right="<i class='fa fa-angle-left pull-right'></i>";
	$gd=$parent_id_p;
	}
	else
	{
		$gd=$mid_p;
	}
	//echo "$ses_page_per-- $ses_user_type";
	?>
        <li class="<?php if($gd==$mid){?> active <?php  }?> treeview">
          <a href="<?php if(!empty($murl)){ echo $murl; } else{ echo "#";}?>">
            <i class="fa <?php echo $img_nm; ?>"></i> <span><?php echo $mbody; ?></span> <?php echo $right; ?>
          </a>
          <?php
          if($cnt>=1){
			?>  
          <ul class="treeview-menu">
          <?php
		 // echo "xxx";
            $sql_sub ="select * from menu_master ";
            $sql_sub.="where parent_id=:mid and show_tag='T' ";
			if($ses_user_type!="A")
			{
            $sql_sub.="and mid in(".$ses_page_per.") ";
			}
            $sql_sub.="order by srl";
			//echo "$sql_sub--$mid<br>";
            $sth_sub = $conn->prepare($sql_sub);
			$sth_sub->bindParam(':mid', $mid);
			$sth_sub->execute();
			$ss_sub=$sth_sub->setFetchMode(PDO::FETCH_ASSOC);
			$row_sub = $sth_sub->fetchAll();
			foreach ($row_sub as $key_sub => $value_sub) 
			{
			   $mbody_sub=$value_sub['mbody'];
			   $murl_sub=$value_sub['murl']; 
			   $img_nm_sub=$value_sub['img_nm'];  
			   $mid_sub=$value_sub['mid'];  
				
				$sql_p="select mbody,parent_id from menu_master ";
				$sql_p.="where murl=:current_page ";
				$sth_p = $conn->prepare($sql_p);
				$sth_p->bindParam(':current_page', $current_page);
				$sth_p->execute();
				$ss_p=$sth_p->setFetchMode(PDO::FETCH_ASSOC);
				$row_p = $sth_p->fetch();
				$parent_id_p=$row_p['parent_id'];
				
      	  ?> 
            <li <?php if(($mid_p==$mid_sub) or($mid_sub==$parent_id_p) ){?> class="active" <?php }?>><a href="<?php echo $full_url; ?>/<?php echo $murl_sub; ?>"><i class="fa <?php echo $img_nm_sub; ?>"></i> <?php echo $mbody_sub; ?></a></li>
            <?php
			 }
			 ?>
          </ul>
          <?php
		  }
		  ?>
        </li>
   <?php
  }
  ?>      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
      <?php 
	   if($current_page!='index.php')
	   {
		    ?>
             <h1>
                 <i class="fa <?php echo $img_nm_p; ?>"></i> <?php echo $mbody_p; ?>
            </h1>
            <?php
			$sql_1="select parent_id,mbody,img_nm,murl from menu_master ";
			$sql_1.="where mid=:parent_id_p ";
			$sth_1 = $conn->prepare($sql_1);
			$sth_1->bindParam(':parent_id_p', $parent_id_p);
			$sth_1->execute();
			$ss_1=$sth_1->setFetchMode(PDO::FETCH_ASSOC);
			$row_1 = $sth_1->fetch();
			$parent_id_1=$row_1['parent_id'];
			$mbody_1=$row_1['mbody'];
			$img_nm_1=$row_1['img_nm'];
			$murl_1=$row_1['murl'];
			
			$sql_2="select parent_id,mbody,img_nm,murl from menu_master ";
			$sql_2.="where mid=:parent_id_1 ";
			$sth_2 = $conn->prepare($sql_2);
			$sth_2->bindParam(':parent_id_1', $parent_id_1);
			$sth_2->execute();
			$ss_2=$sth_2->setFetchMode(PDO::FETCH_ASSOC);
			$row_2 = $sth_2->fetch();
			$parent_id_2=$row_2['parent_id'];
			$mbody_2=$row_2['mbody'];
			$img_nm_2=$row_2['img_nm'];
			$murl_2=$row_2['murl'];
			?>
            <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <?php if(!empty($mbody_2))
			{
				?>
                 <li><a href="<?php echo $murl_2; ?>"><i class="fa <?php echo $img_nm_2; ?>"></i> <?php echo $mbody_2; ?></a></li>
                 <?php
			}
			if(!empty($mbody_1))
			{
			?>
            <li><a href="<?php echo $murl_1; ?>"><i class="fa <?php echo $img_nm_1; ?>"></i> <?php echo $mbody_1; ?></a></li>
            <?php
			}
			?>
            <li class="active"><?php echo $mbody_p; ?></li>
          </ol>
	        <?php
	   }
	   else
	   {
		   ?>
             <h1>
                 <i class="fa fa-dashboard"></i> Dashboard
            </h1>
	        <?php
	   }
	   ?>
    </section>
    <section class="content">