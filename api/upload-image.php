<?php
ini_set('display_errors', 'On'); // TODO:: Turn Off for Security Audit
error_reporting(E_ALL);
header("X-XSS-Protection: 1;mode = block");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
$headers = apache_request_headers();

$str=null;
$compl_ref=null;
foreach ($headers as $header => $value) {
  
     
     
     if($header=="Lazyupdate")
     {
        
        
        //$str.="Header: $header ==> $value\n";
        $data = json_decode(  $value , true );
        foreach($data as $dtk=> $dval)
        {
            
           if($dtk=="value")
           {
            
            $compl_ref=$dval;
           // $str.="COM REF:  $compl_ref <br />\n";
            
           }
        
        }
        
     }
}


//fwrite($fHandler,$str);  
//close handler  
//fclose($fHandler);  

include("../inc/dblib.inc.php");
$conn = OpenDB();


	
	//$Req_file=$data['file'];
	
	$message=null;
	$target_path = "../uploads/";
	 
	$target_path = $target_path . basename( $_FILES['file']['name']);
	
	if ($_FILES["file"]["error"] > 0)
	{
        $error_no= $_FILES["file"]["error"];
		if($error_no==1)
		$message="file_size_exceeded";
		
		
    }else
	{
		
		
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_path);
        $bin_string = file_get_contents($target_path);
		
        $hex_string = base64_encode($bin_string);
		$imageFileType = strtolower(pathinfo($target_path,PATHINFO_EXTENSION));
		
	  	$image = 'data:image/'.$imageFileType.';base64,'.$hex_string;

		
        $stmt ="update flt_mas set comp_img=:comp_img ";
		$stmt.="where dkt_no=:dkt_no ";
		$sth = $conn->prepare($stmt);
		$sth->bindParam(':comp_img', $image);
		$sth->bindParam(':dkt_no', $compl_ref);
		$sth->execute();
		$message="success";
		//unlink($target_path);
		
	   
    }
	$arr = array("message"=>$message);
	
	echo json_encode($arr);	
	

?>

<?php
$conn=null;
?>
