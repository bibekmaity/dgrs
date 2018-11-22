<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
include("../inc/dblib.inc.php");
$conn = OpenDB();
$data = json_decode( file_get_contents( 'php://input' ), true );

$tag = isset($data['tag']) ? $data['tag'] : '';
$mobile_no = isset($data['mobile_no']) ? $data['mobile_no'] : '';
$token = isset($data['token']) ? $data['token'] : '';

?>


<?php
if($tag=="SHOW-COMPLAINT")
{
	
	//----------------show police stations  ---------------
	
	//--------------- check if token is valid --------------
	
	$stmt2 ="SELECT count(*) as ct ";
	$stmt2.="FROM citizen_mas ";
	$stmt2.="where token=:token  ";
	//echo $sth2;
	$sth2 = $conn->prepare($stmt2);
	$sth2->bindParam(':token', $token);
	$sth2->execute();
	$sth2->setFetchMode(PDO::FETCH_ASSOC);
	$row2 = $sth2->fetch();
	$ct=$row2['ct'];
	if($ct>0)
	{
	
		$stmt ="select fm.dkt_no as ref_no";
		$stmt.=",DATE_FORMAT(fm.dkt_date, '%d/%m/%Y %H:%i') as ref_date";
		$stmt.=",DATE_FORMAT(fm.close_date, '%d/%m/%Y %H:%i') as close_date";
		$stmt.=",cm.comp_type_eng as comp_type,fm.status ";
		$stmt.="from flt_mas fm,compl_type_mas cm ";
		$stmt.="where 1=1 ";
		$stmt.="and fm.comp_type_id=cm.comp_type_id ";
		$stmt.="and fm.rmn=:mobile_no ";
		$stmt.="union ";
		$stmt.="select fm.dkt_no as ref_no";
		$stmt.=",DATE_FORMAT(fm.dkt_date, '%d/%m/%Y %H:%i') as ref_date";
		$stmt.=",DATE_FORMAT(fm.close_date, '%d/%m/%Y %H:%i') as close_date";
		$stmt.=",cm.comp_type_eng as comp_type,'C' as status ";
		$stmt.="from flt_his fm,compl_type_mas cm ";
		$stmt.="where 1=1 ";
		$stmt.="and fm.comp_type_id=cm.comp_type_id ";
		$stmt.="and fm.rmn=:mobile_no ";
		$stmt.="order by ref_date desc ";
		//echo $stmt;
	
		$sth = $conn->prepare($stmt);
		$sth->bindParam(':mobile_no', $mobile_no);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row = $sth->fetchAll();
		$arr = array("complain_rpt"=>$row);
		echo json_encode($arr);	
	}
	
	
}
?>


<?php
$conn=null;
?>