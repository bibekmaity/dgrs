<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); ?>
<?php
$mitem = isset($_POST['mitem']) ? $_POST['mitem'] : '';
$submit = isset($_POST['submit']) ? $_POST['submit'] : '';
$hr_id = isset($_REQUEST['hr_id']) ? $_REQUEST['hr_id'] : '';
$hid_hr_id = isset($_POST['hid_hr_id']) ? $_POST['hid_hr_id'] : '';

$block_nm="";

$sql=" select um.uid,um.user_nm,um.page_assign ";
$sql.=" from user_mas um ";
$sql.=" where md5(uid)=:hr_id  ";


$sth = $conn->prepare($sql);
$sth->bindParam(':hr_id', $hr_id);
$sth->execute();
$row = $sth->fetch();
$uid=$row['uid'];
$user_nm=$row['user_nm'];
$page_par_temp=$row['page_assign'];
$page_pars_temp=explode(",",$page_par_temp);

//echo "sss $page_par_temp";
if($submit=="Submit")
{
	$str_mid='';
	for($i=0;$i<=count($mitem)-1;$i++)
	{ 
		$str_mid.= $mitem[$i].",";
	}
	$str_mid.="0";
    if(in_array("12",$mitem))
	{
		$restore_access="Y";
	}
	else
	{
		$restore_access="N";
	}
	
	$sql =" update user_mas set page_assign=trim(:str_mid),restore_access=:restore_access ";
	$sql.=" where  ";
	$sql.=" md5(uid)=:hr_id ";

	$sth = $conn->prepare($sql);
	$sth->bindParam(':str_mid', $str_mid);
	$sth->bindParam(':restore_access', $restore_access);
	$sth->bindParam(':hr_id', $hid_hr_id);
	$sth->execute();

	?>
	<script language=javascript>
    alertify.success('Page Permission Successfully');
    window.location.href='./user-master.php';
    </script>
    <?php
} 
if(!empty($hr_id)) 
{
?>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Page Permission for <?php echo $user_nm; ?>  <?php echo $block_nm; ?>  </h3>
              </div>
            <!-- /.box-header -->
            <form name="form1" method="post" enctype="multipart/form-data" onSubmit="return validate()">

            <div class="box-body table-responsive no-padding">
            
            <table  class="table table-hover">
            <tr>
            <th>Main Menu</th>
            <th>Sub Menu</th>
            <th colspan="3">Menu</th>
            </tr>
            <?php
            $sql ="select * from menu_master where parent_id='0' ";
			//$sql.="and show_tag='T' ";
			$sql.="order by mid";
            $sth = $conn->prepare($sql);
			$sth->execute();
			$row = $sth->fetchAll();
			foreach ($row as $key => $value)
			{ 
				$mbody=$value['mbody'];
				$mid=$value['mid'];
            ?>
            <tr> <td colspan="5"><?php echo $mbody; ?></td></tr>
            
            <?php
            $sql1="select * from menu_master where parent_id=:mid ";
			//$sql1.="and show_tag='T' ";
			$sql1.="order by mid";
            $sth1 = $conn->prepare($sql1);
			$sth1->bindParam(':mid', $mid);
			$sth1->execute();
			$row1 = $sth1->fetchAll();
			foreach ($row1 as $key1 => $value1)
			{ 
				$mbody1=$value1['mbody'];
				$mid1=$value1['mid'];
            ?>
            <tr><td></td>
            <td> 
            <?php
            for($l=0;$l<count($page_pars_temp); $l++)
            {
            if ($page_pars_temp[$l]==$mid1) 
            {
            $flag="checked";
            break;
            }
            else
            $flag="";
            }
            $sql2 ="select count(*) as totm ";
			$sql2.="from menu_master where mid=:mid and show_tag='T'   ";
            $sth2 = $conn->prepare($sql2);
			$sth2->bindParam(':mid', $mid);
			$sth2->execute();
			$row2 = $sth2->fetch();
            $tot2=$row2['totm'];
            if($tot2>0) echo "<input type='CHECKBOX' name='mitem[]' value='$mid1' $flag >&nbsp;&nbsp;$mbody1";
            else echo "&nbsp;$mbody1";
            echo  "</td>";
            $sql3="select * ";
			$sql3.="from menu_master where parent_id=:mid1 and show_tag='F' order by mid";
             $sth3 = $conn->prepare($sql3);
			 $sth3->bindParam(':mid1', $mid1);
			 $sth3->execute();
			 $row3 = $sth3->fetchAll();
             $tot3=count($row3);
            if($tot3 >0)
            echo "<td></td>";
            echo "<td colspan='3'><font color='#0000FF'><b>&nbsp;";
            foreach ($row3 as $key3 => $value3) 
			{
            	$mbody3=$value3['mbody'];
            	$midch3=$value3['mid'];
            for($l=0;$l<count($page_pars_temp); $l++)
            {
            if ($page_pars_temp[$l]==$midch3)
            {
            $flag="checked";
            break;
            }
            else
            $flag="";
            }
            
            echo "<input type='CHECKBOX' name='mitem[]' value='$midch3' $flag >&nbsp;".$mbody3."&nbsp;";
            
            }
            if($tot3 >0)
            echo "&nbsp;&nbsp;</b></font>";
            echo "</td>
            </tr>";
            
            }
            
            
            ?>
            
            
            <?php  
            }
            ?>
            </table>
              
            </div>
            <!-- /.box-body -->
             <div class="box-footer">
                
                <input type="submit" name="submit" id="submit" class="btn btn-primary pull-right" value="Submit">
              </div>
              <input type="hidden" name="hid_hr_id" value="<?php echo $hr_id;?>" />
           </form>
          </div>
          <!-- /.box -->
        </div>
      </div>   
 <?php
 }
 ?>        
<?php include('./footer.php'); ?>     
