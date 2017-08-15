<?php

date_default_timezone_set('America/Chicago');

$args = explode('/', trim($_REQUEST['path'], '/'));
$controller = array_shift($args);
$method = array_shift($args);

if (file_exists("./$controller.php")) {
  include "./$controller.php";
}
else {
  http_response_code(404);
  die;
}
