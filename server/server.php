<?php
	require_once("./db/session.php");
    require_once("./db/initDb.php");
    require_once("./model/users/usuario.php");

    session_start();
    $con = conect();

    $datosJSON = file_get_contents("php://input");
    $datos = json_decode($datosJSON, true);


    // Crear BDD
    init_db($con);

    function handleRequest($con, $datos) {
        if (!isset($datos['action'])) {
            return;
        }
    
        switch ($datos['action']) {
            case 'login':
                handleLogin( $con, $datos);
                break;
            case 'register':
                handleRegister($con, $datos);
                break;
            case 'getUsers':
                handleGetUsers($con);
                break;
            case 'getUserById':
                handleGetUserById($con, $datos);
                break;
            case 'logout':
                handleLogout($con);
                break;
            case 'deleteUser':
                handleDeleteUser($con, $datos);
                break;
            case 'updateUser':
                handleUpdateUser($con, $datos);
                break;
            default:
                $data = array("success" => false, "message" => "Acción no reconocida");
                echo json_encode($data);
                break;
        }
    }
    
    function handleGetUserById($con, $datos) {
        if (isset($datos['userId'])) {
            require_once("./controller/getUsers.php");
            handle_get_users_by_id($con, $datos['userId']);
        } else {
            $data = array("success" => false, "message" => "No se ha encontrado el usuario");
            echo json_encode($data);
        }
    }
    
    function handleLogin( $con, $datos) {
        if (isset($datos['email']) && isset($datos['pwd'])) {
            require_once("./controller/login.php");
            login($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No ha podido realizarse el login");
            echo json_encode($data);
        }
    }
    
    function handleUpdateUser($con, $datos) {
        if (isset($datos['nombre']) && isset($datos['pwd']) && isset($datos['apellidos']) && isset($datos['email'])) {
            require_once("./controller/update.php");
            update($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No ha podido realizarse el registro");
            echo json_encode($data);
        }
    }

    function handleRegister($con, $datos) {
        if (isset($datos['nombre']) && isset($datos['pwd']) && isset($datos['apellidos']) && isset($datos['email'])) {
            require_once("./controller/register.php");
            register($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No ha podido realizarse el registro");
            echo json_encode($data);
        }
    }

    function handleDeleteUser($con, $datos) {
        if (isset($datos['userId'])) {
            require_once("./controller/deleteUser.php");
            handle_delete_user($con, $datos);
        } else {
            $data = array("success" => false, "message" => "No se ha podido eliminar el usuario");
            echo json_encode($data);
        }
    }

    function handleGetUsers($con) {
        require_once("./controller/getUsers.php");
        handle_get_users($con);
    }

    
    function handleLogout($con) {
        require_once("./controller/logout.php");
        logout($con);
    }
    
    // Llama a la función handleRequest con los datos
    handleRequest($con, $datos);
    
?>




