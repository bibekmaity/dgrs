<?php
header("X-XSS-Protection: 1;mode = block");

include('./header.php'); 
$sql ="select current_date as c_date  ";
$sth = $conn->prepare($sql);
$sth->execute();
$rowe = $sth->fetch();
$c_date=$rowe['c_date'];

// Todays Faults
$sqlT ="select count(flt_id) as t_fault from flt_mas  ";
$sqlT.=" where SUBSTR(dkt_date,1,10)=:c_date  ";
$sqlT.=" and dist_id=:ses_dist_id ";
if(!empty($ses_sub_div_id))
{
$sqlT.=" and sub_div_id=:ses_sub_div_id ";
}
if(!empty($ses_block_id))
{
$sqlT.=" and block_id=:ses_block_id ";
}
if(!empty($ses_ps_id))
{
$sqlT.=" and ps_id=:ses_ps_id ";
}
if($ses_user_type=="D")
{
	$sqlT.=" and comp_type_id in($ses_comp_type_id) ";
//	$sql1.=" and f.refer_to is NULL ";
}
if($ses_user_type=="B")
{
	$sqlT.=" and refer_to=:ses_uid ";	
}

$sqlT.=" UNION ";
$sqlT.="select count(flt_id) as t_fault from flt_his  ";
$sqlT.=" where SUBSTR(dkt_date,1,10)=:c_date  ";
$sqlT.=" and dist_id=:ses_dist_id ";
if(!empty($ses_sub_div_id))
{
$sqlT.=" and sub_div_id=:ses_sub_div_id ";
}
if(!empty($ses_block_id))
{
$sqlT.=" and block_id=:ses_block_id ";
}
if(!empty($ses_ps_id))
{
$sqlT.=" and ps_id=:ses_ps_id ";
}
if($ses_user_type=="D")
{
//	$sqlT.=" and comp_type_id=:ses_comp_type_id";
//	$sql1.=" and f.refer_to is NULL ";
}
if($ses_user_type=="B")
{
	$sqlT.=" and refer_to=:ses_uid ";	
}
$sthT = $conn->prepare($sqlT);
$sthT->bindParam(':c_date', $c_date);
$sthT->bindParam(':ses_dist_id', $ses_dist_id);
if(!empty($ses_sub_div_id))
{
	$sthT->bindParam(':ses_sub_div_id', $ses_sub_div_id);
}
if(!empty($ses_block_id))
{
	$sthT->bindParam(':ses_block_id', $ses_block_id);
}
if(!empty($ses_ps_id))
{
	$sthT->bindParam(':ses_ps_id', $ses_ps_id);
}
if($ses_user_type=="B")
{
	$sthT->bindParam(':ses_uid', $ses_uid);
}
if($ses_user_type=="D")
{
	$sthT->bindParam(':ses_comp_type_id', $ses_comp_type_id);
}
$sthT->execute();
$rowT = $sthT->fetch();
$t_fault=$rowT['t_fault'];


// Todays Refer
$sqlTR ="select count(flt_id) as t_refer from flt_mas  ";
$sqlTR.=" where SUBSTR(refer_date,1,10)=:c_date  ";
$sqlTR.=" and dist_id=:ses_dist_id ";
if(!empty($ses_sub_div_id))
{
	$sqlTR.=" and sub_div_id=:ses_sub_div_id ";
}
if(!empty($ses_block_id))
{
	$sqlTR.=" and block_id=:ses_block_id ";
}
if(!empty($ses_ps_id))
{
	$sqlTR.=" and ps_id=:ses_ps_id ";
}
if($ses_user_type=="D")
{
	$sqlTR.=" and comp_type_id in($ses_comp_type_id)";
//	$sql1.=" and f.refer_to is NULL ";
}
if($ses_user_type=="B")
{
	$sqlTR.=" and refer_to=:ses_uid ";	
}
$sqlTR.=" UNION ";
$sqlTR.=" select count(flt_id) as t_refer from flt_his  ";
$sqlTR.=" where SUBSTR(refer_date,1,10)=:c_date ";
$sqlTR.=" and dist_id=:ses_dist_id ";
if(!empty($ses_sub_div_id))
{
	$sqlTR.=" and sub_div_id=:ses_sub_div_id ";
}
if(!empty($ses_block_id))
{
	$sqlTR.=" and block_id=:ses_block_id ";
}
if(!empty($ses_ps_id))
{
	$sqlTR.=" and ps_id=:ses_ps_id ";
}
if($ses_user_type=="D")
{
	$sqlTR.=" and comp_type_id in($ses_comp_type_id) ";
//	$sql1.=" and f.refer_to is NULL ";
}
if($ses_user_type=="B")
{
	$sqlTR.=" and refer_to=:ses_uid ";	
}
$sthTR = $conn->prepare($sqlTR);
$sthTR->bindParam(':c_date', $c_date);
$sthTR->bindParam(':ses_dist_id', $ses_dist_id);
if(!empty($ses_sub_div_id))
{
	$sthTR->bindParam(':ses_sub_div_id', $ses_sub_div_id);
}
if(!empty($ses_block_id))
{
	$sthTR->bindParam(':ses_block_id', $ses_block_id);
}
if(!empty($ses_ps_id))
{
	$sthTR->bindParam(':ses_ps_id', $ses_ps_id);
}
if($ses_user_type=="B")
{
	$sthTR->bindParam(':ses_uid', $ses_uid);
}
if($ses_user_type=="D")
{
//	$sthTR->bindParam(':ses_comp_type_id', $ses_comp_type_id);
}
$sthTR->execute();
$rowTR = $sthTR->fetch();
$t_refer=$rowTR['t_refer'];

// Todays Restore
$sqlTS ="select count(flt_id) as t_resolve from flt_his  ";
$sqlTS.=" where SUBSTR(close_date,1,10)=:c_date ";
$sqlTS.=" and dist_id=:ses_dist_id ";
if(!empty($ses_sub_div_id))
{
	$sqlTS.=" and sub_div_id=:ses_sub_div_id ";
}
if(!empty($ses_block_id))
{
	$sqlTS.=" and block_id=:ses_block_id ";
}
if(!empty($ses_ps_id))
{
	$sqlTS.=" and ps_id=:ses_ps_id ";
}
if($ses_user_type=="D")
{
	$sqlTS.=" and comp_type_id in($ses_comp_type_id) ";
//	$sql1.=" and f.refer_to is NULL ";
}
if($ses_user_type=="B")
{
	$sqlTS.=" and refer_to=:ses_uid ";	
}
$sthTS = $conn->prepare($sqlTS);
$sthTS->bindParam(':c_date', $c_date);
$sthTS->bindParam(':ses_dist_id', $ses_dist_id);
if(!empty($ses_sub_div_id))
{
	$sthTS->bindParam(':ses_sub_div_id', $ses_sub_div_id);
}
if(!empty($ses_block_id))
{
	$sthTS->bindParam(':ses_block_id', $ses_block_id);
}
if(!empty($ses_ps_id))
{
	$sthTS->bindParam(':ses_ps_id', $ses_ps_id);
}
if($ses_user_type=="B")
{
	$sthTS->bindParam(':ses_uid', $ses_uid);
}
if($ses_user_type=="D")
{
//	$sthTS->bindParam(':ses_comp_type_id', $ses_comp_type_id);
}
$sthTS->execute();
$rowTS = $sthTS->fetch();
$t_resolve=$rowTS['t_resolve'];


// Total Pending Faults
$sqlTP ="select count(flt_id) as t_pending from flt_mas  ";
$sqlTP.=" WHERE dist_id=:ses_dist_id ";
if(!empty($ses_sub_div_id))
{
$sqlTP.=" and sub_div_id=:ses_sub_div_id ";
}
if(!empty($ses_block_id))
{
$sqlTP.=" and block_id=:ses_block_id ";
}
if(!empty($ses_ps_id))
{
$sqlTP.=" and ps_id=:ses_ps_id ";
}
if($ses_user_type=="D")
{
	$sqlTP.=" and comp_type_id in($ses_comp_type_id) ";
//	$sql1.=" and f.refer_to is NULL ";
}
if($ses_user_type=="B")
{
	$sqlTP.=" and refer_to=:ses_uid ";	
}

$sthTP = $conn->prepare($sqlTP);
$sthTP->bindParam(':ses_dist_id', $ses_dist_id);
if(!empty($ses_sub_div_id))
{
	$sthTP->bindParam(':ses_sub_div_id', $ses_sub_div_id);
}
if(!empty($ses_block_id))
{
	$sthTP->bindParam(':ses_block_id', $ses_block_id);
}
if(!empty($ses_ps_id))
{
	$sthTP->bindParam(':ses_ps_id', $ses_ps_id);
}
if($ses_user_type=="B")
{
	$sthTP->bindParam(':ses_uid', $ses_uid);
}
if($ses_user_type=="D")
{
//	$sthTP->bindParam(':ses_comp_type_id', $ses_comp_type_id);
}
//echo "$sqlTP<br>DI:$ses_dist_id==>SD:$ses_sub_div_id==>BL:$ses_block_id==>PS:$ses_ps_id<br>";
$sthTP->execute();
$rowTP = $sthTP->fetch();
$t_pending=$rowTP['t_pending'];

$sqlTT ="select count(flt_id) as total_resolve from flt_his  ";
$sthTT = $conn->prepare($sqlTT);
$sthTT->execute();
$rowTT = $sthTT->fetch();
$total_resolve=$rowTT['total_resolve'];

$sqlTF ="select count(flt_id) as tf_fault from flt_mas  ";
$sqlTF.=" union ";
$sqlTF.="select count(flt_id) as tf_fault from flt_his  ";
$sthTF = $conn->prepare($sqlTF);
$sthTF->execute();
$rowTF = $sthTF->fetch();
$tf_fault=$rowTF['tf_fault'];
?>
<div class="row">
	<div class="col-lg-3 col-xs-3">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3></h3>

              <p>Today's Complaints :&nbsp;<?php echo "$t_fault"?></p>
            </div>
            <div class="icon">
              <i class="fa fa-battery-3" aria-hidden="true" style="opacity: .6;"></i>
             <!-- <i class="ion ion-pie-graph"></i>-->
            </div>
            <a href="./today-comp.php" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-3">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3></h3>

              <p>Today's Refer:&nbsp;<?php echo "$t_refer"; ?></p>
            </div>
            <div class="icon">
              <i class="fa fa-battery-empty" aria-hidden="true" style="opacity: .6;"></i>
             <!-- <i class="ion ion-bag"></i>-->
            </div>
            <a href="today-refer.php" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-3">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3> </h3>
              <p>Today's Resolved :&nbsp;<?php echo "$t_resolve"?></p>
            </div>
            <div class="icon">
              <i class="fa fa-battery-1" aria-hidden="true" style="opacity: .6;"></i>
              <!--<i class="ion ion-stats-bars"></i>-->
            </div>
            <a href="today-clear.php" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-3">
          <!-- small box -->
          <div class="small-box bg-fuchsia">
            <div class="inner">
              <h3> </h3>

              <p>Total Pending:&nbsp;<?php echo "$t_pending"?></p>
            </div>
            <div class="icon">
              <i class="fa fa-battery-full" aria-hidden="true" style="opacity: .6;"></i>
             <!-- <i class="ion ion-stats-bars"></i>-->
            </div>
            <a href="tot-pndg.php" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <!-- ./col -->
        
        <!-- ./col -->
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Complaints Grievance</h3>

              <div class="box-tools pull-right col-sm-3">
              <table border="0" width="100%">
              <tr>
              <td style="padding-left:5px; ">
              <div class="btn btn-block btn-danger btn-sm"> Complaints</div>
              </td>
              <td style="padding-left:5px; ">
              <div class="btn btn-block btn-success btn-sm">Resolved</div>
              </td>
              <td style="padding-left:5px; ">
              <div class="btn btn-block btn-info btn-sm">Refer</div>
              </td>
              </tr>
              </table>              
            </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
	$month_desc='';
	$month_total_fault='';
	$month_total_Resolve='';
	$month_total_pending='';
	$month_total_Refer='';
	$current_year=substr($c_date,0,4);
	$sqlM=" select mon_desc, mon_cd from month_mas ";
	$sthM = $conn->prepare($sqlM);
	$sthM->execute();
	$rowM = $sthM->fetchAll();
	foreach ($rowM as $keyM => $valueM) 
	{
		$mon_desc=$valueM['mon_desc'];
		$mon_cd=$valueM['mon_cd'];
		$month_desc .= '"'. $mon_desc.'",';
		
		$chk_month=$current_year.'-'.$mon_cd;
		/********* month wise fault *************/
		$sqlFL="select count(flt_id) as cnt from flt_mas ";
		$sqlFL.=" where SUBSTR(dkt_date,1,7)=:chk_month ";
		$sqlFL.=" and dist_id=:ses_dist_id ";
		if(!empty($ses_sub_div_id))
		{
			$sqlFL.=" and sub_div_id=:ses_sub_div_id ";
		}
		if(!empty($ses_block_id))
		{
			$sqlFL.=" and block_id=:ses_block_id ";
		}
		if(!empty($ses_ps_id))
		{
			$sqlFL.=" and ps_id=:ses_ps_id ";
		}
		if($ses_user_type=="D")
		{
			$sqlFL.=" and comp_type_id in($ses_comp_type_id) ";
		//	$sql1.=" and f.refer_to is NULL ";
		}
		if($ses_user_type=="B")
		{
			$sqlFL.=" and refer_to=:ses_uid ";	
		}
		
		$sqlFL.=" UNION ";
		$sqlFL.="select count(flt_id) as cnt from flt_his ";
		$sqlFL.=" where SUBSTR(dkt_date,1,7)=:chk_month ";
		$sqlFL.=" and dist_id=:ses_dist_id ";
		if(!empty($ses_sub_div_id))
		{
			$sqlFL.=" and sub_div_id=:ses_sub_div_id ";
		}
		if(!empty($ses_block_id))
		{
			$sqlFL.=" and block_id=:ses_block_id ";
		}
		if(!empty($ses_ps_id))
		{
			$sqlFL.=" and ps_id=:ses_ps_id ";
		}
		if($ses_user_type=="D")
		{
			$sqlFL.=" and comp_type_id in($ses_comp_type_id) ";
		//	$sql1.=" and f.refer_to is NULL ";
		}
		if($ses_user_type=="B")
		{
			$sqlFL.=" and refer_to=:ses_uid ";	
		}
		$sthFL = $conn->prepare($sqlFL);
		$sthFL->bindParam(':chk_month', $chk_month);
		$sthFL->bindParam(':ses_dist_id', $ses_dist_id);
		if(!empty($ses_sub_div_id))
		{
			$sthFL->bindParam(':ses_sub_div_id', $ses_sub_div_id);
		}
		if(!empty($ses_block_id))
		{
			$sthFL->bindParam(':ses_block_id', $ses_block_id);
		}
		if(!empty($ses_ps_id))
		{
			$sthFL->bindParam(':ses_ps_id', $ses_ps_id);
		}
		if($ses_user_type=="B")
		{
			$sthFL->bindParam(':ses_uid', $ses_uid);
		}
		if($ses_user_type=="D")
		{
		//	$sthFL->bindParam(':ses_comp_type_id', $ses_comp_type_id);
		}
		$sthFL->execute();
		$ss=$sthFL->setFetchMode(PDO::FETCH_ASSOC);
		$rowFL = $sthFL->fetch();
		$total_fault=$rowFL['cnt'];
		
		$month_total_fault .= '"'. $total_fault.'",';
		
		/********* month wise resolved *************/
		$sqlCL="select count(flt_id) as cnt from flt_his ";
		$sqlCL.=" where SUBSTR(close_date,1,7)=:chk_month  ";
		if(!empty($ses_sub_div_id))
		{
			$sqlCL.=" and sub_div_id=:ses_sub_div_id ";
		}
		if(!empty($ses_block_id))
		{
			$sqlCL.=" and block_id=:ses_block_id ";
		}
		if(!empty($ses_ps_id))
		{
			$sqlCL.=" and ps_id=:ses_ps_id ";
		}
		if($ses_user_type=="D")
		{
			$sqlCL.=" and comp_type_id in($ses_comp_type_id) ";
		//	$sql1.=" and f.refer_to is NULL ";
		}
		if($ses_user_type=="B")
		{
			$sqlCL.=" and refer_to=:ses_uid ";	
		}
		$sthCL = $conn->prepare($sqlCL);
		$sthCL->bindParam(':chk_month', $chk_month);
		if(!empty($ses_sub_div_id))
		{
			$sthCL->bindParam(':ses_sub_div_id', $ses_sub_div_id);
		}
		if(!empty($ses_block_id))
		{
			$sthCL->bindParam(':ses_block_id', $ses_block_id);
		}
		if(!empty($ses_ps_id))
		{
			$sthCL->bindParam(':ses_ps_id', $ses_ps_id);
		}
		if($ses_user_type=="B")
		{
			$sthCL->bindParam(':ses_uid', $ses_uid);
		}
		if($ses_user_type=="D")
		{
		//	$sthCL->bindParam(':ses_comp_type_id', $ses_comp_type_id);
		}
		$sthCL->execute();
		$ss=$sthCL->setFetchMode(PDO::FETCH_ASSOC);
		$rowCL = $sthCL->fetch();
		$total_Resolve=$rowCL['cnt'];
		
		$month_total_Resolve .= '"'. $total_Resolve.'",';
		
		/********* month wise refer *************/
		$sqlRF="select count(flt_id) as cnt from flt_mas  ";
		$sqlRF.=" where SUBSTR(refer_date,1,7)=:chk_month ";
		$sqlRF.=" and dist_id=:ses_dist_id ";
		if(!empty($ses_sub_div_id))
		{
			$sqlRF.=" and sub_div_id=:ses_sub_div_id ";
		}
		if(!empty($ses_block_id))
		{
			$sqlRF.=" and block_id=:ses_block_id ";
		}
		if(!empty($ses_ps_id))
		{
			$sqlRF.=" and ps_id=:ses_ps_id ";
		}
		if($ses_user_type=="D")
		{
			$sqlRF.=" and comp_type_id in($ses_comp_type_id) ";
		//	$sql1.=" and f.refer_to is NULL ";
		}
		if($ses_user_type=="B")
		{
			$sqlRF.=" and refer_to=:ses_uid ";	
		}
		/*
		$sqlRF.=" UNION ";
		$sqlRF.="select count(flt_id) as cnt from flt_his ";
		$sqlRF.=" where SUBSTR(refer_date,1,7)=:chk_month ";
		$sqlRF.=" and dist_id=:ses_dist_id ";
		if(!empty($ses_sub_div_id))
		{
			$sqlRF.=" and sub_div_id=:ses_sub_div_id ";
		}
		if(!empty($ses_block_id))
		{
			$sqlRF.=" and block_id=:ses_block_id ";
		}
		if(!empty($ses_ps_id))
		{
			$sqlRF.=" and ps_id=:ses_ps_id ";
		}
		if($ses_user_type=="D")
		{
			$sqlRF.=" and comp_type_id in($ses_comp_type_id) ";
		//	$sql1.=" and f.refer_to is NULL ";
		}
		if($ses_user_type=="B")
		{
			$sqlRF.=" and refer_to=:ses_uid ";	
		}
		*/
		$sthRF = $conn->prepare($sqlRF);
		$sthRF->bindParam(':chk_month', $chk_month);
		$sthRF->bindParam(':ses_dist_id', $ses_dist_id);
		if(!empty($ses_sub_div_id))
		{
			$sthRF->bindParam(':ses_sub_div_id', $ses_sub_div_id);
		}
		if(!empty($ses_block_id))
		{
			$sthRF->bindParam(':ses_block_id', $ses_block_id);
		}
		if(!empty($ses_ps_id))
		{
			$sthRF->bindParam(':ses_ps_id', $ses_ps_id);
		}
		if($ses_user_type=="B")
		{
			$sthRF->bindParam(':ses_uid', $ses_uid);
		}
		if($ses_user_type=="D")
		{
		//	$sthRF->bindParam(':ses_comp_type_id', $ses_comp_type_id);
		}
		$sthRF->execute();
		$ss=$sthRF->setFetchMode(PDO::FETCH_ASSOC);
		$rowRF = $sthRF->fetch();
		$total_Refer=$rowRF['cnt'];
		
		$month_total_Refer .= '"'. $total_Refer.'",';
		
	  }
      ?>
      <script src="<?php echo $full_url; ?>/plugins/chartjs/Chart.min.js"></script>
      <script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

   
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas);
   var barChartData = {
     labels: [<?php echo substr($month_desc,0,-1); ?>],
     
      datasets: [
        {
          label: "Total Patient",
          fillColor: "#dd4b39",
          strokeColor: "#dd4b39",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
           data: [<?php echo substr($month_total_fault,0,-1); ?>]
        },
        {
          label: "New Patient",
          fillColor: "rgba(60,141,188,0.9)",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
        
       data: [<?php echo substr($month_total_Resolve,0,-1); ?>]
        }
        ,
        {
          label: "New Patient",
          fillColor: "rgba(0,192,239,0.9)",
          strokeColor: "rgba(0,192,239,0.8)",
          pointColor: "#00c0ef",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
        
       data: [<?php echo substr($month_total_Refer,0,-1); ?>]
        }
      ]
    };

    barChartData.datasets[1].fillColor = "#00a65a";
    barChartData.datasets[1].strokeColor = "#00a65a";
    barChartData.datasets[1].pointColor = "#00a65a";
    var barChartOptions = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: true,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - If there is a stroke on each bar
      barShowStroke: true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth: 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing: 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing: 1,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to make the chart responsive
      responsive: true,
      maintainAspectRatio: true
    };

    barChartOptions.datasetFill = true;
    barChart.Bar(barChartData, barChartOptions);
  });
</script>
<?php
include('./footer.php');
?>