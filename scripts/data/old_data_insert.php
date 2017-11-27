<?
include('../database.php');

$myfile = fopen("file/oep_old_data.txt", "r") or die("Unable to open file!");
// Output one line until end-of-file
while(!feof($myfile)) {
  $line = explode(",", fgets($myfile));
  print_r($line);
  echo "<br>";

  $sql = "INSERT INTO OEP_DASHBOARD (ID, ASSIGNED_TO, DATE_ASSIGNED, EXPECTED_COMPLETION, SECTION_NUM, PRIORITY,
  CONTACT, DATE_RECORDED, CAD_ASSIGNED_TO, ACTIONS_TAKEN, ON_HOLD, STATUS, COMPLETED, COMPLETED_DATE, ASSIGNMENT, ISSUE_DESC) 
    VALUES (seq_oep.nextval, '{$line[0]}', '{$line[1]}', '{$line[2]}', '{$line[3]}', '{$line[5]}', '{$line[6]}', '{$line[8]}', '{$line[9]}', '{$line[10]}', '{$line[11]}', '{$line[12]}', '0', '', '{$line[7]}', '{$line[4]}')";
  $db->query($sql);
}
fclose($myfile);
?>