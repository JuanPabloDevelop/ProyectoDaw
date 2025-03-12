<?php
	function get_post_type_filters($con){
		$resultado = mysqli_query($con, "select * from filter;");
		return $resultado;
	};
?>