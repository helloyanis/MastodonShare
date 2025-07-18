<?php
/**
 * Common HTML header used by all pages. Loads stylesheets and meta tags.
 */

echo '<!DOCTYPE html>';
echo '<html>';

echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link href="'.URL.'s/bootstrap.min.css" rel="stylesheet">';
echo '<link href="'.URL.'s/font-awesome.min.css" rel="stylesheet">';
echo '<link rel="stylesheet" href="'.URL.'s/mastodonshare.css" >';
echo '<link rel="shortcut icon" href="'.URL.'s/img/favicon.ico" type="image/x-icon">';
include('inc_metas.php');
echo '</head>';

echo '<body>';
