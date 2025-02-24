<?php
	function obtener_usuario($con, $email_usuario){
		$resultado = mysqli_query($con, "select * from usuario where email='$email_usuario';");
		return $resultado;
	};

	function obtener_usuario_id($con, $id_usuario){
		$resultado = mysqli_query($con, "select * from usuario where id_usuario='$id_usuario';");
		return $resultado;
	};

	function obtener_usuarios($con){
		$resultado = mysqli_query($con, "select * from usuario;");
		return $resultado;
	};

	function crear_usuario($con, $nombre, $apellidos, $password, $email, $rol){
		$stmt = mysqli_prepare($con, "insert into usuario(nombre, apellidos, password, email, rol) values(?, ?, ?, ?, ?)");
		$usuario = array($nombre, $apellidos, $password ,$email, $rol);
		mysqli_stmt_bind_param($stmt, "ssisi", $usuario[0], $usuario[1], $usuario[2], $usuario[3], $usuario[4]);
		mysqli_stmt_execute($stmt);
	}

	function eliminar_usuario($con, $id_usuario) {
		$stmt = mysqli_prepare($con, "delete from usuario where id_usuario= ?");
		mysqli_stmt_bind_param($stmt, "s", $id_usuario);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) > 0) {
			return true;
		} else {
			return false;
		}
	}

	function actualizar_usuario($con, $nombre, $apellidos, $password, $email, $rol) {
		$stmt = mysqli_prepare($con, "update usuario set nombre = ?, apellidos = ?, password = ?, rol = ? where email = ?");
		mysqli_stmt_bind_param($stmt, "ssiss", $nombre, $apellidos, $password, $rol, $email);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) > 0) {
			return array("success" => true, "message" => "Usuario actualizado correctamente");
		} else {
			return array("success" => false, "message" => "No se encontró el usuario para actualizar");
		}
	}



	function comprobar_existencia($con, $email_usuario) {
		$resultado = mysqli_query($con, "select * from usuario where email=$email_usuario");
		$num_filas = obtener_num_filas($resultado);

		if($num_filas == 0){
			return false;
		};
		return true;
	};

	function obtener_resultados($resultado){
		return mysqli_fetch_array($resultado);
	};

	function obtener_num_filas($resultado){
		return mysqli_num_rows($resultado);
	}

?>