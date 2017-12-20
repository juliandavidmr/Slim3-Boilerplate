<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->group('/forum', function () {
	$this->map([
		'POST'
	], '/discussions', function (Request $request, Response $response, array $args) {
		try {
			$body = (array) $request->getParams();
			
			// Validacion de entradas
			$keys = array(
				"discussion",
				"userId",
				"postParent",
				"message"
			);
			
			if (! GenericUtil::hastKeys($body, $keys)) {return $response->withJson(ResponseUtil::Error([
					"Faltan argumentos",
					"msg" => $body
				]), 201);}
			
			if (! empty($body)) {
				$hasToken = ! empty(JWT::getUserByHttpAuthorization($request->getHeader('HTTP_AUTHORIZATION'))) ? 1 : 0;
				$forumDiscussion = $body["discussion"];
				$userId = $body["userId"];
				$subject = array_key_exists("subject", $body)? $body["subject"] : null;
				$postParent = $body["postParent"];
				$message = $body["message"];
				
				$result = ForumModel::InsertForumPost($forumDiscussion, $userId, $subject, $postParent, $message);
				if (GenericUtil::hasData($result)) {
					$data = array(
						"forumDiscussion" => $forumDiscussion,
						"userId" => $userId,
						"subject" => $subject,
						"postParent" => $postParent,
						"listResult" => $result
					);
					
					return $response->withJson(ResponseUtil::Ok($data, "POST"), 200);
				} else {
					return $response->withJson(ResponseUtil::Error("No se pudo registrar " . json_encode($body)), 201);
				}
			}
		} catch (Exception $e) {
			return $response->withJson(ResponseUtil::Error($e->getMessage()), $e->getCode());
		}
		$data = array(
			"msg" => "Sin argumentos definidos",
			"form" => $body
		);
		return $response->withJson(ResponseUtil::Error($data), 201);
	});
});