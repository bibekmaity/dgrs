<?php
header("X-XSS-Protection: 1;mode = block");
header("X-Content-Type-Options: nosniff");
include("./inc/operator_class.php");
include("./inc/dblib.inc.php");
$Session = new Session('Script');
$conn=OpenDB();
$ses_orgn_abbr= $Session->Get('orgn_abbr');
$ses_orgn_nm= $Session->Get('orgn_nm');
$ses_orgn_code= $Session->Get('orgn_code');
$ses_orgn_addr1= $Session->Get('orgn_addr1');
$ses_orgn_addr2= $Session->Get('orgn_addr2');

$phase = isset($_REQUEST['phase']) ? $_REQUEST['phase'] : '';
?> 

<body>
<style type="text/css">
table tr td,table tr th
{
	font-size: 12px !important;
}
</style>

<div class="wrapper">
<center>
<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="./plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="./plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="./plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="./plugins/select2/select2.min.css">
<section class="content">
<div class="row">
<div class="box">
<div class="box-body">

<table width="95%"  class="table table-bordered table-striped">
<thead>
<tr>
<td colspan="15"  align="center"><B><?php echo "$ses_orgn_nm <br> $ses_orgn_addr1 $ses_orgn_addr2";?> </B></td>
</tr>

<tr>
<td colspan="15" align="center">Beneficiary Information &nbsp;&nbsp;&nbsp; (<b>Phase:<?php echo $phase;?></b>).
</td>
</tr>
<tr>
<td align="center"><B>#</B></td>
<td align="center"><B>Ward No</B></td>
<td align="center"><B>Survey Code</B></td>
<td align="center"><B>Profile ID <br> Beneficiary Id</B></td>
<td align="center"><B>Beneficiary Name & Address</B></td>
<td align="center"><B>Status</B></td>
<td align="center"><B>Sex</B></td>
<td align="center"><B>Age</B></td>
<td align="center"><B>Received</B></td>
<td align="center"><B>Paid </B></td>
</tr>
</thead>
<?php
$sl=0;
$sql="select ward_no,survey_cd,ben_cd,name,per_addr,cont_no,sex,age,ben_cd,profile_id,status_id  ";
$sql.="from cust_mas WHERE 1=1 and sanction='Y' and year_id=:ses_session_id  ";
$sql.=" and md5(phase_no)=:phase";
$sth = $conn->prepare($sql);
$sth->bindParam(':phase', $phase);
$sth->bindParam(':ses_session_id', $ses_session_id);

$sth->execute();
$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetchAll();
foreach ($row as $key => $value1) 
{
	$sl++;
	$ward_no=$value1['ward_no'];
	$profile_id=$value1['profile_id'];
	$survey_cd=$value1['survey_cd'];
	$ben_cd=$value1['ben_cd'];
	$name=$value1['name'];
	$per_addr=$value1['per_addr'];
	$cont_no=$value1['cont_no'];
	$sex=$value1['sex'];
	$age=$value1['age'];
	$status_id=$value1['status_id'];
	$ben_cd=$value1['ben_cd'];
	
	if($sex=="M")
	{
		$sex="Male";
	}
	if($sex=="F")
	{
		$sex="Female";
	}
	
	$sqlC="select status_desc from proc_status_mas WHERE status_id=:status_id ";
	$sqlC.="and status_id=:status_id ";//and phase_no=:phase  ";
	$sth = $conn->prepare($sqlC);
	$sth->bindParam(':status_id', $status_id);
	$sth->execute();
	$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
	$row = $sth->fetchAll();
	foreach ($row as $key => $value) 
	{
		$status_desc=$value['status_desc'];
	}

	$sqlC="select sum(amount) as Ramt from trans_mas WHERE tr_code='R' ";
	$sqlC.="and ben_cd=:ben_cd ";//and phase_no=:phase  ";
	$sth = $conn->prepare($sqlC);
	$sth->bindParam(':ben_cd', $ben_cd);
	$sth->execute();
	$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
	$row = $sth->fetchAll();
	foreach ($row as $key => $value) 
	{
		$Recv_amt=$value['Ramt'];
	}

	$sqlC="select sum(amount) as Pamt from trans_mas WHERE tr_code='S' ";
	$sqlC.="and ben_cd=:ben_cd "; //and phase_no=:phase  ";
	$sth = $conn->prepare($sqlC);
	$sth->bindParam(':ben_cd', $ben_cd);
	$sth->execute();
	$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
	$row = $sth->fetchAll();
	foreach ($row as $key => $value) 
	{
		$Paid_amt=$value['Pamt'];
	}
	if(empty($Paid_amt))
	$Paid_amt=0;
	if(empty($Recv_amt))
	$Recv_amt=0;

	?>
    <tr>
    <td align="right"><?php echo $sl;?></td>
    <td align="center"><?php echo $ward_no;?></td>
    <td align="center"><?php echo $survey_cd;?></td>
    <td align="center"><?php echo "$profile_id <br>$ben_cd ";?></td>
    <td><?php echo "$name<br>$per_addr";?></td>
    <td align="left"><?php echo $status_desc;?></td>
    <td><?php echo $sex;?></td>
    <td align="right"><?php echo $age;?></td>
    <td align="right"><?php echo $Recv_amt;?></td>
    <td align="right"><?php echo $Paid_amt;?></td>
    </tr>
    <?php
}
?>
</table>
</div>
</div>
</div>
</section>
</center>
</div>
</body>
<script src="./bootstrap/js/bootstrap.min.js"></script>
<?php
$conn=null;
?>