<?php
class JsonRPC
{
    private $host;
    private $port;
    private $conn;

    public function __construct($host = '127.0.0.1', $port = '80')
    {
        $this->host=$host;
        $this->port=$port;

    }
    protected function connect(){
        $this->conn = fsockopen($this->host, $this->port, $errno, $errstr, 3);
        if (!$this->conn) {
            return false;
        }
    }

    public function Call($url,$method, $params) {
        $this->connect();
        if (!$this->conn) {
            return false;
        }
        $content['id']="0";
        $content['jsonrpc']="2.0";
        $content['method'] = $method;
        $content['params']=$params;
        
        $content=json_encode($content);
        $contentlen=strlen((string)$content);
        $head="POST {$url} HTTP/1.1"."\r\n";
        // $content.='User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0'.PHP_EOL;
        $head.='Accept: application/json'."\r\n";
        $head.='Content-Type: application/json; charset=utf-8'."\r\n";
        $head.='Content-Length: '.$contentlen."\r\n";
        $head.='Connection:close'."\r\n\r\n";
        $head.=(string)$content;
        echo $head.PHP_EOL;
        echo 'request:'.PHP_EOL;
        $err = fwrite($this->conn, $head);
        if ($err === false)
            return false;
        stream_set_timeout($this->conn, 0, 1);
        $response='';
        while(!feof($this->conn)){
            $response.=fgets($this->conn,128);
        }
        $pos=strpos($response,"\r\n\r\n");
        $response=substr($response,$pos+4);
        return json_decode($response,true);
    }
    public function __destruct()
    {
        fclose($this->conn);
    }
}

$count=20000;
$response=0;
$error=0;

for($i=0;$i<$count;$i++){
    $client = new JsonRPC("192.168.0.12", 80);
    $data[] =array(
        'ip'=>getIpSegement($i),
    );
    $params['data']=$data;
    $r = $client->Call("ip.add",$params);
    if($r['result']['result']=='accepted'){
        $response++;
    }else{
        $error++;
    }
    $data=[];
    $client = null;
}

// var_export($r);
echo '成功:'.$response.PHP_EOL;
echo '失败:'.$error.PHP_EOL;
var_export($r);
function getIpSegement($num){
    if($num<=255){
        return '11.0.0.'.$num;
    }else if($num>255 && $num<65025){
        $four=$num % 255;
        $tree = ($num-$four)/255;
        return '11.0.'.$tree.'.'.$four;
    }   
}

