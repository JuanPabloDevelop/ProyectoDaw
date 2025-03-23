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
				array("Carlos", "Gómez López", 123456, "carlos.gomez@test.com", 1),
				array("Ana", "Martínez Ruiz", 123456, "ana.martinez@test.com", 1),
				array("Luis", "Fernández García", 123456, "luis.fernandez@test.com", 1),
				array("Sandra", "Sánchez Pérez", 123456, "maria.sanchez@test.com", 1),
				array("Javier", "Díaz Rodríguez", 123456, "javier.diaz@test.com", 1),
				array("Laura", "Hernández Gómez", 123456, "laura.hernandez@test.com", 1),
				array("Pablo", "Jiménez Martín", 123456, "pablo.jimenez@test.com", 1),
				array("Mercedes", "Moreno González", 123456, "sofia.moreno@test.com", 1),
				array("Diego", "Romero Navarro", 123456, "diego.romero@test.com", 1),
				array("Elena", "Torres Molina", 123456, "elena.torres@test.com", 1),
				array("Miguel", "Ortega Serrano", 123456, "miguel.ortega@test.com", 1),
				array("Ana", "Jiménez Martín", 123456, "ana.jimenez@test.com", 1),
				array("Carlos", "Moreno González", 123456, "carlos.moreno@test.com", 1),
				array("Angel", "Romero Navarro", 123456, "angel.romero@test.com", 1),
				array("Martin", "Torres Molina", 123456, "martin.torres@test.com", 1),
				array("Daniel", "Ortega Serrano", 123456, "daniel.ortega@test.com", 1)
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
			fecha_creacion varchar(30),
			fecha_modificacion varchar(30),
			autor_id int,
			foreign key (autor_id) references usuario(id_usuario))");
		fill_post_table($con);
	};

	function fill_post_table($con){
		require_once("./model/posts/post.php");
		$resultado = get_posts($con);
		$fecha_actual = date("Y-m-d h:ia");
		if(!isset($resultado) || get_num_rows($resultado) == 0){
			$stmt = mysqli_prepare($con, "insert into post(tipo, titulo, contenido, fecha_creacion, fecha_modificacion, autor_id) values(?, ?, ?, ?, ?, ?)");
			$posts = array(
				array("deco", "Mid-Century", "El estilo Mid-Century se caracteriza por su elegancia atemporal, combinando líneas limpias, colores neutros y mobiliario funcional. Con sus formas geométricas y materiales naturales como la madera y el metal, este estilo es ideal para aquellos que buscan una decoración moderna y sofisticada en su hogar.
    					Este estilo, que surgió a mediados del siglo XX, se inspira en la simplicidad y la funcionalidad de la época. Los muebles con patas delgadas, los tonos tierra y los detalles en metal son elementos clave que definen este look. Además, el Mid-Century se adapta perfectamente a espacios pequeños, ya que prioriza la practicidad sin sacrificar el diseño.
    					Para incorporar este estilo en tu hogar, puedes comenzar con piezas icónicas como un sofá de líneas rectas, una mesa de centro de madera con detalles metálicos o estanterías modulares. Combina estos elementos con textiles en tonos neutros y accesorios minimalistas para lograr un ambiente equilibrado y atemporal.", $fecha_actual, "", 1),				
						
						array("ilu", "Lámparas de bola de vidrio opalino", "Las lámparas de bola de vidrio opalino son perfectas para iluminar cualquier habitación con un toque de elegancia y suavidad. Su diseño único crea una luz cálida y difusa que embellece tanto los espacios modernos como los más tradicionales, aportando una atmósfera acogedora y refinada.
						Estas lámparas son ideales para colocarlas en mesas auxiliares, cómodas o incluso en el centro de una mesa de comedor. El vidrio opalino, con su acabado traslúcido, ayuda a difuminar la luz, creando un ambiente relajante y perfecto para momentos de descanso o reuniones familiares.
						Además, su diseño atemporal las convierte en una pieza versátil que puede adaptarse a diferentes estilos de decoración. Si buscas un toque de sofisticación, combina estas lámparas con materiales como el mármol, el latón o la madera oscura. Su luz suave las hace perfectas para dormitorios, salones o incluso baños, donde la iluminación ambiental es clave para crear un espacio acogedor.", $fecha_actual, "", 1),
					
						array("mobi", "Mesas nido", "Las mesas nido son ideales para hogares modernos, ya que ofrecen flexibilidad y funcionalidad. Puedes combinarlas en diferentes configuraciones según lo que necesites en cada momento. ¡Perfectas para ahorrar espacio y dar un toque elegante a cualquier habitación!
						Estas mesas suelen venir en juegos de dos o tres, con tamaños decrecientes que permiten guardarlas una debajo de la otra. Son perfectas para salones pequeños o para añadir superficies adicionales en espacios donde se necesita versatilidad. Además, su diseño compacto las convierte en una opción práctica y estética.
						Puedes elegir mesas nido con acabados en madera natural para un look cálido y orgánico, o optar por modelos con patas metálicas para un estilo más industrial. También existen opciones con cajones ocultos, ideales para guardar revistas, mandos a distancia u otros objetos pequeños. Sea cual sea tu elección, las mesas nido son una inversión inteligente para cualquier hogar.", $fecha_actual, "", 1),
					
						array("text", "Alfombras shaggy", "Las alfombras shaggy son una excelente opción para darle un toque acogedor a cualquier sala de estar o dormitorio. Con su textura suave y esponjosa, hacen que el espacio se sienta más cálido y cómodo. Además, son perfectas para agregar un elemento de contraste a suelos duros.
						Estas alfombras están disponibles en una amplia variedad de colores y tamaños, lo que permite adaptarlas a cualquier estilo de decoración. Su pelo largo y mullido no solo aporta confort bajo los pies, sino que también ayuda a reducir el ruido en habitaciones con eco.
						Para maximizar su impacto, coloca una alfombra shaggy debajo de un sofá o una cama, asegurándote de que sus bordes queden visibles. Combínala con cojines y mantas en tonos coordinados para crear un look cohesionado y acogedor. Además, su mantenimiento es sencillo: basta con aspirarla regularmente para mantener su aspecto impecable.", $fecha_actual, "", 1),
					
						array("acc", "Velas y portavelas", "Las velas y los portavelas no solo proporcionan una atmósfera acogedora, sino que también son perfectos para resaltar la decoración de tu hogar. Escoge portavelas elegantes que complementen tu estilo y velas aromáticas para crear un ambiente relajante y único.
						Las velas pueden ser utilizadas en cualquier espacio, desde el baño hasta el dormitorio, y son ideales para momentos de relajación o cenas románticas. Además, los portavelas pueden ser de diferentes materiales como cerámica, metal o vidrio, lo que permite combinarlos con cualquier tipo de decoración.
						Para crear un ambiente aún más especial, elige velas con aromas como lavanda, vainilla o cítricos. Estas no solo iluminan, sino que también perfuman el ambiente, convirtiendo cualquier habitación en un refugio de calma y bienestar. Colócalas en grupos de diferentes alturas para crear un efecto visual atractivo.", $fecha_actual, "", 1),
					
						array("ilu", "Iluminación focal", "La iluminación focal es perfecta para resaltar detalles importantes en un espacio, como una obra de arte, una planta decorativa o una mesa central. Puedes utilizar focos dirigidos o lámparas de pie para crear efectos dramáticos y personalizar la atmósfera de la habitación.
						Este tipo de iluminación no solo mejora la estética del espacio, sino que también ayuda a dirigir la atención hacia elementos clave de la decoración. Es ideal para salones, galerías o incluso en pasillos donde se quiera destacar un objeto en particular.
						Además, la iluminación focal es una excelente manera de añadir profundidad y dimensión a una habitación. Juega con la intensidad de la luz y los ángulos para crear sombras y contrastes que añadan interés visual al espacio.", $fecha_actual, "", 1),
					
						array("ilu", "Lámparas colgantes de fibras naturales", "Las lámparas colgantes de fibras naturales, como el mimbre o el ratán, aportan una sensación de calidez y naturaleza a cualquier espacio. Son ideales para cocinas, comedores o salones, donde el estilo relajado y orgánico puede dominar la decoración.
						Estas lámparas no solo son funcionales, sino que también actúan como piezas decorativas que añaden textura y personalidad al ambiente. Además, su diseño ligero y natural las convierte en una opción perfecta para espacios que buscan un toque rústico o bohemio.
						Para maximizar su impacto, combínalas con muebles de madera y textiles en tonos neutros. También puedes colgar varias lámparas de diferentes tamaños para crear un efecto de cascada que añada dinamismo al espacio. Su luz suave y difusa es perfecta para crear ambientes relajados y acogedores.", $fecha_actual, "", 1),
					
						array("acc", "Plantas naturales", "Las plantas naturales son esenciales para crear un ambiente fresco y lleno de vida en cualquier hogar. Además de purificar el aire, agregan una dosis de color y textura que mejora el bienestar general de los espacios.
						Puedes optar por plantas de interior resistentes que requieran poco mantenimiento, como los potos, las suculentas o los ficus. Estas plantas no solo decoran, sino que también ayudan a reducir el estrés y mejorar la calidad del aire.
						Colócalas en macetas modernas o colgantes para maximizar su impacto visual. Además, las plantas son una excelente manera de dividir espacios o añadir altura a una habitación. Por ejemplo, una palmera en una esquina puede convertirse en un punto focal, mientras que un conjunto de pequeñas plantas en una repisa añade un toque de frescura.", $fecha_actual, "", 1),
					
						array("deco", "Art Déco", "El estilo Art Déco es sinónimo de lujo y elegancia, con formas geométricas, detalles brillantes y materiales sofisticados como el vidrio, el metal y el mármol. Para lograr este estilo en casa, apuesta por muebles con líneas rectas, acabados brillantes y una mezcla de colores metálicos como dorado y plateado.
						Este estilo, que surgió en los años 20, se caracteriza por su opulencia y glamour, y es perfecto para quienes buscan un ambiente sofisticado y atemporal. Incorpora espejos con marcos dorados, lámparas de cristal y detalles en negro para completar el look.
						Para un toque final, añade accesorios como esculturas, relojes de pared o jarrones con diseños intrincados. El Art Déco es ideal para salones, dormitorios principales o incluso baños, donde se busca crear un ambiente lujoso y refinado.", $fecha_actual, "", 1),
					
						array("mobi", "Bancos de pie de cama", "Los bancos de pie de cama son una excelente opción para añadir almacenamiento adicional y al mismo tiempo embellecer tu dormitorio. Pueden utilizarse para guardar mantas, cojines o accesorios y son ideales para dar un toque sofisticado a la decoración.
						Estos bancos suelen estar tapizados en telas lujosas como terciopelo o lino, y pueden incluir detalles como patas de madera o metal. Además, son prácticos para sentarse mientras te vistes o para colocar ropa o libros de manera temporal.
						Su diseño compacto los hace perfectos para dormitorios pequeños, donde cada pieza de mobiliario debe ser funcional y estética. Para integrarlos en tu decoración, elige un banco que combine con los colores y texturas de tu dormitorio.", $fecha_actual, "", 1),
					
						array("ilu", "Flexos industriales", "Los flexos industriales, con su diseño robusto y funcional, son una gran adición a escritorios o zonas de lectura. Estos proporcionan una luz concentrada y permiten ajustar la dirección de la luz, lo que los hace perfectos para espacios de trabajo o ambientes con una estética urbana.
						Su estilo industrial, con detalles en metal y acabados envejecidos, los convierte en una pieza decorativa además de funcional. Son ideales para estudios, oficinas en casa o incluso en salones con un estilo moderno y rústico.
						Además, su brazo ajustable permite dirigir la luz exactamente donde la necesitas, lo que los hace perfectos para tareas que requieren precisión. Para completar el look, combínalos con muebles de madera recuperada o metal, y añade accesorios como estanterías abiertas o pósters vintage.", $fecha_actual, "", 1),
					
						array("acc", "Cojines de sofá", "Los cojines de sofá son una forma sencilla y económica de actualizar el look de tu salón. Puedes jugar con colores, texturas y patrones para agregar interés visual, y cambiar los cojines cada temporada para renovar la decoración sin necesidad de hacer grandes cambios.
						Los cojines no solo añaden confort, sino que también permiten experimentar con tendencias de diseño sin comprometerte a un cambio permanente. Combina diferentes tamaños y formas para crear un sofá único y lleno de personalidad.
						Además, los cojines son una excelente manera de introducir colores atrevidos en un espacio neutral. Si tu sofá es de un tono sobrio, añade cojines en colores vibrantes como amarillo, turquesa o coral para crear un contraste impactante. También puedes optar por tejidos como el terciopelo o el lino para añadir textura y profundidad.", $fecha_actual, "", 1),
					
						array("text", "Cortinas opacas", "Las cortinas opacas no solo ofrecen privacidad y control sobre la luz, sino que también pueden ser una gran herramienta para mejorar la estética de una habitación. Opta por colores y tejidos que complementen tu decoración, y disfruta de un ambiente más tranquilo y acogedor.
						Estas cortinas son ideales para dormitorios o salas de cine en casa, donde se necesita bloquear la luz exterior por completo. Además, su grosor ayuda a aislar térmicamente la habitación, manteniéndola fresca en verano y cálida en invierno.
						Para maximizar su funcionalidad, combínalas con cortinas traslúcidas que permitan el paso de la luz durante el día. De esta manera, puedes controlar la iluminación y la privacidad según tus necesidades. Además, las cortinas opacas son una excelente manera de reducir el ruido exterior, creando un ambiente más tranquilo y relajado.", $fecha_actual, "", 1),
					
						array("ilu", "Iluminación de exteriores", "La iluminación de exteriores es esencial para crear un ambiente acogedor en patios, jardines o terrazas. Desde luces solares hasta focos empotrados, una buena iluminación exterior puede transformar por completo la atmósfera de tu espacio al aire libre, creando un entorno ideal para disfrutar por la noche.
						Las luces solares son una opción ecológica y económica, mientras que los focos empotrados pueden resaltar senderos o plantas. También puedes considerar lámparas de pie o colgantes para añadir un toque decorativo.
						Además, la iluminación exterior no solo mejora la estética de tu hogar, sino que también aumenta la seguridad. Instala luces con sensores de movimiento en entradas o pasillos para disuadir a intrusos y facilitar el acceso durante la noche. Con la combinación adecuada de luces, puedes convertir tu jardín en un espacio mágico y funcional.", $fecha_actual, "", 1),
					
						array("ilu", "Tiras LED Empotradas", "Las tiras LED empotradas son una opción moderna y discreta para iluminar diferentes áreas de tu hogar. Su versatilidad permite instalarlas en techos, paredes o debajo de los muebles para crear una iluminación suave y ambiente, ideal para zonas de paso o para acentuar detalles decorativos.
						Estas tiras son perfectas para crear efectos de luz indirecta que añaden profundidad y calidez a los espacios. Además, su bajo consumo energético las convierte en una opción sostenible y eficiente.
						Para un toque más dramático, elige tiras LED con cambio de color, que permiten ajustar la atmósfera según tu estado de ánimo o la ocasión. Desde tonos cálidos para relajarte hasta colores vibrantes para fiestas, las posibilidades son infinitas. Además, su instalación es sencilla y puede realizarse en casi cualquier superficie.", $fecha_actual, "", 1),

						array("acc", "Jarrones", "Los jarrones son una excelente manera de agregar estilo y color a cualquier habitación. Puedes elegir entre una gran variedad de formas, tamaños y materiales, como cerámica, vidrio o metal, y combinarlos con flores frescas o simplemente dejarlos como elementos decorativos solitarios.
						Los jarrones altos son ideales para espacios amplios, mientras que los pequeños pueden colocarse en mesas o estanterías. Además, son una forma sencilla de cambiar la decoración según la temporada o el estado de ánimo. Para un toque moderno, opta por jarrones geométricos en tonos neutros, o elige diseños coloridos para dar vida a un espacio minimalista.
						No olvides que los jarrones también pueden ser piezas de arte por sí mismos. Un jarrón único con un diseño escultórico puede convertirse en el punto focal de una habitación, atrayendo miradas y añadiendo personalidad a tu decoración.", $fecha_actual, "", 1),

						array("mobi", "Aparadores", "Los aparadores son una excelente opción para almacenar utensilios, vajillas o cualquier otro objeto en tu comedor o salón. Además de ser prácticos, también aportan un toque de sofisticación y elegancia a tus espacios, especialmente si eliges uno con detalles en madera o acabados metálicos.
						Estos muebles no solo ofrecen almacenamiento adicional, sino que también pueden servir como superficie para exhibir objetos decorativos como jarrones, libros o fotografías. Son perfectos para completar la decoración de un comedor formal o un salón clásico. Además, su diseño versátil permite adaptarlos a diferentes estilos, desde lo moderno hasta lo rústico.
						Para maximizar su funcionalidad, elige un aparador con cajones y estantes que te permitan organizar tus pertenencias de manera eficiente. Combínalo con accesorios decorativos que reflejen tu personalidad y estilo, creando un espacio único y acogedor.", $fecha_actual, "", 1),

						array("deco", "Boho Chic", "El estilo Boho Chic es ideal para quienes buscan una decoración relajada, colorida y llena de personalidad. Con su mezcla de textiles, patrones y accesorios vintage, el Boho Chic aporta un ambiente acogedor y ecléctico, perfecto para quienes disfrutan de la mezcla de culturas y estilos.
						Este estilo se caracteriza por su uso de colores vibrantes, tejidos naturales y piezas únicas que cuentan una historia. Incorpora alfombras tejidas, cojines estampados y plantas para crear un espacio lleno de vida y carácter. Además, los muebles de segunda mano y los objetos artesanales son clave para lograr este look.
						Para un toque final, añade elementos como colgantes de macramé, lámparas de fibras naturales y mantas tejidas. El Boho Chic no sigue reglas estrictas, así que siéntete libre de mezclar y combinar según tu gusto personal.", $fecha_actual, "", 2),

						array("ilu", "Lámparas de araña", "Las lámparas de araña no solo proporcionan una excelente iluminación, sino que también actúan como piezas decorativas impresionantes. Estas lámparas, con sus múltiples brazos y detalles en cristal o metal, añaden un toque de lujo y elegancia a cualquier espacio, desde comedores hasta salas de estar.
						Son ideales para techos altos, donde su diseño puede ser apreciado en toda su magnitud. Además, su luz difusa crea un ambiente cálido y acogedor, perfecto para reuniones familiares o cenas especiales. Puedes elegir entre diseños clásicos con cristales tallados o modelos modernos con líneas limpias y materiales innovadores.
						Para un impacto visual aún mayor, combina la lámpara de araña con otros elementos decorativos en tonos metálicos, como espejos dorados o mesas con patas de latón. Esto creará un ambiente sofisticado y cohesionado.", $fecha_actual, "", 2),

						array("acc", "Relojes de Pared", "Los relojes de pared no solo sirven para medir el tiempo, sino que también son un accesorio decorativo que puede completar la estética de tu hogar. Desde relojes modernos y minimalistas hasta modelos vintage, un reloj bien elegido puede ser el toque final que tu pared necesita.
						Los relojes grandes son ideales para espacios amplios, mientras que los más pequeños pueden colocarse en pasillos o habitaciones. Además, pueden ser una pieza central en la decoración de una sala de estar o comedor. Opta por diseños atrevidos con números grandes o relojes sin números para un look más contemporáneo.
						Para un toque personalizado, elige relojes con detalles únicos, como marcas romanas, esferas de madera o diseños abstractos. Un reloj de pared bien seleccionado no solo es funcional, sino que también puede convertirse en una obra de arte que refleje tu estilo personal.", $fecha_actual, "", 3),
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
			fecha_creacion  varchar(30),
			fecha_modificacion  varchar(30),
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
		$fecha_actual = date("Y-m-d h:ia");
		if(!isset($resultado) || get_num_rows($resultado) == 0){
			$stmt = mysqli_prepare($con, "insert into comment(contenido, fecha_creacion, fecha_modificacion, usuario_id, post_id) values(?, ?, ?, ?, ?)");
			$comments = array(
				array("Excelente", $fecha_actual, "", 1, 1),
				array("Lo recomendaré", $fecha_actual, "", 1, 2),
				array("Lo veo insuficiente", $fecha_actual, "", 1, 3),
				array("Está sacado de una revista", $fecha_actual, "", 1, 4),
				array("No estoy de acuerdo. Creo que aporta un punto de vista interesante con respecto al resto", $fecha_actual, "", 1, 5),
				array("Estoy de acuerdo", $fecha_actual, "", 1, 6),
				array("Esto me hace reflexionar", $fecha_actual, "", 1, 7),
				array("Es justo lo que necesitaba", $fecha_actual, "", 1, 8),
				array("No lo veo interesante", $fecha_actual, "", 1, 9),
				array("A veces pasa si lo lees por encima", $fecha_actual, "", 1, 10),
				array("Necesito más detalles", $fecha_actual, "", 1, 11),
				array("Necesito más detalles", $fecha_actual, "", 1, 12),
				array("Necesito más detalles", $fecha_actual, "", 1, 11),
				array("Necesito más detalles", $fecha_actual, "", 1, 12),
				array("Necesito más detalles", $fecha_actual, "", 1, 11),
				array("Necesito más detalles", $fecha_actual, "", 1, 10),
				array("Necesito más detalles", $fecha_actual, "", 1, 9),
				array("Necesito más detalles", $fecha_actual, "", 1, 11),
				array("Necesito más detalles", $fecha_actual, "", 1, 12),
				array("Muy interesante", $fecha_actual, "", 2, 3),
				array("Gracias por compartir", $fecha_actual, "", 3, 5),
				array("¡Gran aporte!", $fecha_actual, "", 4, 7),
				array("Esto me ayudó mucho", $fecha_actual, "", 5, 2),
				array("No estoy de acuerdo", $fecha_actual, "", 6, 4),
				array("Súper útil", $fecha_actual, "", 7, 6),
				array("Necesito más detalles", $fecha_actual, "", 8, 8),
				array("¡Fantástico!", $fecha_actual, "", 9, 9),
				array("Esto me hace reflexionar", $fecha_actual, "", 6, 6),
				array("Buen contenido", $fecha_actual, "", 8, 8),
				array("No estoy seguro de esto", $fecha_actual, "", 6, 6),
				array("Es justo lo que necesitaba", $fecha_actual, "", 6, 1),
				array("¡Motivador!", $fecha_actual, "", 4, 3),
				array("Estoy impresionado", $fecha_actual, "", 4, 5),
				array("¿Dónde puedo aprender más?", $fecha_actual, "", 6, 7),
				array("Sencillo pero efectivo", $fecha_actual, "", 7, 2),
				array("Interesante enfoque", $fecha_actual, "", 8, 4),
				array("Increíble trabajo", $fecha_actual, "", 9, 6),
				array("Voy a recomendarlo", $fecha_actual, "", 2, 8)
			);
			
			foreach($comments as $comment){
				mysqli_stmt_bind_param($stmt, "sssii", $comment[0], $comment[1], $comment[2], $comment[3], $comment[4]);
				mysqli_stmt_execute($stmt);
			}
		}
	};
?>