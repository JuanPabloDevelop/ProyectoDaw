<?php
	require_once("datos.php");
	function conectar(){
		$con = mysqli_connect($GLOBALS["host"], $GLOBALS["username"], $GLOBALS["pass"]) or die("Error al conectar con la base de datos");
		return $con;
	}


	function cerrar_conexion($con){
		mysqli_close($con);
		session_abort();
	}
?>