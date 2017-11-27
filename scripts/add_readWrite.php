<?
	require_once('mcl_Oci.php');
	include('../database.php');

	$name = trim($_POST['name']);
	$user_id = trim($_POST['user_id']);
	$permissions = $_POST['permissions'];

	$sql_email = "SELECT EMAIL_ID FROM EMPLOYEE@MAXIMO WHERE USER_ID = '{$user_id}'";
	$email = $prod->fetch($sql_email);

	$sql = "INSERT INTO OEP_DASHBOARD_ADMINS (NAME, USER_ID, PERMISSIONS, EMAIL) 
		    VALUES ('{$name}', '{$user_id}', '{$permissions}', '{$email['EMAIL_ID']}')";

	$db->query($sql);
?>