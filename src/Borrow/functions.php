<?php

function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function stripslashes_deep($values) {
	if (ini_get('magic_quotes_gpc') !== '1'){
		return $values;
	}

	if (is_array($values)) {
		foreach ($values as $key => $value) {
			$values[$key] = stripslashes_deep($value);
		}
	} else {
		$values = stripslashes($values);
	}
	return $values;
}

function vd(){
	$backtrace = debug_backtrace();
	$args = func_get_args();
	$size = sizeOf($args) -1;
	$i = 0;

	$title = $backtrace[0]['file'];
	$line = $backtrace[0]['line'];
	$content = '';
	for ($i; $i <= $size; $i++)
	{
		ob_start();
		var_dump($args[$i]);
		$out = ob_get_clean();
		$out = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $out);
		$content .= (strpos($out, 'xdebug-var-dump'))
			? $out
			: h($out);
		if ($i < $size)
		{
			$content .= "\n";
		}
	}
?>
<style>
.d-debug{
	color:#555;
	border-radius:1px;
	font-size:12px;
	font-family:"Courier New";
	padding:10px 36px;
	margin:10px;
	background:#ffecec;
	border:1px solid #f5aca6;
	text-shadow:0px 1px 1px rgba(50,50,50,.2);
}
.d-debug small{
	font-size:12px;
}
.d-debug .d-header{
	text-align:center;
}
.d-debug .d-header span{
	border-bottom:1px dotted #f5aca6;
	color:#777;
	font-size:9px;
	padding:6px;
	text-align:center;
}
.d-debug .d-content{
	word-wrap:break-word;
	line-height:1.5em;
	white-space: pre;
}
.d-debug .d-h1{
	color:#f5aca6;
}
.d-debug .d-h2{
	font-weight:100;
	color:#000;
}
.d-debug .d-h2:before{
	content:" :: ";
}
</style>
<div class="d-debug">
	<div class="d-header"><span><?php echo $title; ?> [line:<?php echo $line; ?>]</span></div>
	<div class="d-content"><?php echo $content; ?></div>
</div>
<?php
	die;
}


function env($key) {
	if ($key === 'HTTPS') {
		return isset($_SERVER['HTTPS'])
			? (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			: (strpos(env('SCRIPT_URI'), 'https://') === 0);
	}

	if ($key === 'SCRIPT_NAME') {
		if (env('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
			$key = 'SCRIPT_URL';
		}
	}

	$val = null;
	if (isset($_SERVER[$key])) {
		$val = $_SERVER[$key];
	} elseif (isset($_ENV[$key])) {
		$val = $_ENV[$key];
	} elseif (getenv($key) !== false) {
		$val = getenv($key);
	}

	if ($key === 'REMOTE_ADDR' && $val === env('SERVER_ADDR')) {
		$addr = env('HTTP_PC_REMOTE_ADDR');
		if ($addr !== null) {
			$val = $addr;
		}
	}

	if ($val !== null) {
		return $val;
	}

	switch ($key) {
		case 'SCRIPT_FILENAME':
			if (defined('SERVER_IIS') && SERVER_IIS === true) {
				return str_replace('\\\\', '\\', env('PATH_TRANSLATED'));
			}
			break;
		case 'DOCUMENT_ROOT':
			$name = env('SCRIPT_NAME');
			$filename = env('SCRIPT_FILENAME');
			$offset = 0;
			if (!strpos($name, '.php')) {
				$offset = 4;
			}
			return substr($filename, 0, -(strlen($name) + $offset));
			break;
		case 'PHP_SELF':
			return str_replace(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
			break;
		case 'CGI_MODE':
			return (PHP_SAPI === 'cgi');
			break;
		case 'HTTP_BASE':
			$host = env('HTTP_HOST');
			$parts = explode('.', $host);
			$count = count($parts);

			if ($count === 1) {
				return '.' . $host;
			} elseif ($count === 2) {
				return '.' . $host;
			} elseif ($count === 3) {
				$gTLD = array(
					'aero',
					'asia',
					'biz',
					'cat',
					'com',
					'coop',
					'edu',
					'gov',
					'info',
					'int',
					'jobs',
					'mil',
					'mobi',
					'museum',
					'name',
					'net',
					'org',
					'pro',
					'tel',
					'travel',
					'xxx'
				);
				if (in_array($parts[1], $gTLD)) {
					return '.' . $host;
				}
			}
			array_shift($parts);
			return '.' . implode('.', $parts);
			break;
	}
	return null;
} 