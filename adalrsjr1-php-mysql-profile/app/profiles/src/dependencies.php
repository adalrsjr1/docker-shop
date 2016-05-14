<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushProcessor(new Monolog\Processor\WebProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    
    /*if (gethostname() == 'linux-vm') {
		$handler = new Monolog\Handler\SocketHandler('udp://localhost:9999');
		
    }else {
    	$handler = new Monolog\Handler\SocketHandler('udp://172.17.0.1:9999');
    }
    $handler->setPersistent(true);
    $formatter = new Monolog\Formatter\LineFormatter(null, "Uu"); // "U" Universal timestamp
    $handler->setFormatter($formatter);
	$logger->pushHandler($handler, Monolog\Logger::DEBUG);*/
    
    return $logger;
};

