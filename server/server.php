<?php
	require_once("./db/session.php");
    require_once("./db/initDb.php");
    require_once("./model/users/usuario.php");

    session_start();
    $con = conect();

    $datosJSON = file_get_contents("php://input");
    $datos = json_decode($datosJSON, true);


    // Crear BDD
    init_db($con);

    function handleRequest($con, $datos) {
        if (!isset($datos['action'])) {
            return;
        }

        if (strpos($datos['action'], 'user-') === 0) {
            require_once("./controller/users.controller.php");
        } 

        if (strpos($datos['action'], 'post-') === 0) {
            require_once("./controller/posts.controller.php");

        }
        
        
    }
    
    // Llama a la función handleRequest con los datos
    handleRequest($con, $datos);
?>