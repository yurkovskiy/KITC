<?php

// PDO Connection parameters
define("HOST", "localhost");
define("USER", "restpdo");
define("PASS", "restpdo");
define("DB", "restpdo");

$dsn_string = "mysql:host=".HOST.";dbname=".DB.";charset=utf8";

$dbh = null;

try {
	$dbh = new PDO($dsn_string, USER, PASS);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die($e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// save data to database
	$params = @json_decode(file_get_contents("php://input"));
	$query = "INSERT INTO categories (name, description) VALUES(:name, :description)";
	try {
		$stmt = $dbh->prepare($query);
		$stmt->bindParam('name', $params->name, PDO::PARAM_STR);
		$stmt->bindParam('description', $params->name, PDO::PARAM_STR);
		$stmt->execute();
		echo json_encode(array("response" => "ok"));
	} catch(PDOException $e) {
		die(json_encode($e->getMessage()));
	}
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
	try {
		$stmt = $dbh->query("SELECT * FROM categories");
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	} catch (PDOException $e) {
		die(json_encode($e->getMessage()));
	}
}

?>