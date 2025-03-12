<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");
    require_once("./model/utils.php");
    function  handle_delete_user($con, $datos) {
        $id = $datos['userId'];

        $comprobarUsuario = get_user_id($con, $id);
        $result = get_num_rows($comprobarUsuario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El usuario no existe");
            echo json_encode($data);
        } else {
            $handlerDelete = delete_user($con, $id);
            if(!$handlerDelete) {
                $data =  array("success" => false, "message" => "No se ha podido eliminar el usuario");
                echo json_encode($data);
                exit;
            }
            $usuarios_result = get_users($con);
            $usuarios = result_to_array($usuarios_result);
            $data =  array(
                "success" => true, 
                "message" => "El usuario se ha eliminado correctamente", 
                "data" => $usuarios
            );
            echo json_encode($data);
        }

    }
?>