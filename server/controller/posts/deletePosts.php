<?php
    require_once("./model/posts/post.php");
    require_once("./controller/utils.php");
    require_once("./model/utils.php");
    function  handle_delete_post($con, $id) {
        $comprobarPost = get_posts_by_id($con, $id);
        $result = get_num_rows($comprobarPost);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El post no existe");
            echo json_encode($data);
        } else {
            //Antes de eliminar el post tenemos que eliminar los comentarios que tenga.
            $handleComments = get_comments_by_post_id($con, $id);
            $resultCommments = get_num_rows($comprobarPost);

            if($resultCommments > 0) {
                $comments = result_to_array($handleComments);
                foreach ($comments as $comment) {
                    $comment_id = $comment['id_comment'];
                    delete_comment($con, $comment_id);
                }
            }

            // Ahora eliminamos el post
            $handlerDelete = delete_post($con, $id);
            if(!$handlerDelete) {
                $data =  array("success" => false, "message" => "No se ha podido eliminar el post");
                echo json_encode($data);
                exit;
            }
            $posts_result = get_posts($con);
            $posts = result_to_array($posts_result);
            $data =  array(
                "success" => true, 
                "message" => "El post se ha eliminado correctamente", 
                "data" => $posts
            );
            echo json_encode($data);
        }
    }
?>