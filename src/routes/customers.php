<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
	$name = $args['name'];
	$response->getBody()->write("Hello, $name");

	return $response;
});

$app->get('/', function ($request, Response $response) {
	$response->getBody()->write("Hello this is a simple SLIM api test sample.");

	return $response;
});

// get all customers

$app->get('/profile', function () {
	require(__DIR__ . '/../../public/profile.html');
});

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

	$sql = "SELECT * FROM customers WHERE user_id = ?";

	try{
		// Get DB Object
		$db = new db();
		//connect
		$db = $db->connect();
		$stm = $db->prepare($sql);
		$stm->execute([
			$user_id
		]);
		$customer = $stm->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($customer);
	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

// add customer

$app->post('/api/customer/add', function(Request $request, Response $response){

	$username = $request->getParam('username');
	$first_name = $request->getParam('first_name');
	$last_name = $request->getParam('last_name');
	$gender = $request->getParam('gender');
	$password = $request->getParam('password');
	$status = $request->getParam('status');

	$sql = "INSERT INTO customers (username,first_name,last_name,gender,password,status) VALUES
		(:username,:first_name,:last_name,:gender, :password, :status)";

try{
	// Get DB Object
	$db = new db();
	//connect
	$db = $db->connect();

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':username', 	$username);
	$stmt->bindParam(':first_name', $first_name);
	$stmt->bindParam(':last_name', 	$last_name);
	$stmt->bindParam(':gender', 	$gender);
	$stmt->bindParam(':password', 	$password);
	$stmt->bindParam(':status', 	$status);

	$stmt->execute();

	echo '{"notice": {"text": "Customer Added"}';

} catch(PDOException $e){
	echo '{"error": {"text": '.$e->getMessage().'}';
}
});

// update customer

$app->put('/api/customer/update/{id}', function(Request $request, Response $response){

	$user_id = $request->getAttribute('id'); 
	$username = $request->getParam('username');
	$first_name = $request->getParam('first_name');
	$last_name = $request->getParam('last_name');
	$gender = $request->getParam('gender');
	$password = $request->getParam('password');
	$status = $request->getParam('status');

	$sql = "UPDATE customers SET
		username 	= :username,
		first_name 	= :first_name,
		last_name 	= :last_name,
		gender		= :gender,
		password	= :password,
		status 		= :status
		WHERE user_id = $user_id";

try{
	// Get DB Object
	$db = new db();
	//connect
	$db = $db->connect();

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':username', 	$username);
	$stmt->bindParam(':first_name', $first_name);
	$stmt->bindParam(':last_name', 	$last_name);
	$stmt->bindParam(':gender', 	$gender);
	$stmt->bindParam(':password', 	$password);
	$stmt->bindParam(':status', 	$status);

	$stmt->execute();

	echo '{"notice": {"text": "Customer Added"}';

} catch(PDOException $e){
	echo '{"error": {"text": '.$e->getMessage().'}';
}
});

// Delete Customer
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){
	$user_id = $request->getAttribute('id');
	$sql = "DELETE FROM customers WHERE user_id = $user_id";
	try{
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$db = null;
		echo '{"notice": {"text": "Customer Deleted"}';
	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});
