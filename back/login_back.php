<?php
header("X-XSS-Protection: 1;mode = block");
include("../inc/dblib.inc.php");
$conn = OpenDB();
?>


<?php
$tag = isset($_POST['tag']) ? $_POST['tag'] : '';

if(($tag=='CITIZEN'))
{
    $myid=$_POST['myid'];

    $sql_search=" select citizen_nm from citizen_mas where ";
    $sql_search.=" rmn=:myid ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':myid', $myid);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	
	$citizen_nm=$row_search['citizen_nm'];
	echo "$citizen_nm";
}
?>
<?php
if(($tag=='OTP'))
{
    $mobile=$_POST['mobile'];
	$name=$_POST['name'];
	$hid_lang=$_POST['hid_lang'];
    $otp=mt_rand(1000, 9999);
	$sqle =" select lang_id,english,bengali ";
	$sqle.="from language_mas ";
	$sqle.=" order by lang_id  " ;
	$sthc = $conn->prepare($sqle);
	$sthc->execute();
	$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
	$rowc = $sthc->fetchAll();
	foreach ($rowc as $keyc => $rowe) 
	{
		$english[]=$rowe['english'];
		$bengali[]=$rowe['bengali'];
		
	}
	if($hid_lang=='B')
	{
		$reotp=$bengali[4];
		$eotp=$bengali[2];
		$submit=$bengali[5];
	}
	else
	{
		$reotp=$english[4];
		$eotp=$english[2];
		$submit=$english[5];
	}
    $sql_search=" select count(*) as cnt from citizen_mas where ";
    $sql_search.=" rmn=:mobile ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':mobile', $mobile);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$cnt=$row_search['cnt'];
	if($cnt>0)
	{
		
		
		$sqlO=" select count(*) as total from otp_mas where ";
	    $sqlO.=" cell_no=:mobile ";
		$sthO = $conn->prepare($sqlO);
		$sthO->bindParam(':mobile', $mobile);
		$sthO->execute();
		$ssO=$sthO->setFetchMode(PDO::FETCH_ASSOC);
		$rowO = $sthO->fetch();
		$total=$rowO['total'];
		if($total>0)
		{
			$sql=" update otp_mas set otp_no=:otp ";
			$sql.=" where cell_no=:mobile "; 
			$sth = $conn->prepare($sql);
			$sth->bindParam(':mobile', $mobile);
			$sth->bindParam(':otp', $otp);
			$sth->execute();
			send_sms($mobile,"Your OTP No: ".$otp);
		}
		else
		{
			
			$sql=" insert into otp_mas (cell_no,otp_no) ";
			$sql.=" values (:mobile,:otp) "; 
			$sth = $conn->prepare($sql);
			$sth->bindParam(':mobile', $mobile);
			$sth->bindParam(':otp', $otp);
			$sth->execute();

			send_sms($mobile,"Your OTP No: ".$otp);

		}

		$sqli=" update citizen_mas set citizen_nm=:name ";
		$sqli.=" where rmn=:mobile "; 
		$sthi = $conn->prepare($sqli);
		$sthi->bindParam(':mobile', $mobile);
		$sthi->bindParam(':name', $name);
		$sthi->execute();
		
	}
	else
	{
		$sqli=" insert into citizen_mas (citizen_nm,rmn) ";
		$sqli.=" values (:name,:mobile) "; 
		$sthi = $conn->prepare($sqli);
		$sthi->bindParam(':mobile', $mobile);
		$sthi->bindParam(':name', $name);
		$sthi->execute();
		$sql=" insert into otp_mas (cell_no,otp_no) ";
		$sql.=" values (:mobile,:otp) "; 
		$sth = $conn->prepare($sql);
		$sth->bindParam(':mobile', $mobile);
		$sth->bindParam(':otp', $otp);
		$sth->execute();
		send_sms($mobile,"Your OTP No: ".$otp);
	}
	?>
 <script src="https://code.jquery.com/jquery-1.9.1.js" integrity="sha384-+GtXzQ3eTCAK6MNrGmy3TcOujpxp7MnMAi6nvlvbZlETUcZeCk7TDwvlCw9RiV6R" crossorigin="anonymous"></script>    
    <div class="row">
      <div class="col-xs-12">
        <input type="button" name="reotp" autocomplete="off" id="reotp" autocomplete="off" value="<?php echo $reotp; ?>"  class="btn btn-primary btn-block btn-flat">
      </div>
    </div>
    
      <div class="form-group has-feedback" style="margin-top: 5px;">
        <input type="text" name="eotp" id="eotp" autocomplete="off"  class="form-control" placeholder="<?php echo $eotp; ?>" tabindex="1">
      </div>
      <div class="row">
        <div class="col-xs-12" id="eotp_div"> 
        </div>
        <div class="col-xs-12" id="reotp_div"> 
        </div>
      </div>
<script>
$("#eotp").keyup(function()
{
	 var mobile=$('#mobile').val();
	 var eotp=$('#eotp').val();
	 var hid_lang=$('#hid_lang').val();
	 if(eotp!="")
    {
	   if(!/^[0-9]+$/.test(eotp))
	   {
		 if(hid_lang=='B')
		 {
			alertify.error("সঠিক OTP ইনপুট দয়া করে");
		 }
		 else
		 {
			alertify.error("Please input correct OTP");
		 }
		 $('#eotp').focus();
		 return false;
	   }
	}
	// alert(eotp);
	 if(eotp.length>3)
	 {
		 var request = $.ajax({
		  url: "./back/login_back.php",
		  method: "POST",
		  data: { eotp: eotp,hid_lang:hid_lang,mobile:mobile, tag: 'EOTP'  },
		  dataType: "html",
		  success:function( msg ) {

		  var name=msg.trim();
			$('#eotp_div').html(name);  
		},
		error: function(xhr, status, error) {
				alert(status);
				alert(xhr.responseText);
			},
		});
	}
});
$("#reotp").click(function(){
    var mobile=$('#mobile').val();
	var hid_lang=$('#hid_lang').val();
	if(mobile!="")
    {
	   if(!/^[0-9]+$/.test(mobile))
	   {
		 if(hid_lang=='B')
		 {
			alertify.error("আপনার মোবাইল নম্বর চেক করুন");
		 }
		 else
		 {
			alertify.error("Please check your mobile no");
		 }
		 $('#mobile').focus();
		 return false;
	   }
	 }
	 var request = $.ajax({
	  url: "./back/login_back.php",
	  method: "POST",
	  data: { mobile: mobile, tag: 'REOTP'  },
	  dataType: "html",
	  success:function( msg ) 
	  {
		 var name=msg.trim();
			$('#reotp_div').html(name);  
	  },
	  error: function(xhr, status, error) 
	  {
			alert(status);
			alert(xhr.responseText);
	  },
	});
});	
    
jQuery('#bengali').click( function() 
{
	$('[id="bengali"]').each(function() {
		if($(this).prop('checked'))
		{
	   		
			    $('#eotp').attr("placeholder", "<?php echo $bengali[2]; ?>");
			
			
			$('#otp').attr("value", "<?php echo $bengali[3]; ?>");
			$('#reotp').attr("value", "<?php echo $bengali[4]; ?>");
			$('#submit').attr("value", "<?php echo $bengali[5]; ?>");
			$('#hid_lang').attr("value", "B");
			
		}
		else
		{
	    	
			if(!$('#eotp').val())
	   		{ 
			    $('#eotp').attr("placeholder", "<?php echo $english[2]; ?>");
			}
			$('#otp').attr("value", "<?php echo $english[3]; ?>");
			$('#reotp').attr("value", "<?php echo $english[4]; ?>");
			$('#submit').attr("value", "<?php echo $english[5]; ?>");
	    }
	});
});  

   </script>   
    <?php
	
}
?>
<?php
if(($tag=='EOTP'))
{
    $mobile=$_POST['mobile'];
	$eotp=$_POST['eotp'];
	$hid_lang=$_POST['hid_lang'];
    $otp=mt_rand(1000, 9999);
	$sqle =" select lang_id,english,bengali ";
	$sqle.="from language_mas ";
	$sqle.=" order by lang_id  " ;
	$sthc = $conn->prepare($sqle);
	$sthc->execute();
	$ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
	$rowc = $sthc->fetchAll();
	foreach ($rowc as $keyc => $rowe) 
	{
		$english[]=$rowe['english'];
		$bengali[]=$rowe['bengali'];
		
	}
	if($hid_lang=='B')
	{
		$submit=$bengali[5];
		$check_otp="OTP চেক করুন";
	}
	else
	{
		$submit=$english[5];
		$check_otp="Check otp";
	}
    $sql_search=" select count(*) as cnt from otp_mas where ";
    $sql_search.=" cell_no=:mobile and otp_no=:eotp";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':mobile', $mobile);
	$sth_search->bindParam(':eotp', $eotp);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$cnt=$row_search['cnt'];
	if($cnt>0)
	{
		?>
		<input type="submit" name="submit" id="submit" value="<?php echo $submit; ?>"  class="btn btn-primary btn-block btn-flat">
		<?php
	}
	else
	{
		?>
        <script>
		
		alertify.error("<?php echo $check_otp; ?>");
		$('#eotp').css("border-color","#FF0000");
		$('#eotp').focus();
		</script>
        <?php
	}
	?>
	<script>
	jQuery('#bengali').click( function() 
	{
		$('[id="bengali"]').each(function() {
			if($(this).prop('checked'))
			{
				$('#submit').attr("value", "<?php echo $bengali[5]; ?>");
				$('#hid_lang').attr("value", "B");
			}
			else
			{
				$('#submit').attr("value", "<?php echo $english[5]; ?>");
		    }
		});
	});  
   </script>   
    <?php
}
?>
<?php
if(($tag=='REOTP'))
{
    $mobile=$_POST['mobile'];
    $otp=mt_rand(1000, 9999);
		
	$sql=" update otp_mas set otp_no=:otp ";
	$sql.=" where cell_no=:mobile "; 
	$sth = $conn->prepare($sql);
	$sth->bindParam(':mobile', $mobile);
	$sth->bindParam(':otp', $otp);
	$sth->execute();
	send_sms($mobile,"Your OTP No: ".$otp);
	?>
    <script>
	$('#eotp').focus();
	</script>
    <?php
}
?>
<?php
$conn=null;
?>