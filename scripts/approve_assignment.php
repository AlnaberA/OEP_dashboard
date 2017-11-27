<?
	require_once('mcl_Oci.php');
	include('../database.php');
	include('../secure.php');
	$person = $user['usid'];

	$btn_id = $_POST['user_id'];

	//$sql_email = "SELECT EMAIL FROM OEP_DASHBOARD_ADMINS WHERE USER_ID = '{$user_id}'";
	$sql_email = "SELECT REQUESTEDEMAIL FROM OEP_DASHBOARD WHERE ID = '{$btn_id}'";
	$email = $db->fetch($sql_email);

	$sql = "UPDATE OEP_DASHBOARD 
			SET STATUS = 'ON TIME' 
			WHERE ID = '{$btn_id}'";

	$db->query($sql);

	//Email content
	$body = "<div style='font-size: 13pt;'><h3>Assignment Approved</h3>";
	$body .= "<p>Your assignment request to the OEP Dashboard has been approved.<br><br>";
	$body .= "<a href='http://lnx825:63666'>Click here to go to the dashboard</a></p><br>";

	//Header for email 'from'
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
	$headers .= 'From: OEP Dashboard' . "\r\n";
	
	mail($email['REQUESTEDEMAIL'], "OEP Dashboard Assignment Approved", $body, $headers);
?>