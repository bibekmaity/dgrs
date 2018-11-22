<?php
 /*#################### DISPLAY DATE #########################*/
 
function sysdate()
 {
 $day=date('d');
 $mon=date('m');
 $year=date('Y');
 $now="$day/$mon/$year";
 return $now;
}

function sysdate_all()
 {
 $day=date('d');
 $mon=date('m');
 $year=date('Y');
 $now="$day$mon$year";
 return $now;
 }

function sysdatetime()
 {
 $day=date('d');
 $mon=date('m');
 $year=date('Y');
 $now="$day-$mon-$year";
 $time=date('H').":".date('i').":".date('s');
 return $now." ".$time;
 }
 
function systime()
 {
 
 $time=date('H').":".date('i').":".date('s');
 return $time;
 }

 
function daylist()
 {
  $num_of_arg=func_num_args();
  if($num_of_arg==1)
   {
   $maxdays=func_get_arg(0);
   }
  else
   {
   $maxdays=date('t');
   }

  $list="";
  $curday=date('d');
  
  $ctr=1;
  while($ctr<=$maxdays)
   {
   $list.="<option ";
   if($curday>$maxdays)
    {
    $list.=($maxdays==$ctr)?" SELECTED":"";
    }
   else
    {
    $list.=($curday==$ctr)?" SELECTED":"";
    } 
   settype($ctr,'string');
   if(strlen($ctr)==1)
    {$ctr="0".$ctr;}
   $list.="value='$ctr' >$ctr</option>";
   $ctr++;
   }
  echo $list;
 }
 
function daylist1($dd)
 {

  $list="";
  $curday=date('d');
  
  $ctr=1;
  while($ctr<=31)
   {
   $list.="<option ";
     $list.=($dd==$ctr)?" SELECTED ":"";

    settype($ctr,'string');
   if(strlen($ctr)==1)
    {$ctr="0".$ctr;}
   $list.="value='$ctr' >$ctr</option>";
   $ctr++;
   }
  echo $list;
 }



function monlist()
 {
  $list="";
 $curmon=date('m');
 $ctr=1;
 while($ctr<=12)
  {
   if(strlen($ctr)==1)
    {$ctr="0".$ctr;}
   $list.="<option value=".$ctr;
   $list.=($curmon==$ctr)?" SELECTED":"";
   settype($ctr,'string');
   if(strlen($ctr)==1)
    {$ctr="0".$ctr;}
    $list.=" >$ctr</option>";
    $ctr++;
    }
  echo $list;
 }
 function monlist1($cm)
 {
  $list="";
 $curmon=date('m');
 $ctr=1;


 for($ctr=1;$ctr<=12;$ctr++)
  {
switch($ctr)
{
case "1":
$ctr_m="JANUARY";

break;
case "2":
$ctr_m="FEBRUARY";
break;
case "3":
$ctr_m="MARCH";
break;
case "4":
$ctr_m="APRIL";
break;
case "5":
$ctr_m="MAY";
break;
case "6":
$ctr_m="JUNE";
break;
case "7":
$ctr_m="JULY";
break;
case "8":
$ctr_m="AUGUST";
break;
case "9":
$ctr_m="SEPTEMBER";
break;
case "10":
$ctr_m="OCTOBER";
break;
case "11":
$ctr_m="NOVEMBER";
break;
case "12":
$ctr_m="DECEMBER";
break;
default:
break;
}
   $list.="<option ";
   settype($ctr,'string');
    if(strlen($ctr)==1)
    {$ctr="0".$ctr;}
	if($cm==$ctr){ 
	$se="SELECTED";
	}
   $list.=" value='$ctr'".$se.">$ctr_m</option>";
   $se="";
   }
  echo $list;
 }
  function monlist2()
 {
  $list="";
 $curmon=date('m');
 $ctr=1;


 for($ctr=1;$ctr<=12;$ctr++)
  {
switch($ctr)
{
case "01":
$ctr_m="JANUARY";

break;
case "02":
$ctr_m="FEBRUARY";
break;
case "03":
$ctr_m="MARCH";
break;
case "04":
$ctr_m="APRIL";
break;
case "05":
$ctr_m="MAY";
break;
case "06":
$ctr_m="JUNE";
break;
case "07":
$ctr_m="JULY";
break;
case "08":
$ctr_m="AUGUST";
break;
case "09":
$ctr_m="SEPTEMBER";
break;
case "10":
$ctr_m="OCTOBER";
break;
case "11":
$ctr_m="NOVEMBER";
break;
case "12":
$ctr_m="DECEMBER";
break;
default:
break;
}
   $list.="<option ";
   
   $list.=" value='$ctr'>$ctr_m</option>";
   }
  echo $list;
 }
 
 function yearlist1($curyear)
 {
  $list="";
 //$curyear=date('Y');
 $ctr=1947;
 while($ctr<=2025)
  {
   $list.="<option value=".$ctr;
   $list.=($curyear==$ctr)?" SELECTED":"";
   $list.=" >$ctr</option>";
   $ctr++;
  }
  echo $list;
 }

function yearlist()
 {
  $list="";
 $curyear=date('Y');
 $ctr=1900;
 while($ctr<=2999)
  {
   $list.="<option ";
   $list.=($curyear==$ctr)?" SELECTED":"";
   $list.="  value='$ctr'>$ctr</option>";
   $ctr++;
  }
  echo $list;
 }
 
 function yearlist2()  // This function do not select the current Year by default :: date:15/7/1006
 {
   $ctr=2005;
   while($ctr<=2025)
   {
     $list.="<option value='$ctr'>$ctr</option>";
     $ctr++;
   }
   echo $list;
 }
 

?>
