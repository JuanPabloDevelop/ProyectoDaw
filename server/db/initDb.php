<?php
    require_once("./model/utils.php");
    function init_db($con) {
        create_bdd($con);
		mysqli_select_db($con, $GLOBALS["dbname"]);
		create_user_table($con);
		create_post_table($con);
		create_filters_table($con);
		create_comments_table($con);
    };

	function create_bdd($con){
		mysqli_query($con, 'create database if not exists ' .$GLOBALS["dbname"]. ';');
	};

	function create_user_table($con){
		mysqli_query($con, "create table if not exists usuario(
			id_usuario int primary key auto_increment, 
			nombre varchar(100), 
			apellidos varchar(100),
			password int,
			email varchar(100),
			rol int DEFAULT 1)");
		fill_user_table($con);
	};

	function fill_user_table($con){
		require_once("./model/users/usuario.php");
		$resultado = get_users($con);
		if(!isset($resultado) || get_num_rows($resultado) == 0){
			$stmt = mysqli_prepare($con, "insert into usuario(nombre, apellidos, password, email, rol) values(?, ?, ?, ?, ?)");
			$usuarios = array(
				array("admin", "admin", 123456, "admin@test.com", 0),
				array("user1", "user1", 123456, "user1@test.com", 1),
				array("user2", "user2", 123456, "user2@test.com", 1),
				array("user3", "user3", 123456, "user3@test.com", 1),
				array("user4", "user4", 123456, "user4@test.com", 1),
				array("user5", "user5", 123456, "user5@test.com", 1),
				array("user6", "user6", 123456, "user6@test.com", 1),
				array("user7", "user7", 123456, "user7@test.com", 1),
				array("user8", "user8", 123456, "user8@test.com", 1),
				array("user9", "user9", 123456, "user9@test.com", 1),
				array("user10", "user10", 123456, "user10@test.com", 1),
				array("user11", "user11", 123456, "user11@test.com", 1)
			);
			

			foreach($usuarios as $usuario){
				mysqli_stmt_bind_param($stmt, "ssisi", $usuario[0], $usuario[1], $usuario[2], $usuario[3], $usuario[4]);
				mysqli_stmt_execute($stmt);
			}
		}
	};

	function create_post_table($con){
		mysqli_query($con, "create table if not exists post(
			id_post int primary key auto_increment, 
			tipo varchar(100), 
			titulo varchar(100),
			contenido varchar(1000),
			fecha_creacion date,
			fecha_modificacion date,
			autor_id int,
			foreign key (autor_id) references usuario(id_usuario))");
		fill_post_table($con);
	};

	function fill_post_table($con){
		require_once("./model/posts/post.php");
		$resultado = get_posts($con);
		$fecha_actual = date('Y-m-d');
		if(!isset($resultado) || get_num_rows($resultado) == 0){
			$stmt = mysqli_prepare($con, "insert into post(tipo, titulo, contenido, fecha_creacion, fecha_modificacion, autor_id) values(?, ?, ?, ?, ?, ?)");
			$posts = array(
				array("deco", "Nuevo descubrimiento", "Se ha encontrado una nueva especie de ave en el Amazonas.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Concierto en el parque", "Este sábado habrá un concierto gratuito en el parque central.", $fecha_actual, $fecha_actual, 1),
				array("mobi", "Oferta especial", "¡Descuentos hasta el 50% en productos seleccionados esta semana!", $fecha_actual, $fecha_actual, 1),
				array("text", "Mi experiencia viajando", "Un resumen de mi viaje por Europa y las lecciones aprendidas.", $fecha_actual, $fecha_actual, 1),
				array("acc", "El futuro de la tecnología", "Reflexionando sobre cómo la IA está cambiando el mundo.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Cómo cocinar pasta", "Una guía sencilla para preparar pasta deliciosa.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Película del mes", "Una ilu sobre la última película de ciencia ficción.", $fecha_actual, $fecha_actual, 1),
				array("acc", "Clima extremo", "Cómo el cambio climático está afectando los patrones climáticos.", $fecha_actual, $fecha_actual, 1),
				array("deco", "Lanzamiento de cohete", "Se lanzó con éxito un cohete de SpaceX al espacio.", $fecha_actual, $fecha_actual, 1),
				array("mobi", "Nueva tienda abierta", "Hoy se inauguró una nueva tienda en el centro comercial.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Feria de comida", "No te pierdas la feria de comida en el centro de la ciudad.", $fecha_actual, $fecha_actual, 1),
				array("acc", "El cambio en educación", "Hablamos sobre cómo la educación en línea está transformando las aulas.", $fecha_actual, $fecha_actual, 1),
				array("text", "Consejos de lectura", "Mis libros favoritos para este año y por qué deberías leerlos.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Cómo plantar árboles", "Consejos simples para ayudar al medio ambiente plantando árboles.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Producto del mes", "Una ilu sobre el gadget más reciente del mercado.", $fecha_actual, $fecha_actual, 1),
				array("acc", "Estado del tráfico", "Un análisis sobre los embotellamientos en las grandes ciudades.", $fecha_actual, $fecha_actual, 1),
				array("mobi", "Empleo disponible", "Una nueva vacante ha sido publicada en nuestra empresa.", $fecha_actual, $fecha_actual, 1),
				array("deco", "Descubrimiento histórico", "Se ha descubierto una antigua civilización bajo el desierto.", $fecha_actual, $fecha_actual, 2),
				array("ilu", "Taller de arte", "Aprende a pintar con este taller gratuito el próximo domingo.", $fecha_actual, $fecha_actual, 2),
				array("acc", "Importancia del deporte", "Reflexionando sobre los beneficios físicos y mentales del deporte.", $fecha_actual, $fecha_actual, 3),
			);
			
			foreach($posts as $post){
				mysqli_stmt_bind_param($stmt, "sssssi", $post[0], $post[1], $post[2], $post[3], $post[4], $post[5]);
				mysqli_stmt_execute($stmt);
			}
		}
	};


	function create_filters_table($con){
		mysqli_query($con, "create table if not exists filter(
			tipo varchar(10) default 'all' check (Tipo IN ('all', 'deco', 'ilu', 'mobi', 'text', 'acc'))
			)");
		fill_filter_table($con);
	};

	function fill_filter_table($con){
		require_once("./model/filters/filter.php");
		$resultado = get_post_type_filters($con);
		if(!isset($resultado) || get_num_rows($resultado) == 0){
			$stmt = mysqli_prepare($con, "insert into filter(tipo) values(?)");
			$filters = array(
				array("all"),
				array("deco"),
				array("ilu"),
				array("mobi"),
				array("text"),
				array("acc"),
			);
			
			foreach($filters as $filter){
				mysqli_stmt_bind_param($stmt, "s", $filter[0]);
				mysqli_stmt_execute($stmt);
			}
		}
	};

	function create_comments_table($con){
		mysqli_query($con, "create table if not exists comment(
			id_comment int primary key auto_increment,
			contenido varchar(1000),
			fecha_creacion date,
			fecha_modificacion date,
			post_id int,
			usuario_id int,
			foreign key (post_id) references post(id_post) on delete cascade,
			foreign key (usuario_id) references usuario(id_usuario) on delete cascade
		);");
		fill_comment_table($con);
	};

	function fill_comment_table($con){
		require_once("./model/comments/comment.php");
		$resultado = get_comments($con);
		$fecha_actual = date('Y-m-d');
		if(!isset($resultado) || get_num_rows($resultado) == 0){
			$stmt = mysqli_prepare($con, "insert into comment(contenido, fecha_creacion, fecha_modificacion, usuario_id, post_id) values(?, ?, ?, ?, ?)");
			$comments = array(
				array("Excelente", $fecha_actual, $fecha_actual, 1, 1),
				array("Lo recomendaré", $fecha_actual, $fecha_actual, 1, 2),
				array("Lo veo insuficiente", $fecha_actual, $fecha_actual, 1, 3),
				array("Está sacado de una revista", $fecha_actual, $fecha_actual, 1, 4),
				array("No estoy de acuerdo. Creo que aporta un punto de vista interesante con respecto al resto", $fecha_actual, $fecha_actual, 1, 5),
				array("Estoy de acuerdo", $fecha_actual, $fecha_actual, 1, 6),
				array("Esto me hace reflexionar", $fecha_actual, $fecha_actual, 1, 7),
				array("Es justo lo que necesitaba", $fecha_actual, $fecha_actual, 1, 8),
				array("No lo veo interesante", $fecha_actual, $fecha_actual, 1, 9),
				array("A veces pasa si lo lees por encima", $fecha_actual, $fecha_actual, 1, 10),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 11),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 12),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 11),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 12),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 11),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 10),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 9),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 11),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 1, 12),
				array("Muy interesante", $fecha_actual, $fecha_actual, 2, 3),
				array("Gracias por compartir", $fecha_actual, $fecha_actual, 3, 5),
				array("¡Gran aporte!", $fecha_actual, $fecha_actual, 4, 7),
				array("Esto me ayudó mucho", $fecha_actual, $fecha_actual, 5, 2),
				array("No estoy de acuerdo", $fecha_actual, $fecha_actual, 6, 4),
				array("Súper útil", $fecha_actual, $fecha_actual, 7, 6),
				array("Necesito más detalles", $fecha_actual, $fecha_actual, 8, 8),
				array("¡Fantástico!", $fecha_actual, $fecha_actual, 9, 9),
				array("Esto me hace reflexionar", $fecha_actual, $fecha_actual, 6, 6),
				array("Buen contenido", $fecha_actual, $fecha_actual, 8, 8),
				array("No estoy seguro de esto", $fecha_actual, $fecha_actual, 6, 6),
				array("Es justo lo que necesitaba", $fecha_actual, $fecha_actual, 6, 1),
				array("¡Motivador!", $fecha_actual, $fecha_actual, 4, 3),
				array("Estoy impresionado", $fecha_actual, $fecha_actual, 4, 5),
				array("¿Dónde puedo aprender más?", $fecha_actual, $fecha_actual, 6, 7),
				array("Sencillo pero efectivo", $fecha_actual, $fecha_actual, 7, 2),
				array("Interesante enfoque", $fecha_actual, $fecha_actual, 8, 4),
				array("Increíble trabajo", $fecha_actual, $fecha_actual, 9, 6),
				array("Voy a recomendarlo", $fecha_actual, $fecha_actual, 2, 8)
			);
			
			foreach($comments as $comment){
				mysqli_stmt_bind_param($stmt, "sssii", $comment[0], $comment[1], $comment[2], $comment[3], $comment[4]);
				mysqli_stmt_execute($stmt);
			}
		}
	};
?>