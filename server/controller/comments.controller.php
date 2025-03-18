<?php
	require_once("./db/session.php");
    require_once("./model/posts/post.php");

    switch ($datos['action']) {
        case 'comments-get-all':
            handleGetComments($con);
            break;

        case 'comments-get-by-post-id':
            handleGetCommentsByPostId($con, $datos['id']);
            break;
        
        default:
            $data = array("success" => false, "message" => "Acción no reconocida");
            echo json_encode($data);
            break;
    }

    function handleGetComments($con) {
        require_once("./controller/comments/getComments.php");
        handle_get_comments($con);
    }

    function handleGetCommentsByPostId($con, $datos) {
        require_once("./controller/comments/getComments.php");
        handle_get_comments_by_post_id($con, $datos);
    }

    
?>