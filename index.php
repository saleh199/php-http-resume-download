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

if(!file_exists($filename)){
	Header("HTTP/1.1 400 Not Found");
	exit();
}
$filesize = filesize($filename); // Bytes
$mime_type = mime_content_type($filename);
$offset = 0;
$limit = $filesize;

$headers = get_apache_headers();

Header("Accept-Ranges: bytes");

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

    if($offset > $limit || $offset >= $filesize || $limit > $filesize){
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        Header("Content-Range: bytes 0-$filesize/$filesize");
        exit();
    }
    
    $content_length = $limit - $offset;

	Header('HTTP/1.0 206 Partial Content');
    Header("Content-Length: $content_length");
    Header("Content-Range: bytes $offset-$limit/$filesize");
}else{
    Header("Content-Length: $filesize");
}

Header("Content-Type: $mime_type");

$handle = fopen($filename, 'rb');

echo stream_get_contents($handle, $limit, $offset);

fclose($handle);

exit();
?>