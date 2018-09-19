<?php
    $handle = popen('passwd', 'w');
    echo "'$handle'; " . gettype($handle) . "\n";
    $res = fwrite($handle,"123456\n");
    var_dump($res);
    fwrite($handle,"123456\n");
    pclose($handle);
