<?
  	require_once('mcl_Oci.php');
  	$xdm = new mcl_Oci('xdm');
  	$prod = new mcl_Oci('prod');

	$sql = "SELECT * FROM OEP_DASHBOARD";

	$count = 0;
	while($row = $xdm->fetch($sql)){
		$insert = "INSERT INTO OEP_DASHBOARD (ID, ASSIGNED_TO, DATE_ASSIGNED, EXPECTED_COMPLETION, SECTION_NUM, PRIORITY, CONTACT, DATE_RECORDED, CAD_ASSIGNED_TO, STATUS, COMPLETED, COMPLETED_DATE, ASSIGNMENT, ISSUE_DESC, REQUESTEDEMAIL, CHANGELOG) VALUES(seq_oep.nextval, '{$row['ASSIGNED_TO']}', '{$row['DATE_ASSIGNED']}', '{$row['EXPECTED_COMPLETION']}', '{$row['SECTION_NUM']}', '{$row['PRIORITY']}', '{$row['CONTACT']}', '{$row['DATE_RECORDED']}', '{$row['CAD_ASSIGNED_TO']}', '{$row['STATUS']}', '{$row['COMPLETED']}', '{$row['COMPLETED_DATE']}', '{$row['ASSIGNMENT']}', '{$row['ISSUE_DESC']}', '{$row['REQUESTEDEMAIL']}', '{$row['CHANGELOG']}')";

		$prod->query($insert);

		$count++;
	}
	echo $count;

?>