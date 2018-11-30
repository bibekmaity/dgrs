<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php');
?>

<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">Transfer List</h3>
</div>
<!-- /.box-header -->
<form name="form1" method="post" enctype="multipart/form-data" >
<input type="hidden" value="<?php echo $ses_uid; ?>" id="hid_uid">
<div class="box-body" style="margin-top: 5px;">    
<table id="example1" class="table table-bordered table-striped" >
<thead>
<tr>

<th>Mobile No</th>
<th class="one">Complaintner</th>
<th  class="one">Address</th>
<th>Comp No</th>
<!--<th>Comp Date</th> -->
<th>Comp Type</th>
<th class="one">Comp Desc</th>
<!--<th>Comp To</th> -->
<th class="one">Officer Remarks</th> 
<th class="one">Document</th>
<th><i class="fa fa-caret-square-o-down" aria-hidden="true"></i></th>
</tr>
</thead>
<tbody>
<?php

$sql1="select f.flt_id,f.rmn,f.dkt_no,f.dkt_date,f.comp_desc,c.citizen_nm,b.block_nm_ben";
$sql1.=" ,p.ps_nm_ben,f.comp_img,cm.comp_type_eng,dm.dept_nm,f.refer_by,f.refer_to ";
$sql1.=",f.refer_date,f.addr,f.street,f.para,f.village,f.landmark,f.refer_rmk,f.doc_upload  ";
$sql1.=" from flt_mas f, citizen_mas c, block_mas b, ps_mas p, compl_type_mas cm ";
$sql1.=" , dept_mas dm where 1=1 ";
$sql1.=" and f.comp_type_id=cm.comp_type_id ";
$sql1.=" and c.rmn=f.rmn ";
$sql1.=" and f.block_id=b.block_id ";
$sql1.=" and f.ps_id=p.ps_id and f.dept_id=dm.dept_id ";

if(!empty($ses_sub_div_id))
{
	$sql1.=" and f.sub_div_id=:ses_sub_div_id ";
}
if(!empty($ses_block_id))
{
	$sql1.=" and f.block_id=:ses_block_id ";
}
if(!empty($ses_ps_id))
{
	$sql1.=" and f.ps_id=:ses_ps_id ";
}
if($ses_user_type=="D")
{
	$sql1.=" and f.comp_type_id in($ses_comp_type_id) ";
//	$sql1.=" and f.refer_to is NULL ";
}
if($ses_user_type=="B")
{
	$sql1.=" and f.refer_to=:ses_uid ";	
}
$sql1.=" and f.dist_id=:ses_dist_id ";
$sql1.=" ORDER BY f.refer_to, f.dkt_date,f.dkt_no DESC ";
$sth = $conn->prepare($sql1);
$sth->bindParam(':ses_dist_id', $ses_dist_id);
if(!empty($ses_sub_div_id))
{
	$sth->bindParam(':ses_sub_div_id', $ses_sub_div_id);
}
if(!empty($ses_block_id))
{
	$sth->bindParam(':ses_block_id', $ses_block_id);
}
if(!empty($ses_ps_id))
{
	$sth->bindParam(':ses_ps_id', $ses_ps_id);
}
if($ses_user_type=="B")
{
	$sth->bindParam(':ses_uid', $ses_uid);
}
if($ses_user_type=="D")
{
//	$sth->bindParam(':ses_comp_type_id', $ses_comp_type_id);
}
$sth->execute();
$ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
$row1 = $sth->fetchAll();
foreach ($row1 as $key => $value1) 
{
	$flt_id=$value1['flt_id'];
	$rmn=$value1['rmn'];
	$dkt_no=$value1['dkt_no'];
	$dkt_date1=$value1['dkt_date'];
	$comp_desc=$value1['comp_desc'];
	$citizen_nm=$value1['citizen_nm'];
	$block_nm_ben=$value1['block_nm_ben'];
	$ps_nm_ben=$value1['ps_nm_ben'];
	$comp_type=$value1['comp_type_eng'];
	$dept_nm=$value1['dept_nm'];
	$comp_img=$value1['comp_img'];
	$refer_by=$value1['refer_by'];
	$refer_to=$value1['refer_to'];
	$refer_date=$value1['refer_date'];
	$refer_rmk=$value1['refer_rmk'];
	$doc_upload=$value1['doc_upload'];
	
	if(empty($refer_by))
	$refer_by=0;
	if(empty($refer_to))
	$refer_to=0;
	
	$addr=$value1['addr'];
	$street=$value1['street'];
	$para=$value1['para'];
	$village=$value1['village'];
	$landmark=$value1['landmark'];
	
	$street1="";
	$para1="";
	$village1="";
	$landmark1="";
	$address="";
	
	if(!empty($street))
	$street1="Street:$street ";

	if(!empty($village))
	$village1="Village:$village ";

	if(!empty($landmark))
	$landmark1="Landmark:$landmark ";
	
	$address="$addr &nbsp;$street1<br>$village1&nbsp;$landmark1 ";
	
	$dkt_date=british_to_ansi(substr($dkt_date1,0,10));
	$dkt_time=substr($dkt_date1,11,5);
	$dkt_dt="$dkt_date<br>$dkt_time ";
	
	$ref_date=british_to_ansi(substr($refer_date,0,10));
	$ref_time=substr($refer_date,11,5);
	
	$ref_dt=" ";
	if(!empty($ref_to))
	{
		$ref_dt=$ref_date.' '.$ref_time;
	}
				
	$sqlR="select user_nm from user_mas WHERE 1=1 ";
	$sqlR.=" and uid=:refer_to ";
	$sth_search = $conn->prepare($sqlR);
	$sth_search->bindParam(':refer_to', $refer_to);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$ref_to=$row_search['user_nm'];

	$sqlR="select user_nm from user_mas WHERE 1=1 ";
	$sqlR.=" and uid=:refer_by ";
	$sth_search = $conn->prepare($sqlR);
	$sth_search->bindParam(':refer_by', $refer_by);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$ref_by=$row_search['user_nm'];

	$dkt_info="Refer Date | Refer By | Refer To | Remarks \n";
	
	$sqlR="select r.refer_date,r.remarks,r.refer_to,u.user_nm from refer_mas r, user_mas u ";
	$sqlR.="WHERE dkt_no=:dkt_no and r.refer_by=u.uid ";
	$sqlR.=" ORDER BY refer_id DESC ";
	$sth_search = $conn->prepare($sqlR);
	$sth_search->bindParam(':dkt_no', $dkt_no);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$rowR = $sth_search->fetchAll();
	$ref_total=$sth_search->rowCount();
	//echo $ref_total;
	foreach ($rowR as $key => $valueR) 
	{	
		$refer_date1=$valueR['refer_date'];
		$ref_from=$valueR['user_nm'];
		$remarks=$valueR['remarks'];
		$refer_to=$valueR['refer_to'];
		$refer_date=british_to_ansi(substr($refer_date1,0,10));
		
		$sqlR="select user_nm from user_mas WHERE 1=1 ";
		$sqlR.=" and uid=:refer_to ";
		$sth_search = $conn->prepare($sqlR);
		$sth_search->bindParam(':refer_to', $refer_to);
		$sth_search->execute();
		$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
		$row_search = $sth_search->fetch();
		$ref_to=$row_search['user_nm'];
				
		$dkt_info.="$refer_date | $ref_from |  $ref_to | $remarks \n";
	}
	$ext = pathinfo($doc_upload, PATHINFO_EXTENSION);
	?>
	<tr> 
	<td><?php echo $rmn;?></td>
	<td class="one"><?php echo $citizen_nm;?></td>
	<td class="one"><?php echo $address;?></td>
	<!--<td><?php echo $dkt_no;?></td>-->
	<?php
	if($ref_total==0)
	{
		?>
		<td valign="top" nowrap="nowrap" align="center" title="<?php echo "$dkt_info";
    ?>" style="cursor:pointer" ><?php echo "$dkt_no <br>$dkt_dt"; ?></td> 
		<?php 
	}
	else
	{
		?>
		<td valign="top" nowrap="nowrap" align="center" title="<?php echo "$dkt_info";
    ?>" style="cursor:pointer" ><a id="<?php echo md5($flt_id); ?>" class="dok_info"><?php echo "$dkt_no <br>$dkt_dt"; ?></a></td> 
		<?php 
	}
	?>
       
	<!--<td nowrap="nowrap" align="center"><?php //echo $dkt_dt;?></td>-->
	<td><?php echo $comp_type;?></td>
	<td class="one"><?php echo $comp_desc;?></td>
	<!--<td><?php //echo $dept_nm;?></td> -->
	<td class="one"><?php echo $refer_rmk;?></td>
	<td align="center" class="one">
	<?php 
	if(!empty($doc_upload))
    {
		$image_file = array("jpg", "jpeg", "gif", "png");
		if(in_array($ext,$image_file))
		{
			?>
			<a href="javascript:void(0);" class="imageresource" id="<?php echo md5($flt_id); ?>" alt="<?php echo $citizen_nm; ?>" title="<?php echo $dkt_no;?>">
			  <i class="fa fa-photo" aria-hidden="true" ></i>
			</a>	
			<?php
		}
		else
		{
			?>
			<a href="./download.php?nama=<?php echo $doc_upload;?>" title="<?php echo $dkt_no;?>">
			  <i class="fa fa-paperclip" aria-hidden="true" ></i>
			</a>
            <?php	
		}
    }
	if(!empty($comp_img))
	{
		?>
			<a href="javascript:void(0);" class="imageresource" id="<?php echo md5($flt_id); ?>" alt="<?php echo $citizen_nm; ?>" title="<?php echo $dkt_no;?>">
			  <i class="fa fa-photo" aria-hidden="true" ></i>
			</a>	
			<?php
	}
    ?>
	</td>
	<td><a href="javascript:void(0);" id="<?php echo md5($flt_id); ?>" class="inv_list" title="For Transfer">
	    <i class="fa fa-exchange" aria-hidden="true" ></i></a>
        <?php
		if($ses_restore_access=='Y' or $ses_user_type=='A')
		{
			?>
            <a href="javascript:void(0);" id="<?php echo md5($flt_id); ?>" class="inv_clear" title="For Disposal">
            	<i class="fa fa-check-circle" aria-hidden="true" ></i>
            </a>
            <?php
		}
		?>
	</td>
	</tr>
	<?php
}

?>
</tbody>
<tfoot>
<tr>
<th>Mobile No</th>
<th class="one">Complaintner</th>
<th class="one">Address</th>
<th>Comp No</th>
<!--<th>Comp Date</th> -->
<th>Comp Type</th>
<th class="one">Comp Desc</th>
<!--<th>Comp To</th> -->
<th class="one">Officer Remarks</th> 
<th class="one">Document</th>
<th><i class="fa fa-caret-square-o-up" aria-hidden="true"></i></th>
</tr>
</tfoot>
</table>
</div>
</form>
</div>
</div>
</div>
</section>
<div id="info"></div>
<style>
.modal {
display: none; /* Hidden by default */
position: fixed; /* Stay in place */
z-index: 1000; /* Sit on top */
padding-top: 10px; /* Location of the box */
left: 0;
top: 0;
width: 100%; /* Full width */
height: 100%; /* Full height */
overflow: auto; /* Enable scroll if needed */
background-color: rgb(0,0,0); /* Fallback color */
background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
.example-modal .modal {
position: relative;
top: auto;
bottom: auto;
right: auto;
left: auto;
display: block;
z-index: 1;
}

.example-modal .modal {
background: transparent !important;
}

</style>
<div id="myModal" class="modal">

</div>
<style>
@media only screen and (max-width: 800px) {
  .one
  {     
	 display: none;
  }
  
}
</style>    
<script type="text/javascript">	

$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}



var modal = document.getElementById('myModal');
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];
$( ".imageresource").click(function() {
	
    var img =$(this).attr("id");
    var img_name =$(this).attr("alt");
    var request = $.ajax({
    url: "./back/refer_back.php",
    method: "POST",
    data: {img: img,img_name:img_name,tag: 'SHOW-PHOTO'  },
    dataType: "html",
    success:function(msg) {
    $("#myModal").html(msg);  

  },
  error: function(xhr, status, error) {
            alert(status);
            alert(xhr.responseText);
        },
  }); 
    modal.style.display = "block";
	
}); 
$( ".inv_list").click(function() {
	
    var hid_uid =$('#hid_uid').val();
	var myid=$(this).attr("id");
	//alert(myid);
    var request = $.ajax({
    url: "./back/refer_back.php",
    method: "POST",
    data: {myid: myid,hid_uid:hid_uid,tag: 'REF-NOTE'  },
    dataType: "html",
    success:function(msg) {
    $("#myModal").html(msg);  

  },
  error: function(xhr, status, error) {
            alert(status);
            alert(xhr.responseText);
        },
  }); 
    modal.style.display = "block";
}); 
$( ".dok_info").click(function() {
	
    var hid_uid =$('#hid_uid').val();
	var myid=$(this).attr("id");
	//alert(myid);
    var request = $.ajax({
    url: "./back/refer_back.php",
    method: "POST",
    data: { myid: myid ,hid_uid:hid_uid,tag: 'DOK-INFO'  },
    dataType: "html",
    success:function(msg) {
    $("#myModal").html(msg);  

  },
  error: function(xhr, status, error) {
            alert(status);
            alert(xhr.responseText);
        },
  }); 
    modal.style.display = "block";
});
$( ".inv_clear").click(function() {
	
    var hid_uid =$('#hid_uid').val();
	var myid=$(this).attr("id");
	//alert(myid);
   var request = $.ajax({
    url: "./back/restore_back.php",
    method: "POST",
    data: {myid: myid,hid_uid:hid_uid,tag: 'REST-NOTE'  },
    dataType: "html",
    success:function(msg) {
    $("#myModal").html(msg);  

  },
  error: function(xhr, status, error) {
            alert(status);
            alert(xhr.responseText);
        },
  }); 
    modal.style.display = "block";
});  
	</script>
<?php 
include('./footer.php'); 
?>