<?php
    require_once("./model/comments/comment.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");

    function  handle_delete_Comment($con, $id) {
        $comprobarComentario = get_comments_by_id($con, $id);
        $result = get_num_rows($comprobarComentario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El comentario no existe");
            echo json_encode($data);
        } else {
            $handlerDelete = delete_comment($con, $id);
            if(!$handlerDelete) {
                $data =  array("success" => false, "message" => "No se ha podido eliminar el comentario");
                echo json_encode($data);
                exit;
            }
            $comments_result = get_comments($con);
            $comments = result_to_array($comments_result);
            $data =  array(
                "success" => true, 
                "message" => "El comentario se ha eliminado correctamente", 
                "data" => $comments
            );
            echo json_encode($data);
        }
    };
?>