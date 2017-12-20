<?php
return [
	'settings' => [
		'displayErrorDetails' => false, // set to false in production
		'addContentLengthHeader' => false, // Allow the web server to send the content-length header
		                                   
		// Renderer settings
		'renderer' => [
			'template_path' => __DIR__ . '/../templates/'
		],
		
		// Monolog settings
		'logger' => [
			'name' => 'slim-api-el-nombre-proyecto',
			'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
			'level' => \Monolog\Logger::DEBUG
		],
		
		// Database settings
		'db' => [
			'host' => '172.xx.xx.xx',
			'database' => 'el_nombre_de_la_base_de_datos',
			'username' => 'nombre_de_usuario',
			'password' => 'la_contraseña',
			'charset' => 'utf8'
		]
	]
];
