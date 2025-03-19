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

        case 'comments-add':
            handleAddComment($con, $datos);
            break;

        case 'comments-delete':
            handleDeleteComment($con, $datos['id']);
            break;

        case 'comments-update':
            handleUpdateComment($con, $datos);
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

    function handleAddComment($con, $datos) {
        require_once("./controller/comments/addComments.php");
        handle_add_Comment($con, $datos);
    }

    function handleDeleteComment($con, $id) {
        require_once("./controller/comments/deleteComments.php");
        handle_delete_Comment($con, $id);
    }

    function handleUpdateComment($con, $datos) {
        require_once("./controller/comments/updateComments.php");
        handle_update_Comment($con, $datos);
    }
?>