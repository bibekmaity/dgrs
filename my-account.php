<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); 

$sql ="select * from user_mas ";
$sql.="where uid=:uid ";
$sth = $conn->prepare($sql);
$sth->bindParam(':uid', $ses_uid);
$sth->execute();
$sth->setFetchMode(PDO::FETCH_ASSOC);
$row2 = $sth->fetch();
$s_user_type=$row2['user_type'];
$s_user_nm=$row2['user_nm'];

?>
<div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header  with-border">
                    <h3 class="box-title">My Details</h3>
                </div>
                <form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()">
                  <input type="hidden" id="hid_uid" value="<?php echo $ses_uid; ?>"/>
                  <input type="hidden" name="csrftoken" id="csrftoken" value="<?php echo $ses_token; ?>" />
                  <input type="hidden" id="hid_id" value="<?php echo $ses_id; ?>"/>
                    <div class="box-body">
                      <div class="col-sm-6">
                         <div class="form-group  has-feedback">
                           <label for="User Name" class="col-sm-4">Name<font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                             <input type="text" name="user_nm" id="user_nm" class="form-control" placeholder="Name" value="<?php echo $s_user_nm; ?>" tabindex="5">
                             <span class="glyphicon glyphicon-user form-control-feedback"></span>
                           </div>
                        </div>
                        <div class="form-group has-feedback">
                          <label for="Password" class="col-sm-4">Password<font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                             <input type="password" name="password" id="password" class="form-control"  tabindex="13" autocomplete="off" required >
                             <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                           </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                         <div class="form-group  has-feedback">
                           <label for="User ID" class="col-sm-4">User ID<font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                              <input type="text" name="user_id" id="user_id" class="form-control" placeholder="User ID" readonly value="<?php echo $ses_user_id; ?>" tabindex="6">
                              <span class="glyphicon glyphicon-user form-control-feedback"></span>
                           </div>
                        </div>
                      </div>
                  </div>
                  <div class="box-footer">
                   <a href="./index.php"  class="btn btn-default">Cancel</a>
                    <input type="button" name="profile" id="profile" class="btn btn-primary pull-right" value="Submit" tabindex="13">
                  </div>
                </form>
            </div>
         </div>
       </div>   
       <div id="info"></div>
       <script src="<?php echo $full_url; ?>/js/register.js"></script>
<?php include('./footer.php'); ?>     
