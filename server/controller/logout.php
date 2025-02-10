<?php
    function logout($con) {
        cerrar_conexion($con);
        $data = array("success" => true, "message" => "Sesión cerrada");
        echo json_encode($data);
    }
?>