<?php
    require_once("./model/utils.php");

	function get_comments($con){
		$resultado = mysqli_query($con, "select comment.id_comment, comment.contenido, comment.fecha_creacion, comment.fecha_modificacion, post.id_post, usuario.nombre AS nombre_usuario, usuario.id_usuario, usuario.apellidos AS apellidos_usuario from comment inner join post on comment.post_id = post.id_post inner join usuario on comment.usuario_id = usuario.id_usuario;"
	);
		return $resultado;
	};

	function get_comments_by_id($con, $id){
		$resultado = mysqli_query($con, "select comment.id_comment, comment.contenido, comment.fecha_creacion, comment.fecha_modificacion, post.id_post, usuario.nombre AS nombre_usuario, usuario.id_usuario, usuario.apellidos AS apellidos_usuario from comment inner join post on comment.post_id = post.id_post inner join usuario on comment.usuario_id = usuario.id_usuario where comment.id_comment = " . $id . ";"
	);
		return $resultado;
	};

	function get_comments_by_post_id($con, $id_post){
		$resultado = mysqli_query($con, "select comment.id_comment, comment.contenido, comment.fecha_creacion, comment.fecha_modificacion, post.id_post, usuario.nombre AS nombre_usuario, usuario.id_usuario, usuario.apellidos AS apellidos_usuario from comment inner join post on comment.post_id = post.id_post inner join usuario on comment.usuario_id = usuario.id_usuario where post.id_post = " . $id_post . ";"
	);
		return $resultado;
	};

	function add_comment($con, $id_post, $id_user, $content){
		$fecha_actual = date("Y-m-d h:ia");
		$stmt = mysqli_prepare($con, "insert into comment(contenido, fecha_creacion, fecha_modificacion, usuario_id, post_id) values(?, ?, ?, ?, ?)");
		$comment = array($content, $fecha_actual, '' ,$id_user, $id_post);
		mysqli_stmt_bind_param($stmt, "sssii", $comment[0], $comment[1], $comment[2], $comment[3], $comment[4]);
		mysqli_stmt_execute($stmt);
	}

	function delete_comment($con, $id_comment) {
		$stmt = mysqli_prepare($con, "delete from comment where id_comment= ?");
		mysqli_stmt_bind_param($stmt, "s", $id_comment);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) > 0) {
			return true;
		} else {
			return false;
		}
	}

	function update_comment($con, $content,  $fechaCreacion, $idUsuario, $postId, $id_comment) {
		$fecha_modificacion = date('Y-m-d');
		$stmt = mysqli_prepare($con, "update comment set contenido = ?, fecha_creacion = ?, fecha_modificacion = ?, usuario_id = ?, post_id = ? where id_comment= ?");
		mysqli_stmt_bind_param($stmt, "sssiii", $content, $fechaCreacion, $fecha_modificacion, $idUsuario, $postId, $id_comment);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) > 0) {
			return array("success" => true, "message" => "Comentario actualizado correctamente");
		} else {
			return array("success" => false, "message" => "No se encontró el comentario para actualizar");
		}
	}

?>