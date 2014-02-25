<?php
function get_apache_headers(){
	foreach($_SERVER as $key => $value) {
		if(substr($key, 0, 5) == 'HTTP_') {
			$headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
		}
	}

	return $headers;
}

// Define file
$filename = 'test.mov';
$filesize = filesize($filename); // Bytes

$headers = get_apache_headers();



if(isset($headers['Range'])){
	$range = $headers['Range'];

	// Get $offset and $limit bytes
	list($offset, $limit) = explode('-', substr($range, 6));
}
?>