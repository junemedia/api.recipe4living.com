<?php


if (file_exists("./$method.php")) {
  include "./$method.php";
}
else {
  http_response_code(404);
  die;
}
