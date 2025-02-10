<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");
    function delete_user($con, $datos) {
        $id = $datos['userId'];

        $comprobarUsuario = obtener_usuario_id($con, $id);
        $result = obtener_num_filas($comprobarUsuario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El usuario no existe");
            echo json_encode($data);
        } else {
            $handlerDelete = eliminar_usuario($con, $id);
            if(!$handlerDelete) {
                $data =  array("success" => false, "message" => "No se ha podido eliminar el usuario");
                echo json_encode($data);
                exit;
            }
            $usuarios_result = obtener_usuarios($con);
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