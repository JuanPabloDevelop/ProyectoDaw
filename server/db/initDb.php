<?php
    require_once("./model/utils.php");
    function init_db($con) {
        create_bdd($con);
		mysqli_select_db($con, $GLOBALS["dbname"]);
		create_user_table($con);
		create_post_table($con);
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
				array("Estilos de decoración", "Nuevo descubrimiento", "Se ha encontrado una nueva especie de ave en el Amazonas.", $fecha_actual, $fecha_actual, 1),
				array("Iluminación", "Concierto en el parque", "Este sábado habrá un concierto gratuito en el parque central.", $fecha_actual, $fecha_actual, 1),
				array("Mobiliario", "Oferta especial", "¡Descuentos hasta el 50% en productos seleccionados esta semana!", $fecha_actual, $fecha_actual, 1),
				array("Textiles", "Mi experiencia viajando", "Un resumen de mi viaje por Europa y las lecciones aprendidas.", $fecha_actual, $fecha_actual, 1),
				array("Accesorios", "El futuro de la tecnología", "Reflexionando sobre cómo la IA está cambiando el mundo.", $fecha_actual, $fecha_actual, 1),
				array("Iluminación", "Cómo cocinar pasta", "Una guía sencilla para preparar pasta deliciosa.", $fecha_actual, $fecha_actual, 1),
				array("Iluminación", "Película del mes", "Una Iluminación sobre la última película de ciencia ficción.", $fecha_actual, $fecha_actual, 1),
				array("Accesorios", "Clima extremo", "Cómo el cambio climático está afectando los patrones climáticos.", $fecha_actual, $fecha_actual, 1),
				array("Estilos de decoración", "Lanzamiento de cohete", "Se lanzó con éxito un cohete de SpaceX al espacio.", $fecha_actual, $fecha_actual, 1),
				array("Mobiliario", "Nueva tienda abierta", "Hoy se inauguró una nueva tienda en el centro comercial.", $fecha_actual, $fecha_actual, 1),
				array("Iluminación", "Feria de comida", "No te pierdas la feria de comida en el centro de la ciudad.", $fecha_actual, $fecha_actual, 1),
				array("Accesorios", "El cambio en educación", "Hablamos sobre cómo la educación en línea está transformando las aulas.", $fecha_actual, $fecha_actual, 1),
				array("Textiles", "Consejos de lectura", "Mis libros favoritos para este año y por qué deberías leerlos.", $fecha_actual, $fecha_actual, 1),
				array("Iluminación", "Cómo plantar árboles", "Consejos simples para ayudar al medio ambiente plantando árboles.", $fecha_actual, $fecha_actual, 1),
				array("Iluminación", "Producto del mes", "Una Iluminación sobre el gadget más reciente del mercado.", $fecha_actual, $fecha_actual, 1),
				array("Accesorios", "Estado del tráfico", "Un análisis sobre los embotellamientos en las grandes ciudades.", $fecha_actual, $fecha_actual, 1),
				array("Mobiliario", "Empleo disponible", "Una nueva vacante ha sido publicada en nuestra empresa.", $fecha_actual, $fecha_actual, 1),
				array("Estilos de decoración", "Descubrimiento histórico", "Se ha descubierto una antigua civilización bajo el desierto.", $fecha_actual, $fecha_actual, 2),
				array("Iluminación", "Taller de arte", "Aprende a pintar con este taller gratuito el próximo domingo.", $fecha_actual, $fecha_actual, 2),
				array("Accesorios", "Importancia del deporte", "Reflexionando sobre los beneficios físicos y mentales del deporte.", $fecha_actual, $fecha_actual, 3),
			);
			
			foreach($posts as $post){
				mysqli_stmt_bind_param($stmt, "sssssi", $post[0], $post[1], $post[2], $post[3], $post[4], $post[5]);
				mysqli_stmt_execute($stmt);
			}
		}
	};

?>