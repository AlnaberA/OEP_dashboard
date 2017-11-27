<?
	require_once('mcl_Oci.php');
	include('../database.php');
	$user_id = trim($_POST['id']);
	include('../secure.php');
	$person = $user['usid'];

	$contact = $_POST['contact'];
	$section_number = $_POST['section'];
	$issue_description = $_POST['issue'];
	$requested_email = $_POST['email'];

	// echo $contact;
	// echo $section_number;
	// echo $issue_description;
	// echo $name;
	// echo $requested_email;

	$sql = "INSERT INTO OEP_DASHBOARD (ID, ASSIGNED_TO, DATE_ASSIGNED, EXPECTED_COMPLETION, SECTION_NUM, PRIORITY, CONTACT, DATE_RECORDED, CAD_ASSIGNED_TO, STATUS, COMPLETED, COMPLETED_DATE, ASSIGNMENT, ISSUE_DESC, REQUESTEDEMAIL, CHANGELOG, DTE_GROUP, UPLOAD) VALUES(seq_oep.nextval, NULL, NULL, NULL, '{$section_number}', NULL, '{$contact}', NULL, NULL, 'REQUESTED', '0', NULL, NULL, '{$issue_description}', '{$requested_email}', NULL, NULL, NULL)";

	$db->query($sql);

	$sql_email = "SELECT EMAIL FROM OEP_DASHBOARD_ADMINS WHERE PERMISSIONS = 'RW'";
	$email_array = array();
	while($rw_email = $db->fetch($sql_email))
	{
		//print_r($rw_email);
		array_push($email_array, $rw_email['EMAIL']);
	}
	echo "<br>";
	print_r($email_array);


	//Email content
	$body = "<div style='font-size: 13pt;'><h3>Assignment Requested</h3>";
	$body .= "<p>".$name." has requested an assignment.<br><br>";
	$body .= "<a href='http://lnx825:63666/readWrite.php'>Click here to approve or deny (if it redirects you after logging in, go to the permissions page)</a></p><br>";

	//Header for email 'from'
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
	$headers .= 'From: OEP Dashboard' . "\r\n";
	
	//$test = "alaa.al-naber@dteenergy.com";
	$to = implode("\n", $email_array);
	mail($to, $name." Requested Assignment to the OEP Dashboard", $body, $headers);

	//Test mail
	//mail($test, $name."Requested Assignment to the OEP Dashboard", $body, $headers);
	
	header("Location: ../in_progress.php");
?>