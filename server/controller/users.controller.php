<?php
	require_once("./db/session.php");
    require_once("./model/users/usuario.php");

    switch ($datos['action']) {
        case 'user-login':
            handleLogin( $con, $datos);
            break;
        case 'user-register':
            handleRegister($con, $datos);
            break;
        case 'user-get-all':
            handleGetUsers($con);
            break;
        case 'user-get-by-id':
            handleGetUserById($con, $datos);
            break;
        case 'user-logout':
            handleLogout($con);
            break;
        case 'user-delete':
            handleDeleteUser($con, $datos);
            break;
        case 'user-update':
            handleUpdateUser($con, $datos);
            break;
        default:
            $data = array("success" => false, "message" => "Acción no reconocida");
            echo json_encode($data);
            break;
    }

    
    function handleGetUserById($con, $datos) {
        if (isset($datos['userId'])) {
            require_once("./controller/users/getUsers.php");
            handle_get_users_by_id($con, $datos['userId']);
        } else {
            $data = array("success" => false, "message" => "No se ha encontrado el usuario");
            echo json_encode($data);
        }
    }
    
    function handleLogin( $con, $datos) {
        if (isset($datos['email']) && isset($datos['pwd'])) {
            require_once("./controller/users/login.php");
            login($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No ha podido realizarse el login");
            echo json_encode($data);
        }
    }
    
    function handleUpdateUser($con, $datos) {
        if (isset($datos['nombre']) && isset($datos['pwd']) && isset($datos['apellidos']) && isset($datos['email'])) {
            require_once("./controller/users/update.php");
            update($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No ha podido realizarse el registro");
            echo json_encode($data);
        }
    }

    function handleRegister($con, $datos) {
        if (isset($datos['nombre']) && isset($datos['pwd']) && isset($datos['apellidos']) && isset($datos['email'])) {
            require_once("./controller/users/register.php");
            register($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No ha podido realizarse el registro");
            echo json_encode($data);
        }
    }

    function handleDeleteUser($con, $datos) {
        if (isset($datos['userId'])) {
            require_once("./controller/users/deleteUser.php");
            handle_delete_user($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No se ha podido eliminar el usuario");
            echo json_encode($data);
        }
    }

    function handleGetUsers($con) {
        require_once("./controller/users/getUsers.php");
        handle_get_users($con);
    }

    
    function handleLogout($con) {
        require_once("./controller/users/logout.php");
        logout($con);
    }
    
?>