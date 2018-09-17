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

