<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");

    function update($con, $datos) {
        $name = $datos['nombre'];
        $password = $datos['pwd'];
        $lastName = $datos['apellidos'];
        $email = $datos['email'];
        $rol = $datos['rol'];

        $comprobarUsuario = obtener_usuario($con, $email);
        $result = obtener_num_filas($comprobarUsuario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El usuario no existe");
            echo json_encode($data);
        }
        actualizar_usuario($con, $name, $lastName, $password, $email, $rol);
            $usuarios_result = obtener_usuarios($con);
            $usuarios = result_to_array($usuarios_result);

        $data =  array(
            "success" => true, 
            "message" => "El usuario se ha actualizado correctamente", 
            "user" => array(
                "name" => $name,
                "lastName" => $lastName,
                "email" => $email,
                "rol" =>  $rol ,
                "password" =>  $password,
            ),
            "data" => $usuarios
        );
        echo json_encode($data);
    }
?>