<?php
	require_once("./db/session.php");
    require_once("./model/posts/post.php");

    switch ($datos['action']) {
        case 'post-get-all':
            handleGetPosts($con, $datos['filter']);
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

    
?>