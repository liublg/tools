<?php
/**
 * 根据翻译好的文本,自动生成对于使用gettext的po文件
 */
$newfile = 'new.file';
$outzh='zh_mydomain.po';
$outen='en_mydomain.po';
$fb = fopen($newfile,'r');
$fizh = fopen($outzh,'w+');
$fien = fopen($outen,'w+');
while(!feof($fb)){
    $content_content=fgets($fb,8192);
    $content_id=fgets($fb,8192);    
    $zh_id='msgid "'.trim($content_id).'"';
    $zh_str='msgstr "'.trim($content_content).'"'."\n\n";
    $en_id='msgid "'.trim($content_id).'"';
    $en_str='msgstr "'.trim($content_id).'"'."\n\n";
    if($content_content ||$content_id){
        $res_zh=fwrite($fizh,$zh_id."\n".$zh_str);
        $res_en=fwrite($fien,$en_id."\n".$en_str);
        if($res_zh && $res_en){
            echo "成功:{$zh_id}{$zh_str}".PHP_EOL;
        }   
    }   
}
fclose($fb);
fclose($fizh);
fclose($fien);
