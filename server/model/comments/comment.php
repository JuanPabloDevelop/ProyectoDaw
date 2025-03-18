<?php
    require_once("./model/utils.php");

	function get_comments($con){
		$resultado = mysqli_query($con, "select comment.id_comment, comment.contenido, comment.fecha_creacion, comment.fecha_modificacion, post.id_post, usuario.nombre AS nombre_usuario, usuario.id_usuario, usuario.apellidos AS apellidos_usuario from comment inner join post on comment.post_id = post.id_post inner join usuario on comment.usuario_id = usuario.id_usuario;"
	);
		return $resultado;
	};

	function get_comments_by_post_id($con, $id_post){
		$resultado = mysqli_query($con, "select comment.id_comment, comment.contenido, comment.fecha_creacion, comment.fecha_modificacion, post.id_post, usuario.nombre AS nombre_usuario, usuario.id_usuario, usuario.apellidos AS apellidos_usuario from comment inner join post on comment.post_id = post.id_post inner join usuario on comment.usuario_id = usuario.id_usuario where  post.id_post=${$id_post};"
	);
		return $resultado;
	};
?>