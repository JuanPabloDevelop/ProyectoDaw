<?php

    function init_db($con) {
        create_bdd($con);
		mysqli_select_db($con, $GLOBALS["dbname"]);
		create_user_table($con);
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

	//function crear_tabla_pelicula($con){
	//	mysqli_query($con, "create table if not exists pelicula(id_pelicula int primary key auto_increment, titulo varchar(255), sinopsis varchar(255), director int, foreign key (director) references director(id_director))");
	// };
?>