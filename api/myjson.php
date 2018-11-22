<?php
/*
$arr = array("status"=>"A","message"=>"Hi, testing message","user_data"=>
    array(
        "user_name" => "atiur",
        "mobile_num" => "8902375503",
		"token" => "asdfasd897908asdXXJH"
    )

);

echo json_encode($arr);
*/

$data = json_decode( file_get_contents( 'php://input' ), true );
print_r($data);

echo "<br>status:".$data['status'];
?>