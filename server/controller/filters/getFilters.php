<?php
    require_once("./model/filters/filter.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");
    function  handle_get_posts_types($con) {
        $posts_result = get_post_type_filters($con);
        $result = get_num_rows($posts_result);
        
        if($result == 0) {
            $data = array("success" => false, "message" => "No hay filtros registrados");
            echo json_encode($data);
            exit;
        }

        $posts = result_to_array($posts_result);
    
        $data = array("success" => true, "message" => "Posts encontrados", "data" => $posts);
        echo json_encode($data);
    };

?>