<?php
	require_once("datos.php");
	function conect(){
		$con = mysqli_connect($GLOBALS["host"], $GLOBALS["username"], $GLOBALS["pass"]) or die("Error al conect con la base de datos");
		return $con;
	}


	function close_conect($con){
		mysqli_close($con);
		session_abort();
	}
?>