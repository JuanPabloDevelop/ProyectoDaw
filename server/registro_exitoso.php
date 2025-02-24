<?php
    session_start();

    $message = array(
        "success" => true, 
        "data" => array(
            "name" => $_SESSION["name"],
            "password" => $_SESSION["password"],
            "lastName" => $_SESSION["lastName"],
            "email" =>$_SESSION["email"],
        )
    );

    echo json_encode($message);
?>




