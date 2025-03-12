<?php
    require_once("./model/posts/post.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");
    function  handle_get_posts($con) {
        $posts_result = get_posts($con);
        $result = get_num_rows($posts_result);
        
        if($result == 0) {
            $data = array("success" => false, "message" => "No hay posts registrados");
            echo json_encode($data);
            exit;
        }

        $posts = result_to_array($posts_result);
    
        $data = array("success" => true, "message" => "Posts encontrados", "data" => $posts);
        echo json_encode($data);
    };

    function handle_get_posts_by_type($con, $type) {
        $posts_result = get_posts_by_type($con, $type);
        $result = get_num_rows($posts_result);

        if($result == 0) {
            $data = array("success" => false, "message" => "No hay ningún post registrado con ese id");
            echo json_encode($data);
            exit;
        }

        $posts = result_to_array($posts_result);
    
        $data = array("success" => true, "message" => "Posts encontrado", "data" => $posts);
        echo json_encode($data);
    };
?>