<?php

/**
 * 
 * @author jul.mora
 *
 */
class SecurityUtil {

	function __construct() {}

	function pkcs7_pad($data, $size) {
		$length = $size - strlen($data) % $size;
		return $data . str_repeat(chr($length), $length);
	}

	public function encryptData($data) {
		$json = json_encode($data, JSON_FORCE_OBJECT);
		return $json;
		/*
		 * return openssl_encrypt(
		 * pkcs7_pad($json, 16), // padded data
		 * 'AES-256-CBC', // cipher and mode
		 * $encryption_key, // secret key
		 * 0, // options (not used)
		 * $iv // initialisation vector
		 * );
		 */
	}
}