<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

/**
 * Ruta principal
 */
$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
