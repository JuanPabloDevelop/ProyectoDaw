<?php
	require_once("./db/session.php");
    require_once("./model/posts/post.php");

    switch ($datos['action']) {
        case 'post-get-all':
            handleGetPosts($con, $datos['filter']);
            break;

        case 'post-get-by-id':
            handleGetPostById($con, $datos['id']);
            break;  

        case 'post-delete':
            handleDeletePost($con, $datos['id']);
            break;
          
        case 'post-update':
            handleUpdatePost($con, $datos);
            break;

        default:
            $data = array("success" => false, "message" => "Acción no reconocida");
            echo json_encode($data);
            break;
    }

    
    function handleGetPosts($con, $filter) {
        require_once("./controller/posts/getPosts.php");
        if($filter == 'all') {
            handle_get_posts($con);
        } else {
            handle_get_posts_by_type($con, $filter);
        }
    }

    function handleGetPostById($con, $id) {
        require_once("./controller/posts/getPosts.php");
        handle_get_posts_by_id($con, $id);
    }

    function handleDeletePost($con, $id) {
        require_once("./controller/posts/deletePosts.php");
        handle_delete_post($con, $id);
    }
    
    function handleUpdatePost($con, $datos) {
        require_once("./controller/posts/updatePost.php");
        handle_update_post($con, $datos);
    }
?>