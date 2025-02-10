<?php
  function result_to_array($result){
    $array = array();
    while($row = mysqli_fetch_assoc($result)){
      $array[] = $row;
    }
    return $array;
  }
?>