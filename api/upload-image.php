<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
include("../inc/dblib.inc.php");
$conn = OpenDB();
//$Req_file=$data['file'];
$message=null;
$fname=null;
$fname_arr=null;
$compl_ref=null;
$target_img=null;
$target_path = "../uploads/";
 
$target_path = $target_path . basename( $_FILES['file']['name']);

if ($_FILES["file"]["error"] > 0)
{
	$error_no= $_FILES["file"]["error"];
	if($error_no==1)
	$message=$error_no;
}
else
{
	$fname=$_FILES['file']['name'];
	$fname_arr=explode(".",$fname);
	$compl_ref=$fname_arr[0];
	$target_img="./uploads/$fname";
	
	move_uploaded_file($_FILES["file"]["tmp_name"], $target_path);
	/*
	$bin_string = file_get_contents($target_path);
	$hex_string = base64_encode($bin_string);
	$imageFileType = strtolower(pathinfo($target_path,PATHINFO_EXTENSION));
	$image = 'data:image/'.$imageFileType.';base64,'.$hex_string;
	*/		
	$stmt ="update flt_mas set doc_upload=:comp_img ";
	$stmt.="where dkt_no=:dkt_no ";
	$sth = $conn->prepare($stmt);
	$sth->bindParam(':comp_img', $target_img);
	$sth->bindParam(':dkt_no', $compl_ref);
	$sth->execute();
	$message="success";
	//unlink($target_path);
}
$conn=null;
$arr = array("message"=>$message);
echo json_encode($arr);	

?>