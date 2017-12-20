<?php

/**
 * 
 * @author jul.mora
 *
 */
class CourseModel {

	function __construct() {}
	
	/**
	 * 
	 * @param int $course
	 * @param int $forum
	 * @param string $name
	 * @param int $userid
	 * @param string $message
	 * @return boolean
	 */
	function insertDiscussion($course, $forum, $name, $userid, $message) {
		$conn = new Connect();
		$stmt = $conn->CallProcedure("PR_INSERT_DISCUSSION($course, $forum, '$name', $userid, '$message')");
		// print mysqli_error($stmt);
		return GenericUtil::hasData($stmt);
	}

	/**
	 * INTERCAMBIO DE DATOS CHAIRA
	 * Agrega un grupo
	 *
	 * @param string $programa
	 * @param string $materia
	 * @param string $grupo
	 * @param string $periodo
	 * @param string $semestre
	 * @return boolean
	 */
	function insertGroup($programa, $materia, $grupo, $periodo, $semestre) {
		$conn = new Connect();
		$stmt = $conn->CallProcedure("PR_INSERT_CODGRUPO('$programa', '$materia', '$grupo', '$periodo', '$semestre')");
		
		return GenericUtil::hasData($stmt);
	}
}