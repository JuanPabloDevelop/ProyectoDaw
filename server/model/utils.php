<?php

    function get_results($resultado){
        return mysqli_fetch_array($resultado);
    };

    function get_num_rows($resultado){
        return mysqli_num_rows($resultado);
    }

?>
