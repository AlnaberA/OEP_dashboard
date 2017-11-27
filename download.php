<?
$filename = $_GET['file'];
$path = 'uploaded_files/'.$filename;

if (file_exists($path)){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($path).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    ob_clean();
    flush();
    readfile($path);
    exit;
}

else{
	?><script>
		alert('Error: File not found.');
	</script><?
}

?>