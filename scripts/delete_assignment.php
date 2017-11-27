<?php
require_once('mcl_Oci.php');
include('../database.php');

$id = $_POST['id'];

$sql = "DELETE FROM OEP_DASHBOARD WHERE ID = '{$id}'";

$db->query($sql);

header("Location: in_progress.php");
?>