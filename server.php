<?php
	require_once("./conectar.php");
    // session_start();
    conectar();
    $datosJSON = file_get_contents("php://input");
    $datos = json_decode($datosJSON, true);

    if(isset($datos['nombre']) && isset($datos['pwd']) && isset($datos['apellidos']) && isset($datos['email'])) {
        $name = $datos['nombre'];
        $password = $datos['pwd'];
        $lastName = $datos['apellidos'];
        $email = $datos['email'];

        if (strcmp('pepe', $name) == 0 && strcmp('123456', $password) == 0) {
            $_SESSION["name"] = $name;
            $_SESSION["password"] = $password;
            $_SESSION["lastName"] = $lastName;
            $_SESSION["email"] = $email;
            header("Location: registro_exitoso.php");
            return;
        };
    }
    $message =  array("success" => false, "message" => "error");
    echo json_encode($message);
    session_abort();
?>




