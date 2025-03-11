<?php
    require_once("./model/users/usuario.php");
    require_once("./model/utils.php");
    require_once("./model/utils.php");
    function login($con, $datos) {
        $email = $datos['email'];
        $usuarioLogado = get_user($con, $email);
        $result = get_num_rows($usuarioLogado);
    
        if($result == 0) {
            $data = array("success" => false, "message" => "No se ha podido logar al usuario.");
            echo json_encode($data);
        } else {
            $info = get_results($usuarioLogado);
            extract($info);

            if($datos['pwd'] == $info['password']) {
                $_SESSION["name"] = $info['nombre'];
                $_SESSION["lastName"] = $info['apellidos'];
                $_SESSION["email"] = $info['email'];
                $_SESSION["rol"] = $info['rol'];
                $_SESSION["id"] = $info['id_usuario'];
    
                $data =  array(
                    "success" => true, 
                    "message" => "usuario logado", 
                    "data" => array("name" => $_SESSION["name"], "lastName" => $_SESSION["lastName"], "email" =>$_SESSION["email"], "rol" => $_SESSION["rol"], "id" =>  $_SESSION["id"])
                );
                echo json_encode($data);
            } else {
                $data =  array("success" => false, "message" => "Contraseña incorrecta");
                echo json_encode($data);
            }
        }
    }
    
?>