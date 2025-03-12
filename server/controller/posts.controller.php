<?php
	require_once("./db/session.php");
    require_once("./model/posts/post.php");

    switch ($datos['action']) {
        case 'post-get-all':
            handleGetPosts($con);
            break;
        // case 'post-get-by-id':
        //     handleGetUserById($con, $datos);
        //     break;
        // case 'post-delete':
        //     handleDeleteUser($con, $datos);
        //     break;
        // case 'post-update':
        //     handleUpdateUser($con, $datos);
        //     break;
        default:
            $data = array("success" => false, "message" => "Acción no reconocida");
            echo json_encode($data);
            break;
    }

    
    function handleGetPosts($con) {
        require_once("./controller/posts/getPosts.php");
        handle_get_posts($con);
    }

    
?>