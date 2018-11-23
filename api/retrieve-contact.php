<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

include("../inc/dblib.inc.php");
$conn = OpenDB();
$data = json_decode( file_get_contents( 'php://input' ), true );

$tag = isset($data['tag']) ? $data['tag'] : '';
$token = isset($data['token']) ? $data['token'] : '';
?>

<?php
if($tag=="CONTACT")
{

	//----------------show complaint type ---------------
	
	//--------------- check if token is valid --------------
	$stmt2 ="SELECT count(*) as ct ";
	$stmt2.="FROM citizen_mas ";
	$stmt2.="where token=:token  ";
	$sth2 = $conn->prepare($stmt2);
	$sth2->bindParam(':token', $token);
	$sth2->execute();
	$sth2->setFetchMode(PDO::FETCH_ASSOC);
	$row2 = $sth2->fetch();
	$ct=$row2['ct'];
	if($ct>0)
	{
		
		
		//--------- show complaint type ----------------
	
		$stmt ="SELECT department,officer_in_chrg as officer";
		$stmt.=",phone as land_line,mobile_no as mobile,email as email";
		$stmt.=",schemes as scheme,status as status ";
		$stmt.="FROM medinipur_data ";
		$sth = $conn->prepare($stmt);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$rowc = $sth->fetchAll();
		$arr = array("info"=>$rowc);
		echo json_encode($arr);	
		
	}
	
	
	
}
?>




<?php
$conn=null;
?>