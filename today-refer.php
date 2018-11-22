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
$ses_comp_type_id = $Session->Get('comp_type_id');

$ses_token= $Session->Get('token');

$ses_ps_id = $Session->Get('ps_id');
$ses_sub_div_id = $Session->Get('sub_div_id');

$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
$csrftoken = $ses_token;

$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
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
    <title> Complaints Transfer</title>
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
	$submit="Submit";
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
        <div class="box-body">
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
        <td rowspan="3" align="center"><img src="<?php echo $full_url; ?>/images/logo.png" /></td>
        <td colspan="13" align="center" style=" border-bottom:none !important;">&nbsp;</td>  
        </tr>
        <tr>
        <td colspan="6" align="left"  style=" border-top:none !important;border-bottom:none !important;"><B><?php echo "Todays Complaints Refer";?></B></td>

        <td colspan="6" align="right"  style=" border-top:none !important;"><B>Print Date : <?php echo $print_date; ?> Time : <?php echo $print_dt[1]; ?></B></td>
        </tr>
        </thead> 
        
        <tr>
        <td align="center"><B>Srl</B></td>
        <td align="center"><B>Complaintner </B></td>
        <td align="center"><B>Address </B></td>
        <td align="center"><B>Mobile No</B></td>
        <td align="center"><B>Block Name </B></td>
        <td align="center"><B>PS Name </B></td>
        <td align="center"><B>Comp Type</B></td>
        <td align="center"><B>Comp Desc</B></td>
        <td align="center"><B>Comp T0</B></td>
        <td align="center"><B>Dkt No</B></td>
        <td align="center"><B>Dkt Date</B></td>
        <td align="center"><B>Refer To</B></td>
        <td align="center"><B>Refer Date</B></td>

        </tr>
        
        <?php
        $sql1="select f.rmn,f.dkt_no,f.dkt_date,f.comp_desc,c.citizen_nm,b.block_nm,p.ps_nm,";
        $sql1.=" cm.comp_type_eng,dm.de_eng,f.refer_to,f.refer_date,f.addr,f.street,f.para,f.village,f.landmark  ";
        $sql1.=" from flt_mas f, citizen_mas c, block_mas b, ps_mas p, compl_type_mas cm, dept_mas dm ";
        $sql1.=" where 1=1 ";
        $sql1.=" and f.comp_type_id=cm.comp_type_id ";
        $sql1.=" and c.rmn=f.rmn ";
        $sql1.=" and f.block_id=b.block_id ";
        $sql1.=" and f.ps_id=p.ps_id and f.dept_id=dm.dept_id ";
        $sql1.=" and substr(f.refer_date,1,10)=CURRENT_DATE ";
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
        $sql1.=" ORDER BY f.dkt_date,f.dkt_no DESC ";
        $sth = $conn->prepare($sql1);
//        $sth->bindParam(':from_date', $from_date);
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
            $addr=$value1['addr'];
            $street=$value1['street'];
            $para=$value1['para'];
            $village=$value1['village'];
            $landmark=$value1['landmark'];
			
			$street1="";
			$para1="";
			$village1="";
			$landmark1="";
			$address="";
			
			if(!empty($street))
			$street1="Street:$street ";

			if(!empty($village))
			$village1="Village:$village ";

			if(!empty($landmark))
			$landmark1="Landmark:$landmark ";
			
			$address="$addr &nbsp;$street1<br>$village1&nbsp;$landmark1 ";
            
            $dkt_date=british_to_ansi(substr($dkt_date1,0,10));
            $dkt_time=substr($dkt_date1,11,5);

            $ref_date=british_to_ansi(substr($refer_date,0,10));
            $ref_time=substr($refer_date,11,5);
			
			$ref_dt=" ";
			if(!empty($refer_to))
			{
				$ref_dt=$ref_date.'<br>'.$ref_time;
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
            <td><?php echo $citizen_nm;?></td>
            <td><?php echo "$address";?></td>
            <td><?php echo $rmn;?></td>
            <td><?php echo $block_nm_ben;?></td>
            <td><?php echo $ps_nm_ben;?></td>
            <td><?php echo $comp_type;?></td>
            <td><?php echo $comp_desc;?></td>
            <td><?php echo $dept_nm;?></td>
            <td><?php echo $dkt_no;?></td>
            <td nowrap align="center"><?php echo "$dkt_date<br/>$dkt_time";?></td>
            <td><?php echo $ref_to;?></td>
            <td nowrap align="center"><?php echo "$ref_dt";?></td>
            </tr>
            <?php
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
    </body>
    </html>
    <?php
}
$conn=null;
?>