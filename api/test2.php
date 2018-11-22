<?php
header("Access-Control-Allow-Origin: *");
include("../inc/dblib.inc.php");
$conn = OpenDB();
?>
<?php
$submit = isset($_POST['submit']) ? $_POST['submit'] : ''; 

/*
$arr = array("status"=>"A","message"=>"Hi, testing message","user_data"=>
    array(
        "user_name" => "atiur",
        "mobile_num" => "8902375503",
		"token" => "asdfasd897908asdXXJH"
    )

);
*/

//------------- send otp test ---------
//$arr=array( "mobile_num"=> "8902375503", "name"=> "sk atiur rahaman","tag"=>"OTP" );

//------------- login test  ---------
//$arr=array( "mobile_num"=> "8902375503", "otp"=> "6776","tag"=>"LOGIN" );

//------------- delete otp  ---------
//$arr=array( "mobile_num"=> "8902375503", "otp"=> "6776","tag"=>"DELETE-OTP" );
if($submit=="Submit")
{
	$arr=array
	(
		"user_mobile"=> "8902375503",
		"user_name"=> "sk atiur rahaman",
		"user_token"=> "6404e1460ad24c5d3f75a8539b5593d9",
		"compl_type"=> "2",
		"compl_dept"=> "3",
		"compl_dist"=> "1",
		"compl_block"=> "5",
		"compl_ps"=> "26",
		"compl_address"=> "harop panbazar",
		"compl_road"=> "harop road",
		"compl_locality"=> "school para",
		"compl_vill_town"=> "harop",
		"compl_pincode"=> 711301,
		"compl_landmark"=> "club",
		"compl_email"=> "atiur@infotechsystems.in",
		"compl_description"=> "no water in our area",
		"file"=>'@'. $_FILES['file']['tmp_name']
          . ';filename=' . $_FILES['file']['name']
	  );
	
	$data =json_encode($arr);
	//$ch = curl_init('http://localhost/citizen/api/login.php');
	
	$ch = curl_init('http://10.152.248.15/citizen/api/insert-data.php');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	print_r($result);
	curl_close($ch);
}
/*
$data = json_decode(file_get_contents('php://input'), true);
print_r($data);
echo $data["operacion"];
*/
?>
<html>
	<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file" id="file">
    <input type="submit" value="Submit" name="submit">
    </form>
</html>

<?php
$conn=null;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>