<?php
  require_once('secure.php');
  $person = $user['usid'];
  include('database.php');
  include('scripts/select_admins.php');

  $time_stamp= date("m/d/Y", strtotime("now"));
  $year =date("Y", strtotime("now"));
 ?>

<!DOCTYPE HTML>

<html lang="en">
<head>
  <style>
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

<body>

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
                    <li><a href="metrics.php">Metrics General</a></li>
                    <li class="active"><a href="metrics_group.php">Metrics Groups</a></li>
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
    <center><legend><h1><b>Standards Assignments by Group - <?echo $time_stamp?></b>
    </h1></legend></center>
    <br><br>
    </div>

<?
	$sql="SELECT ID, DATE_ASSIGNED, EXPECTED_COMPLETION, STATUS, COMPLETED, COMPLETED_DATE, DTE_GROUP,
    					 substr(COMPLETED_DATE, 0, 2) AS COMPLETEDMONTH,
						 substr(COMPLETED_DATE, 7, 8) AS COMPLETEDYEAR
						 FROM OEP_DASHBOARD 
						 WHERE COMPLETED ='1'
             AND DTE_GROUP != 'null' --also can use is not null--
						 AND substr(COMPLETED_DATE, 7, 8) = '{$year}'";

	$month_arr=array();
	$dteGroup_arr=array();
	$monthCounter_arr=array();
  $groupCounter_arr=array();

	while ($row = $db->fetch($sql)) 
	{
		array_push($month_arr, $row['COMPLETEDMONTH']);
		array_push($dteGroup_arr, $row['DTE_GROUP']);
	}
	// print_r($month_arr);
	// echo '<br>';
	// print_r($dteGroup_arr);

	$i=0;

	while ($i<sizeof($month_arr)){
		if($month_arr[$i]=='01')
		{
			$monthCounter_arr[0]++;

      //0-4 are january
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[0]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[1]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[2]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[3]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[4]++;
      }
      else {

      }

		}
		else if($month_arr[$i]=='02')
		{
			$monthCounter_arr[1]++;

      //5-9 are february
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[5]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[6]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[7]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[8]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[9]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='03')
		{
			$monthCounter_arr[2]++;

      //10-14 are march
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[10]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[11]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[12]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[13]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[14]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='04')
		{
			$monthCounter_arr[3]++;

      //15-19 are april
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[15]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[16]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[17]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[18]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[19]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='05')
		{
			$monthCounter_arr[4]++;

      //20-24 are may
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[20]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[21]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[22]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[23]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[24]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='06')
		{
			$monthCounter_arr[5]++;


      //25-29 are june
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[25]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[26]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[27]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[28]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[29]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='07')
		{
			$monthCounter_arr[6]++;

      //30-34 are july
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[30]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[31]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[32]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[33]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[34]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='08')
		{
			$monthCounter_arr[7]++;

      //35-39 are august
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[35]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[36]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[37]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[38]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[39]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='09')
		{
			$monthCounter_arr[8]++;

      //40-44 are september
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[40]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[41]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[42]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[43]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[44]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='10')
		{
			$monthCounter_arr[9]++;

      //45-49 are october
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[45]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[46]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[47]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[48]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[49]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='11')
		{

			$monthCounter_arr[10]++;

      //50-54 are november
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[50]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[51]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[52]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[53]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[54]++;
      }
      else {

      }
		}
		else if($month_arr[$i]=='12')
		{

			$monthCounter_arr[11]++;

      //55-59 are december
      if($dteGroup_arr[$i] == 'DCOE') 
      {
          $groupCounter_arr[55]++;
      }

      else if ($dteGroup_arr[$i] == 'SO') 
      {
          $groupCounter_arr[56]++;
      }

      else if ($dteGroup_arr[$i] == 'P&D') 
      {
          $groupCounter_arr[57]++;
      }

       else if ($dteGroup_arr[$i] == 'OEP') 
       {
          $groupCounter_arr[58]++;
      }

      else if ($dteGroup_arr[$i] == 'Other') 
      {
          $groupCounter_arr[59]++;
      }
      else {

      }
		}
		$i++;
	}
	// echo "<br>";
	// print_r($monthCounter_arr);
 //  echo "<- This is the total month counter index 0-12";
 //  echo "<br>";

 //  print_r($groupCounter_arr);
 //  echo "<- This is the 5 bars in the graph";


  $monthlyPercentage = array();
  //calculate percentages for charts
  for($i=0;$i<60;$i++)
  {
      if($i<=4 && $monthCounter_arr[0] != 0)//check month and divison by zero
      {
        //monthly percentages for 5 groups in january
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[0])*100;
      }
      else if(($i>4 && $i<=9) && ($monthCounter_arr[1] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in february
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[1])*100;
      }
      else if(($i>9 && $i<=14) && ($monthCounter_arr[2] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in march
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[2])*100;
      }
      else if(($i>14 && $i<=19) && ($monthCounter_arr[3] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in april
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[3])*100;
      }
      else if(($i>19 && $i<=24) && ($monthCounter_arr[4] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in may
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[4])*100;
      }
      else if(($i>24 && $i<=29) && ($monthCounter_arr[5] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in june
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[5])*100;
      }
      else if(($i>29 && $i<=34) && ($monthCounter_arr[6] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in july
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[6])*100;
      }
      else if(($i>34 && $i<=39) && ($monthCounter_arr[7] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in august
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[7])*100;
      }
      else if(($i>39 && $i<=44) && ($monthCounter_arr[8] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in september
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[8])*100;
      }
      else if(($i>44 && $i<=49) && ($monthCounter_arr[9] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in october
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[9])*100;
      }
      else if(($i>49 && $i<=54) && ($monthCounter_arr[10] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in november
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[10])*100;
      }
      else if(($i>54 && $i<=59) && ($monthCounter_arr[11] != 0)){//check month and divison by zero

        //monthly percentages for 5 groups in december
        $monthlyPercentage[$i] = ($groupCounter_arr[$i]/$monthCounter_arr[11])*100;
      }

  }


  //  echo "<br>";

  // print_r($monthlyPercentage);
?>



 
     <div style="height:50%;" id="chart_standardAssignmentByGroup"></div>
  

  </body>


<script>

   $("#print_metrics").click(function () {
    print()
  });

  $(function(){
    var JAN_DCOE = <?echo(json_encode($monthlyPercentage[0]));?> ;var FEB_DCOE = <?echo(json_encode($monthlyPercentage[5]));?>; var MAR_DCOE = <?echo(json_encode($monthlyPercentage[10]));?>;
    var APR_DCOE = <?echo(json_encode($monthlyPercentage[15]));?>;var MAY_DCOE = <?echo(json_encode($monthlyPercentage[20]));?>;var JUN_DCOE = <?echo(json_encode($monthlyPercentage[25]));?>;
    var JUL_DCOE = <?echo(json_encode($monthlyPercentage[30]));?>;var AUG_DCOE = <?echo(json_encode($monthlyPercentage[35]));?>;var SEP_DCOE = <?echo(json_encode($monthlyPercentage[40]));?>;
    var OCT_DCOE = <?echo(json_encode($monthlyPercentage[45]));?>;var NOV_DCOE = <?echo(json_encode($monthlyPercentage[50]));?>;var DEC_DCOE = <?echo(json_encode($monthlyPercentage[55]));?>;
    
    var JAN_SO = <?echo(json_encode($monthlyPercentage[1]));?> ;var FEB_SO= <?echo(json_encode($monthlyPercentage[6]));?>; var MAR_SO = <?echo(json_encode($monthlyPercentage[11]));?>;
    var APR_SO = <?echo(json_encode($monthlyPercentage[16]));?>;var MAY_SO= <?echo(json_encode($monthlyPercentage[21]));?>;var JUN_SO = <?echo(json_encode($monthlyPercentage[26]));?>;
    var JUL_SO = <?echo(json_encode($monthlyPercentage[31]));?>;var AUG_SO= <?echo(json_encode($monthlyPercentage[36]));?>;var SEP_SO = <?echo(json_encode($monthlyPercentage[41]));?>;
    var OCT_SO = <?echo(json_encode($monthlyPercentage[46]));?>;var NOV_SO= <?echo(json_encode($monthlyPercentage[51]));?>;var DEC_SO = <?echo(json_encode($monthlyPercentage[56]));?>;

    var JAN_PD = <?echo(json_encode($monthlyPercentage[2]));?> ;var FEB_PD = <?echo(json_encode($monthlyPercentage[7]));?>; var MAR_PD = <?echo(json_encode($monthlyPercentage[12]));?>;
    var APR_PD = <?echo(json_encode($monthlyPercentage[17]));?>;var MAY_PD = <?echo(json_encode($monthlyPercentage[22]));?>;var JUN_PD = <?echo(json_encode($monthlyPercentage[27]));?>;
    var JUL_PD = <?echo(json_encode($monthlyPercentage[32]));?>;var AUG_PD = <?echo(json_encode($monthlyPercentage[37]));?>;var SEP_PD = <?echo(json_encode($monthlyPercentage[42]));?>;
    var OCT_PD = <?echo(json_encode($monthlyPercentage[47]));?>;var NOV_PD = <?echo(json_encode($monthlyPercentage[52]));?>;var DEC_PD = <?echo(json_encode($monthlyPercentage[57]));?>;

    var JAN_OEP = <?echo(json_encode($monthlyPercentage[3]));?> ;var FEB_OEP = <?echo(json_encode($monthlyPercentage[8]));?>; var MAR_OEP = <?echo(json_encode($monthlyPercentage[13]));?>;
    var APR_OEP = <?echo(json_encode($monthlyPercentage[18]));?>;var MAY_OEP = <?echo(json_encode($monthlyPercentage[23]));?>;var JUN_OEP = <?echo(json_encode($monthlyPercentage[28]));?>;
    var JUL_OEP = <?echo(json_encode($monthlyPercentage[33]));?>;var AUG_OEP = <?echo(json_encode($monthlyPercentage[38]));?>;var SEP_OEP = <?echo(json_encode($monthlyPercentage[43]));?>;
    var OCT_OEP = <?echo(json_encode($monthlyPercentage[48]));?>;var NOV_OEP = <?echo(json_encode($monthlyPercentage[53]));?>;var DEC_OEP = <?echo(json_encode($monthlyPercentage[58]));?>;

    var JAN_OTHER = <?echo(json_encode($monthlyPercentage[4]));?> ;var FEB_OTHER = <?echo(json_encode($monthlyPercentage[9]));?>; var MAR_OTHER = <?echo(json_encode($monthlyPercentage[14]));?>;
    var APR_OTHER = <?echo(json_encode($monthlyPercentage[19]));?>;var MAY_OTHER = <?echo(json_encode($monthlyPercentage[24]));?>;var JUN_OTHER = <?echo(json_encode($monthlyPercentage[29]));?>;
    var JUL_OTHER = <?echo(json_encode($monthlyPercentage[34]));?>;var AUG_OTHER = <?echo(json_encode($monthlyPercentage[39]));?>;var SEP_OTHER = <?echo(json_encode($monthlyPercentage[44]));?>;
    var OCT_OTHER = <?echo(json_encode($monthlyPercentage[49]));?>;var NOV_OTHER = <?echo(json_encode($monthlyPercentage[54]));?>;var DEC_OTHER = <?echo(json_encode($monthlyPercentage[59]));?>;


    Highcharts.setOptions({
   	 	 colors: ['#5175E6', '#46AC44', '#DBDA4F', '#D33A3A', '#6C6868'],
    	 });


    var date = new Date();
    var year = date.getFullYear();

    var chart1 = new Highcharts.Chart({
        chart: {
            type: 'column',
            renderTo: 'chart_standardAssignmentByGroup',
            height: 750
        },
        title: {
            text: 'Monthly Assignments by Group ' + year,
            style:{
                fontWeight: 'bold',
                fontSize: '22px'
            }
        },
        subtitle: {
            text: 'Source: OEP DASHBOARD'
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
            max: 100,
            title: {
                text: 'Groups Completed (%)'
            },

        },
          legend: {
              enabled: true
          },

       tooltip: {
              enabled: true,
              color: '#FFFFFF',
              formatter: function () {
              return this.series.name + ': ' + Highcharts.numberFormat(this.y, 1)  +'%';
          },
              y: 10, // 10 pixels down from the top
            },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
            },
        },
        series: [{
          name: 'DCOE',
          data: [JAN_DCOE, FEB_DCOE, MAR_DCOE, APR_DCOE, MAY_DCOE, JUN_DCOE, JUL_DCOE, AUG_DCOE, SEP_DCOE, OCT_DCOE, NOV_DCOE, DEC_DCOE],


             dataLabels: {
              enabled: true,
              color: '#000000',
              rotation: '0',
              align: 'center',
              formatter: function () {
              return Highcharts.numberFormat(this.y,0);//numberformat function formats numbers to precision (object, axis, SETPRECISION)
          },
              y: -10, // 10 pixels down from the top
              style: {
                  fontSize: '9px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
        }, {
          name: 'SO',
          data: [JAN_SO, FEB_SO, MAR_SO, APR_SO, MAY_SO, JUN_SO, JUL_SO, AUG_SO, SEP_SO, OCT_SO, NOV_SO, DEC_SO],
     

             dataLabels: {
              enabled: true,
              color: '#000000',
              rotation: '0',
              align: 'center',
              formatter: function () {
              return Highcharts.numberFormat(this.y,0);//numberformat function formats numbers to precision (object, axis, SETPRECISION)
          },
              y: -10, // 10 pixels down from the top
              style: {
                  fontSize: '9px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
        },
        {
          name: 'P&D',
          data: [JAN_PD, FEB_PD, MAR_PD, APR_PD, MAY_PD, JUN_PD, JUL_PD, AUG_PD, SEP_PD, OCT_PD, NOV_PD, DEC_PD],
 

             dataLabels: {
              enabled: true,
              color: '#000000',
              rotation: '0',
              align: 'center',
              formatter: function () {
              return Highcharts.numberFormat(this.y,0);//numberformat function formats numbers to precision (object, axis, SETPRECISION)
          },
              y: -10, // 10 pixels down from the top
              style: {
                  fontSize: '9px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
        },
        {
          name: 'OEP',
          data: [JAN_OEP, FEB_OEP, MAR_OEP, APR_OEP, MAY_OEP, JUN_OEP, JUL_OEP, AUG_OEP, SEP_OEP, OCT_OEP, NOV_OEP, DEC_OEP],
       

             dataLabels: {
              enabled: true,
              color: '#000000',
              rotation: '0',
              align: 'center',
              formatter: function () {
              return Highcharts.numberFormat(this.y,0);//numberformat function formats numbers to precision (object, axis, SETPRECISION)
          },
              y: -10, // 10 pixels down from the top
              style: {
                  fontSize: '9px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
        },
        {
          name: 'Other',
          data: [JAN_OTHER, FEB_OTHER, MAR_OTHER, APR_OTHER, MAY_OTHER, JUN_OTHER, JUL_OTHER, AUG_OTHER, SEP_OTHER, OCT_OTHER, NOV_OTHER, DEC_OTHER],
          

          dataLabels: {
              enabled: true,
              color: '#000000',
              rotation: '0',
              align: 'center',
              formatter: function () {
              return Highcharts.numberFormat(this.y,0);//numberformat function formats numbers to precision (object, axis, SETPRECISION)
          },
              y: -10, // 10 pixels down from the top
              style: {
                  fontSize: '9px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
        }]

    });
  })
  </script>
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="http://code.highcharts.com/highcharts.js"></script>