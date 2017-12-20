<?php

/**
 * JSON Web Token implementation
 *
 * Minimum implementation used by Realtime auth, based on this spec:
 * http://self-issued.info/docs/draft-jones-json-web-token-01.html.
 *
 * @author Neuman Vong <neuman@twilio.com>
 * @author Julian David <https://twitter.com/anlijudavid>
 */
class JWT {

	private static $keyJwt = "'ax98x71.2x8*c-*asd12_z_gh_#_='";

	/**
	 * Obtiene usuario desde autorizacion
	 *
	 * @param string $http_authorization
	 *        	Cadena de autorizacion
	 * @return array Usuario
	 */
	public static function getUserByHttpAuthorization($http_authorization) {
		if (! empty($http_authorization)) {
			$http_authorization = $http_authorization[0];
			// Extract token"Basic 'xxxxx'"
			$jwt = substr($http_authorization, 6);
			return JWT::getUserByToken($jwt);
		}
		return array();
	}

	public static function getUserByToken($jwt) {
		try {
			$jwtObj = (array) JWT::decode($jwt);
			return (array) $jwtObj['data'];
		} catch (Exception $e) {}
		return array();
	}

	/**
	 * Corta el segmento perteneciente a token y lo devuelve
	 *
	 * @example Basic da9x9f2d312dsdf => da9x9f2d312dsdf
	 * @param string $http_authorization
	 * @return string
	 */
	public static function getTokenFromHeaderStr($http_authorization) {
		if (! empty($http_authorization)) {return substr($http_authorization, 6);}
		return $http_authorization;
	}

	/**
	 *
	 * @param mixed $data
	 * @return string
	 */
	public static function encodeFinal($data) {
		$time = time();
		$toEncode = array(
			'iat' => $time, // Tiempo que inició el token
			'exp' => $time + (60 * 60), // Tiempo que expirará el token (+1 hora)
			'data' => $data
		);
		$jwt = JWT::encode($toEncode);
		return $jwt;
	}

	/**
	 *
	 * @param string $jwt
	 *        	The JWT
	 * @param string|null $key
	 *        	The secret key
	 * @param bool $verify
	 *        	Don't skip verification process
	 *        	
	 * @return object The JWT's payload as a PHP object
	 */
	public static function decode($jwt, $key = null, $verify = true) {
		if (isset($key) || $key == null) {
			$key = JWT::$keyJwt;
		}
		
		$tks = explode('.', $jwt);
		if (count($tks) != 3) {throw new UnexpectedValueException('Wrong number of segments');}
		list ($headb64, $payloadb64, $cryptob64) = $tks;
		if (null === ($header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64)))) {throw new UnexpectedValueException('Invalid segment encoding');}
		if (null === $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($payloadb64))) {throw new UnexpectedValueException('Invalid segment encoding');}
		$sig = JWT::urlsafeB64Decode($cryptob64);
		if ($verify) {
			if (empty($header->alg)) {throw new DomainException('Empty algorithm');}
			if ($sig != JWT::sign("$headb64.$payloadb64", $key, $header->alg)) {throw new UnexpectedValueException('Signature verification failed');}
		}
		return $payload;
	}

	/**
	 *
	 * @param object|array $payload
	 *        	PHP object or array
	 * @param string $key
	 *        	The secret key
	 * @param string $algo
	 *        	The signing algorithm
	 *        	
	 * @return string A JWT
	 */
	public static function encode($payload, $key = null, $algo = 'HS256') {
		if (isset($key) || $key == null) {
			$key = JWT::$keyJwt;
		}
		
		$header = array(
			'typ' => 'JWT',
			'alg' => $algo
		);
		
		$segments = array();
		$segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($header));
		$segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($payload));
		$signing_input = implode('.', $segments);
		
		$signature = JWT::sign($signing_input, $key, $algo);
		$segments[] = JWT::urlsafeB64Encode($signature);
		
		return implode('.', $segments);
	}

	/**
	 *
	 * @param string $msg
	 *        	The message to sign
	 * @param string $key
	 *        	The secret key
	 * @param string $method
	 *        	The signing algorithm
	 *        	
	 * @return string An encrypted message
	 */
	public static function sign($msg, $key, $method = 'HS256') {
		$methods = array(
			'HS256' => 'sha256',
			'HS384' => 'sha384',
			'HS512' => 'sha512'
		);
		if (empty($methods[$method])) {throw new DomainException('Algorithm not supported');}
		return hash_hmac($methods[$method], $msg, $key, true);
	}

	/**
	 *
	 * @param string $input
	 *        	JSON string
	 *        	
	 * @return object Object representation of JSON string
	 */
	public static function jsonDecode($input) {
		$obj = json_decode($input);
		if (function_exists('json_last_error') && $errno = json_last_error()) {
			JWT::handleJsonError($errno);
		} else if ($obj === null && $input !== 'null') {throw new DomainException('Null result with non-null input');}
		return $obj;
	}

	/**
	 *
	 * @param object|array $input
	 *        	A PHP object or array
	 *        	
	 * @return string JSON representation of the PHP object or array
	 */
	public static function jsonEncode($input) {
		$json = json_encode($input);
		if (function_exists('json_last_error') && $errno = json_last_error()) {
			JWT::handleJsonError($errno);
		} else if ($json === 'null' && $input !== null) {throw new DomainException('Null result with non-null input');}
		return $json;
	}

	/**
	 *
	 * @param string $input
	 *        	A base64 encoded string
	 *        	
	 * @return string A decoded string
	 */
	public static function urlsafeB64Decode($input) {
		$remainder = strlen($input) % 4;
		if ($remainder) {
			$padlen = 4 - $remainder;
			$input .= str_repeat('=', $padlen);
		}
		return base64_decode(strtr($input, '-_', '+/'));
	}

	/**
	 *
	 * @param string $input
	 *        	Anything really
	 *        	
	 * @return string The base64 encode of what you passed in
	 */
	public static function urlsafeB64Encode($input) {
		return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
	}

	/**
	 *
	 * @param int $errno
	 *        	An error number from json_last_error()
	 *        	
	 * @return void
	 */
	private static function handleJsonError($errno) {
		$messages = array(
			JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
			JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
			JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON'
		);
		throw new DomainException(isset($messages[$errno]) ? $messages[$errno] : 'Unknown JSON error: ' . $errno);
	}
}
