<?php
// Routes

$app->get('/login/{user}', function ($request, $response, $args) {
	$uniqueId = '';
	if ($request->hasHeader('X-Unique-Id')) {
			$uniqueid = $request->getHeaderLine('X-Unique-Id');
	}

	$PRODUCTS = "products.default.svc.cluster.local:8080/products/public/query?";
	$PROFILES = "profiles.default.svc.cluster.local:8090/profiles/public/user/";
	if(gethostname() == 'linux-vm') {
		$PRODUCTS = "http://localhost/products/products/public/query?";
		$PROFILES = "http://localhost/profile/profiles/public/user/";
		$this->logger->addInfo("deployed at localhost");
	}
	else {
		$this->logger->addInfo("deployed at kubernetes");
	}
	
	$user = $args['user'];
	$this->logger->addInfo("selecting profile from ".$user);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $PROFILES.$user);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-Unique-Id',md5(uniqid($PROFILES.$user.rand(),true))));
	$profile = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	
	$profile = json_decode($profile,true);
	
	if(count($profile) == 0) {
		if($http_status == 200) {
			$http_status = 404;
		}
		return $response
				->withStatus($http_status)
				->withHeader('Content-Type', 'application/json;charset=utf-8')
				->write('User not found');
	}
	
	$profile = array_pop($profile);
	$profile = array_pop($profile);
	
	$product = key($profile);
	$price = array_pop($profile[$product]['preco']);
	$color = array_pop($profile[$product]['cor']);
	
	$query = "product=$product&color=$color&price=$price";
		
	$this->logger->addInfo("matching with products");
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $PRODUCTS.$query);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-Unique-Id',md5(uniqid($PRODUCTS.$query.rand(),true))));
	$products = curl_exec($curl);

	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);
		
	if(isset($uniqueId) && !empty($uniqueId)) {
		$response->withHeader('X-Unique-Id',$uniqueid);
	}
		
	return $response->write($products)
		            ->withStatus($http_status)
     	            ->withHeader('Content-Type', 'application/json;charset=utf-8');
	
});



