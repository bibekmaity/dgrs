<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header1.php'); ?>
<?php
$hr_id = isset($_REQUEST['hr_id']) ? $_REQUEST['hr_id'] : '';

$sql ="select department,officer_in_chrg,phone,mobile_no,email,schemes,status ";
$sql.=" FROM medinipur_data ";
$sql.="where md5(id)=:hr_id ";
$sth = $conn->prepare($sql);
$sth->bindParam(':hr_id', $hr_id);
$sth->execute();
$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetch();
$department=$row['department'];
$officer_in_chrg=$row['officer_in_chrg'];
$phone=$row['phone'];
$mobile_no=$row['mobile_no'];
$email=$row['email'];
$schemes=$row['schemes'];
$status=$row['status'];
?>
<div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header  with-border">
                    <h3 class="box-title">Other Information Details</h3>
                </div>
                <form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()">
                    <div class="box-body">
                      <div class="col-md-6">
                        <div class="form-group">
                           <label for="Department Name" class="col-sm-4">Department</label>
                           <div class="col-sm-8">
                             <input type="text" class="form-control" value="<?php echo $department; ?>">                             
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="Phone" class="col-sm-4">Phone</label>
                           <div class="col-sm-8">
                              <input type="text" class="form-control" value="<?php echo $phone; ?>">                                        
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="Mobile No" class="col-sm-4">Mobile No</label>
                           <div class="col-sm-8">
                             <input type="text" class="form-control"  value="<?php echo $mobile_no; ?>">                             
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="Email" class="col-sm-4">Email</label>
                           <div class="col-sm-8">
                              <input type="text" class="form-control" value="<?php echo $email; ?>">                                        
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="status" class="col-sm-4">Status</label>
                           <div class="col-sm-8">
                              <input type="text" class="form-control" value="<?php echo $status; ?>">                                        
                           </div>
                        </div>
                      </div>
                      <div class="col-md-6"> 
                        <div class="form-group">
                           <label for="Officer" class="col-sm-4">Officer</label>
                           <div class="col-sm-8">
                             <input type="text" class="form-control"  value="<?php echo $officer_in_chrg; ?>">                             
                           </div>
                        </div>
                        
                        <div class="form-group">
                           <label for="Schemes" class="col-sm-4">Schemes</label>
                           <div class="col-sm-8">
                            <textarea class="form-control" rows="8"><?php echo $schemes; ?></textarea>
                                           
                           </div>
                        </div>
                      </div>
                     
                  </div>
                  
                </form>
            </div>
         </div>
       </div>   
    
   
<?php 

include('./footer.php'); ?>     
