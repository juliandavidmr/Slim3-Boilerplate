<?php

if (PHP_SAPI == 'cli-server') {
	// To help the built-in PHP dev server, check if the request was actually for
	// something which should probably be served as a static file
	$url = parse_url($_SERVER['REQUEST_URI']);
	$file = __DIR__ . $url['path'];
	if (is_file($file)) {return false;}
}

date_default_timezone_set('America/Bogota');

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Register utils
require __DIR__ . '/../src/utils/JWT.php';
require __DIR__ . '/../src/utils/Responses.php';
require __DIR__ . '/../src/utils/Security.php';
require __DIR__ . '/../src/utils/Generic.php';
require __DIR__ . '/../src/utils/BrowserDetection.php';

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register models
require __DIR__ . '/../src/models/Connect.php';
require __DIR__ . '/../src/models/UserModel.php';;
require __DIR__ . '/../src/models/ForumModel.php';
require __DIR__ . '/../src/models/CourseModel.php';

// Register middleware
// require __DIR__ . '/../src/middlewares/AuthMiddleware.php';
// require __DIR__ . '/../src/middlewares/RegisterEventMiddleware.php';
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';
require __DIR__ . '/../src/controllers/AuthController.php';
require __DIR__ . '/../src/controllers/ForumController.php';
require __DIR__ . '/../src/controllers/CourseController.php';

// Run app
$app->run();