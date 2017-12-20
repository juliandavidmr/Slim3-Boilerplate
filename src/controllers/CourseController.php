<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->group('/course', function () {
	
	/**
	 * --------------------------------------------------------------------------------
	 * Agregar una discusion
	 * --------------------------------------------------------------------------------
	 */
	$this->map([
		'POST'
	], '/discussion', function (Request $request, Response $response, array $args) {
		$body = $request->getParams();
		
		if (empty($body)) {return $response->withJson("Solicitud denegada. Argumentos no establecidos", 401);}
		
		$keys = array(
			"course",
			"forum",
			"name",
			"userid",
			"message"
		);
		if (! GenericUtil::hastKeys($body, $keys)) {return $response->withJson(ResponseUtil::Error("Faltan argumentos sdsa"), 201);}
		
		$course = $body["course"];
		$forum = $body["forum"];
		$name = $body["name"];
		$userid = $body["userid"];
		$message = $body["message"];
		
		if ((new CourseModel())->insertDiscussion($course, $forum, $name, $userid, $message)) {
			$data = array(
				"msg" => "Inserción correcta. Los argumentos fueron procesados.",
				[
					$body
				]
			);
			return $response->withJson(ResponseUtil::Ok($data), 201);
		}
		return $response->withJson(ResponseUtil::Error("No se pudo registrar " . json_encode($body)), 201);
	});
});
	