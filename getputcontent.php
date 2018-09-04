<?php
$method= $_SERVER['REQUEST_METHOD'];
if($method=='PUT'){
    $tmp_file='./putfile'; 
    $handle  = fopen('php://input', 'r');
    $fp = fopen($tmp_file, "w+");
    while ($data = fread($handle, 1024)) {
        fwrite($fp, $data);
    }
    fclose($fp);
    fclose($handle);
    if(filesize($tmp_file)){
            echo '上传成功,方法是'.$method.'大小是:'.filesize($tmp_file).'byte';
    } else {
            echo '上传失败';
    }
}else{
   echo '方法不对';    
}

