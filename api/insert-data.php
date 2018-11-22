<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
include("../inc/dblib.inc.php");
$conn = OpenDB();

$data = json_decode( file_get_contents( 'php://input' ), true );

?>

<?php
	$Req_mobile=$data['user_mobile'];
	$Req_name=$data['user_name'];
	$Req_token=$data['user_token'];
	$Req_compl_flag=strtoupper($data['compl_flag']);
	$Req_compl=$data['compl_type'];
	$Req_block=$data['compl_block'];
	$Req_ps=$data['compl_ps'];
	$Req_addr=addslashes($data['compl_address']);
	$Req_street=addslashes($data['compl_road']);
	$Req_para=addslashes($data['compl_locality']);
	$Req_village=addslashes($data['compl_vill_town']);
	$Req_pin=$data['compl_pincode'];
	$Req_landmark=addslashes($data['compl_landmark']);
	$Req_email=addslashes($data['compl_email']);
	$Req_compDesc=addslashes($data['compl_description']);

	
	//--------------- check if token is valid --------------
	$stmt2 ="SELECT count(*) as ct ";
	$stmt2.="FROM citizen_mas ";
	$stmt2.="where token=:token  ";
	$sth2 = $conn->prepare($stmt2);
	$sth2->bindParam(':token', $Req_token);
	$sth2->execute();
	$sth2->setFetchMode(PDO::FETCH_ASSOC);
	$row2 = $sth2->fetch();
	$ct=$row2['ct'];
	if($ct>0)
	{
		
	
			//----------------- get sub divisin----------------------
			
			$stmt ="SELECT pm.sub_div_id ";
			$stmt.="FROM ps_mas pm,block_mas bm ";
			$stmt.="where pm.sub_div_id=bm.sub_div_id ";
			if(!empty($block_id))
			{
			$stmt.="and bm.block_id=:block_id ";
			}
			$sth = $conn->prepare($stmt);
			if(!empty($block_id))
			{
			$sth->bindParam(':block_id', $Req_block);
			}
			$sth->execute();
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$row = $sth->fetch();
			$sub_div_id=$row['sub_div_id'];
			
			
			
			
			//---------------- get complaint serial --------------------
			
			if($Req_compl_flag=="S")
			$Req_compl=4;
			
			
			$stmt ="SELECT comp_pfx,lpad(comp_srl,5,'0') as comp_srl,dept_id ";
			$stmt.="FROM compl_type_mas ";
			$stmt.="where 1=1 ";
			$stmt.="and comp_type_id=:Req_compl ";
			$sth = $conn->prepare($stmt);
			$sth->bindParam(':Req_compl', $Req_compl);
			$sth->execute();
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$row = $sth->fetch();
			$comp_pfx=$row['comp_pfx'];
			$comp_srl=$row['comp_srl'];
			$dept_id=$row['dept_id'];
			
			$dkt_no=null;
			$dkt_no="$comp_pfx$comp_srl";
			
		
			$sql ="insert into flt_mas(rmn,dkt_no,dkt_date,flt_type,comp_type_id,comp_desc";	
			$sql.=",comp_img,dept_id,sub_div_id,block_id,ps_id";
			$sql.=",addr,street,para,village,pin,landmark) ";
			$sql.="values(:rmn,:dkt_no,current_timestamp,upper(:flt_type)";
			$sql.=",:comp_type_id,:comp_desc";
			$sql.=",null,:dept_id,:sub_div_id,:block_id,:ps_id";
			$sql.=",:addr,:street,:para,:village,:pin,:landmark";
			$sql.=")";
			$sth = $conn->prepare($sql);
			$sth->bindParam(':rmn', $Req_mobile);
			$sth->bindParam(':dkt_no', $dkt_no);
			$sth->bindParam(':flt_type', $Req_compl_flag);
			$sth->bindParam(':comp_type_id', $Req_compl);
			$sth->bindParam(':comp_desc', $Req_compDesc);
			$sth->bindParam(':dept_id', $dept_id);
			$sth->bindParam(':sub_div_id', $sub_div_id);
			$sth->bindParam(':block_id', $Req_block);
			$sth->bindParam(':ps_id', $Req_ps);
			$sth->bindParam(':addr', $Req_addr);
			$sth->bindParam(':street', $Req_street);
			$sth->bindParam(':para', $Req_para);
			$sth->bindParam(':village', $Req_village);
			$sth->bindParam(':pin', $Req_pin);
			$sth->bindParam(':landmark', $Req_landmark);
			$sth->execute();
			$complaint_desc=null;
			if($Req_compl_flag=="S")
			{
				$complaint_desc="Your suggestion has been registered. ID : ";
			}else
			{
				$complaint_desc="Your complaint has been registered. Complaint No: ";
			}
			send_sms($Req_mobile,$complaint_desc."".$dkt_no);

			
			//-------------- delete from master ---------------
			$stmt ="update compl_type_mas set comp_srl=comp_srl+1 ";
			$stmt.="where comp_type_id=:Req_compl ";
			$sth = $conn->prepare($stmt);
			$sth->bindParam(':Req_compl', $Req_compl);
			$sth->execute();
			
			$arr = array("message"=>"success","compl_ref"=>$dkt_no);
			echo json_encode($arr);	
		
		
	}else
	{
		$arr = array("message"=>"invalid_token");
		echo json_encode($arr);	
	}

?>

<?php
$conn=null;
?>