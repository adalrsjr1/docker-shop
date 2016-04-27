<?php
// Routes

$app->get('/login/{user}', function ($request, $response, $args) {
	$PROUCTS = "products.default.svc.cluster.local:8080/products/public/query?";
	$PROFILES = "profiles.default.svc.cluster.local:8090/products/public/user/";
	if(gethostname() == 'linux-vm') {
		$PRODUCTS = "http://localhost:8080/products/public/query?";
		$PROFILES = "http://localhost:8090/profiles/public/user/";
	}
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $PROFILES.$args['user']);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$profile = curl_exec($curl);
	curl_close($curl);
	
	$profile = json_decode($profile,true);
	$profile = array_pop($profile);
	$profile = array_pop($profile);
	
	$product = key($profile);
	$price = array_pop($profile[$product]['preco']);
	$color = array_pop($profile[$product]['cor']);
	
	$query = "product=$product&color=$color&price=$price";
		
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $PRODUCTS.$query);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$products = curl_exec($curl);
	curl_close($curl);
		
	return $response->write($products)
     	            ->withHeader('Content-Type', 'application/json;charset=utf-8');
	
});

/*
$app->get('/products/[{product}]', function ($request, $response, $args) use($db) {
	$products = json_decode($db);
	
	$selected = [];
	foreach($products as $product) {
		$aux = NULL;
		if(!isset($args['product']) )
			$aux = $product;
		else if(isset($product->roupa) && $args['product'] == "roupa")
			$aux = $product->roupa;
		else if(isset($product->calcado) && $args['product'] == "calcado") 
			$aux = $product->calcado;
			
		if(isset($aux)) {
			array_push($selected, $product);
		}
	}
	
	return $response->write(json_encode($selected))
     	               ->withHeader('Content-Type', 'application/json;charset=utf-8');	
});

$app->get('/color/{color}', function ($request, $response, $args) use($db) {
	$products = json_decode($db);
	
	$selected = [];
	foreach($products as $product) {
		$aux = NULL;
		if(isset($product->roupa))
			$aux = $product->roupa;
		else if(isset($product->calcado)) 
			$aux = $product->calcado;
			
		if($aux->cor == $args['color']) {
			array_push($selected, $product);
		}
	}
	
	return $response->write(json_encode($selected))
     	               ->withHeader('Content-Type', 'application/json;charset=utf-8');
});

$app->get('/price/{price}', function ($request, $response, $args) use($db) {
	$price = explode("-",$args['price']);

	$products = json_decode($db);
	
	$selected = [];
	foreach($products as $product) {
		$aux = NULL;
		if(isset($product->roupa))
			$aux = $product->roupa;
		else if(isset($product->calcado)) 
			$aux = $product->calcado;
			
		if($aux->preco >= $price[0] && $aux->preco <= $price[1]) {
			array_push($selected, $product);
		}
	}
	
	return $response->write(json_encode($selected))
     	               ->withHeader('Content-Type', 'application/json;charset=utf-8');
});

$app->get('/query', function($request, $response, $args) use($db) {
	$query = $request->getUri()->getQuery();
	$args = explode("&",$query);
	$size = count($args);

	$map = [];
	foreach($args as $arg) {
		$kv = explode("=",$arg);
		$map[$kv[0]] = $kv[1];		
	}
	
	$size = count($map);
	$products = json_decode($db);
	
	$selected = [];
	foreach($products as $product) {
		if(isset($map['product'])) {
			if($map['product'] == 'roupa' && isset($product->roupa)) {
				$aux = $product->roupa;
			}
			else if($map['product'] == 'calcado' && isset($product->calcado)) {
				$aux = $product->calcado;
			}
			else if($map['product'] != 'calcado' && $map['product'] != 'roupa') {
				return $response
							->withStatus(404)
							->withHeader('Content-Type', 'application/json;charset=utf-8')
							->write('{"Type not found"}');
			}
		
			$price = NULL;
			if(isset($map['price'])) {
				$price = explode("-",$map['price']);
			}
						
			if( (isset($aux) && isset($map['color']) && $map['color'] == $aux->cor) &&
				(isset($aux) && isset($map['price']) && $aux->preco >= $price[0] && $aux->preco <= $price[1])) {

				array_push($selected, $product);
			}
		}
		else return $response
						->withStatus(404)
						->withHeader('Content-Type', 'application/json;charset=utf-8')
						->write('{"Type not found"}');
	}
	return $response->write(json_encode($selected))
     	                ->withHeader('Content-Type', 'application/json;charset=utf-8');
	
});


*/


