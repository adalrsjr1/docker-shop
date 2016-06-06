<?php
// Routes

/*
{"Usuario1":{"calcado":{"preco":["185-348"],"tamanho":["33-39"],"cor":["bordô","bege","verde","violeta"],"tipo":["pantufa","tênis"],"marca":["Crocs","Plasma","Adidas","Democrata","Vibe","Reebok"]}}}
{"Usuario2":{"roupa":{"preco":["25-36"],"tamanho":["Grande"],"cor":["amarelo","pele","preto"],"tipo":["casaco","tanga","espartilho","calcinha","legging","camisa"],"descricao":["dando"]}}}
{"Usuario3":{"roupa":{"preco":["62-138"],"tamanho":["Medio"],"cor":["laranja","rosa","violeta"],"tipo":["kilt","jardineira","sarouel","colete"],"descricao":["fios","básica","estrutura","que"]}}}
{"Usuario4":{"calcado":{"preco":["82-298"],"tamanho":["25-35"],"cor":["roxo","cinza","bordô","bege","verde","azul"],"tipo":["sapatilha","sapato"],"marca":["Adidas","Democrata","Columbia","Arezzo","Reebok","Asics"]}}}
{"Usuario5":{"roupa":{"preco":["51-124"],"tamanho":["ExtraGrande"],"cor":["roxo","bordô"],"tipo":["jaqueta","smoking","sutiã","jeans","colete","pijama"],"descricao":["freqüentemente","com","forte","mais","Apresenta","prático","tela","coutil","sujar","cidade","apresentam","que","lavagem"]}}}
{"Usuario6":{"calcado":{"preco":["109-240"],"tamanho":["27-44"],"cor":["rosa","ciano","prata"],"tipo":["bota","chinelo","sneaker"],"marca":["Plasma","Columbia","Havaiannas","Reebok"]}}}
{"Usuario7":{"roupa":{"preco":["13-126"],"tamanho":["Grande"],"cor":["laranja","vinho","prata","violeta","vermelho"],"tipo":["sunga","espartilho","kispo"],"descricao":["tecido","corpo","Fibra","climas","sua","quando","origem","umidade","para"]}}}
{"Usuario8":{"calcado":{"preco":["178-237"],"tamanho":["30-35"],"cor":["ciano","amarelo"],"tipo":["chinelo","tênis","sapato"],"marca":["Donadelli","Timberland","Allstar","Asics"]}}}
{"Usuario9":{"roupa":{"preco":["20-86"],"tamanho":["Grande"],"cor":["prata","amarelo"],"tipo":["short","cuecas"],"descricao":["branco","com","agulha","fundo","Forma","então","são","Caracteriza-se"]}}}
{"Usuario10":{"roupa":{"preco":["35-52"],"tamanho":["Grande"],"cor":["pele"],"tipo":["sunga","kussaquianas","legging"],"descricao":["semitransparente","com","feita"]}}}

*/

$db ='[
{"Usuario1":{"calcado":{"preco":["0-30"],"tamanho":["33-39"],"cor":["violeta","bege","verde","cinza"],"tipo":["pantufa","tênis"],"marca":["Crocs","Plasma","Adidas","Democrata","Vibe","Reebok"]}}},
{"Usuario4":{"calcado":{"preco":["100-400"],"tamanho":["25-35"],"cor":["azul","cinza","bordô","bege","verde","laranja"],"tipo":["sapatilha","sapato"],"marca":["Adidas","Democrata","Columbia","Arezzo","Reebok","Asics"]}}},
{"Usuario6":{"calcado":{"preco":["109-240"],"tamanho":["27-44"],"cor":["prata","ciano","preto"],"tipo":["bota","chinelo","sneaker"],"marca":["Plasma","Columbia","Havaiannas","Reebok"]}}},
{"Usuario8":{"calcado":{"preco":["300-500"],"tamanho":["30-35"],"cor":["ciano","amarelo"],"tipo":["chinelo","tênis","sapato"],"marca":["Donadelli","Timberland","Allstar","Asics"]}}},
{"Usuario2":{"roupa":{"preco":["25-36"],"tamanho":["Grande"],"cor":["amarelo","pele","preto"],"tipo":["casaco","tanga","espartilho","calcinha","legging","camisa"],"descricao":["dando"]}}},
{"Usuario3":{"roupa":{"preco":["62-138"],"tamanho":["Medio"],"cor":["violeta","rosa","azul"],"tipo":["kilt","jardineira","sarouel","colete"],"descricao":["fios","básica","estrutura","que"]}}},
{"Usuario5":{"roupa":{"preco":["51-184"],"tamanho":["ExtraGrande"],"cor":["bordô","carmesim"],"tipo":["jaqueta","smoking","sutiã","jeans","colete","pijama"],"descricao":["freqüentemente","com","forte","mais","Apresenta","prático","tela","coutil","sujar","cidade","apresentam","que","lavagem"]}}},
{"Usuario7":{"roupa":{"preco":["13-126"],"tamanho":["Grande"],"cor":["vermelho","vinho","prata","violeta","bordô"],"tipo":["sunga","espartilho","kispo"],"descricao":["tecido","corpo","Fibra","climas","sua","quando","origem","umidade","para"]}}},
{"Usuario9":{"roupa":{"preco":["20-86"],"tamanho":["Grande"],"cor":["amarelo","escarlate"],"tipo":["short","cuecas"],"descricao":["branco","com","agulha","fundo","Forma","então","são","Caracteriza-se"]}}},
{"Usuario10":{"roupa":{"preco":["22-52"],"tamanho":["Grande"],"cor":["preto"],"tipo":["sunga","kussaquianas","legging"],"descricao":["semitransparente","com","feita"]}}}
]';
/*
$app->get('/hello/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/

$app->get('/users/', function ($request, $response, $args) use($db) {
	$uniqueId = '';
	if ($request->hasHeader('X-Unique-Id')) {
			$uniqueid = $request->getHeaderLine('X-Unique-Id');
	}
	if(gethostname() == 'linux-vm') {
		$this->logger->addInfo("deployed at localhost");
	}
	else {
		$this->logger->addInfo("deployed at kubernetes");
	}
	$users = json_decode($db);
	
	$this->logger->addInfo("getting all profiles");
	$selected = [];
	foreach($users as $user) {
		array_push($selected, $user);
	}
	
	if(isset($uniqueId) && !empty($uniqueId)) {
		$response->withHeader('X-Unique-Id',$uniqueid);
	}
	
	return $response->write(json_encode($selected))
     	               ->withHeader('Content-Type', 'application/json;charset=utf-8');	
});

$app->get('/user/{user}', function ($request, $response, $args) use($db) {
	$uniqueId = '';
	if ($request->hasHeader('X-Unique-Id')) {
			$uniqueid = $request->getHeaderLine('X-Unique-Id');
	}
	if(gethostname() == 'linux-vm') {
		$this->logger->addInfo("deployed at localhost");
	}
	else {
		$this->logger->addInfo("deployed at kubernetes");
	}
	$users = json_decode($db, true);
	
	$selected = [];
	
	$this->logger->addInfo("filtering profile from ".$args['user']);
	foreach($users as $user) {
		$aux = NULL;

		if(isset($args['user']) && key($user) == $args['user'])
			$aux = $user;
			
		if(isset($aux)) {
			array_push($selected, $user);
		}
	}
	
	if(isset($uniqueId) && !empty($uniqueId)) {
		$response->withHeader('X-Unique-Id',$uniqueid);
	}
	
	return $response->write(json_encode($selected))
     	               ->withHeader('Content-Type', 'application/json;charset=utf-8');	
});






