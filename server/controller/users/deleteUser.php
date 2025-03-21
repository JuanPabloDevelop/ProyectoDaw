<?php
    require_once("./model/users/usuario.php");
    require_once("./controller/utils.php");
    require_once("./model/utils.php");
    function  handle_delete_user($con, $datos) {
        $id = $datos['userId'];

        $comprobarUsuario = get_user_id($con, $id);
        $result = get_num_rows($comprobarUsuario);
        if($result == 0) {
            $data =  array("success" => false, "message" => "El usuario no existe");
            echo json_encode($data);
        } else {
            // Antes de eliminar el usuario tenemos que eliminar los posts
            $handlePosts = get_posts_by_user_id($con, $id);
            $resultePosts = get_num_rows($handlePosts);

            if($resultePosts > 0) {
                $posts = result_to_array($handlePosts);
                foreach ($posts as $post) {
                    $post_id = $post['id_post'];
   
                    $comprobarPost = get_posts_by_id($con, $post_id);
                    $result = get_num_rows($comprobarPost);
                    if($result > 0) {
                        //Antes de eliminar el post tenemos que eliminar los comentarios que tenga.
                        $handleComments = get_comments_by_post_id($con, $post_id);
                        $resultCommments = get_num_rows($comprobarPost);
            
                        if($resultCommments > 0) {
                            $comments = result_to_array($handleComments);
                            foreach ($comments as $comment) {
                                $comment_id = $comment['id_comment'];
                                delete_comment($con, $comment_id);
                            }
                        }
            
                        // Ahora eliminamos el post
                        $handlerDeletePost = delete_post($con, $post_id);
                        if(!$handlerDeletePost) {
                            $data =  array("success" => false, "message" => "No se ha podido eliminar el post");
                            echo json_encode($data);
                            exit;
                        }
                    }
                }
            }
  
            //Ahora eliminamos al usuario
            $handlerDeleteUser = delete_user($con, $id);
            if(!$handlerDeleteUser) {
                $data =  array("success" => false, "message" => "No se ha podido eliminar el usuario");
                echo json_encode($data);
                exit;
            }
            $usuarios_result = get_users($con);
            $usuarios = result_to_array($usuarios_result);
            $data =  array(
                "success" => true, 
                "message" => "El usuario se ha eliminado correctamente", 
                "data" => $usuarios
            );
            echo json_encode($data);
        }

    }
?>