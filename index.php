<?php
function get_apache_headers(){
	foreach($_SERVER as $key => $value) {
		if(substr($key, 0, 5) == 'HTTP_') {
			$headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
		}
	}

	return $headers;
}

$filename = 'test.mov';

$headers = get_apache_headers();


?>