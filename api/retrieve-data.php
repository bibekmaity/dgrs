<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

include("../inc/dblib.inc.php");
$conn = OpenDB();
$data = json_decode( file_get_contents( 'php://input' ), true );

$tag = isset($data['tag']) ? $data['tag'] : '';
$block_id = isset($data['block_id']) ? $data['block_id'] : '';
$token = isset($data['token']) ? $data['token'] : '';
?>

<?php
if($tag=="COMPTYPE-DEPT-DIST")
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
	
		$stmt ="SELECT comp_type_id,comp_type ";
		$stmt.="FROM compl_type_mas ";
		$stmt.="where comp_pfx!='S' ";
		$sth = $conn->prepare($stmt);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$rowc = $sth->fetchAll();
		
		//$arr = array("complain_types"=>$row);
		//echo json_encode($arr);	
	

	//---------- show district --------------	
		$stmt ="SELECT dist_id,dist_nm ";
		$stmt.="FROM district_mas ";
		$stmt.="order by dist_nm ";
		$sth = $conn->prepare($stmt);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$rowt = $sth->fetchAll();
		
		$arr = array("complain_types"=>$rowc);
		echo json_encode($arr);	
		
	}
	
}
?>


<?php
if($tag=="BLOCK")
{

	//----------------show blocks ---------------
	
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
	
		$stmt ="SELECT block_id,block_nm_ben ";
		$stmt.="FROM block_mas ";
		$sth = $conn->prepare($stmt);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row = $sth->fetchAll();
		$arr = array("complain_blocks"=>$row);
		echo json_encode($arr);	
	}
}
?>
<?php
if($tag=="PS")
{
	
	//----------------show police stations  ---------------
	
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
	
		$stmt ="SELECT pm.ps_id,pm.ps_nm_ben ";
		$stmt.="FROM ps_mas pm,block_mas bm ";
		$stmt.="where pm.sub_div_id=bm.sub_div_id ";
		if(!empty($block_id))
		{
		$stmt.="and bm.block_id=:block_id ";
		}
		$stmt.="order by pm.ps_nm_ben ";
		//echo $stmt;
		$sth = $conn->prepare($stmt);
		if(!empty($block_id))
		{
		$sth->bindParam(':block_id', $block_id);
		}
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row = $sth->fetchAll();
		$arr = array("complain_ps"=>$row);
		echo json_encode($arr);	
	}
}
?>


<?php
$conn=null;
?>