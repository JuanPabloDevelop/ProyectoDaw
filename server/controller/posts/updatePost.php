<?php
    require_once("./model/posts/post.php");
    require_once("./controller/utils.php");
    require_once("./model/utils.php");
    function  handle_update_post($con, $datos) {

        $comprobarUsuario = get_posts_by_id($con,  $datos['id']);
        $result = get_num_rows($comprobarUsuario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El post no existe");
            echo json_encode($data);
        } else {
            $id = $datos['id'];
            $titulo = $datos['titulo'];
            $contenido = $datos['contenido'];
            $tipo = $datos['tipo'];
            $handlerDelete = update_post($con, $id, $titulo, $contenido, $tipo);
            if(!$handlerDelete) {
                $data =  array("success" => false, "message" => "No se ha podido actualizar el post");
                echo json_encode($data);
                exit;
            }
            $posts_result = get_posts($con);
            $posts = result_to_array($posts_result);
            $data =  array(
                "success" => true, 
                "message" => "El post se ha actualizado correctamente", 
                "data" => $posts
            );
            echo json_encode($data);
        }
    }
?>