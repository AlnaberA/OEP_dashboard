<?
  require_once('secure.php');
  include('database.php');

$textfile = fopen("users.txt", "a+") or die("ERROR:Unable to open file.");

$informationArray = array();



$person = $user['usid'];

$today = date("l jS \of F Y h:i:s A");

$ip = $_SERVER['REMOTE_ADDR'];
// print_r($today);

// echo "<br>";

// echo gettype($person);
// echo $person;


array_push($informationArray, $person);
array_push($informationArray, $today);
array_push($informationArray, $ip);
print_r($informationArray);
$count= count($informationArray);

for($i=0;$i<$count;$i++){
fwrite($textfile, $informationArray[$i]."\n");
}

fwrite($textfile, "-------------"."\n");

fclose($textfile);





//header("Location: admin.php");
?>