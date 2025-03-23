<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");
    require_once("./controller/users/login.php");
    require_once("./model/utils.php");

    function register($con, $datos) {
        $name = $datos['nombre'];
        $password = $datos['pwd'];
        $lastName = $datos['apellidos'];
        $email = $datos['email'];
        $rol = '1';

        $comprobarUsuario = get_user($con, $email);
        $result = get_num_rows($comprobarUsuario);
        if($result == 0) {  
            $newUser = create_user($con, $name, $lastName, $password, $email, $rol);
            $checkNewUser = get_user($con, $email);
            $result = get_num_rows($checkNewUser);
            
            if($result == 0) {
                $data = array("success" => false, "message" => "Error al registrar el usuario");
                echo json_encode($data);
                exit;
            }
        
            if($checkNewUser != null) {
                $userData = result_to_array($checkNewUser);
                $data = array("success" => true, "message" => "Usuario registrado", "data" => array("id_usuario" => $userData[0]["id_usuario"], "nombre" => $userData[0]["nombre"], "apellidos" => $userData[0]["apellidos"], "email" =>$userData[0]["email"], "rol" => $userData[0]["rol"]));
                echo json_encode($data);
            }
        } else {
            $data =  array("success" => false, "message" => "El usuario ya existe");
            echo json_encode($data);
        };
    }
?>