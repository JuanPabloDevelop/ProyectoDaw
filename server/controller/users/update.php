<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");
    require_once("./model/utils.php");
    
    function update($con, $datos) {
        $name = $datos['nombre'];
        $password = $datos['pwd'];
        $lastName = $datos['apellidos'];
        $email = $datos['email'];
        $rol = $datos['rol'];

        $comprobarUsuario = get_user($con, $email);
        $result = get_num_rows($comprobarUsuario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El usuario no existe");
            echo json_encode($data);
        }
        $user_array =  result_to_array($comprobarUsuario);
        $id = $user_array[0]['id_usuario'];


        update_user($con, $name, $lastName, $password, $email, $rol);
        $usuarios_result = get_users($con);
        $usuarios = result_to_array($usuarios_result);

        if ($rol == 0) {
            $data = array(
                "success" => true, 
                "message" => "El usuario se ha actualizado correctamente y es administrador", 
                "user" => array(
                    "name" => $name,
                    "lastName" => $lastName,
                    "id" => $id,
                    "email" => $email,
                    "rol" =>  $rol,
                ),
                "data" => $usuarios
            );
        } else {
            // Si el rol no es 0, puedes asignar un mensaje diferente o manejarlo como prefieras
            $data = array(
                "success" => true, 
                "message" => "El usuario se ha actualizado correctamente", 
                "user" => array(
                    "name" => $name,
                    "lastName" => $lastName,
                    "email" => $email,
                    "rol" =>  $rol,
                    "id" => $id,
                ),
            );
        }
        echo json_encode($data);
    }
?>



