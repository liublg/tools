<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style type='text/css'>
        *{
            font-family: 'microsoft yahei';
            color: #4A4A4A;
        }
        .upload{
            width: 700px;
            padding: 20px;
            border: 1px dashed #ccc;
            margin: 100px auto;
            border-radius: 5px;
        }
        .uploadBox{
            width: 100%;
            height: 35px;
            position: relative;
        }
        .uploadBox input{
            width: 200px;
            height: 30px;
            background: red;
            position: absolute;
            top: 2px;
            left: 0;
            z-index: 201;
            opacity: 0;
            cursor: pointer;
        }
        .uploadBox .inputCover{
            width: 200px;
            height: 30px;
            position: absolute;
            top: 2px;
            left: 0;
            z-index: 200;
            text-align: center;
            line-height: 30px;
            font-size: 14px;
            border: 1px solid #009393;
            border-radius: 5px;
            cursor: pointer;
        }
        .uploadBox button.submit{
            width: 100px;
            height: 30px;
            position: absolute;
            left: 230px;
            top: 2px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: #F0F0F0;
            outline: none;
            cursor: pointer;
        }
        .uploadBox button.submit:hover{
            background: #E0E0E0;
        }
        .uploadBox button.upagain{
            width: 100px;
            height: 30px;
            position: absolute;
            left: 340px;
            top: 2px;
            display: none;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: #F0F0F0;
            outline: none;
            cursor: pointer;
        }
        .uploadBox button.upagain:hover{
            background: #E0E0E0;
        }
        .processBar{
            display: inline-block;
            width: 0;
            height: 7px;
            position: absolute;
            left: 500px;
            top: 14px;
            background: #009393;
        }
        .processNum{
            position: absolute;
            left: 620px;
            color: #009393;
            font-size: 12px;
            line-height: 35px;
        }
    
    </style>
</head>
<body>
<div class="upload"> <div class="uploadBox">
    <span class="inputCover">选择文件</span>
	<form enctype="">
	    <input type="file" name="file" id="file" />
	    <button type="button" class="submit">上传</button>
	</form>
	<button type="button" class="upagain">继续上传</button>
	<span class="processBar"></span>
	<span class="processNum">未选择文件</span>
</div>
</div>
<script src="jquery.js"></script>
<script>
$(document).ready(function(){
    var inputCover = $(".inputCover");
    var processNum = $(".processNum");
    var processBar = $(".processBar");
    //上传准备信息
    $("#file").change(function(){
        var file = document.getElementById('file');
        var fileName = file.files[0].name;
	var fileSize = file.files[0].size;
        processBar.css("width",0); 
        //验证要上传的文件
	if(fileSize > 1024*1024*1024){
	    inputCover.html("<font>文件过大，请重新选择</font>");
	    processNum.html('未选择文件');
	    document.getElementById('file').value = '';
	    return false;
	}else{
	    inputCover.html(fileName+' / '+parseInt(fileSize/1024)+'K');
	    processNum.html('等待上传');
	}
    })

    //提交验证
    $(".submit").click(function(){
	if($("#file").val() == ''){
            alert('请先选择文件！');
	}else{
	    upload();
	}
    })

    //创建ajax对象，发送上传请求
    function upload(){
        var file = document.getElementById('file').files[0];
	var form = new FormData();
	form.append('myfile',file);
	$.ajax({
	    url: 'upload.php',//上传地址
	    async: true,//异步
	    type: 'post',//post方式
	    data: form,//FormData数据
	    processData: false,//不处理数据流 !important
 	    contentType: false,//不设置http头 !important
 	    xhr:function(){//获取上传进度            
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',function(e){//监听progress事件
                    var loaded = e.loaded;//已上传
                        var total = e.total;//总大小
                        var percent = Math.floor(100*loaded/total);//百分比
                        processNum.text(percent+"%");//数显进度
                        processBar.css("width",percent+"px");//图显进度
                        }, false);
                }
                return myXhr;
            },
 	    success: function(data){//上传成功回调
 		console.log("文档当前位置是："+data);//获取文件链接
 		document.cookie = "url="+data;//此行可忽略
 		$(".submit").text('上传成功');
 		$(".upagain").css("display","block");
             }
	})
    }

    //继续上传
    $(".upagain").click(function(){
	$("#file").click();
	processNum.html('未选择文件');
        processBar.css("width",0); 
        $(".submit").text('上传');
	document.getElementById('file').value = '';
	$(this).css("display","none");
    })
})
</script>
</body>
</html>
