<?php

include('db.php');


// get the HTTP method, path and body of the request

$method = $_SERVER['REQUEST_METHOD'];


// date defaults to today
$date = date('Y-m-d');
if (isset($args[0])) {
  $date = array_shift($args);
}

if ($method === 'GET') {
  $campaign = getCampaign($date);
  if (!$campaign) {
    http_response_code(404);
    die;
  }

  switch($datatype) {
    case 'html':
      include 'templates/onepot.html';
      break;
    case 'json':
    default:
      echo json_encode($campaign);
  }
}

// close mysql connection
mysqli_close($link);


function getCampaign($date) {
  $sql = "SELECT `id`, `subject`
          FROM `newsletterCampaign`
          WHERE `campaign` <= '$date'
          AND `newsletter` = 'onepot'
          ORDER BY `campaign` DESC
          LIMIT 1";
  $result = _query($sql);

  if (!$result || mysqli_num_rows($result) === 0) {
    return false;
  }

  $campaign = mysqli_fetch_object($result);
  $campaignItems = _getCampaignItems($campaign->id);
  if (!$campaignItems) {
    return false;
  }
  $campaign->items = $campaignItems;
  return $campaign;
}

function _getCampaignItems($campaignId) {
  $items = array();

  $sql = "SELECT `id`, `targetUrl`
          FROM `newsletterItem`
          WHERE `newsletterCampaignId` = $campaignId
          ORDER BY `order` ASC";
  $result = _query($sql);

  if (!$result) {
    return $items;
  }

  for ($i = 0; $i < mysqli_num_rows($result); $i++) {
    $item = mysqli_fetch_object($result);
    $slug = _getSlug($item->targetUrl);
    $article = _getArticle($slug);
    $article['targetUrl'] = $item->targetUrl;

    $items[] = $article;
    //echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
  }
return $items;

}

function _getArticle($slug) {
  $sql = "SELECT a.`id`, a.`title`, a.`teaser`, a.`slug`, ai.`filename` as image
          FROM `articles` as a
          LEFT JOIN `articleImages` as ai
          ON ai.`articleId` = a.`id`
          WHERE a.`slug` = '$slug'
          AND ai.`type` = 'default'
          LIMIT 1";
  $result = _query($sql);
  $article = mysqli_fetch_assoc($result);
  return $article;
}





/**
 *	Execute a query
 *
 *	@param string Query
 *	@return mixed Resource on success, else false
 */
function _query($sql) {
  global $link;

  // excecute SQL statement
  $result = mysqli_query($link, $sql);

  // die if SQL statement failed
  if (!$result) {
    return false;
  }

  return $result;
}

/**
 *  Get slug from a URL
 *
 *  @param string url
 *  return string slug
 */
function _getSlug($url) {
  $slug = '';

  $path = parse_url($url, PHP_URL_PATH);
  $path = explode('/', $path);
  // discard the first element which is empty since path has a leading slash
  array_shift($path);

  if ($path[0] === 'slidearticles') {
    if ($path[1] === 'details') {
      $slug = $path[2];
    }
  }

  return $slug;
}

