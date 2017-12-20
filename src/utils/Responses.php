<?php

/**
 * Funciones de respuestas y constantes http
 * @author jul.mora <anlijudavid@hotmail.com>
 */
class ResponseUtil {

	// Ver https://es.wikipedia.org/wiki/Anexo:C%C3%B3digos_de_estado_HTTP
	// region Codigos de respuesta
	const MethodNotAllowed = 405;
 // Una petición fue hecha a una URI utilizando un método de solicitud no soportado por dicha URI
	const Gone = 410;
 // Indica que el recurso solicitado ya no está disponible y no lo estará de nuevo.
	                  // endregion
	private static function getDate() {
		return date('Y-m-d');
	}

	/**
	 * Devuelve estructura para respuestas Http OK
	 * 
	 * @param mixed $data
	 *        	Contenido principal, ie: resultados de sentencia mysql
	 * @param string $method
	 *        	Tipo de metodo Http: GET, POST, PUT...
	 * @param array $params
	 *        	Parametros obtenidos
	 * @return array
	 */
	public static function Ok($data, $method, $params = []) {
		return array(
			'data' => $data,
			'method' => $method,
			'params' => $params,
			'error' => false,
			'date' => self::getDate()
		);
	}

	public static function Error($data) {
		return array(
			'data' => $data,
			'error' => true,
			'date' => self::getDate()
		);
	}
}