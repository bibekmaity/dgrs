<?php
header("X-XSS-Protection: 1;mode = block");
header("X-Content-Type-Options: nosniff");

include("./inc/operator_class.php");

function curPageName() 
{
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
$current_page=curPageName(); 

include("./inc/dblib.inc.php");
include("./inc/datelib.inc.php");

$Session = new Session('Script');
$ses_citizen_id = $Session->Get('citizen_id');
$ses_hid_lang = $Session->Get('hid_lang');
$ses_rmn = $Session->Get('rmn');
$ses_citizen_nm = $Session->Get('citizen_nm');
$ses_addr = $Session->Get('addr');
$ses_alter_no = $Session->Get('alter_no');
$ses_land_mark = $Session->Get('land_mark');
$ses_ps_id = $Session->Get('ps_id');
$ses_email_id = $Session->Get('email_id');
$ses_photo_path = $Session->Get('photo_path');
$ses_token= $Session->Get('token');

$conn = OpenDB();
/*
$base_dir  = __DIR__; 
$doc_root  = preg_replace("!${_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']); 
$base_url  = preg_replace("!^${doc_root}!", '', $base_dir);
$protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$port      = $_SERVER['SERVER_PORT'];
$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
$domain    = $_SERVER['SERVER_NAME'];
*/
//$full_url  = "${protocol}://${domain}${disp_port}${base_url}";
$full_url=".";

?>




<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Citizen Complaints</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <meta http-equiv="Content-Security-Policy" content="default-src 'self';img-src * 'self' data: http:; connect-src 'self' 'unsafe-inline' 'unsafe-eval' *; child-src 'self' 'unsafe-inline' 'unsafe-eval' *; script-src 'self' 'unsafe-inline' 'unsafe-eval' *  ; style-src  'self' 'unsafe-inline' 'unsafe-eval' * data: http:">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo $full_url; ?>/dist/css/skins/_all-skins.min.css">
 <!-- <script src = "<?php echo $full_url; ?>/js/jquery-1.9.1.js"></script>-->
 <script src="https://code.jquery.com/jquery-1.9.1.js" integrity="sha384-+GtXzQ3eTCAK6MNrGmy3TcOujpxp7MnMAi6nvlvbZlETUcZeCk7TDwvlCw9RiV6R" crossorigin="anonymous"></script>
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
    <a href="<?php echo $full_url; ?>/index1.php" class="logo">
     <img src="<?php echo $full_url; ?>/images/logo.png" alt="Citizen Complaints" title="Citizen Complaints" height="40px" width="25px" align="left" style="margin-top:8px;" />
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>T</b>P</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Citizen </b> &nbsp; Complaints</span>
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
                  <img src="<?php echo $ses_photo_path; ?>" class="user-image" alt="<?php echo $ses_citizen_nm; ?>">
                  <?php
			  }
			  else
			  {
				  ?>
				  <img src="<?php echo $full_url; ?>/images/user.png" class="user-image" alt="<?php echo $ses_citizen_nm; ?>">
                 <?php
			  }
			  ?>
              <span class="hidden-xs"><?php echo $ses_citizen_nm; ?></span>
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
				  <img src="<?php echo $full_url; ?>/images/user.png" class="img-circle" alt="<?php echo $ses_citizen_nm; ?>"  height="160px">
                 <?php
			  }
			  ?>
              <p><?php echo $ses_citizen_nm; ?></p>
              </li>
              <li class="user-footer">
                
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
        
        <?php 
		if($ses_hid_lang=='B')
		{
			?>
            
             <li>
              <a href="<?php echo $full_url; ?>/index1.php">
                <i class="fa fa-comments"></i> <span>অভিযোগ নিবন্ধন করুন</span>
              </a>
            </li>
            <li>
              <a href="<?php echo $full_url; ?>/Complaint-history.php">
                <i class="fa fa-history"></i> <span>অভিযোগের ইতিহাস দেখুন</span>
              </a>
            </li>
            <?php
		}
		else
		{
			?>
            
            <li>
              <a href="<?php echo $full_url; ?>/index1.php">
                <i class="fa fa-comments"></i> <span>Lodge Complaint</span>
              </a>
            </li>
            <li>
              <a href="<?php echo $full_url; ?>/Complaint-history.php">
                <i class="fa fa-history"></i> <span>View Complaint History</span>
              </a>
            </li>
            <?php
		}
		?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	<?php 
    if($ses_hid_lang=='B')
    {
        ?>
         <h1>
            <?php
			if($current_page=='index1.php')
			{
				?>
                 <i class="fa fa-comments"></i> অভিযোগ নিবন্ধন করুন
                 <?php
			}
			?>
            <?php
			if($current_page=='Complaint-history.php')
			{
				?>
                 <i class="fa fa-history"></i> অভিযোগের ইতিহাস দেখুন
                 <?php
			}
			?>
        </h1>
        <?php
    }
	else
	{
        ?>
         <h1>
            <?php
			if($current_page=='index1.php')
			{
				?>
                 <i class="fa fa-comments"></i> Lodge Complaint
                 <?php
			}
			?>
             <?php
			if($current_page=='Complaint-history.php')
			{
				?>
                 <i class="fa fa-history"></i>View Complaint History
                 <?php
			}
			?>
        </h1>
        <?php
    }
    ?>
         
	       
             
	       
    </section>
    <section class="content">