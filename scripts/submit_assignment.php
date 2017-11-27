<?php
  require_once('../secure.php');
  include('../database.php');	//DataBase connection

$person = $user['usid']; // corresponds to the person logged in 

$today = date("m/d/y");                         // 01/01/1970

$date_assigned = $_POST['date_assigned'];

$Change_14days=date("m/d/Y", strtotime("$date_assigned + 14 days"));
$Change_21days=date("m/d/Y", strtotime("$date_assigned + 21 days"));
$Change_28days=date("m/d/Y", strtotime("$date_assigned + 28 days"));
$Change_45days=date("m/d/Y", strtotime("$date_assigned + 45 days"));

$expected_completion = date("m/d/Y");

$assigned_to = $_POST['assigned_to'];
if($_POST['pLevel'] == '2 weeks') {
        $expected_completion = $Change_14days;
		$priority = "1";
    } 
else if($_POST['pLevel'] == '3 weeks') {
       $expected_completion = $Change_21days;
    	$priority = "2";
    }
else if($_POST['pLevel'] == '4 weeks') {
       $expected_completion = $Change_28days;
		$priority =  "3";
    }
else if($_POST['pLevel'] == '45 days') {
       $expected_completion = $Change_45days;
		$priority = "4";
    }
else{
    $expected_completion = $_POST['date_expected'];
	$priority = $_POST['pLevel'];
}

//submit a file
$file_name = $_FILES["file"]["name"];

$file_result = " ";
if ($_FILES["file"]["error"] > 0)
{
$file_result .= "No File Uploaded";
$file_tesult .= "Error Code: ". $_FILES["file"]["error"] . "<br>";
} else {
  $file_result .= 
  "Upload: " . $_FILES["file"]["name"] . "<br>" .
  "Type: " . $_FILES["file"]["type"] . "<br>" .
  "Size: " . ($_FILES["file"]["size"] / 1024) . "Kb <br>" .
  "Temp File: " . $_FILES["file"]["tmp_name"] . "<br>" ;

move_uploaded_file($_FILES["file"]["tmp_name"], '../uploaded_files/' . $_FILES["file"]["name"]);

$file_result .= "File upload Successful";
}
echo $file_name;



$contact = $_POST['contact'];

$section_number = $_POST['sect_num'];

$issue_description = $_POST['issue_descript'];
$issue_description = str_replace("'", "&#39;", $issue_description); //replace single quotes with html code for quote because single quote is the escape char in oracle

$assignment = $_POST['assignment'];
$assignment = str_replace("'", "&#39;", $assignment); //replace single quotes with html code for quote because single quote is the escape char in oracle

$date_recorded = $_POST['date_recorded'];

$CAD_assigned = $_POST['cad_assigned_to'];

$GROUP = $_POST['group'];

if($_POST['cu_radio'] == 'Yes'){
    $CU_update = 'YES';
}else{
    $CU_update = 'NO';
}


//TESTING
// echo $assigned_to;
// echo $priority;
// echo $expected_completion;
// echo $contact;
// echo $section_number;
// echo $issue_description;
// echo $assignment;
// echo $date_recorded;
// echo $CAD_assigned;
// echo $action_taken; 
//echo $GROUP;

$sql= "INSERT INTO OEP_DASHBOARD (ID, ASSIGNED_TO, DATE_ASSIGNED, EXPECTED_COMPLETION, SECTION_NUM, PRIORITY, CONTACT, DATE_RECORDED, CAD_ASSIGNED_TO, STATUS, COMPLETED, COMPLETED_DATE, ASSIGNMENT, ISSUE_DESC, REQUESTEDEMAIL, CHANGELOG, DTE_GROUP, UPLOAD, CU_UPDATE) VALUES(seq_oep.nextval, '{$assigned_to}', '{$date_assigned}', '{$expected_completion}', '{$section_number}', '{$priority}', '{$contact}', '{$date_recorded}', '{$CAD_assigned}', 'ON TIME', '0', NULL, '{$assignment}', '{$issue_description}', NULL, NULL, '{$GROUP}', '{$file_name}','{$CU_update}')";

$db->query($sql);

header("Location: ../in_progress.php");

?>