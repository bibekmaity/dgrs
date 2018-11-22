<?php
include "../qrcode/qrlib.php";    

function ISqrcode($file_name,$codeContents)
{
date_default_timezone_set("Asia/Kolkata");
 //set it to writable location, a place for temp generated PNG files
    //$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    $PNG_TEMP_DIR = '../qrcode/temp/';
    
    //html PNG location prefix
    $PNG_WEB_DIR = '../qrcode/temp/';

    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 2;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (!empty($codeContents)) 
	{ 
         // user data
        $filename = $PNG_TEMP_DIR.$file_name;
       // echo $filename;
        $codeContents1=stripcslashes($codeContents);
        QRcode::png($codeContents1, $filename, $errorCorrectionLevel, $matrixPointSize, 2);        $file=QRcode::image($codeContents1);    
		
        
    }   
        
 	 return $file;
	
}
?>