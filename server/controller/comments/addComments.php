<?php
    require_once("./model/comments/comment.php");
    require_once("./controller/comments/getComments.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");

    function  handle_add_comment($con, $datos) {
        $id_post = $datos['idPost'];
        $id_user = $datos['userId'];
        $content = $datos['content'];

        $comments_result = add_comment($con, $id_post, $id_user, $content);
        $commentsByIdPost = get_comments_by_post_id($con, $id_post);
        $result = get_num_rows($commentsByIdPost);
        
        if($result == 0) {
            $data = array("success" => false, "message" => "No hay comentarios registrados");
            echo json_encode($data);
            exit;
        }
    
        if($commentsByIdPost != null) {
            $comments = result_to_array($commentsByIdPost);
            $data = array("success" => true, "message" => "Comentarios añadido", "data" => $comments);
            echo json_encode($data);
        }
    };
?>