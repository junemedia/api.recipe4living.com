<?php

date_default_timezone_set('America/Chicago');

$args = explode('/', trim($_REQUEST['path'], '/'));
$controller = array_shift($args);
list($method, $datatype) = explode('.', array_shift($args));
// default to return json unless specified
if (empty($datatype)) {
  $datatype = 'json';
}


if (file_exists("./$controller.php")) {
  include "./$controller.php";
}
else {
  http_response_code(404);
  die;
}
