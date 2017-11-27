<?
include('../database.php');

$id = $_POST['id'];
$today = date("m/d/Y", strtotime("now"));

$sql = "UPDATE OEP_DASHBOARD 
        SET COMPLETED = '1',
            COMPLETED_DATE = '{$today}'
        WHERE ID = '{$id}'";

$db->query($sql);
?>