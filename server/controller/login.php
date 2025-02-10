<?php
    require_once("./model/users/usuario.php");
    function login($con, $datos) {
        $email = $datos['email'];
        $password = $datos['pwd'];
        $usuarioLogado = obtener_usuario($con, $email);
        $result = obtener_num_filas($usuarioLogado);
    
        if($result == 0) {
            $data = array("success" => false, "message" => "No se ha podido registrar el usuario.");
            echo json_encode($data);
        } else {
            $info = obtener_resultados($usuarioLogado);
            extract($info);
            if($password != $info['password']) {
                $data =  array("success" => false, "message" => "Contraseña incorrecta");
                echo json_encode($data);
            } else {
                $_SESSION["name"] = $info['nombre'];
                $_SESSION["lastName"] = $info['apellidos'];
                $_SESSION["email"] = $info['email'];
                $_SESSION["rol"] = $info['rol'];
    
                $data =  array(
                    "success" => true, 
                    "message" => "usuario logado", 
                    "data" => array("name" => $_SESSION["name"], "lastName" => $_SESSION["lastName"], "email" =>$_SESSION["email"], "rol" => $_SESSION["rol"])
                );
                echo json_encode($data);
            }
        }
    }
    
?>