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
				array("deco", "Mid-Century", "<p>El estilo Mid-Century se caracteriza por su elegancia atemporal, combinando líneas limpias, colores neutros y mobiliario funcional. Con sus formas geométricas y materiales naturales como la madera y el metal, este estilo es ideal para aquellos que buscan una decoración moderna y sofisticada en su hogar.</p>
     					<p>Este estilo, que surgió a mediados del siglo XX, se inspira en la simplicidad y la funcionalidad de la época. Los muebles con patas delgadas, los tonos tierra y los detalles en metal son elementos clave que definen este look. Además, el Mid-Century se adapta perfectamente a espacios pequeños, ya que prioriza la practicidad sin sacrificar el diseño.</p>
     					<p>Para incorporar este estilo en tu hogar, puedes comenzar con piezas icónicas como un sofá de líneas rectas, una mesa de centro de madera con detalles metálicos o estanterías modulares. Combina estos elementos con textiles en tonos neutros y accesorios minimalistas para lograr un ambiente equilibrado.</p>", $fecha_actual, $fecha_actual, 1),				
 						
 						array("ilu", "Lámparas de bola de vidrio opalino", "<p>Las lámparas de bola de vidrio opalino son perfectas para iluminar cualquier habitación con un toque de elegancia y suavidad. Su diseño único crea una luz cálida y difusa que embellece tanto los espacios modernos como los más tradicionales, aportando una atmósfera acogedora y refinada.</p>
 						<p>Estas lámparas son ideales para colocarlas en mesas auxiliares, cómodas o incluso en el centro de una mesa de comedor. El vidrio opalino, con su acabado traslúcido, ayuda a difuminar la luz, creando un ambiente relajante y perfecto para momentos de descanso o reuniones familiares.</p>
 						<p>Además, su diseño atemporal las convierte en una pieza versátil que puede adaptarse a diferentes estilos de decoración. Si buscas un toque de sofisticación, combina estas lámparas con materiales como el mármol, el latón o la madera oscura. Su luz suave las hace perfectas para dormitorios, salones o incluso baños, donde la iluminación ambiental es clave para crear un espacio acogedor.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("mobi", "Mesas nido", "<p>Las mesas nido son ideales para hogares modernos, ya que ofrecen flexibilidad y funcionalidad. Puedes combinarlas en diferentes configuraciones según lo que necesites en cada momento. ¡Perfectas para ahorrar espacio y dar un toque elegante a cualquier habitación!</p>
 						<p>Estas mesas suelen venir en juegos de dos o tres, con tamaños decrecientes que permiten guardarlas una debajo de la otra. Son perfectas para salones pequeños o para añadir superficies adicionales en espacios donde se necesita versatilidad. Además, su diseño compacto las convierte en una opción práctica y estética.</p>
 						<p>Puedes elegir mesas nido con acabados en madera natural para un look cálido y orgánico, o optar por modelos con patas metálicas para un estilo más industrial. También existen opciones con cajones ocultos, ideales para guardar revistas, mandos a distancia u otros objetos pequeños. Sea cual sea tu elección, las mesas nido son una inversión inteligente para cualquier hogar.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("text", "Alfombras shaggy", "<p>Las alfombras shaggy son una excelente opción para darle un toque acogedor a cualquier sala de estar o dormitorio. Con su textura suave y esponjosa, hacen que el espacio se sienta más cálido y cómodo. Además, son perfectas para agregar un elemento de contraste a suelos duros.</p>
 						<p>Estas alfombras están disponibles en una amplia variedad de colores y tamaños, lo que permite adaptarlas a cualquier estilo de decoración. Su pelo largo y mullido no solo aporta confort bajo los pies, sino que también ayuda a reducir el ruido en habitaciones con eco.</p>
 						<p>Para maximizar su impacto, coloca una alfombra shaggy debajo de un sofá o una cama, asegurándote de que sus bordes queden visibles. Combínala con cojines y mantas en tonos coordinados para crear un look cohesionado y acogedor. Además, su mantenimiento es sencillo: basta con aspirarla regularmente para mantener su aspecto impecable.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("acc", "Velas y portavelas", "<p>Las velas y los portavelas no solo proporcionan una atmósfera acogedora, sino que también son perfectos para resaltar la decoración de tu hogar. Escoge portavelas elegantes que complementen tu estilo y velas aromáticas para crear un ambiente relajante y único.</p>
 						<p>Las velas pueden ser utilizadas en cualquier espacio, desde el baño hasta el dormitorio, y son ideales para momentos de relajación o cenas románticas. Además, los portavelas pueden ser de diferentes materiales como cerámica, metal o vidrio, lo que permite combinarlos con cualquier tipo de decoración.</p>
 						<p>Para crear un ambiente aún más especial, elige velas con aromas como lavanda, vainilla o cítricos. Estas no solo iluminan, sino que también perfuman el ambiente, convirtiendo cualquier habitación en un refugio de calma y bienestar. Colócalas en grupos de diferentes alturas para crear un efecto visual atractivo.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("ilu", "Iluminación focal", "<p>La iluminación focal es perfecta para resaltar detalles importantes en un espacio, como una obra de arte, una planta decorativa o una mesa central. Puedes utilizar focos dirigidos o lámparas de pie para crear efectos dramáticos y personalizar la atmósfera de la habitación.</p>
 						<p>Este tipo de iluminación no solo mejora la estética del espacio, sino que también ayuda a dirigir la atención hacia elementos clave de la decoración. Es ideal para salones, galerías o incluso en pasillos donde se quiera destacar un objeto en particular.</p>
 						<p>Además, la iluminación focal es una excelente manera de añadir profundidad y dimensión a una habitación. Juega con la intensidad de la luz y los ángulos para crear sombras y contrastes que añadan interés visual al espacio.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("ilu", "Lámparas colgantes de fibras naturales", "<p>Las lámparas colgantes de fibras naturales, como el mimbre o el ratán, aportan una sensación de calidez y naturaleza a cualquier espacio. Son ideales para cocinas, comedores o salones, donde el estilo relajado y orgánico puede dominar la decoración.</p>
 						<p>Estas lámparas no solo son funcionales, sino que también actúan como piezas decorativas que añaden textura y personalidad al ambiente. Además, su diseño ligero y natural las convierte en una opción perfecta para espacios que buscan un toque rústico o bohemio.</p>
 						<p>Para maximizar su impacto, combínalas con muebles de madera y textiles en tonos neutros. También puedes colgar varias lámparas de diferentes tamaños para crear un efecto de cascada que añada dinamismo al espacio. Su luz suave y difusa es perfecta para crear ambientes relajados y acogedores.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("acc", "Plantas naturales", "<p>Las plantas naturales son esenciales para crear un ambiente fresco y lleno de vida en cualquier hogar. Además de purificar el aire, agregan una dosis de color y textura que mejora el bienestar general de los espacios.</p>
 						<p>Puedes optar por plantas de interior resistentes que requieran poco mantenimiento, como los potos, las suculentas o los ficus. Estas plantas no solo decoran, sino que también ayudan a reducir el estrés y mejorar la calidad del aire.</p>
 						<p>Colócalas en macetas modernas o colgantes para maximizar su impacto visual. Además, las plantas son una excelente manera de dividir espacios o añadir altura a una habitación. Por ejemplo, una palmera en una esquina puede convertirse en un punto focal, mientras que un conjunto de pequeñas plantas en una repisa añade un toque de frescura.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("deco", "Art Déco", "<p>El estilo Art Déco es sinónimo de lujo y elegancia, con formas geométricas, detalles brillantes y materiales sofisticados como el vidrio, el metal y el mármol. Para lograr este estilo en casa, apuesta por muebles con líneas rectas, acabados brillantes y una mezcla de colores metálicos como dorado y plateado.</p>
 						<p>Este estilo, que surgió en los años 20, se caracteriza por su opulencia y glamour, y es perfecto para quienes buscan un ambiente sofisticado y atemporal. Incorpora espejos con marcos dorados, lámparas de cristal y detalles en negro para completar el look.</p>
 						<p>Para un toque final, añade accesorios como esculturas, relojes de pared o jarrones con diseños intrincados. El Art Déco es ideal para salones, dormitorios principales o incluso baños, donde se busca crear un ambiente lujoso y refinado.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("mobi", "Bancos de pie de cama", "<p>Los bancos de pie de cama son una excelente opción para añadir almacenamiento adicional y al mismo tiempo embellecer tu dormitorio. Pueden utilizarse para guardar mantas, cojines o accesorios y son ideales para dar un toque sofisticado a la decoración.</p>
 						<p>Estos bancos suelen estar tapizados en telas lujosas como terciopelo o lino, y pueden incluir detalles como patas de madera o metal. Además, son prácticos para sentarse mientras te vistes o para colocar ropa o libros de manera temporal.</p>
 						<p>Su diseño compacto los hace perfectos para dormitorios pequeños, donde cada pieza de mobiliario debe ser funcional y estética. Para integrarlos en tu decoración, elige un banco que combine con los colores y texturas de tu dormitorio.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("ilu", "Flexos industriales", "<p>Los flexos industriales, con su diseño robusto y funcional, son una gran adición a escritorios o zonas de lectura. Estos proporcionan una luz concentrada y permiten ajustar la dirección de la luz, lo que los hace perfectos para espacios de trabajo o ambientes con una estética urbana.</p>
 						<p>Su estilo industrial, con detalles en metal y acabados envejecidos, los convierte en una pieza decorativa además de funcional. Son ideales para estudios, oficinas en casa o incluso en salones con un estilo moderno y rústico.</p>
 						<p>Además, su brazo ajustable permite dirigir la luz exactamente donde la necesitas, lo que los hace perfectos para tareas que requieren precisión. Para completar el look, combínalos con muebles de madera recuperada o metal, y añade accesorios como estanterías abiertas o pósters vintage.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("acc", "Cojines de sofá", "<p>Los cojines de sofá son una forma sencilla y económica de actualizar el look de tu salón. Puedes jugar con colores, texturas y patrones para agregar interés visual, y cambiar los cojines cada temporada para renovar la decoración sin necesidad de hacer grandes cambios.</p>
 						<p>Los cojines no solo añaden confort, sino que también permiten experimentar con tendencias de diseño sin comprometerte a un cambio permanente. Combina diferentes tamaños y formas para crear un sofá único y lleno de personalidad.</p>
 						<p>Además, los cojines son una excelente manera de introducir colores atrevidos en un espacio neutral. Si tu sofá es de un tono sobrio, añade cojines en colores vibrantes como amarillo, turquesa o coral para crear un contraste impactante. También puedes optar por tejidos como el terciopelo o el lino para añadir textura y profundidad.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("text", "Cortinas opacas", "<p>Las cortinas opacas no solo ofrecen privacidad y control sobre la luz, sino que también pueden ser una gran herramienta para mejorar la estética de una habitación. Opta por colores y tejidos que complementen tu decoración, y disfruta de un ambiente más tranquilo y acogedor.</p>
 						<p>Estas cortinas son ideales para dormitorios o salas de cine en casa, donde se necesita bloquear la luz exterior por completo. Además, su grosor ayuda a aislar térmicamente la habitación, manteniéndola fresca en verano y cálida en invierno.</p>
 						<p>Para maximizar su funcionalidad, combínalas con cortinas traslúcidas que permitan el paso de la luz durante el día. De esta manera, puedes controlar la iluminación y la privacidad según tus necesidades. Además, las cortinas opacas son una excelente manera de reducir el ruido exterior, creando un ambiente más tranquilo y relajado.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("ilu", "Iluminación de exteriores", "<p>La iluminación de exteriores es esencial para crear un ambiente acogedor en patios, jardines o terrazas. Desde luces solares hasta focos empotrados, una buena iluminación exterior puede transformar por completo la atmósfera de tu espacio al aire libre, creando un entorno ideal para disfrutar por la noche.</p>
 						<p>Las luces solares son una opción ecológica y económica, mientras que los focos empotrados pueden resaltar senderos o plantas. También puedes considerar lámparas de pie o colgantes para añadir un toque decorativo.</p>
 						<p>Además, la iluminación exterior no solo mejora la estética de tu hogar, sino que también aumenta la seguridad. Instala luces con sensores de movimiento en entradas o pasillos para disuadir a intrusos y facilitar el acceso durante la noche. Con la combinación adecuada de luces, puedes convertir tu jardín en un espacio mágico y funcional.</p>", $fecha_actual, $fecha_actual, 1),
 					
 						array("ilu", "Tiras LED Empotradas", "<p>Las tiras LED empotradas son una opción moderna y discreta para iluminar diferentes áreas de tu hogar. Su versatilidad permite instalarlas en techos, paredes o debajo de los muebles para crear una iluminación suave y ambiente, ideal para zonas de paso o para acentuar detalles decorativos.</p>
 						<p>Estas tiras son perfectas para crear efectos de luz indirecta que añaden profundidad y calidez a los espacios. Además, su bajo consumo energético las convierte en una opción sostenible y eficiente.</p>
 						<p>Para un toque más dramático, elige tiras LED con cambio de color, que permiten ajustar la atmósfera según tu estado de ánimo o la ocasión. Desde tonos cálidos para relajarte hasta colores vibrantes para fiestas, las posibilidades son infinitas. Además, su instalación es sencilla y puede realizarse en casi cualquier superficie.</p>", $fecha_actual, $fecha_actual, 1),

						array("acc", "Jarrones", "<p>Los jarrones son una excelente manera de agregar estilo y color a cualquier habitación. Puedes elegir entre una gran variedad de formas, tamaños y materiales, como cerámica, vidrio o metal, y combinarlos con flores frescas o simplemente dejarlos como elementos decorativos solitarios.</p>
 						<p>Los jarrones altos son ideales para espacios amplios, mientras que los pequeños pueden colocarse en mesas o estanterías. Además, son una forma sencilla de cambiar la decoración según la temporada o el estado de ánimo. Para un toque moderno, opta por jarrones geométricos en tonos neutros, o elige diseños coloridos para dar vida a un espacio minimalista.</p>
 						<p>No olvides que los jarrones también pueden ser piezas de arte por sí mismos. Un jarrón único con un diseño escultórico puede convertirse en el punto focal de una habitación, atrayendo miradas y añadiendo personalidad a tu decoración.</p>", $fecha_actual, $fecha_actual, 1),
 
 						array("mobi", "Aparadores", "<p>Los aparadores son una excelente opción para almacenar utensilios, vajillas o cualquier otro objeto en tu comedor o salón. Además de ser prácticos, también aportan un toque de sofisticación y elegancia a tus espacios, especialmente si eliges uno con detalles en madera o acabados metálicos.</p>
 						<p>Estos muebles no solo ofrecen almacenamiento adicional, sino que también pueden servir como superficie para exhibir objetos decorativos como jarrones, libros o fotografías. Son perfectos para completar la decoración de un comedor formal o un salón clásico. Además, su diseño versátil permite adaptarlos a diferentes estilos, desde lo moderno hasta lo rústico.</p>
 						<p>Para maximizar su funcionalidad, elige un aparador con cajones y estantes que te permitan organizar tus pertenencias de manera eficiente. Combínalo con accesorios decorativos que reflejen tu personalidad y estilo, creando un espacio único y acogedor.</p>", $fecha_actual, $fecha_actual, 1),
 
 						array("deco", "Boho Chic", "<p>El estilo Boho Chic es ideal para quienes buscan una decoración relajada, colorida y llena de personalidad. Con su mezcla de textiles, patrones y accesorios vintage, el Boho Chic aporta un ambiente acogedor y ecléctico, perfecto para quienes disfrutan de la mezcla de culturas y estilos.</p>
 						<p>Este estilo se caracteriza por su uso de colores vibrantes, tejidos naturales y piezas únicas que cuentan una historia. Incorpora alfombras tejidas, cojines estampados y plantas para crear un espacio lleno de vida y carácter. Además, los muebles de segunda mano y los objetos artesanales son clave para lograr este look.</p>
 						<p>Para un toque final, añade elementos como colgantes de macramé, lámparas de fibras naturales y mantas tejidas. El Boho Chic no sigue reglas estrictas, así que siéntete libre de mezclar y combinar según tu gusto personal.</p>", $fecha_actual, $fecha_actual, 2),
 
 						array("ilu", "Lámparas de araña", "<p>Las lámparas de araña no solo proporcionan una excelente iluminación, sino que también actúan como piezas decorativas impresionantes. Estas lámparas, con sus múltiples brazos y detalles en cristal o metal, añaden un toque de lujo y elegancia a cualquier espacio, desde comedores hasta salas de estar.</p>
 						<p>Son ideales para techos altos, donde su diseño puede ser apreciado en toda su magnitud. Además, su luz difusa crea un ambiente cálido y acogedor, perfecto para reuniones familiares o cenas especiales. Puedes elegir entre diseños clásicos con cristales tallados o modelos modernos con líneas limpias y materiales innovadores.</p>
 						<p>Para un impacto visual aún mayor, combina la lámpara de araña con otros elementos decorativos en tonos metálicos, como espejos dorados o mesas con patas de latón. Esto creará un ambiente sofisticado y cohesionado.</p>", $fecha_actual, $fecha_actual, 2),
 
 						array("acc", "Relojes de Pared", "<p>Los relojes de pared no solo sirven para medir el tiempo, sino que también son un accesorio decorativo que puede completar la estética de tu hogar. Desde relojes modernos y minimalistas hasta modelos vintage, un reloj bien elegido puede ser el toque final que tu pared necesita.</p>
 						<p>Los relojes grandes son ideales para espacios amplios, mientras que los más pequeños pueden colocarse en pasillos o habitaciones. Además, pueden ser una pieza central en la decoración de una sala de estar o comedor. Opta por diseños atrevidos con números grandes o relojes sin números para un look más contemporáneo.</p>
 						<p>Para un toque personalizado, elige relojes con detalles únicos, como marcas romanas, esferas de madera o diseños abstractos. Un reloj de pared bien seleccionado no solo es funcional, sino que también puede convertirse en una obra de arte que refleje tu estilo personal.</p>", $fecha_actual, $fecha_actual, 3),
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
            // Mid-Century
            array("¡Me encanta el estilo Mid-Century! La combinación de materiales naturales y líneas sencillas es fascinante.", $fecha_actual, "", 2, 1),
            array("Muy buen artículo, me da muchas ideas para redecorar mi casa.", $fecha_actual, "", 3, 1),
			array("¡Este artículo me ha inspirado a redecorar toda mi sala! Estoy empezando con el estilo Mid-Century.", $fecha_actual, "", 4, 1),
			array("El estilo Mid-Century es perfecto para hogares pequeños. Sus líneas sencillas y funcionalidad hacen maravillas en el espacio.", $fecha_actual, "", 7, 1),

            // Lámparas de bola de vidrio opalino
            array("Nunca había considerado las lámparas de bola de vidrio opalino, ¡pero ahora quiero una para mi sala!", $fecha_actual, "", 2, 2),
            array("Es una opción genial para iluminar el espacio con estilo. Me encanta su versatilidad.", $fecha_actual, "", 4, 2),
			array("¡Gracias por este artículo! Siempre estoy buscando nuevas ideas de iluminación, y esto es justo lo que necesitaba.", $fecha_actual, "", 5, 2),

            // Mesas nido
            array("Las mesas nido son perfectas para espacios pequeños, siempre se ven elegantes y funcionales.", $fecha_actual, "", 5, 3),
            array("Excelente elección de tema. Las mesas nido se están volviendo cada vez más populares.", $fecha_actual, "", 6, 3),

            // Alfombras shaggy
            array("¡Las alfombras shaggy son tan acogedoras! Las tengo en mi sala y definitivamente mejoran el ambiente.", $fecha_actual, "", 3, 4),
            array("Muy interesante. Ahora tengo una mejor idea sobre cómo elegir la alfombra perfecta.", $fecha_actual, "", 2, 4),
			array("Excelente artículo, ¡definitivamente una alfombra shaggy es la mejor opción para cualquier habitación!", $fecha_actual, "", 5, 4),

            // Velas y portavelas
            array("Las velas aromáticas son ideales para crear un ambiente relajante en casa. ¡Me encanta la idea!", $fecha_actual, "", 6, 5),
            array("¡Gran elección! Aporta una atmósfera cálida y tranquila.", $fecha_actual, "", 7, 5),

            // Iluminación focal
            array("La iluminación focal puede cambiar completamente el ambiente de un espacio. Es clave para resaltar ciertos detalles.", $fecha_actual, "", 8, 6),
            array("Sin duda, la iluminación focal es una excelente forma de crear énfasis en elementos decorativos.", $fecha_actual, "", 5, 6),
			array("Una excelente forma de crear atmósferas únicas. ¡Me encanta este tipo de iluminación!", $fecha_actual, "", 7, 6),
			array("Me encanta cómo la iluminación focal puede transformar el ambiente de una habitación, creando énfasis donde más lo necesitamos.", $fecha_actual, "", 6, 6),

            //  Lámparas colgantes de fibras naturales
            array("Me encanta cómo las lámparas de fibras naturales aportan un toque orgánico. ¡Quiero poner una en mi comedor!", $fecha_actual, "", 9, 7),
            array("El estilo rústico nunca pasa de moda, estas lámparas son un acierto total.", $fecha_actual, "", 3, 7),
			array("Las lámparas de fibras naturales aportan una sensación de calidez. ¡Me gustaría tener varias en mi comedor!", $fecha_actual, "", 8, 7),
            array("¡Qué gran idea! Me encanta cómo las lámparas de fibras naturales pueden crear una atmósfera tan acogedora en el hogar.", $fecha_actual, "", 2, 7),

            //  Plantas naturales
            array("Las plantas siempre añaden frescura a los espacios. ¡Quiero más plantas en mi hogar!", $fecha_actual, "", 4, 8),
            array("Muy buen consejo, las plantas naturales son esenciales para mantener el aire limpio y darle vida a cualquier habitación.", $fecha_actual, "", 5, 8),

            //  Art Déco
            array("¡El Art Déco es tan elegante! Me encanta cómo se mezcla lo antiguo con lo moderno.", $fecha_actual, "", 6, 9),
            array("Excelente artículo. El Art Déco tiene un glamour único y es perfecto para darle lujo a cualquier espacio.", $fecha_actual, "", 2, 9),
			array("¡Me encanta el Art Déco! Es un estilo tan sofisticado y lleno de glamour, perfecto para cualquier hogar.", $fecha_actual, "", 4, 9),


            //  Bancos de pie de cama
            array("Los bancos de pie de cama son funcionales y con mucho estilo. ¡Son perfectos para cualquier dormitorio!", $fecha_actual, "", 7, 10),
            array("Totalmente de acuerdo, son ideales para añadir espacio de almacenamiento sin perder el estilo.", $fecha_actual, "", 8, 10),

            //  Flexos industriales
            array("Los flexos industriales son la opción perfecta para un espacio de trabajo moderno. Me encanta su diseño robusto.", $fecha_actual, "", 5, 11),
            array("¡Ideal para escritorios! Aporta un toque industrial muy elegante.", $fecha_actual, "", 9, 11),

            //  Cojines de sofá
            array("Los cojines de sofá realmente pueden cambiar el look de una sala. ¡Una forma económica de renovar la decoración!", $fecha_actual, "", 4, 12),
            array("Siempre tengo cojines nuevos cada temporada para darle un aire fresco al salón. ¡Gran consejo!", $fecha_actual, "", 3, 12),

            //  Cortinas opacas
            array("Las cortinas opacas son perfectas para mantener la privacidad. Las tengo en mi dormitorio y funcionan de maravilla.", $fecha_actual, "", 2, 13),
            array("Gran elección de tema, las cortinas opacas no solo mejoran la decoración, sino que también ofrecen mucha funcionalidad.", $fecha_actual, "", 6, 13),

            //  Iluminación de exteriores
            array("La iluminación exterior transforma completamente un jardín. ¡Una de las mejores inversiones para crear ambiente!", $fecha_actual, "", 9, 14),
            array("¡Gracias por los consejos! Estoy pensando en instalar luces solares en mi terraza para darle un toque acogedor.", $fecha_actual, "", 4, 14),

            //  Tiras LED Empotradas
            array("Las tiras LED empotradas son una excelente opción para crear efectos de luz sutiles. ¡Perfectas para cualquier ambiente!", $fecha_actual, "", 8, 15),
            array("Muy práctico, y me encanta la idea de cambiar el color de las luces según el ambiente que busque crear.", $fecha_actual, "", 2, 15),
			array("¡Son una opción genial para techos y paredes! Me gusta que son discretas pero muy efectivas para dar ambiente.", $fecha_actual, "", 6, 15),


            //  Jarrones
            array("Los jarrones siempre son un detalle decorativo que aporta mucho estilo a una habitación. ¡Me encanta la idea de tener varios!", $fecha_actual, "", 5, 16),
            array("¡Perfectos para cualquier espacio! Además de decorar, pueden servir como piezas de arte.", $fecha_actual, "", 3, 16),

            //  Aparadores
            array("Los aparadores no solo son funcionales, sino que también pueden convertirse en una pieza clave de la decoración. ¡Gran tema!", $fecha_actual, "", 2, 17),
            array("Estoy buscando uno para mi salón, ¡gracias por la inspiración!", $fecha_actual, "", 8, 17),
			array("¡Me encantan los aparadores con acabado en madera! Son perfectos para darle un toque cálido a cualquier sala.", $fecha_actual, "", 5, 17),

            //  Boho Chic
            array("Me encanta el estilo Boho Chic. Es tan relajado y lleno de personalidad. ¡Estoy pensando en aplicarlo a mi casa!", $fecha_actual, "", 9, 18),
            array("Es un estilo perfecto para aquellos que buscan algo único y con mucho carácter. ¡Gracias por la inspiración!", $fecha_actual, "", 6, 18),

            //  Lámparas de araña
            array("Las lámparas de araña son la pieza central perfecta para cualquier sala. Añaden elegancia y sofisticación.", $fecha_actual, "", 4, 19),
            array("¡Qué hermoso artículo! Las lámparas de araña realmente transforman un espacio.", $fecha_actual, "", 3, 19),

            //  Relojes de Pared
            array("¡Los relojes de pared son un detalle tan único! Siempre me ha gustado cómo combinan funcionalidad y estética.", $fecha_actual, "", 6, 20),
            array("Los relojes de pared pueden ser una excelente pieza central en la decoración de cualquier habitación.", $fecha_actual, "", 5, 20),
			array("¡Gran idea! Los relojes de pared son una forma fácil de darle personalidad a cualquier espacio.", $fecha_actual, "", 7, 20)

        );
			
			foreach($comments as $comment){
				mysqli_stmt_bind_param($stmt, "sssii", $comment[0], $comment[1], $comment[2], $comment[3], $comment[4]);
				mysqli_stmt_execute($stmt);
			}
		}
	};
?>