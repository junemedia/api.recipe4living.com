<?php

/**
 * This file is part of the Liquid package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Liquid
 */

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Liquid\\', __DIR__ . '/src/Liquid');

use Liquid\Liquid;
use Liquid\Template;

Liquid::set('INCLUDE_SUFFIX', 'tpl');
Liquid::set('INCLUDE_PREFIX', '');

$protectedPath = dirname(__FILE__) . DIRECTORY_SEPARATOR;

$liquid = new Template($protectedPath . 'templates' . DIRECTORY_SEPARATOR);

// Uncomment the following lines to enable cache
//$cache = array('cache' => 'file', 'cache_dir' => $protectedPath . 'cache' . DIRECTORY_SEPARATOR);
//$liquid->setCache($cache);

$liquid->parse(file_get_contents($protectedPath . 'templates' . DIRECTORY_SEPARATOR . $newsletter . '.html'));

// gotta throw in some junk Maropost would usually supply
$campaign->contact = array('email' => 'johns@junemedia.com');
$campaign->campaign = array('id' => '123456');

// convert our object to an array
$data = get_object_vars($campaign);

echo $liquid->render($data);
