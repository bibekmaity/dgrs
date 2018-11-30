<?php
header("X-XSS-Protection: 1;mode = block");
header("X-Content-Type-Options: nosniff");
include("../inc/dblib.inc.php");
$conn = OpenDB();

date_default_timezone_set("Asia/Calcutta");

$remarks= $_POST['remarks'];
$hid_fault= $_POST['hid_fault']; 
$hid_uid= $_POST['hid_uid'];
$refer_to=$hid_uid;
$uploaddir="./uploads/";	
$photo= $_FILES['photo'];

if(!empty($photo))
{

	$base=$uploaddir.fileCkecking($photo,0);
}
$sql_search=" select flt_id,rmn,dist_id,dkt_no from flt_mas where ";
$sql_search.=" md5(flt_id)=:hid_fault ";
$sth_search = $conn->prepare($sql_search);
$sth_search->bindParam(':hid_fault', $hid_fault);
$sth_search->execute();
$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
$row_search = $sth_search->fetch();
$flt_id=$row_search['flt_id'];
$rmn=$row_search['rmn'];
$dist_id=$row_search['dist_id'];
$dkt_no=$row_search['dkt_no'];	
$sql ="insert into refer_mas (";
$sql.="flt_id,dist_id,rmn,dkt_no,doc_upload,remarks,refer_by ";
$sql.=" ,refer_to,refer_date) values ( ";
$sql.=" :flt_id,:dist_id,:rmn,:dkt_no,:base ";
$sql.=" ,trim(:remarks) ,trim(:hid_uid),:refer_to,current_timestamp) ";
$sth = $conn->prepare($sql);
$sth->bindParam(':flt_id', $flt_id);
$sth->bindParam(':dist_id', $dist_id);
$sth->bindParam(':rmn', $rmn);
$sth->bindParam(':dkt_no', $dkt_no);
$sth->bindParam(':base', $base);
$sth->bindParam(':remarks', addslashes($remarks));
$sth->bindParam(':hid_uid', $hid_uid);
$sth->bindParam(':refer_to', $refer_to);
$sth->execute();
$sqlU=" update  flt_mas set refer_rmk=:remarks,close_by=trim(:hid_uid)";
$sqlU.=" ,close_date=current_timestamp where md5(flt_id)=:flt_id ";
$sthU = $conn->prepare($sqlU);
$sthU->bindParam(':remarks', addslashes($remarks));
$sthU->bindParam(':flt_id', $hid_fault);
$sthU->bindParam(':hid_uid', $hid_uid);
$sthU->execute();

$sql=" insert into flt_his (flt_id,rmn,dkt_no,dkt_date,flt_type,comp_type_id,comp_desc ";
$sql.=" ,comp_img,dist_id,dept_id,sub_div_id,block_id,ps_id,status,close_date,close_by ";
$sql.=" ,refer_to,refer_date,refer_rmk,addr,street,para,village,pin,landmark,doc_upload ) ";
$sql.=" select flt_id,rmn,dkt_no,dkt_date,flt_type,comp_type_id,comp_desc ";
$sql.=" ,comp_img,dist_id,dept_id,sub_div_id,block_id,ps_id,status,close_date,close_by ";
$sql.=" ,refer_to,refer_date,refer_rmk,addr,street,para,village,pin,landmark,doc_upload ";
$sql.=" from flt_mas where  md5(flt_id)=:flt_id ";
//echo $sql;
$sth = $conn->prepare($sql);
$sth->bindParam(':flt_id', $hid_fault);
$sth->execute();

$sqlD=" delete from flt_mas where md5(flt_id)=:flt_id ";
$sthD = $conn->prepare($sqlD);
$sthD->bindParam(':flt_id', $hid_fault);
$sthD->execute();
		
		

$conn=null;
?>