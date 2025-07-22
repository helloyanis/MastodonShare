<?php

function detect_objects($a) {
	$return = [];
	$a = explode(' ',$a);
	foreach($a as $o) {
		$is = is_object_index($o);
		if($is) {
			array_push($return,$is);
		}
	}
	return $return;
}

function is_object_index($a) {
	// if(substr($a,0,1) == '#')
	if(preg_match('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u',$a)) {
		return ['hashtag',$a];
	}
	if(preg_match('/@([A-Za-z0-9\-\_]+)/u',$a)) {
		return ['mention',$a];
	}
	if(filter_var($a, FILTER_VALIDATE_URL)) {
		return ['url',$a];

	}
	return false;
}

function clean_i18n($a) {
	return preg_replace("/[^\p{L}\p{N}]/",'',$a);
}

function is_blank($value) {
    return empty($value) && !is_numeric($value);
}

function mastodon_link($user,$instance,$class = '') {
	$instance = trim($instance,'@ ');
	$user     = trim($user,'@ ');
	return '<a target="_new"'.(empty($class)?'':' class="'.$class.'"').' href="'.mastodon_url($user,$instance).'">@'.$user.'@'.$instance.'</a>';
}

function mastodon_name($user,$instance) {
	$instance = trim($instance,'@ ');
	$user     = trim($user,'@ ');
	return '@'.$user.'@'.$instance;
}

function mastodon_url($user,$instance) {
	return 'https://'.$instance.'/@'.$user;
}

function strlen_urls($a) {
	$t = array();
	$b = explode(' ',trim($a));
	foreach($b as $k => $v) {
		$trim = trim($v);
		if(filter_var($trim,FILTER_VALIDATE_URL)) {
			$t[] = 23+
				mb_strlen($v,mb_detect_encoding($a))-
				mb_strlen($trim,mb_detect_encoding($a)); //.' '.$v;
		// } elseif(preg_match('/@[a-zA-Z0-9\_]/',$trim) === true) {
		// 	$t[] =
		} else {
			$t[] = mb_strlen($v,mb_detect_encoding($a)); //.' '.$v;
		}
	}
	return array_sum($t)+count($t)-1;
	return array(strlen($a),$t,array_sum($t)+count($t)-1);
}
function tail($string,$tail) {
	$tail = trim($tail);
	$uno = 1;
	$string = str_ireplace(strrev($tail),'',strrev($string),$uno);
	$string = trim(strrev($string));
	return $string;
}
function public_url($path) {
	return str_ireplace(ROOT,URL,$path);
}
function show_domain($url) {
	$domain = parse_url($url,PHP_URL_HOST) ? : $url;
	return str_ireplace('www.','',$domain);
}
function show_date($time) {
	if($time < time()-(86400*30)) {
		return date('Y-m-d',$time);
	} elseif($time < time()-(86400*2)) {
		return ceil((time()-$time)/86400).' days ago';
	} elseif($time < time()-(86400)) {
		return '1 day ago';
	} elseif($time >= time()-86400
		&& $time < time()-3600) {
		return ceil((time()-$time)/3600).' hours ago';
	} elseif($time >= time()-3600
		&& $time < time()) {
		return '1 hour ago';
	} elseif($time < time()) {
		return date('Y-m-d H:i',$time);
	} else {
		return date('Y-m-d H:i',$time);
	}
}
function show_url($url,$max = 50) {
	$join = '(...)';
	$url = str_ireplace(array('http://','https://','www.'),'',$url);
	if(strlen($url) > $max-strlen($join)) {
		return substr($url,0,ceil($max/2)-strlen($join)).
				$join.
				substr($url,(ceil($max/2)-strlen($join))*-1);
	}
	return $url;
}

function wordwrap_urls($a,$cada,$separador = "\n") {
	$l = strlen_urls($a);
	if($l <= $cada) {
		return $a;
	}
	$r = array();
	$e = explode(' ',$a);
	$c = $j = 0;
	foreach($e as $k => $v) {
		$trim = trim($v,' .,');
		if(filter_var($trim,FILTER_VALIDATE_URL)) {
			$t = 23+
				mb_strlen($v,mb_detect_encoding($a))-
				mb_strlen($trim,mb_detect_encoding($a));
		} else {
			$t = mb_strlen($v,mb_detect_encoding($a));
		}
		$c += $t+1;
		if($c > $cada) {
			$j++;
			$c = 0;
		}
		$r[$j][] = $v; //' '.$t.' '.$c;
	}
	foreach($r as $j => $v) {
		$r[$j] = implode(' ',$v);
	}
	return implode($separador,$r);
}

function clean_user($string) {
	$string = strtolower(trim($string));
	return preg_replace('/[^a-z0-9\_]/','',$string);
}

function clean_instance($string) {

	if(filter_var($string, FILTER_VALIDATE_URL)) {
		$string = parse_url($string,PHP_URL_HOST);
	}

	$string = strtolower(trim($string));

	if(empty($string)) {
		return null;
	}

	if(stripos($string,'.') === false) {
		return null;
	}

	return preg_replace('/[^a-z0-9\-\.]/','',$string);
}

function clear_address($string) {
	$string = strtolower(trim($string));
	return preg_replace('/[^a-z0-9\-\.\_\@]/','',$string);
}

function instance_user_status($string) {

	$return = [
		'user'     => '',
		'instance' => '',
		'status'   => ''
	];

	if(filter_var($string, FILTER_VALIDATE_URL)) {
		// p("es url");
		if(stripos($string, "/") === false) {
			return $return;
			// return clean_instance($string);
		}
		$split = explode("/",$string);

		if(count($split) > 3 && empty($split[1])) {
			$return['instance'] = clean_instance($split[2]);
			$return['user']     = clean_user($split[3]);
			$return['status']   = ($split[4]);
		}

		return $return;
	}

	if(stripos($string, "@") === false) {
		return $return;
	}
	$split = explode("@",$string);

	if(count($split) == 3 && empty($split[0])) {
		unset($split[0]);
		$split = array_values($split);
	}

	if(count($split) == 2) {
		$return['user'] = clean_user($split[0]);

		if(stripos($split[1], "/") !== false) {
			$split2 = explode("/",$split[1]);

			if(count($split2) == 2) {
				$return['instance'] = clean_instance($split2[0]);
				$return['status']   = clean_instance($split2[1]);
			}
		}
	}

	return $return;
}

function only_instance($string) {
	if(stripos($string, "@") === false) {
		return clean_instance($string);
	}
	$split = explode("@",$string);
	if(count($split) >= 2) {
		return clean_instance(end($split));
	}
}

function only_user($string) {
	$instance = only_instance($string);
	return str_ireplace([$instance,'@'],'',$string);
}

function clean_general($string) {
	$string = str_ireplace([
		'"',
		'\\',
		// '/',
		';',
		'(',
		')',
		'{',
		'}',
		"'",
	],'',$string);
	$string = str_ireplace([
		'&',
	],[
		'& '
	],$string);
	return trim($string);
}

function clean_url($string) {
	$url = parse_url($string);
	// p($url);
	// exit;
	if(!empty($url['query'])) {
		$url['path'] .= '?'.$url['query'];
	}
	return mb_strtolower($url['host'].$url['path']);
}

function clean_url_no_case($string) {
	$url = parse_url($string);
	// p($url);
	// exit;
	if(!empty($url['query'])) {
		$url['path'] .= '?'.$url['query'];
	}
	return trim($url['host'].$url['path']);
}

function clean($string) {
	$string = trim(str_replace(' ', '-', $string));
	return preg_replace('/[^A-Za-z0-9\_\-]/','',$string);
}

function clean_az($string) {
	return mb_strtolower(preg_replace('/[^A-Za-z]/','',$string));
}

function clean_09($string) {
	return (preg_replace('/[^0-9]/','',$string));
}

function numeros($string) {
	return intval(preg_replace('/[^0-9]/','',$string));
}

function dominio($a) {
	$a = parse_url($a,PHP_URL_HOST);
	$a = str_ireplace('www.','',$a);
	return $a;
}

function lista($a,$between = ', ',$final = ' & ') {
	if(is_string($a)) {
		$b = array();
		$b[] = $a;
		$a = $b;
		unset($b);
	}
	$a = array_values($a);
	$a = array_unique($a);
	$a = array_map('trim',$a);
	$c = count($a);
	if($c == 1) {
		return $a[0];
	} elseif($c == 2) {
		return implode($a,$final);
	}
	$a[$c-2] = $a[$c-2].$final.$a[$c-1];
	unset($a[$c-1]);
	return implode($a,$between);
}

function ordenar($array,$campo,$desc = true,$sinclave = false) {
	
	$t = array();
	foreach($array as $key => $value) {
		$t[$key] = $value[$campo];
	}

	if($desc === true) {
		arsort($t);
	} elseif($desc === 'desc') {
		arsort($t);
	} elseif($desc === 'asc') {
		asort($t);
	} elseif($desc === 'nat') {
		natsort($t);
	} else {
		asort($t);
	}

	$r = array();
	foreach($t as $key => $value) {
		$r[$key] = $array[$key];
	}
	if($sinclave) {
		$r = array_values($r);
	}
	return $r;
}

function get_redirect_url($url){
	
	$url_parts = @parse_url($url);
	if (!$url_parts) return false;
	if (!isset($url_parts['host'])) return false; //can't process relative URLs
	if (!isset($url_parts['path'])) $url_parts['path'] = '/';

	$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
	if (!$sock) return false;

	$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n";
	$request .= 'Host: ' . $url_parts['host'] . "\r\n";
	$request .= "Connection: Close\r\n\r\n";
	fwrite($sock, $request);
	$response = '';
	while(!feof($sock)) $response .= fread($sock, 8192);
	fclose($sock);

	if(preg_match('/^Location: (.+?)$/m', $response, $matches)){
		if(substr($matches[1], 0, 1) == "/" )
			return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
		else
			return trim($matches[1]);

	} else {
		return false;
	}

}
function get_all_redirects($url){
	$redirects = array();
	while ($newurl = get_redirect_url($url)){
		if (in_array($newurl,$redirects)){
			break;
		}
		$redirects[] = $newurl;
		$url = $newurl;
	}
	return $redirects;
}

function get_final_url($url){
	$redirects = get_all_redirects($url);
	if(count($redirects)>0){
		return array_pop($redirects);
	} else {
		return $url;
	}
}

?>
