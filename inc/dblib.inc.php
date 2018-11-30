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

function fileCkecking($file,$idx)
{
$msg=array();
$error=array();
$msg['invalid']='';
$allowedExtensions = array("jpg","jpeg","gif","png","doc","docx","xls","xlsx","pdf","odt","ods");
//	print_r($FILES);
$remove_file =null; 
  if(!empty($file['name'][$idx]))
  {
  $bas_dir="../uploads/";
        $name_of_file = basename($file['name'][$idx]);
        $path_of_uploaded_file=$bas_dir.$name_of_file;
        $tmp_path = $file["tmp_name"][$idx];

    if ($file['tmp_name'][$idx] > '') {
      if (!in_array(end(explode(".",
            strtolower($file['name'][$idx]))),
            $allowedExtensions)) {
        $msg['invalid']='<font color="red" size="3">This <b>('.$file['name'][$idx].')</b> is an invalid file type</font>';
        $error['errorfile']="Error";
      }
      else
      {
           if(file_exists($path_of_uploaded_file))
             {
             $name_of_file = basename($file['name'][$idx]);
             $ren_file=explode(".",$name_of_file);
             $r_file=$ren_file[0].date('d-m-i-s').'.'.$ren_file[1];
             $path_of_uploaded_file=$bas_dir.$r_file;

             $remove_file["$idx"]=$r_file;
             copy($tmp_path,$path_of_uploaded_file);
             }
             else
             {
              $remove_file["$idx"]=$file['name'][$idx];
              copy($tmp_path,$path_of_uploaded_file);
             }
      }
    }
  }
   echo $msg['invalid'];   
  return $remove_file["$idx"];
 
}
function fileCkecking2($_FILES)
{

$msg=array();
$error=array();
$msg['invalid']='';
$allowedExtensions = array("txt","doc","pdf","jpg","jpeg","gif","docx","png","xls","xlsx","odt","ods");
//print_r($FILES);
 	foreach ($_FILES as $key => $file) {
  
	if(!empty($file['name']))
	  {
	    $bas_dir="./uploads/";
        $name_of_file = basename($file['name']);
        $path_of_uploaded_file=$bas_dir.$name_of_file;
        $tmp_path = $file["tmp_name"];
        $myfile=strtolower($file['name']);

//	    if ($file['tmp_name'] > '') 
	    if ($tmp_path > '') 
		{
           if(file_exists($path_of_uploaded_file))
             {
             $name_of_file = basename($file['name']);
             $ren_file=explode(".",$name_of_file);
             $r_file=$ren_file[0].date('d-m-i-s').'.'.$ren_file[1];
             $path_of_uploaded_file=$bas_dir.$r_file;

             $remove_file["$key"]=$r_file;
             copy($tmp_path,$path_of_uploaded_file);
             }
             else
             {
              $remove_file["$key"]=$file['name'];
              copy($tmp_path,$path_of_uploaded_file);
             }
    }
  }
} 
echo $msg['invalid'];   
return $remove_file;
}
?>
