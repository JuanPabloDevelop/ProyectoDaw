<?php
	require_once("./db/session.php");
    require_once("./model/posts/post.php");

    switch ($datos['action']) {
        case 'filter-get-posts-types':
            handleGetPostsTypes($con);
            break;
        
        default:
            $data = array("success" => false, "message" => "Acción no reconocida");
            echo json_encode($data);
            break;
    }

    
    function handleGetPostsTypes($con) {
        require_once("./controller/filters/getFilters.php");
        handle_get_posts_types($con);
    }

    
?>