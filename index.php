<?php
function get_apache_headers(){
	foreach($_SERVER as $key => $value) {
		if(substr($key, 0, 5) == 'HTTP_') {
			$headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
		}
	}

	return $headers;
}

list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);

// Define file
$filename = 'test.mov';

if(!file_exists($filename)){
	Header("HTTP/1.1 400 Not Found");
	exit();
}
$filesize = filesize($filename); // Bytes
$mime_type = mime_content_type($filename);
$offset = 0;
$limit = $filesize;


$headers = get_apache_headers();

Header("Accept-Ranges: 0-$filesize");

if(isset($headers['Range'])){
	$range = $headers['Range'];

	// Get $offset and $limit bytes
	list($offset, $limit) = explode('-', substr($range, 6));

	if($offset == ''){
		$offset = 0;
	}

	if($limit == ''){
		$limit = $filesize;
	}

	if($limit > $filesize){
		$limit = $filesize;
	}

	Header('HTTP/1.0 206 Partial Content');
}

$content_length = $limit - $offset;

Header("Content-Type: $mime_type");
Header("Content-Length: $content_length");
Header("Content-Range: bytes $offset-$limit/$filesize");

$handle = fopen($filename, 'rb');

echo stream_get_contents($handle, $content_length, $offset);

fclose($handle);

exit();
?>