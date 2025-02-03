<?php
	require_once("datos.php");
	session_start();

	function conectar(){
		$con = mysqli_connect($GLOBALS["host"], $GLOBALS["username"], $GLOBALS["pass"]) or die("Error al conectar con la base de datos");
		crear_bdd($con);
		mysqli_select_db($con, $GLOBALS["dbname"]);
		crear_tabla_usuarios($con);
		$__SESSION['con'] = $con;
		// crear_tabla_pelicula($con);
		return $con;
	}

	function crear_bdd($con){
		mysqli_query($con, 'create database if not exists ' .$GLOBALS["dbname"]. ';');
	}

	function crear_tabla_usuarios($con){
		mysqli_query($con, "create table if not exists usuario(
			id_usuario int primary key auto_increment, 
			nombre varchar(100), 
			apellidos varchar(100),
			password int,
			email varchar(100),
			rol int DEFAULT 1)");
		rellenar_tabla_usuario($con);
	}

	
	function obtener_usuario($con, $id_usuario){
		$resultado = mysqli_query($con, "select * from usuario where id_usuario=$id_usuario");
		return $resultado;
	}

	function obtener_usuarios($con){
		$resultado = mysqli_query($con, "select * from usuario;");
		return $resultado;
	}

	function obtener_resultados($resultado){
		return mysqli_fetch_array($resultado);
	}

	function obtener_num_filas($resultado){
		return mysqli_num_rows($resultado);
	}

	function rellenar_tabla_usuario($con){
		$resultado = obtener_usuarios($con);
		if(obtener_num_filas($resultado) == 0){
			$stmt = mysqli_prepare($con, "insert into usuario(nombre, apellidos, password, email, rol) values(?, ?, ?, ?, ?)");
			$usuarios = array(array("admin", "admin", 123456, "admin@test.com", 0), array("user", "1", 123456, "user1@test.com", 1));

			foreach($usuarios as $usuario){
				mysqli_stmt_bind_param($stmt, "ssisi", $usuario[0], $usuario[1], $usuario[2], $usuario[3], $usuario[4]);
				mysqli_stmt_execute($stmt);
			}
		}
	}

	function crear_tabla_pelicula($con){
		mysqli_query($con, "create table if not exists pelicula(id_pelicula int primary key auto_increment, titulo varchar(255), sinopsis varchar(255), director int, foreign key (director) references director(id_director))");
	}

	function cerrar_conexion($con){
		mysqli_close($con);
	}
?>