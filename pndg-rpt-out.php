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
$conn=OpenDB();

$Session = new Session('Script');
$ses_uid = $Session->Get('uid');
$ses_dist_id = $Session->Get('dist_id');
$ses_block_id = $Session->Get('block_id');
$ses_comp_id = $Session->Get('comp_id');
$ses_user_nm = $Session->Get('user_nm');
$ses_user_id = $Session->Get('user_id');
$ses_user_type = $Session->Get('user_type');
$ses_user_addr = $Session->Get('user_addr');
$ses_user_cont_no = $Session->Get('user_cont_no');
$ses_user_status = $Session->Get('status');
$ses_photo_path = $Session->Get('user_photo');
$ses_mail_id= $Session->Get('mail_id');
$ses_page_per= $Session->Get('page_assign');
$ses_id= $Session->Get('id');
$full_url= $Session->Get('full_url');
$ses_ps_id = $Session->Get('ps_id');
$ses_sub_div_id = $Session->Get('sub_div_id');
$ses_comp_type_id = $Session->Get('comp_type_id');

$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
$reservation = isset($_POST['reservation']) ? $_POST['reservation'] : '';
$csrftoken = isset($_POST['csrftoken']) ? $_POST['csrftoken'] : '';
if(!empty($reservation))
{
$date_period=explode("-",$reservation);

	$from_date1 =$date_period[0];
	$to_date1 = $date_period[1];
	
	
	$from_date1 =test_input($from_date1);
	$to_date1 =test_input($to_date1);
	
	if(!empty($from_date1))
	{
	$from_date=british_to_ansi($from_date1);
	}
	if(!empty($to_date1))
	{
	$to_date=british_to_ansi($to_date1);
	}
}
$sqlTK="SELECT count(id) as tk from user_log_mas where token=:csrftoken and id=:ses_id ";
//echo "$sqlTK $csrftoken";
$sthTK = $conn->prepare($sqlTK);
$sthTK->bindParam(':csrftoken', $csrftoken);
$sthTK->bindParam(':ses_id', $ses_id);
$sthTK->execute();
$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
$rowTK = $sthTK->fetch();
$tk=$rowTK['tk'];
//echo $tk;
//$tk=1;
if($tk>0)
{

    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pending Complaints</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self';img-src * 'self' data: http:; connect-src 'self' 'unsafe-inline' 'unsafe-eval' *; child-src 'self' 'unsafe-inline' 'unsafe-eval' *; script-src 'self' 'unsafe-inline' 'unsafe-eval' *  ; style-src  'self' 'unsafe-inline' 'unsafe-eval' * data: http:">
      <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" href="<?php echo $full_url; ?>/bower_components/Ionicons/css/ionicons.min.css">
      <link rel="stylesheet" href="<?php echo $full_url; ?>/dist/css/AdminLTE.min.css">
      <link rel="stylesheet" href="<?php echo $full_url; ?>/dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="<?php echo $full_url; ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo $full_url; ?>/js/respond.min.js"></script>
    <![endif]-->

    </head>
    <link rel="stylesheet" href="<?php echo $full_url; ?>/css/report-print.css">
    <style>
    table.table td
    {
    	padding:4px !important;
    }
    </style>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">

    <section class="content">

    <?php

    if($submit=="Submit")
    { 
    	?>
    	<div class="row"  id="print">
    	<div class="col-md-12">
    	<input type="submit" name="print" id="print" class="btn btn-info pull-left" value="Print" onclick="window.print();" style="margin-bottom:5px;">
    	</div>
    	</div>
        
        <div class="row">
        <div class="col-md-12">
        <div class="box">
        <div class="box-header with-border">
        </div>
        <div class="box-body" style="overflow:scroll;">
        <?php
    	    $sql="SELECT CURRENT_TIMESTAMP as print_dtl ";
    		$sth = $conn->prepare($sql);
    		$sth->execute();
    		$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
    		$row2 = $sth->fetch();
    		$print_dtl=$row2['print_dtl'];
    		$print_dt=explode(' ',$print_dtl);
    		$print_date=british_to_ansi($print_dt[0]);

    	?>
        <table class="table table-bordered" width="95%">
        <thead>
        <tr>
        <td rowspan="3" align="center"><img src="<?php echo $full_url; ?>/images/logo.png" width="35" hight="45"/></td>
        <td colspan="14" align="center" style=" border-bottom:none !important;">&nbsp;</td>  
        </tr>
        <tr>
        <td colspan="14" align="center"  style=" border-top:none !important;border-bottom:none !important;"><B><?php echo "Pending Complaints <br />Period: $from_date1 - $to_date1";?></B></td>
        </tr>
        <tr>
        <td colspan="14" align="right"  style=" border-top:none !important;"><B>Print Date : <?php echo $print_date; ?> Time : <?php echo $print_dt[1]; ?></B></td>
        </tr>
        </thead> 
        
        <tr>
        <td align="center"><B>Srl</B></td>
        <td align="center" class="one"><B>Complaintner </B></td>
        <td align="center" class="one"><B>Mobile No</B></td>
        <td align="center" class="one"><B>Block Name </B></td>
        <td align="center" class="one"><B>PS Name </B></td>
        <td align="center" ><B>Comp Type</B></td>
        <td align="center" class="one"><B>Comp Desc</B></td>
        <td align="center" class="one"><B>Comp T0</B></td>
        <td align="center"><b>Dkt No</b></td>
        <td align="center"><B>Dkt Date</B></td>
        <td align="center" class="one"><B>Trf To</B></td>
        <td align="center" class="one"><B>Trf Date</B></td>
        <td align="center" class="one"><B>Remarks</B></td>

        </tr>
        
        <?php
        $sql1="select f.rmn,f.dkt_no,f.dkt_date,f.comp_desc,c.citizen_nm,b.block_nm,p.ps_nm,";
        $sql1.=" cm.comp_type_eng,dm.de_eng,f.refer_to,f.refer_date,f.refer_rmk  ";
        $sql1.=" from flt_mas f, citizen_mas c, block_mas b, ps_mas p, compl_type_mas cm, dept_mas dm ";
        $sql1.=" where 1=1 ";
        $sql1.=" and f.comp_type_id=cm.comp_type_id ";
        $sql1.=" and c.rmn=f.rmn ";
        $sql1.=" and f.block_id=b.block_id ";
        $sql1.=" and f.ps_id=p.ps_id and f.dept_id=dm.dept_id ";
        $sql1.=" and substr(f.dkt_date,1,10)>=:from_date ";
        $sql1.=" and substr(f.dkt_date,1,10)<=:to_date ";
		if(!empty($ses_sub_div_id))
		{
			$sql1.=" and f.sub_div_id=:ses_sub_div_id ";
		}
		if(!empty($ses_block_id))
		{
			$sql1.=" and f.block_id=:ses_block_id ";
		}
		if(!empty($ses_ps_id))
		{
			$sql1.=" and f.ps_id=:ses_ps_id ";
		}
		if($ses_user_type=="D")
		{
			$sql1.=" and f.comp_type_id in($ses_comp_type_id) ";
		//	$sql1.=" and f.refer_to is NULL ";
		}
		if($ses_user_type=="B")
		{
			$sql1.=" and f.refer_to=:ses_uid ";	
		}
		$sql1.=" and f.dist_id=:ses_dist_id ";
        $sql1.=" ORDER BY f.dkt_no DESC, f.dkt_date ";
        $sth = $conn->prepare($sql1);
        $sth->bindParam(':from_date', $from_date);
        $sth->bindParam(':to_date', $to_date);
		$sth->bindParam(':ses_dist_id', $ses_dist_id);
		if(!empty($ses_sub_div_id))
		{
			$sth->bindParam(':ses_sub_div_id', $ses_sub_div_id);
		}
		if(!empty($ses_block_id))
		{
			$sth->bindParam(':ses_block_id', $ses_block_id);
		}
		if(!empty($ses_ps_id))
		{
			$sth->bindParam(':ses_ps_id', $ses_ps_id);
		}
		if($ses_user_type=="B")
		{
			$sth->bindParam(':ses_uid', $ses_uid);
		}
		if($ses_user_type=="D")
		{
		//	$sth->bindParam(':ses_comp_type_id', $ses_comp_type_id);
		}
        $sth->execute();
        $ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
        $row1 = $sth->fetchAll();
        $srl=0;
        foreach ($row1 as $key => $value1) 
        {
            $srl++;
            $rmn=$value1['rmn'];
            $dkt_no=$value1['dkt_no'];
            $dkt_date1=$value1['dkt_date'];
            $comp_desc=$value1['comp_desc'];
            $citizen_nm=$value1['citizen_nm'];
            $block_nm_ben=$value1['block_nm'];
            $ps_nm_ben=$value1['ps_nm'];
            $comp_type=$value1['comp_type_eng'];
            $dept_nm=$value1['de_eng'];
            $refer_to=$value1['refer_to'];
            $refer_date=$value1['refer_date'];
            $refer_rmk=$value1['refer_rmk'];
            
            $dkt_date=british_to_ansi(substr($dkt_date1,0,10));
            $dkt_time=substr($dkt_date1,11,5);

            $ref_date=british_to_ansi(substr($refer_date,0,10));
            $ref_time=substr($refer_date,11,5);
			
			$ref_dt=" ";
			if(!empty($refer_to))
			{
				$ref_dt=$ref_date.' '.$ref_time;
			}
						
			$sqlR="select user_nm from user_mas WHERE 1=1 ";
			$sqlR.=" and uid=:refer_to ";
			$sth_search = $conn->prepare($sqlR);
			$sth_search->bindParam(':refer_to', $refer_to);
			$sth_search->execute();
			$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
			$row_search = $sth_search->fetch();
			$ref_to=$row_search['user_nm'];

            ?>
            <tr> 
            <td align="right"><?php echo $srl;?></td>
            <td class="one"><?php echo $citizen_nm;?></td>
            <td class="one"><?php echo $rmn;?></td>
            <td class="one"><?php echo $block_nm_ben;?></td>
            <td class="one"><?php echo $ps_nm_ben;?></td>
            <td><?php echo $comp_type;?></td>
            <td class="one"><?php echo $comp_desc;?></td>
            <td class="one"><?php echo $dept_nm;?></td>
            <td><?php echo $dkt_no;?></td>
            <td nowrap align="center"><?php echo "$dkt_date<br/>$dkt_time";?></td>
            <td class="one"><?php echo $ref_to;?></td>
            <td align="center" class="one"><?php echo "$ref_dt";?></td>
            <td align="center" class="one"><?php echo "$refer_rmk";?></td>
            </tr>
            <?php
			
			$sqlC="select count(*) as cnT from refer_mas WHERE rmn=:rmn and dkt_no=:dkt_no ";
			$sth_search = $conn->prepare($sqlC);
			$sth_search->bindParam(':rmn', $rmn);
			$sth_search->bindParam(':dkt_no', $dkt_no);
			$sth_search->execute();
			$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
			$row_search = $sth_search->fetch();
			$cnT=$row_search['cnT'];
			if($cnT>1)
			{
				$sqlR="select r.refer_date,r.remarks,u.user_nm from refer_mas r, user_mas u ";
				$sqlR.=" WHERE r.refer_to=u.uid and r.rmn=:rmn and r.dkt_no=:dkt_no ";
				$sth_search = $conn->prepare($sqlR);
				$sth_search->bindParam(':rmn', $rmn);
				$sth_search->bindParam(':dkt_no', $dkt_no);
				$sth_search->execute();
				$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
				$row_search = $sth_search->fetchAll();
				foreach ($row_search as $key => $value1) 
				{
					$srl++;
					$refer_date=$value1['refer_date'];
					$remarks=$value1['remarks'];
					$user_nm=$value1['user_nm'];
					if($refer_rmk!=$remarks)
					{
						?>
						<tr>
						<td colspan="10">&nbsp;</td>
						<td class="one"><?php echo $user_nm;?></td>
						<td align="center" class="one"><?php echo "$refer_date";?></td>
						<td align="left" class="one"><?php echo "$remarks";?></td>
						</tr>
						<?php
					}
				}
			}
        }
    	?>
        </table>
        </div>
        </div>
        </div>
        </div>
        <?php
    }
    ?>
<style>
@media only screen and (max-width: 800px) {
  .one
  {     
	/* display: none;*/
  }
  
  
}
</style>    

    </body>
    </html>
    <?php
}
$conn=null;
?>