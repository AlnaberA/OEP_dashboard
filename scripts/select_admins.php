<?
	require_once('mcl_Oci.php');
	include('database.php');	//DataBase connection

//Admins Permission
	$sql = "SELECT * FROM OEP_DASHBOARD_ADMINS WHERE PERMISSIONS = 'ADMIN' ORDER BY NAME";


	$admin_id = array();
	$admins = array();

	while($row = $db->fetch($sql)){   
	  array_push($admin_id, $row['USER_ID']);
	  array_push($admins, $row);
	} 

	$users = array();
	$sql = "SELECT * FROM OEP_DASHBOARD_ADMINS ORDER BY PERMISSIONS, NAME";
	while($row = $db->fetch($sql)){   
	  array_push($users, $row);
	} 


//Read/write Permission

	$sqlReadWrite = "SELECT * FROM OEP_DASHBOARD_ADMINS WHERE PERMISSIONS = 'RW' ORDER BY NAME";

	$readWrite_id = array();
	$readWrite = array();

while($row = $db->fetch($sqlReadWrite)){   
	  array_push($readWrite_id, $row['USER_ID']);
	  array_push($readWrite, $row);
	} 

	



?>