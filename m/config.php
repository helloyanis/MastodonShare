<?php
$_GET['include_config'] = 1;

define('URL','https://mastodonshare.com/');
define('DOMAIN','mastodonshare.com');
define('APP_NAME','Mastodon Share');
define('ROOT','/var/www/mastodonshare.com/');
define('HTML',ROOT.'html/');

require_once(ROOT.'m/database.php');
require_once(ROOT.'m/general.php');
require_once(ROOT.'m/display.php');
require_once(ROOT.'m/opengraph.php');
require_once(ROOT.'m/ascii.php');
require_once(ROOT.'m/redirects.php');
require_once(ROOT.'m/simple_html_dom.php');

ini_set('display_errors','Off');
error_reporting(0);
