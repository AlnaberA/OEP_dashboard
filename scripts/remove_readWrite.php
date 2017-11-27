<?
	require_once('mcl_Oci.php');
	include('../database.php');
	$user_id = $_POST['user_id'];

	$sql = "DELETE FROM OEP_DASHBOARD_ADMINS WHERE USER_ID = '{$user_id}'";

	$db->query($sql);
?>