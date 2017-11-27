<?php
require_once('mcl_Oci.php');
require_once('../secure.php');
include('../database.php');
$person = $user['usid']; // corresponds to the person logged in 

$id = $_POST['id_hidden'];


$today = date("m/d/y");                         // 01/01/1970
$today_changelog= date("m/d/Y", strtotime("now"));

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

$contact = $_POST['contact'];
$section_number = $_POST['sect_num'];
$issue_description = $_POST['issue_descript'];
$issue_description = str_replace("'", "&#39;", $issue_description); //replace single quotes with html code for quote because single quote is the escape char in oracle

$assignment = $_POST['assignment'];
$assignment = str_replace("'", "&#39;", $assignment); //replace single quotes with html code for quote because single quote is the escape char in oracle

$date_recorded = $_POST['date_recorded'];
$CAD_assigned = $_POST['cad_assigned_to'];
$changelog = $_POST['changelog'];
$change_notes = $_POST['change_notes'];
$GROUP = $_POST['group'];

if($_POST['cu_radio'] == 'Yes'){
    $CU_update = 'YES';
}else{
    $CU_update = 'NO';
}

// echo $assigned_to;
// echo $priority;
// echo $expected_completion;
// echo $contact;
// echo $section_number;
// echo $issue_description;
// echo $assignment;
// echo $date_recorded;
// echo $CAD_assigned;
// echo $id;

$sql_expected_completion = "SELECT EXPECTED_COMPLETION FROM OEP_DASHBOARD WHERE ID = '{$id}'";
$sql_completed_date = "SELECT COMPLETED_DATE FROM OEP_DASHBOARD WHERE ID = '{$id}'";

$expected = $db->fetch($sql_expected_completion);
$completed = $db->fetch($sql_completed_date);


$expected = date("Y/m/d", strtotime($expected_completion));
$completed = date("Y/m/d", strtotime($completed['COMPLETED_DATE']));

if($completed > $expected)
{
    $status = 'LATE';
}
else if($completed <= $expected)
{
    $status = 'ON TIME';
}

//echo $completed[0];
// echo $completed."<br>";
// echo $expected."<br>";
// echo $status;

$columns_changed = '';
$sql_current = "SELECT * FROM OEP_DASHBOARD WHERE ID = '{$id}'";
$current = $db->fetch($sql_current);

if ($current['CONTACT'] != $contact){
    $columns_changed .= 'Contact, ';
}

if ($current['ASSIGNED_TO'] != $assigned_to){
    $columns_changed .= 'Assigned To, ';
}

if ($current['DATE_ASSIGNED'] != $date_assigned){
    $columns_changed .= 'Date Assigned, ';
}

if ($current['PRIORITY'] != $priority){
    $columns_changed .= 'Priority, ';
}

if ($current['EXPECTED_COMPLETION'] != $expected_completion){
    $columns_changed .= 'Expected Completion, ';
}

if ($current['SECTION_NUM'] != $section_number){
    $columns_changed .= 'Section, ';
}

if ($current['ISSUE_DESC'] != $issue_description){
    $columns_changed .= 'Issue Description, ';
}

if ($current['ASSIGNMENT'] != $assignment){
    $columns_changed .= 'Resolution, ';
}

if ($current['DATE_RECORDED'] != $date_recorded){
    $columns_changed .= 'Date Recorded, ';
}

if ($current['CAD_ASSIGNED_TO'] != $CAD_assigned){
    $columns_changed .= 'Currently Assigned, ';
}


$columns_changed = substr($columns_changed, 0, -2);

if($change_notes == ''){
    $changelog = $current['CHANGELOG'].$today_changelog." ".$user['name']." | ".$columns_changed."&#13;&#10;&#13;&#10;";
}

else{
    $changelog = $current['CHANGELOG'].$today_changelog." ".$user['name']." | ".$columns_changed."&#13;&#10;".$change_notes."&#13;&#10;&#13;&#10;";
}

$sql_file_check = "SELECT UPLOAD, ID FROM OEP_DASHBOARD WHERE ID = '{$id}'";
$row = $db->fetch($sql_file_check);

$old_file = $row['UPLOAD'];

//Update file
$file_name = $_FILES["file"]["name"];

$file_result = " ";
if ($_FILES["file"]["error"] > 0)
{
$file_result .= "No File Uploaded";
$file_result .= "Error Code: ". $_FILES["file"]["error"] . "<br>";
} else {
  $file_result .= 
  "Upload: " . $_FILES["file"]["name"] . "<br>" .
  "Type: " . $_FILES["file"]["type"] . "<br>" .
  "Size: " . ($_FILES["file"]["size"] / 1024) . "Kb <br>" .
  "Temp File: " . $_FILES["file"]["tmp_name"] . "<br>" ;

move_uploaded_file($_FILES["file"]["tmp_name"], '../uploaded_files/' .$file_name);

$file_result .= "File upload Successful";
}

echo $file_result;

if($file_name == NULL)
{
$sql = "UPDATE OEP_DASHBOARD 
		SET ASSIGNED_TO ='{$assigned_to}',
            DATE_ASSIGNED ='{$date_assigned}', 
            EXPECTED_COMPLETION ='{$expected_completion}',
            SECTION_NUM ='{$section_number}',
            PRIORITY ='{$priority}',
            CONTACT ='{$contact}',
            DATE_RECORDED ='{$date_recorded}',
            CAD_ASSIGNED_TO ='{$CAD_assigned}',
            STATUS = '{$status}',
            ASSIGNMENT ='{$assignment}',
            ISSUE_DESC='{$issue_description}',
            CHANGELOG = '{$changelog}',
            DTE_GROUP = '{$GROUP}',
            CU_UPDATE = '{$CU_update}'
            WHERE ID = '{$id}'";
}
else
{
	$sql = "UPDATE OEP_DASHBOARD 
		SET ASSIGNED_TO ='{$assigned_to}',
            DATE_ASSIGNED ='{$date_assigned}', 
            EXPECTED_COMPLETION ='{$expected_completion}',
            SECTION_NUM ='{$section_number}',
            PRIORITY ='{$priority}',
            CONTACT ='{$contact}',
            DATE_RECORDED ='{$date_recorded}',
            CAD_ASSIGNED_TO ='{$CAD_assigned}',
            STATUS = '{$status}',
            ASSIGNMENT ='{$assignment}',
            ISSUE_DESC='{$issue_description}',
            CHANGELOG = '{$changelog}',
            DTE_GROUP = '{$GROUP}',
            UPLOAD = '{$file_name}',
            CU_UPDATE = '{$CU_update}'
            WHERE ID = '{$id}'";
}

$db->query($sql);

header("Location: ../in_progress.php");
?>