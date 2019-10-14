<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

#$dbconnecterror = FALSE;
#$dbh = NULL;

#require_once 'credentials.php';

#try{

#	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;

#	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
#	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

#}catch(Exception $e){
#	$dbconnecterror = TRUE;
#}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	} else {
		$complete = 0;
	}

	if (empty($_POST['finBy'])) {
		$finBy = null;
	} else {
		$finBy = $_POST['finBy'];
	}

#	if (!$dbconnecterror) {
#		try {
#			$sql = "INSERT INTO doList (complete, listItem, finishDate) VALUES (:complete, :listItem, :finishDate)";
#			$stmt = $dbh->prepare($sql);
#			$stmt->bindParam(":complete", $complete);
#			$stmt->bindParam(":listItem", $_POST['listItem']);
#			$stmt->bindParam(":finishDate", $finBy);
#			$response = $stmt->execute();


#			header("Location: index.php");

	#	} catch (PDOException $e) {
	#		header("Location: index.php?error=add");
#		}
#	} else {
#		header("Location: index.php?error=add");
#	}
#}
$url = "3.228.227.26/api/task.php?completed=$complete";

$data = array(/*"completed" => $complete ,*/ "taskName" => $listItem , /*"listID" => listID ,*/ "taskDate" => $finBy);
$data_json = json_encode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length:' .strlen($data_json)));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);
/*$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' .strlen($data_json)));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);
*/
if($httpcode==204) {
	header("Location: index.php");
}else{
	#echo(var_dump($data, $url);)
	header("Location: index.php?error=add");
	#var_dump($data, $url);
}
}

?>
