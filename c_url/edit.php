<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Declare the credentials to the database
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

	$listID = $_POST['listID'];

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
	$listItem = $_POST['listItem'];


	#if (!$dbconnecterror) {
	#	try {
	#		$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
	#		$stmt = $dbh->prepare($sql);
	#		$stmt->bindParam(":complete", $complete);
	#		$stmt->bindParam(":listItem", $listItem);
	#		$stmt->bindParam(":finishDate", $finBy);
	#		$stmt->bindParam(":listID", $listID);
	#
	#		$response = $stmt->execute();
	#
	#		header("Location: index.php");

	#	} catch (PDOException $e) {
	#		header("Location: index.php?error=edit");
	#
	#	}
	#} else {
	#	header("Location: index.php?error=edit");
	#}
#}

#?????
$url = "3.228.227.26/api/task.php?listID=$listID";

$data = array("completed"  => $complete , "taskName" => $listItem, /*"listID" => $listID , */  "taskDate" => $finBy);

$data_json = json_encode($data);
#$url = 3.228.227.26/api/task.php;

$ch = curl_init();
#Naner?
curl_setopt($ch, CURLOPT_URL, $url); #??????????????????????????????????????????????It just works?!????
#That's it? ??????? //I was wrong, as it turns out it works when there is no other code, but I dunno how to do that :|
#nani
#Oh My Why moe, no Shin Day Rue

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode ==204) {
	header("Location: index.php");
}else{
	header("Location: index.php?error=edit");
	}
}
#var_dump(data_json)
?>
