<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");
    require_once("./model/utils.php");
    function  handle_get_users($con) {
        $usuarios_result = get_users($con);
        $result = get_num_rows($usuarios_result);
        
        if($result == 0) {
            $data = array("success" => false, "message" => "No hay usuarios registrados");
            echo json_encode($data);
            exit;
        }

        $usuarios = result_to_array($usuarios_result);
    
        $data = array("success" => true, "message" => "Usuarios encontrados", "data" => $usuarios);
        echo json_encode($data);
    };

    function handle_get_users_by_id($con, $idUsusario) {
        $usuario_result = get_user_id($con, $idUsusario);
        $result = get_num_rows($usuario_result);

        if($result == 0) {
            $data = array("success" => false, "message" => "No hay ningún usuario registrado con ese id");
            echo json_encode($data);
            exit;
        }

        $usuario = mysqli_fetch_assoc($usuario_result);
    
        $data = array("success" => true, "message" => "Usuario encontrado", "data" => $usuario);
        echo json_encode($data);
    };
?>