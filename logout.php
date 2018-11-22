<?php
header("X-XSS-Protection: 1;mode = block");
header("X-Content-Type-Options: nosniff");
include("./inc/operator_class.php");
include("./inc/dblib.inc.php");
$conn = OpenDB();

$Session = new Session('Script');
$ses_uid = $Session->Get('uid');
$ses_user_nm = $Session->Get('user_nm');
$ses_user_id = $Session->Get('user_id');
$ses_id = $Session->Get('id');
$sql1 ="select current_timestamp as logout_time ";
$sth1 = $conn->prepare($sql1);;
$sth1->execute();
$ss1=$sth1->setFetchMode(PDO::FETCH_ASSOC);
$rowt = $sth1->fetch();
$logout_time=$rowt['logout_time'];

$sqlu="update user_log_mas set logout_on=:logout_time where uid=:ses_uid and  ";
$sqlu.=" logout_on='0000-00-00 00:00:00' and id=:ses_id";
$sthI = $conn->prepare($sqlu);
$sthI->bindParam(':logout_time', $logout_time);
$sthI->bindParam(':ses_uid', $ses_uid);
$sthI->bindParam(':ses_id', $ses_id);
$sthI->execute();

unset($_SESSION['uid']);
unset($_SESSION['user_nm']);
unset($_SESSION['user_id']);

session_destroy();
$conn=null;

?>
<script language="javascript">
window.location.href='./index.php';
</script>
<?php
ob_flush();
?>
