<?php
	require_once("./db/session.php");
    require_once("./model/posts/post.php");

    switch ($datos['action']) {
        case 'post-get-all':
            handleGetPosts($con, $datos);
            break;

        case 'post-get-by-id':
            handleGetPostById($con, $datos['id']);
            break;
            
        case 'post-get-by-user-id':
            handleGetPostByUserId($con, $datos['id']);
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

    
    function handleGetPosts($con, $datos) {
        require_once("./controller/posts/getPosts.php");

        $filterType = $datos["filterbytype"];
        $filterUser = $datos["filterbyuser"];

        if($filterType == 'all' && $filterUser == 'allUsers') {
            handle_get_posts($con);
        } 

        if($filterType == 'all' && $filterUser == 'miUser') {
            handle_get_posts_by_user_id($con, $datos["userId"]);
        } 

        if($filterType != 'all' && $filterUser == 'allUsers') {
            handle_get_posts_by_type($con, $filterType);
        } 

        if($filterType != 'all' && $filterUser == 'miUser') {
            handle_get_posts_by_type_and_user($con, $filterType, $datos["userId"]);
        } 


    }

    function handleGetPostById($con, $id) {
        require_once("./controller/posts/getPosts.php");
        handle_get_posts_by_id($con, $id);
    }

    function handleGetPostByUserId($con, $id) {
        require_once("./controller/posts/getPosts.php");
        handle_get_posts_by_user_id($con, $id);
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