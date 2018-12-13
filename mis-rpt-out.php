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
$month = isset($_POST['month']) ? $_POST['month'] : '';
$csrftoken = isset($_POST['csrftoken']) ? $_POST['csrftoken'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';
$memo_no = isset($_POST['memo_no']) ? $_POST['memo_no'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
if(!empty($date))
$memo_date=british_to_ansi($date);
if($month==1)
{
	$pmonth=12;
	$year=$year-1;
}
else
{
	$pmonth=$month-1;
}
$check_date=$year.'-'.$month;
$prv_date=$year.'-'.$pmonth;

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
    <title>MIS Report</title>
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
		/*border:none !important;*/
    }
	 table.borderless td,table.borderless th{
     border: none !important;
	}
    </style>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<style type="text/css">
body
{
}
@media print
{
	#page
	{
		page-break-after:always !important;
        width:210mm !important;
		zoom:100% !important;
	}
	#change
	{
		margin-top:-50px !important;
	}
}
</style>
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
        
        <div class="row" id="page">
        <div class="col-md-12">
        
        <?php
    	    $sql="SELECT mon_desc from month_mas WHERE mon_cd=:month ";
    		$sth = $conn->prepare($sql);
    	    $sth->bindParam(':month', $month);
    		$sth->execute();
    		$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
    		$row2 = $sth->fetch();
    		$mon_desc=$row2['mon_desc'];

    	    $sql="SELECT mon_desc from month_mas WHERE mon_cd=:pmonth ";
    		$sth = $conn->prepare($sql);
    	    $sth->bindParam(':pmonth', $pmonth);
    		$sth->execute();
    		$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
    		$row2 = $sth->fetch();
    		$Pmon_desc=$row2['mon_desc'];

    	    $sql="SELECT CURRENT_TIMESTAMP as print_dtl ";
    		$sth = $conn->prepare($sql);
	   		$sth->execute();
    		$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
    		$row2 = $sth->fetch();
    		$print_dtl=$row2['print_dtl'];
    		$print_dt=explode(' ',$print_dtl);
    		$print_date=british_to_ansi($print_dt[0]);
    	?>
        <table class="table borderless" width="100%" id="change">
        	<tr>
        		<td align="center"  colspan="4">
                	<img src="<?php echo $full_url; ?>/images/logo.png" width="65" hight="75" />
                </td>
        	</tr>
       		<tr>
        		<td align="center" colspan="4" style="font-weight:bold !important; font-family:"Arial Black", Gadget, sans-serif !important;">
                     
        			<h3>Government of West Bengal <br>Office of the District Magistrate
        			<br>(Public Grievance Cell)<br>Collectorate, Paschim Medinipur.
                    <br>(E-mail- pgcellpasmid@gmail.com)</h3>
                    
        		</td>
        	</tr>
        	<tr style="font-weight:bold;"> 
        		<td align="left" colspan="3">Memo No: <?php echo $memo_no;?></td>
        		<td align="right">Dated: <?php echo $date;?></td>        
        	</tr>
			<tr>
        		<td align="left" colspan="4">
        		<br><br>
        		From: <br><b>The District Magistrate,</b><br>Paschim Medinipur.<br><br>
        
        		To: <br><b>The Divisional Commissioner,</b><br>Midnapur Division,<br>Office at Keranitola,<br>
				Paschim Medinipur. <br>             
        
        		<u>Subject: Monthly Progress report on Public Grievance and redressal for the  month of <?php echo "$mon_desc, $year";?></u><br><br><br>
        
				Sir,<br>
       			The statements showing the latest position of Public Grievances and redressal for the month of <?php echo "$Pmon_desc, $year";?> in the prescribed format is submitted herewith for your kind perusal and necessary action.
        		<br/><br/><br><br>
                <tr>
                	<td align="right" colspan="4"> 
						Yours faithfully,                        
                    	<br>
                    </td>
                </tr>
                <tr>
                	<td align="left" colspan="4"> 
						Enclo…..As stated
        			</td>
                </tr>
        		<tr>
                	<td align="right" colspan="4"> 
                        <br/>
                        <b>District Magistrate<br>
                        Paschim Medinipur</b>
                        <br>
                        <br/>
                    </td>
                </tr>        		
                </td>
                </tr>
                
                <tr  style="font-weight:bold;"> 
                    <td align="left">Memo No:</td>
                    <td><?php echo $memo_no;?></td>
                    <td align="right">Dated:</td>
                    <td><?php echo $date;?></td>        
                </tr>
                <tr>
                	<td align="left" colspan="4"> 
                   <br>
        		Copy forwarded for information and taking necessary action to -<br>
       			 1)	The Joint Secretary, Government of West Bengal, (Home Department)) R.T.I & RTC  Branch, NABANNA, 325-Sarat Chatterjee Road, Howrah-711102.
        		<br><br>
                </td>
                </tr>
                <tr>
                	<td align="right" colspan="4"> 
                    <b>District Magistrate&nbsp;&nbsp;<br>
                    Paschim Medinipur</b>
     
                    <!--<br><br><br><br>
                    <br><br><br><br>-->            
       			 </td>
             </tr> 
        </table>
       </div>
      </div>
      <div class="row" id="page">
        <div class="col-md-12">
       
		<?php
		$sqlP="select count(*) as Pre_pndg from flt_mas WHERE 1=1 and ";
		$sqlP.=" substr(dkt_date,1,7)<:check_date ";
		$sthTK = $conn->prepare($sqlP);
		$sthTK->bindParam(':check_date', $check_date);
		$sthTK->execute();
		$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
		$rowTK = $sthTK->fetch();
		$Pre_pndg=$rowTK['Pre_pndg'];
		if(empty($Pre_pndg))
		$Pre_pndg=0;
		
		$sqlP1="select count(*) as Pre_pndg1 from flt_his WHERE 1=1 and ";
		$sqlP1.=" substr(dkt_date,1,7)=:prv_date and substr(close_date,1,7)=:check_date ";
		$sthTK1 = $conn->prepare($sqlP1);
		$sthTK1->bindParam(':check_date', $check_date);
		$sthTK1->bindParam(':prv_date', $prv_date);
		$sthTK1->execute();
		$ssTK1=$sthTK1->setFetchMode(PDO::FETCH_ASSOC);
		$rowTK1 = $sthTK1->fetch();
		$Pre_pndg1=$rowTK1['Pre_pndg1'];
		if(empty($Pre_pndg1))
		$Pre_pndg1=0;
		$Pre_pndg=$Pre_pndg+$Pre_pndg1;
		
		$sqlP="select count(*) as comp_recv from flt_mas WHERE 1=1 and ";
		$sqlP.=" substr(dkt_date,1,7)=:check_date ";
		$sthTK = $conn->prepare($sqlP);
		$sthTK->bindParam(':check_date', $check_date);
		$sthTK->execute();
		$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
		$rowTK = $sthTK->fetch();
		$comp_recv=$rowTK['comp_recv'];
		if(empty($comp_recv))
		$comp_recv=0;
		$tot=0;
		$tot=$Pre_pndg+$comp_recv;
		
		$sqlP="select count(*) as comp_clr from flt_his WHERE 1=1 and ";
		$sqlP.=" substr(close_date,1,7)=:check_date ";
		$sthTK = $conn->prepare($sqlP);
		$sthTK->bindParam(':check_date', $check_date);
		$sthTK->execute();
		$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
		$rowTK = $sthTK->fetch();
		$comp_clr=$rowTK['comp_clr'];
		if(empty($comp_clr))
		$comp_clr=0;
		
		$sqlP="select count(*) as tot_pndg from flt_mas WHERE 1=1 and ";
		$sqlP.=" substr(dkt_date,1,7)<=:check_date ";
		$sthTK = $conn->prepare($sqlP);
		$sthTK->bindParam(':check_date', $check_date);
		$sthTK->execute();
		$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
		$rowTK = $sthTK->fetch();
		$tot_pndg=$rowTK['tot_pndg'];
		if(empty($tot_pndg))
		$tot_pndg=0;
		
		$sqlP="select count(*) as pndg_8wks from flt_mas where datediff(current_date,dkt_date)>56 ";
		$sthTK = $conn->prepare($sqlP);
		$sthTK->execute();
		$ssTK=$sthTK->setFetchMode(PDO::FETCH_ASSOC);
		$rowTK = $sthTK->fetch();
		$pndg_8wks=$rowTK['pndg_8wks'];
		if(empty($pndg_8wks))
		$pndg_8wks=0;		
		
		?>
        <table class="table  borderless" width="100%">
        		<tr>
                  <td colspan="3">&nbsp;
                    <br><br><br><br>
                    <br><br><br><br>
                    <br><br><br><br>
                    <br><br><br><br>
        		</td>
       		</tr>
        	<tr>
        		<td align="center" colspan="3"><b>Monthly Progress Report on Public Grievance and  Redressal for the Month of <?php echo "$mon_desc, $year";?> </b><br>
        		</td>        
        	</tr>
        </table>
        <table class="table table-bordered table-striped" width="100%" style="border:1px !important;">
        	<tr>
        		<td><b>Srl</b></td>
        		<td align="center"><b>Name of the Department / Government Office</b></td>
       			<td align="center"><b>District Magistrate, Paschim Medinipur</b></td>
        	</tr>                                                                                                    					            <tr>
        		<td>a)</td>
        		<td align="left">No. of petition pending at the end of previous month <?php echo "$Pmon_desc, $year";?></td>
        		<td align="right"><?php echo $Pre_pndg;?></td>
        	</tr> 
        	<tr>
        		<td>b)</td>
        		<td align="left">No. of petition received during the month <?php echo "$mon_desc, $year";?></td>
        		<td align="right"><?php echo $comp_recv;?></td>
        	</tr> 
        	<tr>
        		<td>c)</td>
        		<td align="left">Total (a+b) </td>
        		<td align="right"><?php echo $tot;?></td>
        	</tr>
        	<tr>
        		<td>d)</td>
        		<td align="left">No. of petition disposed during <?php echo "$mon_desc, $year";?></td>
        		<td align="right"><?php echo $comp_clr;?></td>
        	</tr>
        	<tr>
        		<td>e)</td>
        		<td align="left">No. of petition pending for disposal in the month of <?php echo "$mon_desc, $year";?></td>
        		<td align="right"><?php echo $tot_pndg;?></td>
       		</tr>
        	<tr>
        		<td>f)</td>
        		<td align="left">No. of petition pending for more than 8 weeks and reasons thereof</td>
        		<td align="right"><?php echo $pndg_8wks;?></td>
            </tr>
         </table>
         <table class="table  borderless" width="100%">
            <tr>
                <td align="right" colspan="4"> 
                    <br><br><br>
                    <br><br><br>
                    <b>District Magistrate<br>
                    Paschim Medinipur</b>
                </td>
            </tr>
        </table>
        </div>
        </div>
        </div>
        </div>
        <?php
        $sqlM="SELECT count(mis_id) as mid from mis_mas where memo_no=:memo_no ";
        //echo "$sqlTK $csrftoken";
        $sthM = $conn->prepare($sqlM);
        $sthM->bindParam(':memo_no', $memo_no);
        $sthM->execute();
       // $ssM=$sthM>setFetchMode(PDO::FETCH_ASSOC);
        $rowM = $sthM->fetch();
        $mid=$rowM['mid'];
        if($mid>0)
        {
            $sqlU=" update mis_mas set memo_dt=:memo_date,proc_dt=:check_date ";
            $sqlU.=" ,lm_pndg=:Pre_pndg,cm_comp=:comp_recv,total=:tot,tot_disp=:comp_clr ";
            $sqlU.=",tot_pndg=:tot_pndg,pndg_8wk=:pndg_8wks";
            $sqlU.=" where memo_no=:memo_no ";
            $sthU = $conn->prepare($sqlU);
            $sthU->bindParam(':memo_no', $memo_no);
            $sthU->bindParam(':memo_date', $memo_date);
            $sthU->bindParam(':check_date', $check_date);
            $sthU->bindParam(':Pre_pndg', $Pre_pndg);
            $sthU->bindParam(':comp_recv', $comp_recv);
            $sthU->bindParam(':tot', $tot);
            $sthU->bindParam(':comp_clr', $comp_clr);
            $sthU->bindParam(':tot_pndg', $tot_pndg);
            $sthU->bindParam(':pndg_8wks', $pndg_8wks);
            $sthU->execute();
        }
        else
        {
            $sqlU=" insert into mis_mas (memo_dt,proc_dt,lm_pndg,cm_comp,total,tot_disp,tot_pndg,pndg_8wk,memo_no) ";
            $sqlU.=" values (:memo_date,:check_date,:Pre_pndg,:comp_recv,:tot,:comp_clr ";
            $sqlU.=",:tot_pndg,:pndg_8wks,:memo_no) ";
            $sthU = $conn->prepare($sqlU);
            $sthU->bindParam(':memo_no', $memo_no);
            $sthU->bindParam(':memo_date', $memo_date);
            $sthU->bindParam(':check_date', $check_date);
            $sthU->bindParam(':Pre_pndg', $Pre_pndg);
            $sthU->bindParam(':comp_recv', $comp_recv);
            $sthU->bindParam(':tot', $tot);
            $sthU->bindParam(':comp_clr', $comp_clr);
            $sthU->bindParam(':tot_pndg', $tot_pndg);
            $sthU->bindParam(':pndg_8wks', $pndg_8wks);
            $sthU->execute();

        }

    }
    ?>
    
    </body>
    </html>
    <?php
}
$conn=null;
?>