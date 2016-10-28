<?php
function __autoload($className) {
	$path = dirname ( __FILE__ ) . "/" . $className . '.php';
	if (file_exists ( $path )) {
		require_once $path;
		return true;
	}
	return false;
}
