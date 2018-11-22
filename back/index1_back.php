<?php
header("X-XSS-Protection: 1;mode = block");
include("../inc/dblib.inc.php");
$conn = OpenDB();
?>


<?php
$tag = isset($_POST['tag']) ? $_POST['tag'] : '';

if(($tag=='POLICE'))
{
	$block = isset($_POST['block']) ? $_POST['block'] : '';
	$hid_lang = isset($_POST['hid_lang']) ? $_POST['hid_lang'] : '';

	$sql_search=" select sub_div_id from block_mas where ";
	$sql_search.=" block_id=:block ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':block', $block);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$sub_div_id=$row_search['sub_div_id'];
	if($hid_lang=='B')
    {
      $police_sel="থানা নির্বাচন করুন";
    }
    else
    {
      $police_sel="Select Police Station";
    }
	?>
	<select name="police" id="police" class="form-control select2" style="width:100%;"  tabindex="5" >   
        <option value=""><?php echo $police_sel; ?></option>
          <?php
          $sqlf=" select ps_id,ps_nm,ps_nm_ben ";
          $sqlf.="from ps_mas where sub_div_id=:sub_div_id order by ps_id ";
          $sthf = $conn->prepare($sqlf);
          $sthf->bindParam(':sub_div_id', $sub_div_id);
          $sthf->execute();
          $ssf=$sthf->setFetchMode(PDO::FETCH_ASSOC);
          $rowf = $sthf->fetchAll();
          foreach ($rowf as $keyf => $rowd) 
          {
            $ps_id=$rowd['ps_id'];
            $ps_nm=$rowd['ps_nm'];
            $ps_nm_ben=$rowd['ps_nm_ben'];
            if($hid_lang=='B')
            {
              $ps=$ps_nm_ben;
            }
            else
            {
              $ps=$ps_nm;
            }
            ?>
             <option value="<?php echo $ps_id; ?>"><?php echo $ps; ?></option>
             <?php
            }
          ?>
       </select>
       <script>

  $(function () {
    $('.select2').select2()
  });
</script>  
	<?php 	
}
?>
<?php
if(($tag=='B-TYPE'))
{
  $book_type = isset($_POST['book_type']) ? $_POST['book_type'] : '';
  $hid_lang = isset($_POST['hid_lang']) ? $_POST['hid_lang'] : '';
//echo $hid_lang;
  if($hid_lang=='B')
  {  
    $comp_label="অভিযোগের ধরণ নির্বাচন করুন";
  }
  else
  {
    $comp_label="Complaint Type";
  }

if($book_type=='C')
{
   ?>
  <label for="<?php echo $comp_label; ?>" class="col-sm-4"><?php echo $comp_label; ?> <font color="#FF0000">*</font></label>
  <div class="col-sm-8">
    <select name="com_type" id="com_type" class="form-control select2" style="width:100%;"  tabindex="3">
      <option value=""><?php echo $comp_label; ?></option>    
      <?php
      $sqlf=" select comp_type_id,comp_type,comp_type_eng ";
      $sqlf.="from compl_type_mas where comp_pfx not in ('S') order by comp_type_id ";
      $sthf = $conn->prepare($sqlf);
      $sthf->execute();
      $ssf=$sthf->setFetchMode(PDO::FETCH_ASSOC);
      $rowf = $sthf->fetchAll();
      foreach ($rowf as $keyf => $rowd) 
      {
          $comp_type_id=$rowd['comp_type_id'];
          $comp_type=$rowd['comp_type'];
          $comp_type_eng=$rowd['comp_type_eng'];
          if($hid_lang=='B')
          {
            $comp=$comp_type;
          }
          else
          {
            $comp=$comp_type_eng;
          }
          ?>
          <option value="<?php echo $comp_type_id; ?>"><?php echo $comp; ?></option>
          
          <?php
      }
      ?>
     </select>
  </div>
  <?php   
}
else
{
   ?>
  <label for="Complaint Type" class="col-sm-4"></label>
  <div class="col-sm-8"><div class="form-control" id="com_type" style="border:none !important;"></div></div>
  <script type="text/javascript"> 
$(function () {
    $('.select2').select2()
  });
  </script>
  <?php 

}
  
}
?>

<?php
$conn=null;
?>