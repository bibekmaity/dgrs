<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

include("../inc/dblib.inc.php");
$conn = OpenDB();

$data = json_decode( file_get_contents( 'php://input' ), true );

$mobile = isset($data['mobile_num']) ? $data['mobile_num'] : '';
$name = isset($data['name']) ? $data['name'] : '';
$tag = isset($data['tag']) ? $data['tag'] : '';
$otp = isset($data['otp']) ? $data['otp'] : '';
$token = isset($data['token']) ? $data['token'] : '';


if(($tag=='OTP'))
{
    $otp=mt_rand(1000, 9999);
	$hid_lang="B";	

    $sql_search=" select count(*) as cnt from citizen_mas where ";
    $sql_search.=" rmn=:mobile ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':mobile', $mobile);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$cnt=$row_search['cnt'];
	if($cnt>0)
	{
		
		
		$sql=" insert into otp_mas (cell_no,otp_no) ";
		$sql.=" values (:mobile,:otp) "; 
		$sth = $conn->prepare($sql);
		$sth->bindParam(':mobile', $mobile);
		$sth->bindParam(':otp', $otp);
		$sth->execute();
		
		$sqlu ="update citizen_mas set citizen_nm=:name ";
		$sqlu.="where rmn=:mobile ";
		$sth3 = $conn->prepare($sqlu);
		$sth3->bindParam(':mobile', $mobile);
		$sth3->bindParam(':name', $name);
		$sth3->execute();
		
		
		$stmt ="SELECT otp_no ";
		$stmt.="FROM otp_mas ";
		$stmt.="where cell_no=:mobile ";
		$sth = $conn->prepare($stmt);
		$sth->bindParam(':mobile', $mobile);
		$sth->execute();
		$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row = $sth->fetch();
		$otp_no=null;
		$otp_no=$row['otp_no'];
		send_sms($mobile,"Your OTP No: ".$otp_no);
		$arr = array("otp_no"=>$otp_no,"message"=>"success");
		echo json_encode($arr);
	}
	else
	{
		$sqli=" insert into citizen_mas (citizen_nm,rmn) ";
		$sqli.=" values (:name,:mobile) "; 
		$sthi = $conn->prepare($sqli);
		$sthi->bindParam(':mobile', $mobile);
		$sthi->bindParam(':name', $name);
		$sthi->execute();
		$sql=" insert into otp_mas (cell_no,otp_no) ";
		$sql.=" values (:mobile,:otp) "; 
		$sth = $conn->prepare($sql);
		$sth->bindParam(':mobile', $mobile);
		$sth->bindParam(':otp', $otp);
		$sth->execute();
		
		$stmt ="SELECT otp_no ";
		$stmt.="FROM otp_mas ";
		$stmt.="where cell_no=:mobile ";
		$sth = $conn->prepare($stmt);
		$sth->bindParam(':mobile', $mobile);
		$sth->execute();
		$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row = $sth->fetch();
		$otp_no=null;
		$otp_no=$row['otp_no'];
		send_sms($mobile,"Your OTP No: ".$otp_no);
		$arr = array("otp_no"=>$otp_no,"message"=>"success");
		echo json_encode($arr);

	}
	

}
?>


<?php
if($tag=="LOGIN")
{
//------------- top mactched-----------------	
$stmt ="SELECT count(*) as ct ";
$stmt.="FROM otp_mas ";
$stmt.="where cell_no=:mobile and otp_no=:otp ";
$sth = $conn->prepare($stmt);
$sth->bindParam(':mobile', $mobile);
$sth->bindParam(':otp', $otp);
$sth->execute();
$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetch();
$ct=0;
$ct=$row['ct'];
$status=null;	
//echo json_encode($row);	
if($ct>0)
{
	$status="success";
	$tokan=md5(uniqid($mobile, true));
	
	$sqlu ="update citizen_mas set token=:tokan ";
	$sqlu.="where rmn=:mobile ";
	$sth3 = $conn->prepare($sqlu);
	$sth3->bindParam(':mobile', $mobile);
	$sth3->bindParam(':tokan', $tokan);
	$sth3->execute();
	
	
	//--------------- get user information--------------
	$stmt2 ="SELECT ucase(citizen_nm) as citizen_nm,rmn ";
	$stmt2.="FROM citizen_mas ";
	$stmt2.="where rmn=:mobile  ";
	$sth2 = $conn->prepare($stmt2);
	$sth2->bindParam(':mobile', $mobile);
	$sth2->execute();
	$sth2->setFetchMode(PDO::FETCH_ASSOC);
	$row2 = $sth2->fetch();
	$citizen_nm=$row2['citizen_nm'];
	$rmn=$row2['rmn'];



}
else
{
	$status="failed";
}


$arr = array("status"=>$status,"message"=>"","user_data"=>
    array(
        "user_name" => $citizen_nm,
        "mobile_num" => $rmn,
		"token" => $tokan
    )

);

echo json_encode($arr);
?>

<?php	
}
?>

<?php
if($tag=="DELETE-OTP")
{
//----------- insert into history ----------------	
$stmt ="insert into otp_his SELECT * ";
$stmt.="FROM otp_mas ";
$stmt.="where cell_no=:mobile and otp_no=:otp ";
$sth = $conn->prepare($stmt);
$sth->bindParam(':mobile', $mobile);
$sth->bindParam(':otp', $otp);
$sth->execute();

//-------------- delete from master ---------------
$stmt ="delete ";
$stmt.="FROM otp_mas ";
$stmt.="where cell_no=:mobile and otp_no=:otp ";
$sth = $conn->prepare($stmt);
$sth->bindParam(':mobile', $mobile);
$sth->bindParam(':otp', $otp);
$sth->execute();

$arr = array("message"=>"success");
echo json_encode($arr);	
?>

<?php	
}
?>

<?php
if($tag=="LOGOUT")
{
	//-------------- delete from master ---------------
	$stmt ="update citizen_mas set token=null ";
	$stmt.="where rmn=:mobile and token=:token ";
	$sth = $conn->prepare($stmt);
	$sth->bindParam(':mobile', $mobile);
	$sth->bindParam(':token', $token);
	$sth->execute();
	
	$arr = array("message"=>"success");
	echo json_encode($arr);	

}
?>

<?php
$conn=null;
?>