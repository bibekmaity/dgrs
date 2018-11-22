<?php
if (file_exists(__DIR__ . '/../config.inc.php')) {
  require_once __DIR__ . '/../config.inc.php';
} else {
  require_once __DIR__ . '/../config.sample.inc.php';
}
require_once __DIR__ . '/../smsgw/smsgw.inc.php';
 function OpenDB()
  {
   $DBLink = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, 
  array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
  ));
   $DBLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
   return $DBLink;
  }


  
  function british_to_ansi($date)
 {
    list($day, $month, $year) = split('[/.-]', $date);
    $day = strlen($day) < 2 ? "0".$day : $day ;
    $month = strlen($month) < 2 ? "0".$month : $month ;
    $date = $year . "-" . $month . "-" . $day ;
    return $date ;
 }


 function ansi_to_british($date)
 {
    list($year,$month,$day) = split('[/.-]', $date);
    $day = strlen($day) < 2 ? "0".$day : $day ;
    $month = strlen($month) < 2 ? "0".$month : $month ;
    $date = $day."/".$month."/".$year ;
    return $date ;
 }  
 
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function send_sms($cell_no,$mMessage){
	
	// Authorisation details.
$result = SMSGW::SendSMS($mMessage, $cell_no); // This is the result from the API
//print_r($result);
}

?>
