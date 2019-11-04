<?php
#so this will be a file that does some api magic oooooo spoooooky wooo weeeeeee wooooo. ##Also It would probably be a bad idea to include stuff in this file that we don't need, but then again that applies to everything.  #We got a thing that does json input, were gonna use that too# ##ini_set('display_errors', 1);
// Declare the credentials to the database
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Declare the credentials to the database
$dbconnecterror = FALSE;
$dbh = NULL;
require_once 'credentials.php';
try{

	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;

	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(Exception $e){
	$dbconnecterror = TRUE;
	http_response_code(419); #db not found or connected
	exit();
	//return500
}
//update a task.
if ($_SERVER['REQUEST_METHOD'] == "PUT") {
		if(array_key_exists('listID', $_GET)) {
		$listID = $_GET['listID'];
	}else{
		//no list idea//return 4xx error
		http_response_code(418); //tbd
		exit();
	}
	//decoding json body
	//ean example: example.com/api/task.php?listid=123, this would be a put request

	$task  = json_decode(file_get_contents('php://input'), true); #also need to check to see what fields are missing


	if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	} else {
		$taskName = FALSE;
	}
	if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate"];
	} else {
		$taskDate  = FALSE;
	}
	if (array_key_exists('completed', $task )) {
		$complete = $task["completed"];
	} else {
		$complete = FALSE;
	}


	if (!$dbconnecterror) {

			try {
				if ($complete == "true"){
					$complete = 1;
				}else{
					$complete = 0;
				}
	$sql = "UPDATE doList SET completed=:completed, listID=:listID, taskDate=:taskDate, taskName=:taskName WHERE listID=:listID";
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(":completed", $complete);
	$stmt->bindParam(":taskName", $taskName);
	$stmt->bindParam(":taskDate", $taskDate);
	$stmt->bindParam(":listID", $listID);
	$response = $stmt->execute();
	http_response_code(204);
	exit();
		#header("Location: index.php");

	} catch (PDOException $e) {
		/* return 500 message */
		http_response_code(504);
		var_dump($e);
		exit();
			}
	} else {
		/*return 500 message */
		http_response_code(545);
		exit();

	}
	#else if here

} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
		if(array_key_exists('listID', $_GET)) {
		$listID = $_GET['listID'];
	}else{
		//no list idea//return 4xx error
		http_response_code(418); //tbd
		exit();
	}
	if (!$dbconnecterror) {
   try {
	$sql = "DELETE FROM doList WHERE listID=:listID";
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(":listID", $listID);
	$response = $stmt->execute();
	http_response_code(204);
	exit();
		#header("Location: index.php");

	} catch (PDOException $e) {
		/* return 500 message */
		http_response_code(504);
		var_dump($e);
		exit();
			}
	} else {
		/*return 500 message */
		http_response_code(545);
		exit();
	}
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$task  = json_decode(file_get_contents('php://input'), true); #also need to check to see what fields are missing


	if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	} else {
		$taskName = FALSE;
	}
	if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate"];
	} else {
		$taskDate  = FALSE;
	}
	if (array_key_exists('completed', $task )) {
		$complete = $task["completed"];
	} else {
		$complete = FALSE;
	}


	if (!$dbconnecterror) {

			try {
				if ($complete == "true"){
					$complete = 1;
				}else{
					$complete = 0;
				}
			$sql = "INSERT INTO doList (completed, taskName, taskDate) VALUES (:completed, :taskName, :taskDate)";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":completed", $complete);
			$stmt->bindParam(":taskName", $taskName);
			$stmt->bindParam(":taskDate", $taskDate);
			$response = $stmt->execute();
			$anIdcode = $dbh -> lastInsertId(); #Gets the I.D that was created by mySql
			$Tasklist = [
			"listID" => $anIdcode , "completed" => $complete ,  "taskName" => $taskName , "taskDate" => $taskDate ];
			http_response_code(204);
			exit();
		} catch (PDOException $e) {
		/* return 500 message */
		http_response_code(504);
		var_dump($e);
		exit();
	}
	}
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if(array_key_exists('listID', $_GET)) {
		$listID = $_GET['listID'];
	}else{
		//no list idea//return 4xx error
		http_response_code(418); //tbd
		exit();
	}

	if (!$dbconnecterror) {
	try {
		$sql = "SELECT * FROM doList WHERE listID=:listID";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(":listID", $listID);
		$response = $stmt->execute();	# won't actually return the data
		$resultsofGET =  $stmt -> fetch(PDO::FETCH_ASSOC);

		if (!is_array($resultsofGET)) {
			http_response_code(404);
			exit();
		}

		http_response_code(200);
		echo json_encode($resultsofGET);
		exit();

	} catch (PDOException $e) {
		/* return 500 message */
		http_response_code(504);
		var_dump($e);
		exit();
	}
	} else {
		/*return 500 message */
		http_response_code(545);
		exit();
	}
} else {
	http_response_code(405); //method not allowed
	echo "unexpected not Get";
	exit();
}//Expecting put
