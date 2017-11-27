<?
	require_once('secure.php');
	$person = $user['usid'];
	include('database.php');
	include('scripts/select_admins.php');
	

	$sql = "SELECT * FROM OEP_DASHBOARD ORDER BY DATE_ASSIGNED";

	$time_stamp= date("m/d/Y", strtotime("now"));
	$today = date("Y/m/d", strtotime("now"));
	$total = 0;
	$not_assigned = 0;
	$active = 0;
	$active_late = 0;
	$active_ontime = 0;
	$completed = 0;
	$completed_late = 0;
	$completed_ontime = 0;
	$completed_lastweek_late = 0;
	$completed_lastweek_ontime = 0;
	$completed_twoweeks_late = 0;
	$completed_twoweeks_ontime = 0;
	$completed_threeweeks_late = 0;
	$completed_threeweeks_ontime = 0;
	$completed_fourweeks_late = 0;
	$completed_fourweeks_ontime = 0;
	$due_this_week = 0;

	$year = date("Y"); 
	while($row = $db->fetch($sql)){
		$total++;

		//if there is no one filled into the assigned to column, it is not assigned
		if(($row['DATE_ASSIGNED'] == '' && $row['EXPECTED_COMPLETION'] == '') && $row['COMPLETED'] == '0'){
			$not_assigned++;
		}

		//active if either the expected completion of date assigned is filled in and it is not completed
		else if(($row['DATE_ASSIGNED'] != '' || $row['EXPECTED_COMPLETION'] != '') && $row['COMPLETED'] == '0'){
			$active++;

			//if today is later than the expected completion date, it is late
			if ($today > date("Y/m/d", strtotime($row['EXPECTED_COMPLETION']))){
	          $active_late++;
	        }

	        else {
	          $active_ontime++;
	        }
		}

		else if ($row['COMPLETED'] == '1'){
			$completed++;

			//If completed in the last week.
			if (date("Y/m/d", strtotime($row['COMPLETED_DATE'])) >= date("Y/m/d", strtotime("now - 7 days"))) {

				//if the expected completion date is after the completed date
				if (date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) >= date("Y/m/d", strtotime($row['COMPLETED_DATE'])) ){
	          		$completed_lastweek_ontime++;
	          	}

	          	//if the expected completion date is before the completed date
	          	else if ( date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) <= date("Y/m/d", strtotime($row['COMPLETED_DATE'])) ) {
	          		$completed_lastweek_late++;
	          	}
	        }

	        //If completed between 1 and 2 weeks ago
	        else if (( date("Y/m/d", strtotime($row['COMPLETED_DATE'])) >= date("Y/m/d", strtotime("now - 14 days")) ) && ( date("Y/m/d", strtotime($row['COMPLETED_DATE'])) <= date("Y/m/d", strtotime("now - 8 days")) )){

		        if (date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) >= date("Y/m/d", strtotime($row['COMPLETED_DATE']))){
		        	$completed_twoweeks_ontime++;
		        }

		        else if (date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) <= date("Y/m/d", strtotime($row['COMPLETED_DATE']))) {
		        	$completed_twoweeks_late++;
		        }			        
	        }

	        //If completed between 2 and 3 weeks ago
	        else if (( date("Y/m/d", strtotime($row['COMPLETED_DATE'])) >= date("Y/m/d", strtotime("now - 21 days")) ) && ( date("Y/m/d", strtotime($row['COMPLETED_DATE'])) <= date("Y/m/d", strtotime("now - 15 days")) )){

		        if (date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) >= date("Y/m/d", strtotime($row['COMPLETED_DATE']))){
		        	$completed_threeweeks_ontime++;
		        }

		        else if (date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) <= date("Y/m/d", strtotime($row['COMPLETED_DATE']))) {
		        	$completed_threeweeks_late++;
		        }			        
	        }

	        //If completed between 3 and 4 weeks ago
	        else if (( date("Y/m/d", strtotime($row['COMPLETED_DATE'])) >= date("Y/m/d", strtotime("now - 28 days")) ) && ( date("Y/m/d", strtotime($row['COMPLETED_DATE'])) <= date("Y/m/d", strtotime("now - 22 days")) )){

		        if (date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) >= date("Y/m/d", strtotime($row['COMPLETED_DATE']))){
		        	$completed_fourweeks_ontime++;
		        }

		        else if (date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])) <= date("Y/m/d", strtotime($row['COMPLETED_DATE']))) {
		        	$completed_fourweeks_late++;
		        }			        
	        }

		}

		//if the expected completion date is after today, but before a week from now, it is due in the next week
		//EX:
		/*
			if |today is 2017/02/23 and expected completion is 2017/02/24| && 7 days from now is greater than expected completion date
		*/
		if ( ($today <= date("Y/m/d", strtotime($row['EXPECTED_COMPLETION']))) && (date("Y/m/d", strtotime("now + 7 days")) >= date("Y/m/d", strtotime($row['EXPECTED_COMPLETION']))) && $row['COMPLETED'] == '0' ){
			$due_this_week++;
		}

	}

	$completed_lastweek_total = $completed_lastweek_late + $completed_lastweek_ontime;
	$completed_twoweeks_total = $completed_twoweeks_late + $completed_twoweeks_ontime;
	$completed_threeweeks_total = $completed_threeweeks_late + $completed_threeweeks_ontime;
	$completed_fourweeks_total = $completed_fourweeks_late + $completed_fourweeks_ontime;



	//Correction for 0 completed in that week (divide by 0 error fix)
	if ($completed_lastweek_total == 0)
		$last_week = 0;

	else
		$last_week = ($completed_lastweek_ontime/$completed_lastweek_total)*100;


	if ($completed_twoweeks_total == 0)
		$two_week = 0;

	else
		$two_week = ($completed_twoweeks_ontime/$completed_twoweeks_total)*100;


	if ($completed_threeweeks_total == 0)
		$three_week = 0;

	else
		$three_week = ($completed_threeweeks_ontime/$completed_threeweeks_total)*100;


	if ($completed_fourweeks_total == 0)
		$four_week = 0;

	else
		$four_week = ($completed_fourweeks_ontime/$completed_fourweeks_total)*100;
	// echo "Total: ".$total."<br>";
	// echo "Not Assigned: ".$not_assigned."<br><br>";
	// echo "Active Total: ".$active."<br>";
	// echo "Active Late: ".$active_late."<br>";
	// echo "Active On Time: ".$active_ontime."<br><br>";

	// echo "Completed Total: ".$completed."<br>";
	// echo "Completed Late: ".$completed_late."<br>";
	// echo "Completed On Time: ".$completed_ontime."<br><br>";

	// echo "Completed in the last week total: ".$completed_lastweek_total."<br>";
	// echo "Completed in the last week late: ".$completed_lastweek_late."<br>";
	// echo "Completed in the last week on time: ".$completed_lastweek_ontime."<br><br>";

	// echo "Due this week: ".$due_this_week."<br>";
	
	// //Alaa's tests:
	// echo "------------------------------------------------------<br>";


	$completedDateArray = array();
	$statusArray = array();
	$sqlCompletedDate = "SELECT ID, ASSIGNED_TO, DATE_ASSIGNED, EXPECTED_COMPLETION, SECTION_NUM, PRIORITY, CONTACT, DATE_RECORDED, CAD_ASSIGNED_TO, STATUS, COMPLETED, COMPLETED_DATE, ASSIGNMENT, ISSUE_DESC, REQUESTEDEMAIL, CHANGELOG,
    					 substr(COMPLETED_DATE, 0, 2) AS COMPLETEDMONTH,
                                         substr(COMPLETED_DATE, 7, 8) AS COMPLETEDYEAR
						 FROM OEP_DASHBOARD 
						 WHERE COMPLETED ='1'
						 AND substr(COMPLETED_DATE, 7, 8) = '{$year}'";



	while($row = $db->fetch($sqlCompletedDate)){

		array_push($completedDateArray, substr($row['COMPLETED_DATE'], 0, 2));
		array_push($statusArray, $row['STATUS']);

	}

// 	//Testing
// 	print_r($completedDateArray);
// 	echo " |Size of completed date array: ";
// 	echo sizeof($completedDateArray);
// 	echo '<br>';
// 	print_r($statusArray);
// 	echo " |Size of Status array: ";
// 	echo sizeof($statusArray);



 //-Alaa- working on converting old code to new code (using array counters rather than variables)
// 	$month_arr_late=array();//months late
// 	$month_arr_onTime=array();//months on time
//
// 	$i=0;//i is the position to determine the month in the completeddate/status array
// 	$j=0;//j is the position to determine where to place the month in the ontime/late array
// 	while($i<sizeof($completedDateArray))
// 	{
// 		if($completedDateArray[$i]=='01')
// 		{
// 			$j=0;
// 			if($statusArray[$i] == 'ON TIME')
// 			{
// 				//$janOnTime++;
// 				$month_arr_onTime[$j]++;
// 			}
// 			else if($statusArray[$i] == 'LATE')
// 			{
// 				//$janLate++;
// 				$month_arr_late[$j]++;
// 			}
// 		}
// 		if($completedDateArray[$i]=='02')
// 		{
// 			$j=1;
// 			if($statusArray[$i] == 'ON TIME')
// 			{
// 				//$febOnTime++;
// 				$month_arr_onTime[$j]++;
// 			}
// 			else if($statusArray[$i] == 'LATE')
// 			{
// 				//$febLate++;
// 				$month_arr_late[$j]++;
// 			}
// 		}
// 		$i++;
// 	}
// 	echo '<br>';
// 	print_r($month_arr_late);
// 	echo '<br>';
// 	print_r($month_arr_onTime);




	$i=0;
	$janLate=0;$febLate=0;$marLate=0;$aprLate=0;$mayLate=0;$junLate=0;$julLate=0;$augLate=0;$sepLate=0;$octLate=0;$novLate=0;$decLate=0;
	$janOnTime=0;$febOnTime=0;$marOnTime=0;$aprOnTime=0;$mayOnTime=0;$junOnTime=0;$julOnTime=0;$augOnTime=0;$sepOnTime=0;$octOnTime=0;$novOnTime=0;$decOnTime=0;

	$janTotal=0;$febTotal=0;$marTotal=0;$aprTotal=0;$mayTotal=0;$junTotal=0;$julTotal=0;$augTotal=0;$sepTotal=0;$octTotal=0;$novTotal=0;$decTotal=0;
	
	while($i<sizeof($completedDateArray))
	{
		if($completedDateArray[$i]=='01')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$janOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$janLate++;
			}
			$janTotal++;
		}
		else if($completedDateArray[$i]=='02')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$febOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$febLate++;
			}
			$febTotal++;
		}
		else if($completedDateArray[$i]=='03')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$marOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$marLate++;
			}
			$marTotal++;
		}
		else if($completedDateArray[$i]=='04')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$aprOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$aprLate++;
			}
			$aprTotal++;
		}
		else if($completedDateArray[$i]=='05')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$mayOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$mayLate++;
			}
			$mayTotal++;
		}
		else if($completedDateArray[$i]=='06')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$junOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$junLate++;
			}
			$junTotal++;
		}
		else if($completedDateArray[$i]=='07')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$julOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$julLate++;
			}
			$julTotal++;
		}
		else if($completedDateArray[$i]=='08')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$augOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$augLate++;
			}
			$augTotal++;
		}
		else if($completedDateArray[$i]=='09')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$sepOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$sepLate++;
			}
			$sepTotal++;
		}
		else if($completedDateArray[$i]=='10')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$octOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$octLate++;
			}
			$octTotal++;
		}
		else if($completedDateArray[$i]=='11')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$novOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$novLate++;
			}
			$novTotal++;
		}
		else if($completedDateArray[$i]=='12')
		{
			if($statusArray[$i] == 'ON TIME')
			{
				$decOnTime++;
			}
			else if($statusArray[$i] == 'LATE')
			{
				$decLate++;
			}
			$decTotal++;
		}
		$i++;
	}
//ECHO $febLate;
//
//ECHO $janLate;
//        print_r($completedDateArray);
//        echo '<br>';
//        print_r($statusArray);
        
	$year_total = $janTotal+$febTotal+$marTotal+$aprTotal+$mayTotal+$junTotal+$julTotal+$augTotal+$sepTotal+$octTotal+$novTotal+$decTotal;
	$year_ontime = $janOnTime+$febOnTime+$marOnTime+$aprOnTime+$mayOnTime+$junOnTime+$julOnTime+$augOnTime+$sepOnTime+$octOnTime+$novOnTime+$decOnTime;
	$year_late = $janLate+$febLate+$marLate+$aprLate+$mayLate+$junLate+$julLate+$augLate+$sepLate+$octLate+$novLate+$decLate;

	if($janTotal != 0)
	{
		$janTotal = ($janOnTime/$janTotal)*100;
	}
	if($febTotal != 0)
	{
		$febTotal = ($febOnTime/$febTotal)*100;
	}
	if($marTotal != 0)
	{
		$marTotal = ($marOnTime/$marTotal)*100;
	}
	if($aprTotal != 0)
	{
		$aprTotal = ($aprOnTime/$aprTotal)*100;
	}
	if($mayTotal != 0)
	{
		$mayTotal = ($mayOnTime/$mayTotal)*100;
	}
	if($junTotal != 0)
	{
		$junTotal = ($junOnTime/$junTotal)*100;
	}
	if($julTotal != 0)
	{
		$julTotal = ($julOnTime/$julTotal)*100;
	}
	if($augTotal != 0)
	{
		$augTotal = ($augOnTime/$augTotal)*100;
	}
	if($sepTotal != 0)
	{
		$sepTotal = ($sepOnTime/$sepTotal)*100;
	}
	if($octTotal != 0)
	{
		$octTotal = ($octOnTime/$octTotal)*100;
	}
	if($novTotal != 0)
	{
		$novTotal = ($novOnTime/$novTotal)*100;
	}
	if($decTotal != 0)
	{
		$decTotal = ($decOnTime/$decTotal)*100;
	}

	$year_percentage = ($year_ontime/$year_total)*100;
?>



<!DOCTYPE HTML>

<html lang="en">



<head>
	<style>
	/*label tags are to fix browser disabling background colors when print button is clicked*/
	.label
	{
		color:white !important;
	}
        .white
	{
		color:white !important;
	}
	.label-success
	{  
		background-color: #5cb85c !important
	}
	.label-danger
	{
		background-color: #d9534f !important
	}
	.label-primary
	{
		background-color: #428bca !important
	}
	.label-info
	{
		background-color: #5bc0de !important
	}
	.label-warning
	{
		background-color: orange !important
	}
	.label-default
	{
		background-color: gray !important
	}

	/*resize page to fit metrics on tabloid size*/
	@media print {
    .panel-heading {
        display:none
    }
     body {
   		zoom:60%;
	}

	}
	</style>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">

	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  	<link rel="stylesheet" href="css/style.css">
</head>

<body class="index_Body">

<!--Navigation bar-->
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!--Navbar header-->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">OEP Dashboard</a>
      </div>

      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
         <?
        if(in_array($person, $readWrite_id)||in_array($person, $admin_id)){
          ?>
          <li><a href="index.php">New Assignment</a></li>
        <?}else {?>
          <li><a href="index.php">Request Assignment</a></li>
        <?}?>
          <li><a href="in_progress.php">In Progress</a></li>
          <li><a href="completed.php">Completed</a></li>
          <li class="dropdown active"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Metrics<span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li class="active"><a href="metrics.php">Metrics General</a></li>
                    <li><a href="metrics_group.php">Metrics Groups</a></li>
                  </ul>
          </li>

      	 <?if (in_array($person, $readWrite_id)||in_array($person, $admin_id)){?>
          <li><a href="readWrite.php">Permissions</a></li>
          <?}
              if (in_array($person, $admin_id)) { ?>
                  <li><a href="admin.php">Admin</a></li>
             <? } ?>


        </ul>
         <ul class="nav navbar-nav navbar-right">
             <ul class="nav navbar-nav navbar-brand">
                  	<div><button id="print_metrics" class="glyphicon glyphicon-print btn-md btn-success"></button></div>
             </ul>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <? echo $name ?><span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li style="padding-bottom: 5px"><center><?mcl_Header::logout_btn();?></center></li>
              </ul>
            </li>
          </ul>
      </div>
    </div>
  </nav>

  <center> 
  <div class="container" style="width: 75%;">
  	<legend><center><h1><b>Standards Assignment Metrics - <?echo $time_stamp?></b>
  	</h1></center></legend>
  	<br><br>

  	<div class="row">
	  	<div class="col-md-3">
		  	<h2>YEAR TO DATE - COMPLETED</h2>
		</div>

		<div class="col-md-3">
		  	<h2>LAST WEEK - COMPLETED</h2>
		</div>

		<div class="col-md-3">
		  	<h2>ACTIVE</h2>
		</div>
  	</div>
	

  	<h5>
  	<br><br>
  	<div class="row">
  		<div class="col-md-3">
                    <div style="font-size: 150%;" class="label label-success">ON TIME: <?echo $year_ontime?></div>
	  	</div>

	  	<div class="col-md-3">
                    <span style="font-size: 150%;" class="label label-success">ON TIME: <?echo $completed_lastweek_ontime?></span>
	  	</div>

	  	<div class="col-md-3">
                    <span style="font-size: 150%;" class="label label-success">ON TIME: <?echo $active_ontime?></span>
	  	</div>

	  	<div class="col-md-3">
                    <span style="font-size: 150%;padding-bottom: 7%" class="label label-default">NOT ACTIVE: <?echo $not_assigned?></span><br>
                    <p style="color:white;"><b class="white">*2017 Target = 18 Inactives</b></p>
	  	</div>
  	</div>

  	<br><br>

  	<div class="row">
  		<div class="col-md-3">
  			<span style="font-size: 150%;" class="label label-danger">LATE: <?echo $year_late?></span>
  		</div>

  		<div class="col-md-3">
  			<span style="font-size: 150%;" class="label label-danger">LATE: <?echo $completed_lastweek_late?></span>
  		</div>

  		<div class="col-md-3">
  			<span style="font-size: 150%;" class="label label-danger">LATE: <?echo $active_late?></span>
  		</div>

  		<div class="col-md-3">
  			<span style="font-size: 150%;" class="label label-warning">DUE THIS WEEK: <?echo $due_this_week?></span>
  		</div>
  	</div>

  	<br><br>

  	<div class="row">
  		<div class="col-md-3">
  			<span style="font-size: 150%;" class="label label-primary">TOTAL: <?echo $year_total?></span>
  		</div>

  		<div class="col-md-3">
  			<span style="font-size: 150%;" class="label label-primary">TOTAL: <?echo $completed_lastweek_total?></span>
  		</div>

  		<div class="col-md-3">
  			<span style="font-size: 150%;" class="label label-primary">TOTAL: <?echo $active?></span>
  		</div>
  	</div>
  	</h5>

  <?
  	$sql_group = "SELECT DTE_GROUP FROM OEP_DASHBOARD WHERE COMPLETED = '0'";

  	$group_array = array();

  	while ($row = $db->fetch($sql_group)) {
  		array_push($group_array, $row['DTE_GROUP']);
  	}
  	//print_r($group_array);

  	$dcoeCounter=0;
  	$soCounter=0;
  	$pdCounter=0;
  	$oepCounter=0;
  	$otherCounter=0;


  	for($i=0; $i<=sizeof($group_array);$i++){
  		if($group_array[$i]=='DCOE'){
  			$dcoeCounter++;
  		
  		}
  		else if($group_array[$i]=='SO'){
  			$soCounter++;
  		
  		}
  		else if($group_array[$i]=='P&D'){
  			$pdCounter++;
  		
  		}
  		else if($group_array[$i]=='OEP'){
  			$oepCounter++;
  		
  		}
  		else if($group_array[$i]=='Other'){
  			$otherCounter++;
  		}
  	}

  	// echo $dcoeCounter;
  	// echo $soCounter;
  	// echo $pdCounter;
  	// echo $oepCounter;
  	// echo $otherCounter;
  ?>

  <center>
  <div class="container">
  	<legend><center><h2>ACTIVE BY GROUP</h2></center></legend>
  	<div class="row">
  		<div class="col-sm-1"></div>
	  	<div class="col-sm-2">
	  		<div style="font-size: 150%;" class="label label-info">DCOE: <?echo $dcoeCounter?></div>
	  	</div>

	  	<div class="col-sm-2">
	  		<div style="font-size: 150%;" class="label label-info">SO: <?echo $soCounter?></div>
	  	</div>

	  	<div class="col-sm-2">
	  		<div style="font-size: 150%;" class="label label-info">P&D: <?echo $pdCounter?></div>
		</div>

	  	<div class="col-sm-2">
	  		<div style="font-size: 150%;" class="label label-info">OEP: <?echo $oepCounter?></div>
	  	</div>

	  	<div class="col-sm-2">
	  		<div style="font-size: 150%;" class="label label-info">Other: <?echo $otherCounter?></div>
	  	</div>
	  	<div class="col-sm-1"></div>
  	</div>
  </div>
  </center>

<br> 
  <div class="row">
  	<div class="col-md-9">
            <div id="chart_standardAssignment"></div>
  	</div>
        <div class="col-md-3">
            <div id="inactive_target_chart_standardAssignment"></div>
  	</div>
  </div>

  <br><br>
    <div class="row" style="padding-bottom: 8%">
        <div class="col-md-9">
            <div id="weekly_chart_standardAssignment"></div>
        </div>


        <div class="col-md-3">
                <div id="yearly_chart_standardAssignment"></div>
        </div>
    </div>
</div>
</body>

  
<!--Scripts Need to be moved to a different javascript file-->
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="http://code.highcharts.com/highcharts.js"></script>


  <script>
   $('#table').css('width', '50%');

   $("#print_metrics").click(function () {
    print()
});

  </script>




  <script type="text/javascript">
  
  	$(function(){

  		Highcharts.setOptions({
   	 	 colors: ['#5175E6']
    	 });

     	var date = new Date();
		var year = date.getFullYear();

		var chart1 = new Highcharts.Chart({
        chart: {
            type: 'column',
            renderTo: 'chart_standardAssignment',
            height: 450
        },
        title: {
            text: 'Monthly Compliance ' + year,
            style:{
                fontWeight: 'bold',
                fontSize: '22px'
            }
        },
        subtitle: {
            text: 'Source: /PROD/DQM/OEP_DASHBOARD'
        },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: 'Standards Completed (%)'
            },

                plotLines: [{
                    value: 90,
                    color: 'green',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Target'
                    }
                }, {
                    value: 85,
                    color: 'red',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Min'
                    }
                }, {
                    value: 100,
                    color: 'black',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Max'
                    }
                }]
        },
          legend: {
              enabled: false
          },

	  	 tooltip: {
	             enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,1);
			    },
	            y: 10, // 10 pixels down from the top
	          },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
            }
        },
        series: [{
            name: 'Monthly Compliance',
            data:  [
            ['Jan', <? echo $janTotal; ?>],
            ['Feb', <? echo $febTotal; ?>],
            ['Mar', <? echo $marTotal; ?>],
            ['Apr', <? echo $aprTotal; ?>],
            ['May', <? echo $mayTotal; ?>],
            ['Jun', <? echo $junTotal; ?>],
            ['Jul', <? echo $julTotal; ?>],
            ['Aug', <? echo $augTotal; ?>],
            ['Sep', <? echo $sepTotal; ?>],
            ['Oct', <? echo $octTotal; ?>],
            ['Nov', <? echo $novTotal; ?>],
            ['Dec', <? echo $decTotal; ?>]
            ],
	        dataLabels: {
	            enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,1);
			    },
	            y: 10, // 10 pixels down from the top
	            style: {
	                fontSize: '13px',
	                fontFamily: 'Verdana, sans-serif'
	            }
	        }

        }]
    });

		var chart2 = new Highcharts.Chart({
        chart: {
            type: 'column',
              renderTo: 'weekly_chart_standardAssignment',
              height: 450
        },
        title: {
            text: 'Weekly Compliance ' + year,
            style:{
                fontWeight: 'bold',
                fontSize: '22px'
            }
        },
        subtitle: {
            text: 'Source: /PROD/DQM/OEP_DASHBOARD'
        },
        xAxis: {
            categories: [
			   'Four Weeks Ago',
			   'Three Weeks Ago',
			   'Two Weeks Ago',
			   'Last Week'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: 'Standards Completed (%)'
            },

                plotLines: [{
                    value: 90,
                    color: 'green',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Target'
                    }
                }, {
                    value: 85,
                    color: 'red',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Min'
                    }
                }, {
                    value: 100,
                    color: 'black',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Max'
                    }
                }]
        },
          legend: {
              enabled: false
          },

   		tooltip: {
                 enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,1);
			    },
	            y: 10, // 10 pixels down from the top
          },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Weekly Compliance',
            data:  [
            ['4 Weeks Ago', <?echo $four_week;?>,],
            ['3 Weeks Ago', <?echo $three_week;?>],            
            ['2 Weeks Ago', <?echo $two_week;?>],            
            ['Last Week', <?echo $last_week;?>]
            ],
            dataLabels: {
	            enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,1);
			    },
	            y: 10, // 10 pixels down from the top
	            style: {
	                fontSize: '13px',
	                fontFamily: 'Verdana, sans-serif'
	            }
	        }

        }]
    });


	var chart3 = new Highcharts.Chart({
        chart: {
            type: 'column',
              renderTo: 'yearly_chart_standardAssignment',
              height: 450
        },
        title: {
            text: 'Yearly Compliance ' + year,
            style:{
                fontWeight: 'bold',
                fontSize: '22px'
            }
        },
        subtitle: {
            text: 'Source: /PROD/DQM/OEP_DASHBOARD'
        },
        xAxis: {
            categories: [
               'Year To Date'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: 'Standards Completed (%)'
            },

                plotLines: [{
                    value: 90,
                    color: 'green',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Target'
                    }
                }, {
                    value: 85,
                    color: 'red',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Min'
                    }
                }, {
                    value: 100,
                    color: 'black',
                    dashStyle: 'shortdash',
                    width: 1,
                    label: {
                        text: 'Max'
                    }
                }]
        },
          legend: {
              enabled: false
          },

  		 tooltip: {
                 enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,1);
			    },
	            y: 10, // 10 pixels down from the top
          },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Yearly Compliance',
            data:  [
            [<?echo $year;?>, <? echo $year_percentage; ?>],
            ],
            dataLabels: {
	            enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,1);
			    },
	            y: 10, // 10 pixels down from the top
	            style: {
	                fontSize: '13px',
	                fontFamily: 'Verdana, sans-serif'
	            }
	        }
        }]
    });
    
    var chart4 = new Highcharts.Chart({
        colors: ['#808080'],
        chart: {
            type: 'column',
              renderTo: 'inactive_target_chart_standardAssignment',
              height: 450
        },
        title: {
            text: 'Inactive Target ' + year,
            style:{
                fontWeight: 'bold',
                fontSize: '22px'
            }
        },
        subtitle: {
            text: 'Source: /PROD/DQM/OEP_DASHBOARD'
        },
        xAxis: {
            categories: [
               'Year To Date'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: 'Total Inactive'
            },

                plotLines: [{
                    value: 18,
                    color: 'red',
                    dashStyle: 'line',
                    width: 3,
                    label: {
                        text: 'Target',
                    }
                }]
        },
          legend: {
              enabled: false
          },

  		 tooltip: {
                 enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,0);
			    },
	            y: 10, // 10 pixels down from the top
          },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Active Target',
            data:  [
            [<?echo $year;?>, <? echo $not_assigned; ?>,],
            ],
            dataLabels: {
	            enabled: true,
	            rotation: -90,
	            color: '#FFFFFF',
	            align: 'right',
	            formatter: function () {
			        return Highcharts.numberFormat(this.y,0);
			    },
	            y: 10, // 10 pixels down from the top
	            style: {
	                fontSize: '13px',
	                fontFamily: 'Verdana, sans-serif'
	            }
	        }
        }]
    });

	});
  </script>
