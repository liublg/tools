<script>
document.write('<script src="//' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')
</script>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
    <style type="text/css">
    </style>

<body>
<form id="upload-form" action="upload.php" method="POST" enctype="multipart/form-data" style="margin:15px 0" target="hidden_iframe">
    <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="test" />
        <p><input type="file" name="file1" /></p>
        <p><input type="submit" value="Upload" /></p>
</form>
    <div id="progress" class="progress" style="margin-bottom:15px;display:none;">
    <div class="label">0%</div>
</div>
</body>
<script src="jquery.js">
</script>
<script type="text/javascript">
        function fetch_progress(){
            $.get('progress.php',{ '<?php echo ini_get("session.upload_progress.name"); ?>' : 'test'}, function(data){
                var progress = parseInt(data);
                $('#progress .label').html(progress + '%');
                if(progress < 100){
                    setTimeout('fetch_progress()', 100);    //当上传进度小于100%时，显示上传百分比
                }else{
                    $('#progress .label').html('完成!'); //当上传进度等于100%时，显示上传完成
                }
            }, 'html');
    }

$('#upload-form').submit(function(){
    $('#progress').show();
    setTimeout('fetch_progress()', 100);//每0.1秒执行一次fetch_progress()，查询文件上传进度
});
</script>
</html>
