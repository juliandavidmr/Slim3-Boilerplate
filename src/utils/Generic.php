<?php

class GenericUtil {

	/**
	 * Corta correo largo
	 *
	 * @example jul.mora@udla.edu.co => jul.mora
	 * @param string $fullemail
	 * @return string
	 */
	public static function getShortEmail($fullemail) {
		$i = strrpos($fullemail, "@");
		if ($i != false) {return substr($fullemail, 0, $i);}
		return $fullemail;
	}

	/**
	 * Chequea si $data tiene datos, usado para saber
	 * si el resultado de una consulta posee datos,
	 * tambien para evaluar si una operacion insert fue
	 * realizada correctamente
	 *
	 * @param mysqli_result $data
	 *        	Resultado de consulta mysql
	 * @return boolean
	 */
	public static function hasData($data) {
		// Usado para insert. 1 => Insercion correcta
		if ($data == 1) {return true;}
		if (is_object($data) && get_class($data) == 'mysqli') {return $data->num_rows > 0;}
		return false;
	}

	/**
	 * Verifica que "list" contenga todas las claves dadas en "keys"
	 *
	 * @param array $list
	 *        	Array a evaluar
	 * @param array $keys
	 *        	Listado de claves (Array<string>)
	 * @example $list = array("a"=>2, "b"=>999), listado de claves $keys = ["a", "b"]
	 * @return boolean True si "list" contiene todas las claves establecidas en el arreglo de string "keys"
	 */
	public static function hastKeys(array $list, array $keys) {
		foreach ($keys as $k) {
			if (! array_key_exists($k, $list)) {return false;}
		}
		return true;
	}
}