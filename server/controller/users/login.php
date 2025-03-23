<?php
    require_once("./model/users/usuario.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");

    function login($con, $datos) {
        $email = $datos['email'];
        $usuarioLogado = get_user($con, $email);
        $result = get_num_rows($usuarioLogado);
    
        if($result == 0) {
            $data = array("success" => false, "message" => "No se ha podido logar al usuario.");
            echo json_encode($data);
        } else {
            $info = result_to_array($usuarioLogado);
            $data = array("success" => true, "message" => "Usuario logado", "data" => array("id_usuario" => $info[0]["id_usuario"], "nombre" => $info[0]["nombre"], "apellidos" => $info[0]["apellidos"], "email" =>$info[0]["email"], "rol" => $info[0]["rol"]));
            echo json_encode($data);
        }
    }
?>