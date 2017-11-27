<?
/*This page is run everyday at a specific */
  require_once('../secure.php');
  include('../database.php');

  $sql="SELECT * FROM OEP_DASHBOARD ORDER BY ID";

	$STATUS_arr=array();
	$EXPECTED_COMPLETION_arr=array();
	$COMPLETED_DATE_arr=array();
	$ID_arr = array();
	$size_counter=0;

	while ($row = $db->fetch($sql)) {
		array_push($STATUS_arr, $row['STATUS']);

		//#If- value at expected/completed is not null then convert to year month day
		if(($row['EXPECTED_COMPLETION'] && $row['COMPLETED_DATE'])!=NULL)
		{
			array_push($EXPECTED_COMPLETION_arr, date("Y/m/d", strtotime($row['EXPECTED_COMPLETION'])));
			array_push($COMPLETED_DATE_arr, date("Y/m/d", strtotime($row['COMPLETED_DATE'])));
		}//#End if
		//#Else- push into array expected/completed with a null date
		else{
			array_push($EXPECTED_COMPLETION_arr, $row['EXPECTED_COMPLETION']);
			array_push($COMPLETED_DATE_arr, $row['COMPLETED_DATE']);
		}

		array_push($ID_arr, $row['ID']);
		$size_counter++;	
	}
	echo $size_counter;
	print_r($STATUS_arr);
	echo "<br>--------------------<br>";
	print_r($EXPECTED_COMPLETION_arr);
	echo "<br>--------------------<br>";
	print_r($COMPLETED_DATE_arr);
	echo "<br>--------------------<br>";
	print_r($ID_arr);
	echo "<br>--------------------<br>";



	for($i=0;$i<$size_counter;$i++)
	{
		if($COMPLETED_DATE_arr[$i] >$EXPECTED_COMPLETION_arr[$i])
		{
			$STATUS_arr[$i] = 'LATE';
		}
		else if ($COMPLETED_DATE_arr[$i] <= $EXPECTED_COMPLETION_arr[$i] && $COMPLETED_DATE_arr[$i] != NULL)
		{
			$STATUS_arr[$i] = 'ON TIME';
		}
		else{
			$STATUS_arr[$i] = NULL;
		}
	}
// echo "<br>--------------------<br>";
//	print_r($STATUS_arr);
		//$status = implode(",", $STATUS_arr);
		$i=0;
		while($i<$size_counter)
		{
			$sql_update_status = "UPDATE OEP_DASHBOARD SET STATUS = '{$STATUS_arr[$i]}' WHERE ID = '{$ID_arr[$i]}'";
			$db->query($sql_update_status);
			$i++;
		}

	
header('Location: ../metrics.php');
	
  ?>