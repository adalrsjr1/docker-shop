<?php
// Routes

/*
'{"calcado":{"preco":29,"tamanho:38,"cor":"cinza,"tipo":"pantufa","marca":"Havaiannas"}}',
'{"calcado":{"preco":388,"tamanho:40,"cor":"laranja,"tipo:""sandália","marca":"Dakotinha"}}',
'{"calcado":{"preco":189,"tamanho:39,"cor":"amarelo,"tipo":"bota","marca":"Nike"}}',
'{"calcado":{"preco":112,"tamanho:36,"cor":"preto,"tipo":"sapatilha","marca":"Vibe"}}',
'{"calcado":{"preco":443,"tamanho:29,"cor":"amarelo,"tipo":"sneaker","marca":"Fila"}}',
'{"roupa":{"preco":58,"tamanho":"Pequeno","cor":"escarlate","tipo":"colete","descricao":"Também chamado de felpo, é um tecido obtido com fios em forma de laços que sobem da estrutura básica, dando um efeito felpudo em ambas as faces do tecido ou apenas num dos lados."}}',
'{"roupa":{"preco":22,"tamanho":"ExtraGrande","cor":"preto","tipo":"biquini","descricao":"Também chamado de felpo, é um tecido obtido com fios em forma de laços que sobem da estrutura básica, dando um efeito felpudo em ambas as faces do tecido ou apenas num dos lados."}}',
'{"roupa":{"preco":138,"tamanho":"Grande","cor":"azul,"tipo":"kussaquianas","descricao":"Trama de algodão, clara e semitransparente. Pode ser feita com crochê de fibras naturais ou sintéticas. Mantém o ar dentro da roupa, refrigerando no verão e aquecendo durante o inverno."}}',
'{"roupa":{"preco":179,"tamanho":"Pequeno","cor":"carmesim","tipo":"sarouel","descricao":"Tecido de algodão, forte, originário da cidade de Némes. Apresenta ligamento em sarja. O tecido com este tipo de ligamento é, frequentemente, mais firme do que os que apresentam ligamento em tela, tendo menos tendência a sujar, apesar de ser de lavagem mais difécil. é semelhante ao coutil, jeans e denim. É prático e resistente."}}',
'{"roupa":{"preco":63,"tamanho":"ExtraGrande","cor":"bordô","tipo":"casaco","descricao":"Tecido de algodão ou de composição mista, caracterizado pelo aspecto enrugado, obtido pelo pré-encolhimento dos fios, seja do urdume ou da trama. Seu nome de origem é seersucker."}}'
*/

$db ='[{"calcado":{"preco":29,"tamanho":38,"cor":"cinza","tipo":"pantufa","marca":"Havaiannas"}},
{"calcado":{"preco":388,"tamanho":40,"cor":"preto","tipo":"sandália","marca":"Dakotinha"}},
{"calcado":{"preco":189,"tamanho":39,"cor":"amarelo","tipo":"bota","marca":"Nike"}},
{"calcado":{"preco":112,"tamanho":36,"cor":"preto","tipo":"sapatilha","marca":"Vibe"}},
{"calcado":{"preco":443,"tamanho":29,"cor":"amarelo","tipo":"sneaker","marca":"Fila"}},
{"roupa":{"preco":58,"tamanho":"Pequeno","cor":"escarlate","tipo":"colete","descricao":"Também chamado de felpo, é um tecido obtido com fios em forma de laços que sobem da estrutura básica, dando um efeito felpudo em ambas as faces do tecido ou apenas num dos lados."}},
{"roupa":{"preco":22,"tamanho":"ExtraGrande","cor":"preto","tipo":"biquini","descricao":"Também chamado de felpo, é um tecido obtido com fios em forma de laços que sobem da estrutura básica, dando um efeito felpudo em ambas as faces do tecido ou apenas num dos lados."}},
{"roupa":{"preco":138,"tamanho":"Grande","cor":"azul","tipo":"kussaquianas","descricao":"Trama de algodão, clara e semitransparente. Pode ser feita com crochê de fibras naturais ou sintéticas. Mantém o ar dentro da roupa, refrigerando no verão e aquecendo durante o inverno."}},
{"roupa":{"preco":179,"tamanho":"Pequeno","cor":"carmesim","tipo":"sarouel","descricao":"Tecido de algodão, forte, originário da cidade de Némes. Apresenta ligamento em sarja. O tecido com este tipo de ligamento é, frequentemente, mais firme do que os que apresentam ligamento em tela, tendo menos tendência a sujar, apesar de ser de lavagem mais difécil. é semelhante ao coutil, jeans e denim. É prático e resistente."}},
{"roupa":{"preco":63,"tamanho":"ExtraGrande","cor":"bordô","tipo":"casaco","descricao":"Tecido de algodão ou de composição mista, caracterizado pelo aspecto enrugado, obtido pelo pré-encolhimento dos fios, seja do urdume ou da trama. Seu nome de origem é seersucker."}}]';

// this middleware is for intercept and log
/*$app->add(function ($request, $response, $next) {
	$timestamp = time();
	$host = $request->getUri()->getHost();
	$base_path = $request->getUri()->getBasePath();
	$path = $request->getUri()->getPath();
	$port = $request->getUri()->getPort();
	$status = $response->getStatusCode();
	
	$log = $timestamp.' ['.$status.'] '.$host.':'.$port.$base_path.$path;
	
	$response->getBody()->write($log);
	$response = $next($request, $response);
	
	

	return $response;
});*/

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






