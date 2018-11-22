<?php
header("Access-Control-Allow-Origin: *");
include("../inc/dblib.inc.php");
$conn = OpenDB();
?>

<html>
	<form action="./upload-image.php" method="post" enctype="multipart/form-data">
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