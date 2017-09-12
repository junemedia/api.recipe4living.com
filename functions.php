<?php

function getCampaign($newsletter, $date) {
  $sql = "SELECT `id`, `subject`
          FROM `newsletterCampaign`
          WHERE `campaign` <= '$date'
          AND `newsletter` = '$newsletter'
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
  }
return $items;

}


function _getArticle($slug) {
  $sql = "SELECT a.`id`, a.`title`, a.`teaser`, a.`slug`, ai.`filename` as image
          FROM `articles` as a
          LEFT JOIN `articleImages` as ai
          ON ai.`articleId` = a.`id`
             AND ai.`type` = 'default'
          WHERE a.`slug` = '$slug'
          LIMIT 1";
  $result = _query($sql);
  $article = mysqli_fetch_assoc($result);
  return $article;
}
