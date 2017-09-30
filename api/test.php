<?php

	function filter_username($content) {
		$strArr = array();
		$arr = explode(" ", trim($content));
		foreach ($arr as $value) {
			if (startsWith($value, "@")) {
				$strArr[] = substr($value, 1);
			}
		}
		return $strArr;
	}

	function startsWith($haystack, $needle) {
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}

	$str = "@123 akdk @eiekf kskdif fkfkd @111 ";
	$filters = filter_username($str);
	$userStr = "";
	if (count($filters) > 0) {
		foreach ($filters as $value) {
			$userStr .= "'" . $value . "',";	
		}
		$userStr = substr($userStr, 0, strlen($userStr) - 1);
		$userQuery = "SELECT * FROM user WHERE user_name IN ($userStr)";
		echo $userQuery;
	}
	echo json_encode($filters);
?>