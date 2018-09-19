<?php
//判断是http还是https
function httptype(){
    $httptype= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $httptype;
}
/**
* 优化格式的打印输出
* @param string $var 变量
* @param bool $return 是否return
* @return mixed
*/
function dump($var, $return=false)
{
	ob_start();
	var_dump($var);
	$output = ob_get_clean();
	if (!extension_loaded('xdebug')) {
		$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
		$output = '<pre style="text-align:left">'. htmlspecialchars($output, ENT_QUOTES). '</pre>';
	}
	if (!$return) {
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<pre style="text-align:left">';
		echo($output);
		echo '</pre>';
	} else {
		return $output;
	}
}

/**
* 用于判断文件后缀是否是图片
* @param string file 文件路径，通常是$_FILES['file']['tmp_name']
* @return bool
*/
function is_image_file($file)
{
	$fileextname = strtolower(substr(strrchr(rtrim(basename($file), '?'), "."), 1, 4));
	if (in_array($fileextname, array('jpg', 'jpeg', 'gif', 'png', 'bmp'))) {
		return true;
	} else {
		return false;
	}
}

/**
* 用于判断文件后缀是否是PHP、EXE类的可执行文件
* @param string file 文件路径
* @return bool
*/
function is_notsafe_file($file)
{
	$fileextname = strtolower(substr(strrchr(rtrim(basename($file), '?'), "."), 1, 4));
		if (in_array($fileextname, array('php', 'php3', 'php4', 'php5', 'exe', 'sh'))) {
			return true;
		} else {
			return false;
		}
}
/**
* 用于判断文件后缀是否是PHP、EXE类的可执行文件
* @param string file 文件路径
* @return bool
*/
function is_notsafe_file($file)
{
	$fileextname = strtolower(substr(strrchr(rtrim(basename($file), '?'), "."), 1, 4));
	if (in_array($fileextname, array('php', 'php3', 'php4', 'php5', 'exe', 'sh'))) {
		return true;
	} else {
		return false;
	}
}



/**
* t函数用于过滤标签，输出没有html的干净的文本
* @param string text 文本内容
* @return string 处理后内容
*/
function t($text)
{
	$text = nl2br($text);
	$text = real_strip_tags($text);
	$text = addslashes($text);
	$text = trim($text);
	return $text;
}
/**
* h函数用于过滤不安全的html标签，输出安全的html
* @param string $text 待过滤的字符串
* @param string $type 保留的标签格式
* @return string 处理后内容
*/
function h($text, $type = 'html')
{
// 无标签格式
$text_tags = '';
//只保留链接
$link_tags = '<a>';
//只保留图片
$image_tags = '<img>';
//只存在字体样式
$font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
//标题摘要基本格式
$base_tags = $font_tags.'<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
//兼容Form格式
$form_tags = $base_tags.'<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
//内容等允许HTML的格式
$html_tags = $base_tags.'<meta><ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';
//专题等全HTML格式
$all_tags = $form_tags.$html_tags.'<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
//过滤标签
$text = real_strip_tags($text, ${$type.'_tags'});
// 过滤攻击代码
if ($type != 'all') {
// 过滤危险的属性，如：过滤on事件lang js
	while (preg_match('/(<[^><]+)(allowscriptaccess|ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
		$text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
	}
	while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
		$text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
	}
}
	return $text;
}
/**
* 获取客户端IP地址
*/
function get_client_ip($type = 0)
{
	$type = $type ? 1 : 0;
	static $ip = null;
	if ($ip !== null) {
		return $ip[$type];
	}
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos = array_search('unknown', $arr);
		if (false !== $pos) {
			unset($arr[$pos]);
		}
		$ip = trim($arr[0]);
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u", ip2long($ip));
	$ip = $long ? array($ip, $long) : array('127.0.0.1', 0);
	return $ip[$type];
}

