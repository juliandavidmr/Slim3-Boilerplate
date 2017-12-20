<?php

/**
 * 
 * @author jul.mora
 *
 */
class UserModel {

	function __construct() {}

	/**
	 * Agrega un usuario a moodle
	 *
	 * @param string $username
	 *        	Nombre de usuario chaira (i.e: jul.mora)
	 * @param string $firstname
	 *        	Nombre completo
	 * @param string $lastname
	 *        	Apellidos completos
	 * @param string $email
	 *        	Correo institucional
	 * @param string $phone1
	 *        	Telefono principal
	 * @param string $department
	 *        	departament
	 * @param string $address
	 *        	direccion
	 * @param string $city
	 *        	ciudad actual
	 * @param string $country
	 *        	pais actual?
	 * @param string $dni
	 *        	numero de identificacion: cedula, # libreta, ...
	 * @return boolean
	 */
	function insert($username, $firstname, $lastname, $email, $phone1, $department, $address, $city, $country, $dni) {
		$conn = new Connect();
		$sql = "PR_INSERT_USER('$username', '$firstname', '$lastname', '$email', '$phone1', '$department', '$address', '$city', '$country', '$dni')";
		$stmt = $conn->CallProcedure($sql);
		
		// if (mysqli_error($stmt)) {
			// print "Error: ($sql)" . mysqli_error($stmt);
		// }
		
		return GenericUtil::hasData($stmt);
	}

	/**
	 * Obtiene informacion de un usuario
	 *
	 * @param string $email
	 * @return array
	 */
	function getByEmail($email) {
		$conn = new Connect();
		$userResult = $conn->CallProcedure("PR_AUTH('$email')");
		
		if (! empty($userResult) && $userResult->num_rows > 0) {return $userResult->fetch_assoc();}
		throw new Exception("Usuario incorrecto en moodle");
	}

	/**
	 * Registra una accion de un usuario en la base de datos
	 *
	 * @param string $email
	 *        	Correo institucional - email corto - ie: jul.mora
	 * @param string $resource
	 *        	Recurso solicitado - ie: /url/query/params
	 * @param string $method
	 *        	Tipo de metodo solicitado - ie: GET, POST, ...
	 */
	function addUserLog($email, $resource, $method) {
		(new Connect())->CallProcedure(sprintf("PR_INSERT_USER_LOG('%s', '%s', '%s')", $email, $resource, $method));
	}
}