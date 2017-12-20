<?php

/**
 * 
 * @author jul.mora
 *
 */
class Connect
{

	private $connection = null;
	private $credentials = null;

	function __construct()
	{
		$this->initCredentials();
	}

	function initCredentials()
	{
		global $settings;
		$this->credentials = $settings['settings']['db'];
	}

	function __destruct()
	{
		$this->Close();
	}

	/**
	 * Obtiene o crea la conexion mysql
	 * @return mysqli
	 */
	private function getConnection()
	{
		if (is_object($this->connection) && get_class($this->connection) == 'mysqli') {
			return $this->connection;
		} else if ($this->credentials != null && $this->connection == null) {
			$this->connection = new mysqli(
				$this->credentials['host'],
				$this->credentials['username'],
				$this->credentials['password'],
				$this->credentials['database']
			);
			$this->connection->set_charset("utf8");

			if ($this->connection->connect_errno) {
				throw new Exception("Fallo al conectar a MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error);
			}
		} else {
			throw new Exception("Error Processing Request Connect MySQL " . json_encode($this->credentials), 1);
		}
		return $this->connection;
	}

	/**
	 * Ejecutar sentencia sql
	 * @param string $query
	 * @return mysqli_result
	 */
	public function Query($query)
	{
		return $this->getConnection()->query($query);
	}

	/**
	 * Ejecutar procedimiento almacenado
	 * @param string $procedure
	 * @return mysqli_result|bool
	 */
	public function CallProcedure($procedure)
	{
		return $this->Query("CALL $procedure");
	}

	/**
	 * Cierra conexion mysql
	 * @return void
	 */
	public function Close()
	{
		if (is_object($this->connection) && get_class($this->connection) == 'mysqli') {
			return mysqli_close($this->connection);
		}
	}
}