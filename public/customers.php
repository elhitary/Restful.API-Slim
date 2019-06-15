<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// get all customers

$app->get('/api/customers', function(Request $request, Response $response){
	$sql = "SELECT * FROM customers";

	try{
		// Get DB Object
		$db = new db();
		//connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$customers = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($customers);
	} catch(PDOexception $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}

});
// get specific customers

$app->get('/api/customer/{id}', function(Request $request, Response $response){

	$user_id = $request->getAttribute('id');

	$sql = "SELECT * FROM customers WHERE user_id = $user_id";

	try{
		// Get DB Object
		$db = new db();
		//connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$customer = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($customer);
	} catch(PDOexception $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});
