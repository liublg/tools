<?php
$url ='http://domain.com';//url地址
$contents='{"method":"getuser","data":{}}';//要发的参数
$opts  = array(
   'http' =>array(
     'method' => "POST" ,
     'header' => "Content-Type: application/json; charset=utf-8\r\n"."Accept: application/json\r\n",
     'content'=>$contents."\r\n" 
   )   
);
$context=stream_context_create($opts);
$fp =fopen ($url,'r',false,$context);
fpassthru($fp);
while (!feof($fp)) {
    $response .= fgets($fp, 128);
}
fclose($fp);
