<?php
/**
 * Front controller for MastodonShare.
 *
 * Determines which page to display depending on the provided GET
 * parameters and the user's stored instance cookie.  When the user
 * has supplied both text and url the script redirects to the
 * appropriate instance's share endpoint.
 */
require_once 'm/config.php';

$instance = null;

/*
user arrives without parameters =
    1. has instance
        1. take them to the form so they can enter them

    2. does not have instance
        1. request instance
        2. save instance in a cookie
        3. take them to the form so they can enter them

user arrives with parameters
    1. has instance
        1. redirect to the share page of their instance with the parameters
    2. does not have instance
        1. save parameters in session
        2. request instance
        3. save instance in a cookie
        4. redirect to the share page of their instance with the parameters

- instance can be forced via _GET
*/

if(!empty($_GET['set_instance'])) {
	$instance = only_instance($_GET['set_instance']);
	$_COOKIE['instance'] = $instance;
	setcookie(
		"instance",
		$instance,
		time()+60*60*24*365,
		"/",
		".".DOMAIN,
		true
	);
} elseif(!empty($_GET['del_instance'])) {
	$_COOKIE = false;
	setcookie(
		"instance",
		"",
		time()-1000,
		"/",
		".".DOMAIN,
		true
	);
	header("Location: ".URL);
	exit;
} elseif($_GET['s'] === 'about') {
	include(HTML.$_GET['s'].'.php');
	exit;
} elseif($_GET['s'] === 'share') {
	if(empty($_GET['url'])) {
		$_GET['url']  = $_SERVER['HTTP_REFERER'];
	}
}

if(!empty($_COOKIE['instance'])) {
	$instance = only_instance($_COOKIE['instance']);
}
if(!empty($_GET['instance'])) {
	$instance = only_instance($_GET['instance']);
}

if(empty($_GET['url']) && empty($_GET['text'])) {

	if(empty($instance)) {
		$_GET['s'] = 'instance';
	} else {
		$_GET['s'] = 'create';
	}

} else {

	session_start();
	$_SESSION['text'] 	= htmlspecialchars(($_GET['text']));
	$_SESSION['url'] 	= htmlspecialchars(($_GET['url']));
	$_SESSION['note'] 	= htmlspecialchars(($_GET['note']));

	if(empty($instance)) {
		$_GET['s'] = 'shareform';
	} else {
		$_GET['s'] = 'redirect';
	}

}

include(HTML.$_GET['s'].'.php');
exit;