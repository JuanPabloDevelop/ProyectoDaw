<?php
    require_once("./model/comments/comment.php");
    require_once("./controller/comments/getComments.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");

    function  handle_update_Comment($con, $datos) {
        $id_comment = $datos['commentId'];
        $content = $datos['content'];

        $comprobarComentario = get_comments_by_id($con, $id_comment);
        $result = get_num_rows($comprobarComentario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El comentario no existe");
            echo json_encode($data);
        } else {
            $resultData = result_to_array($comprobarComentario);
            $postId =  $resultData[0]['id_post'];
            $fechaCreacion = $resultData[0]['fecha_creacion'];
            $idUsuario = $resultData[0]['id_usuario'];

            $handlerUpdate = update_comment($con, $content,  $fechaCreacion, $idUsuario, $postId, $id_comment);
            if(!$handlerUpdate) {
                $data =  array("success" => false, "message" => "No se ha podido actualizar el comentario");
                echo json_encode($data);
                exit;
            }
            $comments_result = get_comments($con);
            $comments = result_to_array($comments_result);
            $data =  array(
                "success" => true, 
                "message" => "El comentario se ha actualizado correctamente", 
                "data" => $comments,
            );
            echo json_encode($data);
        }
    };
?>