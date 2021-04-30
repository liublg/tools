<?php

/**
 * @desc 由于 phpstorm /idea  http 客户端仅支持 curl 转 rest.http 不支持 rest.http 转 curl
 *       而 实际开发中 可能涉及内网请求 而又无法使用 http 代理的情况 因此 实现此小工具 rest.http 转 curl
 *       可以复制 生成的 curl 到内网机器执行
 * @usage php idea_set_client_to_curl.php dev  # 获取 开发环境
 * */

$ideaHttpClientFile   = "./api.http"; // 设置 rest http 的文件
$envFile              = "./rest-client.env.json"; // rest-client 的环境配置文件
$envSet               = $argv[1];
$isHaveEnv            = false;
$httpMethods          = ["GET", "POST", "DELETE", "PUT", "OPTIONS"];
$httpBodyContentStart = false;
$httpInfo             = getClearHttpArr();

if ($envSet) {
    if (!file_exists($envFile)) {
        throw new Exception("http client env 环境变量文件有误");
    }
    $envFileContent = file_get_contents($envFile);
    $envArr         = json_decode(trim($envFileContent), true);
    $envInfo        = $envArr[$envSet];
    $isHaveEnv      = true;
}

$fd = fopen($ideaHttpClientFile, "r");
if (!$fd) {
    throw new Exception("http client 文件获取失败");
}

while (!feof($fd)) {
    $rowContents = fgets($fd, 8192);
    $firstChar   = substr($rowContents, 0, 1);

    //如果是 # 开头 则为注释 或不处理的行 就跳过
    if ($firstChar == "#") {
        //如果是 ### 三个字符开头的行 , 则是一个 http 片段的结尾
        if (substr($rowContents, 0, 3) == "###") {
            $httpBodyContentStart = false;

            $curlCmd = "curl ";

            $curlCmd .= "-X {$httpInfo['method']} ";

            if (!empty($httpInfo['header'])) {
                $curlCmd .= "-H'{$httpInfo['header']}' ";
            }

            if (!empty($httpInfo['body'])) {
                $curlCmd .= "-d'{$httpInfo['body']}' ";
            }

            $curlCmd .= $httpInfo['url'];
            echo $curlCmd . PHP_EOL;

            $httpInfo = getClearHttpArr();
            continue;
        }
        echo $rowContents;
        continue;
    }

    if ($httpBodyContentStart) {
        $httpInfo['body'] .= trim($rowContents);
    }

    // 用 空格分割, 如果 分割后的数组第一个 为 HTTP 方法 则 记录 http 方法 和 url

    $splitRowContent = explode(" ", $rowContents);
    $checkHttpMethod = trim($splitRowContent[0]);

    if (in_array($checkHttpMethod, $httpMethods)) {
        $httpInfo['method'] = $checkHttpMethod;
        $url                = trim($splitRowContent[1]);
        if ($isHaveEnv) {
            foreach ($envInfo as $var => $value) {
                $replace = '{{' . $var . '}}';
                $url     = str_replace($replace, $value, $url);
            }
        }
        $httpInfo['url'] = $url;
        //再往下 获取一行
        while (!feof($fd)) {
            $rowContents = fgets($fd, 8192);
            if ($rowContents != "\n") {
                $httpInfo['header'] .= trim($rowContents) . ";";
            } else {
                $httpBodyContentStart = true;
                break;
            }
        }
    }
}

function getClearHttpArr()
{
    $httpInfo = [
        "method" => "",
        "url"    => "",
        "header" => "",
        "body"   => "",
    ];

    return $httpInfo;
}

