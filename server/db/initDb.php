<?php
    require_once("./model/utils.php");
    function init_db($con) {
        create_bdd($con);
		mysqli_select_db($con, $GLOBALS["dbname"]);
		create_user_table($con);
		create_post_table($con);
		create_filters_table($con);
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
				array("deco", "Mid-Century", "El estilo Mid-Century se caracteriza por su elegancia atemporal, combinando líneas limpias, colores neutros y mobiliario funcional. Con sus formas geométricas y materiales naturales como la madera y el metal, este estilo es ideal para aquellos que buscan una decoración moderna y sofisticada en su hogar.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Lámparas de bola de vidrio opalino", "Las lámparas de bola de vidrio opalino son perfectas para iluminar cualquier habitación con un toque de elegancia y suavidad. Su diseño único crea una luz cálida y difusa que embellece tanto los espacios modernos como los más tradicionales, aportando una atmósfera acogedora y refinada.", $fecha_actual, $fecha_actual, 1),
				array("mobi", "Mesas nido", "Las mesas nido son ideales para hogares modernos, ya que ofrecen flexibilidad y funcionalidad. Puedes combinarlas en diferentes configuraciones según lo que necesites en cada momento. ¡Perfectas para ahorrar espacio y dar un toque elegante a cualquier habitación!", $fecha_actual, $fecha_actual, 1),
				array("text", "Alfombras shaggy", "Las alfombras shaggy son una excelente opción para darle un toque acogedor a cualquier sala de estar o dormitorio. Con su textura suave y esponjosa, hacen que el espacio se sienta más cálido y cómodo. Además, son perfectas para agregar un elemento de contraste a suelos duros.", $fecha_actual, $fecha_actual, 1),
				array("acc", "Velas y portavelas", "Las velas y los portavelas no solo proporcionan una atmósfera acogedora, sino que también son perfectos para resaltar la decoración de tu hogar. Escoge portavelas elegantes que complementen tu estilo y velas aromáticas para crear un ambiente relajante y único.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Iluminación focal", "La iluminación focal es perfecta para resaltar detalles importantes en un espacio, como una obra de arte, una planta decorativa o una mesa central. Puedes utilizar focos dirigidos o lámparas de pie para crear efectos dramáticos y personalizar la atmósfera de la habitación.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Lámparas colgantes de fibras naturales", "Las lámparas colgantes de fibras naturales, como el mimbre o el ratán, aportan una sensación de calidez y naturaleza a cualquier espacio. Son ideales para cocinas, comedores o salones, donde el estilo relajado y orgánico puede dominar la decoración.", $fecha_actual, $fecha_actual, 1),
				array("acc", "Plantas naturales", "Las plantas naturales son esenciales para crear un ambiente fresco y lleno de vida en cualquier hogar. Además de purificar el aire, agregan una dosis de color y textura que mejora el bienestar general de los espacios. Puedes optar por plantas de interior resistentes que requieran poco mantenimiento.", $fecha_actual, $fecha_actual, 1),
				array("deco", "Art Déco", "El estilo Art Déco es sinónimo de lujo y elegancia, con formas geométricas, detalles brillantes y materiales sofisticados como el vidrio, el metal y el mármol. Para lograr este estilo en casa, apuesta por muebles con líneas rectas, acabados brillantes y una mezcla de colores metálicos como dorado y plateado.", $fecha_actual, $fecha_actual, 1),
				array("mobi", "Bancos de pie de cama", "Los bancos de pie de cama son una excelente opción para añadir almacenamiento adicional y al mismo tiempo embellecer tu dormitorio. Pueden utilizarse para guardar mantas, cojines o accesorios y son ideales para dar un toque sofisticado a la decoración.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Flexos industriales", "Los flexos industriales, con su diseño robusto y funcional, son una gran adición a escritorios o zonas de lectura. Estos proporcionan una luz concentrada y permiten ajustar la dirección de la luz, lo que los hace perfectos para espacios de trabajo o ambientes con una estética urbana.", $fecha_actual, $fecha_actual, 1),
				array("acc", "Cojines de sofá", "Los cojines de sofá son una forma sencilla y económica de actualizar el look de tu salón. Puedes jugar con colores, texturas y patrones para agregar interés visual, y cambiar los cojines cada temporada para renovar la decoración sin necesidad de hacer grandes cambios.", $fecha_actual, $fecha_actual, 1),
				array("text", "Cortinas opacas", "Las cortinas opacas no solo ofrecen privacidad y control sobre la luz, sino que también pueden ser una gran herramienta para mejorar la estética de una habitación. Opta por colores y tejidos que complementen tu decoración, y disfruta de un ambiente más tranquilo y acogedor.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Iluminación de exteriores", "La iluminación de exteriores es esencial para crear un ambiente acogedor en patios, jardines o terrazas. Desde luces solares hasta focos empotrados, una buena iluminación exterior puede transformar por completo la atmósfera de tu espacio al aire libre, creando un entorno ideal para disfrutar por la noche.", $fecha_actual, $fecha_actual, 1),
				array("ilu", "Tiras LED Empotradas", "Las tiras LED empotradas son una opción moderna y discreta para iluminar diferentes áreas de tu hogar. Su versatilidad permite instalarlas en techos, paredes o debajo de los muebles para crear una iluminación suave y ambiente, ideal para zonas de paso o para acentuar detalles decorativos.", $fecha_actual, $fecha_actual, 1),
				array("acc", "Jarrones", "Los jarrones son una excelente manera de agregar estilo y color a cualquier habitación. Puedes elegir entre una gran variedad de formas, tamaños y materiales, como cerámica, vidrio o metal, y combinarlos con flores frescas o simplemente dejarlos como elementos decorativos solitarios.", $fecha_actual, $fecha_actual, 1),
				array("mobi", "Aparadores", "Los aparadores son una excelente opción para almacenar utensilios, vajillas o cualquier otro objeto en tu comedor o salón. Además de ser prácticos, también aportan un toque de sofisticación y elegancia a tus espacios, especialmente si eliges uno con detalles en madera o acabados metálicos.", $fecha_actual, $fecha_actual, 1),
				array("deco", "Boho Chic", "El estilo Boho Chic es ideal para quienes buscan una decoración relajada, colorida y llena de personalidad. Con su mezcla de textiles, patrones y accesorios vintage, el Boho Chic aporta un ambiente acogedor y ecléctico, perfecto para quienes disfrutan de la mezcla de culturas y estilos.", $fecha_actual, $fecha_actual, 2),
				array("ilu", "Lámparas de araña", "Las lámparas de araña no solo proporcionan una excelente iluminación, sino que también actúan como piezas decorativas impresionantes. Estas lámparas, con sus múltiples brazos y detalles en cristal o metal, añaden un toque de lujo y elegancia a cualquier espacio, desde comedores hasta salas de estar.", $fecha_actual, $fecha_actual, 2),
				array("acc", "Relojes de Pared", "Los relojes de pared no solo sirven para medir el tiempo, sino que también son un accesorio decorativo que puede completar la estética de tu hogar. Desde relojes modernos y minimalistas hasta modelos vintage, un reloj bien elegido puede ser el toque final que tu pared necesita.", $fecha_actual, $fecha_actual, 3),

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
?>