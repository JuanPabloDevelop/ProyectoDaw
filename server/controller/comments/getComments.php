<?php
    require_once("./model/filters/filter.php");
    require_once("./model/utils.php");
    require_once("./controller/utils.php");

    function  handle_get_comments($con) {
        $comments_result = get_comments($con);
        $result = get_num_rows($comments_result);
        
        if($result == 0) {
            $data = array("success" => false, "message" => "No hay comentarios registrados");
            echo json_encode($data);
            exit;
        }

        $comments = result_to_array($comments_result);
    
        $data = array("success" => true, "message" => "Comentarios encontrados", "data" => $comments);
        echo json_encode($data);
    };

    function  handle_get_comments_by_post_id($con, $id_post) {
        $comments_result = get_comments_by_post_id($con, $id_post);
        $result = get_num_rows($comments_result);
        
        if($result == 0) {
            $data = array("success" => false, "message" => "No hay comentarios registrados");
            echo json_encode($data);
            exit;
        }

        $comments = result_to_array($comments_result);
    
        $data = array("success" => true, "message" => "Comentarios encontrados", "data" => $comments);
        echo json_encode($data);
    };

?>