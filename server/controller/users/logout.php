<?php
    function logout($con) {
        close_conect($con);
        $data = array("success" => true, "message" => "Sesión cerrada");
        echo json_encode($data);
    }
?>