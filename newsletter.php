<?php

include('db.php');
include('functions.php');

// date defaults to today
$date = date('Y-m-d');
if (isset($args[0])) {
  $date = array_shift($args);
}

$newsletter = $method;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $campaign = getCampaign($newsletter, $date);
  if (!$campaign) {
    http_response_code(404);
    die;
  }

  switch($datatype) {
    case 'html':
      include 'buildTemplate.php';
      break;
    case 'json':
    default:
      echo json_encode($campaign);
  }
}

// close mysql connection
mysqli_close($link);
