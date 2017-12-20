<?php
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Autenticacion de usuario
 */
$app->group('/auth', function () {

	/**
	 * Receive data post
	 */
	$this->map([
		'POST'
	], '/login', function (Request $request, Response $response, array $args) {
		$body = $request->getParams();

		if (empty($body)) {
			return $response->withJson("Acceso denegado. Argumentos no establecidos", 401);
		}

		$keys = array(
			"email",
			"pass"
		);

		if (!GenericUtil::hastKeys($body, $keys)) {
			return $response->withJson("Faltan argumentos", 401);
		}

		$email = $body['email'];
		$pass = $body['pass'];

		if (!empty($email) && !empty($pass)) {						
			// Logger
			$time = time();
			$this->logger->info("Usuario autenticado '$email' - $time");
			
			// Response
			return $response->withJson("Authenticated", 200);			
		}

		return $response->withJson("Acceso denegado", 401);
	});

	/**
	 * Render template login
	 */
	$this->map([
		'GET'
	], '/login', function (Request $request, Response $response, $args) {
		return $this->renderer->render($response, 'user/login.phtml', $args);
	});
});