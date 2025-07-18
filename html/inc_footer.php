<?php
/**
 * Footer markup shared across pages.
 */


echo '<div id="f">';
if(!empty($_COOKIE['instance'])) {
    echo '<a href="'.URL.'?del_instance=true">Remove '.$_COOKIE['instance'].' as default</a>';
}
echo '<a href="'.URL.'">Home</a>';
echo '<a href="'.URL.'about">About</a>';
echo '</div>';

echo '</body>';
echo '</html>';