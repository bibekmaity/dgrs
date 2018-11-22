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
	$sql_search=" select sub_div_id from block_mas where ";
	$sql_search.=" block_id=:block ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':block', $block);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$sub_div_id=$row_search['sub_div_id'];
	
	?>
	<select name="police" id="police" class="form-control select2" style="width:100%;"  tabindex="8" >   
        <option value="">--Police Station--</option>
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
            ?>
             <option value="<?php echo $ps_id; ?>"><?php echo $ps_nm; ?></option>
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

if(($tag=='B-TYPE'))
{
  $book_type = isset($_POST['book_type']) ? $_POST['book_type'] : '';

if($book_type=='C')
{
   ?>
  <label for="Complaint Type" class="col-sm-4">Complaint Type <font color="#FF0000">*</font></label>
  <div class="col-sm-8">
    <select name="com_type" id="com_type" class="form-control select2" style="width:100%;"  tabindex="5">
      <option value="">Complaint Type</option>    
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
          ?>
          <option value="<?php echo $comp_type_id; ?>"><?php echo $comp_type_eng; ?></option>
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