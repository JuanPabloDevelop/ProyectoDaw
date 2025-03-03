<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");
    require_once("./controller/login.php");

    function register($con, $datos) {
        $name = $datos['nombre'];
        $password = $datos['pwd'];
        $lastName = $datos['apellidos'];
        $email = $datos['email'];
        $rol = '1';

        $comprobarUsuario = get_user($con, $email);
        $result = get_num_rows($comprobarUsuario);
        if($result == 0) {
            create_user($con, $name, $lastName, $password, $email, $rol);

            $_SESSION["name"] = $name;
            $_SESSION["lastName"] = $lastName;
            $_SESSION["email"] =  $email;
            $_SESSION["rol"] = $rol;

            $data =  array(
                "success" => true, 
                "message" => "usuario registrado", 
                "data" => array("name" => $_SESSION["name"], "lastName" => $_SESSION["lastName"], "email" =>$_SESSION["email"], "rol" => $_SESSION["rol"])
            );
            echo json_encode($data);
        } else {
            $data =  array("success" => false, "message" => "El usuario ya existe");
            echo json_encode($data);
        };
    }
?>