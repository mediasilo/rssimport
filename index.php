<?php
	
	require 'vendor/autoload.php';
	require 'api.php';

	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim(array(
    	'debug' => true,
    	'templates.path' => 'templates'
	));


	//*
	//ROUTES
	//*
	
	
	$app->get('/', function () use ($app) {
    	 $app->render('login.php');
	});

	$app->get('/migrate', function () use ($app) {
    	$api = new ApiClass;
  		$projects = json_decode($api->getUserProjects());
    	$app->render('migrate.php', array('projects' => $projects));
	});

	$app->post('/login', function () use ($app) {
   		$api = new ApiClass;
   		
   		$body = $app->request->getBody();	 
   		$username = $app->request->params('username');
   		$password = $app->request->params('password');
   		$hostname = $app->request->params('hostname');
    	
   		$user = $api->getUserInfo($username,$password,$hostname);
  
  		if($user != "Could not authenticate"){
		    $user = array(
		        'username'    => $username,
		        'password'    => $password,
		        'hostname'    => $hostname
		    );
    		setcookie('mediasilo', json_encode($user), time() + 4800);
    		$app->response->redirect('/migrate', 303);
		} else {
			$app->response->redirect('/', 303);	
		}
	});

	$app->get('/projects', function () use ($app) {
    	$api = new ApiClass;
		$ca = $api->getUserProjects();
		
		$response = $app->response();
	    $response['Content-Type'] = 'application/json';
	  	$response->body($ca);
	});

	$app->post('/createasset', function () use ($app) {
    	$body = $app->request->getBody();
    	$payload = json_decode($body);

		$api = new ApiClass;
		$ca = $api->createAsset($payload);
		
		$response = $app->response();
	    $response['Content-Type'] = 'application/json';
	  	$response->body(json_encode($ca));
	});


	$app->post('/createproject', function () use ($app) {
    	$body = $app->request->getBody();
    	$payload = json_decode($body);
    	
		$api = new ApiClass;
		$ca = $api->createProject($payload);
		$response = $app->response();
	    $response['Content-Type'] = 'application/json';
	  	$response->body($ca);
	});

	$app->run();

?>
