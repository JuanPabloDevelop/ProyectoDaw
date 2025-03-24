<?php
    require_once("./model/posts/post.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");

    function handle_add_post($con, $datos) {
        $titulo = $datos['titulo'];
        $contenido = $datos['contenido'];
        $tipo = $datos['tipo'];
        $autor_id = $datos['autor_id'];

        $insert_result = add_post($con, $titulo, $contenido, $tipo, $autor_id);
        $allPosts = get_posts($con); 

        if (!$insert_result) {
            $data = array("success" => false, "message" => "Error al insertar el post");
            echo json_encode($data);
            exit;
        }

        $numRows = get_num_rows($allPosts);
        if ($numRows == 0) {
            $data = array("success" => false, "message" => "No hay posts registrados");
            echo json_encode($data);
            exit;
        }

        $posts = result_to_array($allPosts);
        $data = array("success" => true, "message" => "Post creado correctamente", "data" => $posts);
        echo json_encode($data);
    }
?>
