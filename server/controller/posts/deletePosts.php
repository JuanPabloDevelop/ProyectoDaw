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