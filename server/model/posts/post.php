<?php
    require_once("./model/utils.php");
	function get_post($con, $email_post){
		$resultado = mysqli_query($con, "select * from post where email='$email_post';");
		return $resultado;
	};

	function get_posts_by_type($con, $type){
		$resultado = mysqli_query($con, "select p.id_post, p.tipo, p.titulo, p.contenido, p.fecha_creacion, p.fecha_modificacion, p.autor_id, u.nombre, u.apellidos, u.email, u.rol from post p join usuario u on p.autor_id = u.id_usuario where p.tipo='$type';;");
		return $resultado;
	};


	function get_posts($con){
		$resultado = mysqli_query($con, "select p.id_post, p.tipo, p.titulo, p.contenido, p.fecha_creacion, p.fecha_modificacion, p.autor_id, u.nombre, u.apellidos, u.email, u.rol from post p join usuario u on p.autor_id = u.id_usuario;");
		return $resultado;
	};

	function create_post($con, $nombre, $apellidos, $password, $email, $rol){
		$stmt = mysqli_prepare($con, "insert into post(nombre, apellidos, password, email, rol) values(?, ?, ?, ?, ?)");
		$post = array($nombre, $apellidos, $password ,$email, $rol);
		mysqli_stmt_bind_param($stmt, "ssisi", $post[0], $post[1], $post[2], $post[3], $post[4]);
		mysqli_stmt_execute($stmt);
	}

	function delete_post($con, $id_post) {
		$stmt = mysqli_prepare($con, "delete from post where id_post= ?");
		mysqli_stmt_bind_param($stmt, "s", $id_post);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) > 0) {
			return true;
		} else {
			return false;
		}
	}

	function update_post($con, $nombre, $apellidos, $password, $email, $rol) {
		$stmt = mysqli_prepare($con, "update post set nombre = ?, apellidos = ?, password = ?, rol = ? where email = ?");
		mysqli_stmt_bind_param($stmt, "ssiss", $nombre, $apellidos, $password, $rol, $email);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) > 0) {
			return array("success" => true, "message" => "Usuario actualizado correctamente");
		} else {
			return array("success" => false, "message" => "No se encontró el post para actualizar");
		}
	}



	function check_if_post_exists($con, $email_post) {
		$resultado = mysqli_query($con, "select * from post where email=$email_post");
		$num_filas = get_num_rows($resultado);

		if($num_filas == 0){
			return false;
		};
		return true;
	};
?>