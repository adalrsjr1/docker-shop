<?php
// Routes

$app->get('/', function($request, $response, $args) {
	return $this->renderer->render($response, 'index.php', $args);
});

$app->get('/products', function($request, $response, $args) {

	$URL = 'match.default.svc.cluster.local:8100/match/public/login/';
	if(gethostname() == 'linux-vm') {
		$URL = 'http://localhost/match/match/public/login/';
		$this->logger->addInfo("deployed at localhost");
	}
	else {
		$this->logger->addInfo("deployed at kubernetes");
	}
	
	$query_input = $request->getUri()->getQuery();
	$args = explode("&",$query_input);
	$size = count($args);
	
	$map = [];
	foreach($args as $arg) {
		$kv = explode("=",$arg);
		$map[$kv[0]] = $kv[1];		
	}
	
	$user = "";
	if(isset($map['user'])) {
		$user = $map['user'];
	}
	$this->logger->addInfo("user: $user accessing");
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $URL.$user);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$products = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);
	$this->logger->addInfo("printing response from $user");
	return $response->write($products)
					->withHeader('Content-Type', 'application/json;charset=utf-8')
					->withStatus($http_status);

	
});



