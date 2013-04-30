<?php
//脚本回调函数
$callback = $A->strGet('callback');
//上传目录
$fileDir = $A->strGet('dir');
//获取上传类型，确定上传后辍
$fileType = $A->strGet('type');
$suffix = $A->system['uploadSuffix'];
$suffix = isset($suffix[$fileType]) ? $suffix[$fileType] : $suffix['image'];
$fileExt = $dot = '';
foreach (explode(',', $suffix) as $v)
{
	$fileExt .= $dot . '*.'.$v;
	$dot = ';';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>本地计算机上的文件 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_TOOLS; ?>uploadify/uploadify.css"/>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
$(function()
{
	var _continue = $('#continue');
	
	$("#fileInput").uploadify
	({
		/*注意前面需要书写path的代码*/  
		'uploader'       : '<?php echo URL_TOOLS; ?>uploadify/uploadify.swf',
		'script'         : 'control/?mode=file.upload',
		'scriptData'     : {'type': '<?php echo $fileType; ?>', 'dir': '<?php echo $fileDir; ?>', 'passport': '<?php echo $A->getCookie('passport'); ?>'},
		'method'         : 'Post',
		'queueID'        : 'fileQueue', //和存放队列的DIV的id一致  
		'fileDataName'   : 'fileInput', //和input的name属性一致  
		'auto'           : true, //是否自动开始
		'multi'          : true, //是否支持多文件上传
		'sizeLimit'      : <?php echo $A->system['uploadSize'] * 1024 * 1024; ?>, //设置单个文件大小限制  
		'simUploadLimit' : 20, //最多同时上传的文件数目
		'queueSizeLimit' : 30, //队列中同时存在的文件个数限制  
		'fileDesc'       : '支持格式:<?php echo str_replace(',', '/', $suffix); ?>', //如果配置了以下的'fileExt'属性，那么这个属性是必须的  
		'fileExt'        : '<?php echo $fileExt; ?>',//允许的格式
		'buttonText'     : 'Browse', //按钮文字
		'buttonImg'		 : '<?php echo URL_SKIN; ?>upload.png',
		'cancelImg'      : '<?php echo URL_TOOLS; ?>uploadify/uploadify-cancel.png',
		'width'			 : 72,
		'height'		 : 25,
		onComplete: function(event, queueID, fileObj, response, data)
		{
			var obj = eval('('+response+')');
			var str = '';
			if (obj.error == '')
			{
				if (obj.isImage)
				{	
					str += '<li><span><a href="#" onclick="$$.filesUploadLayer.opener.<?php echo $callback; ?>({id:'+obj.fileID+',image:true,http:false,name:\''+obj.fileNewName+'\',title:\''+obj.fileName+'\',dir:\''+obj.fileDir+'\',url:\''+obj.fileUrl+'\',pipe:\''+obj.filePipe+'\',size:'+obj.fileSize+'});return false;">使用</a></span><img src="'+obj.fileUrl+'" />'+obj.fileName+'&nbsp; |&nbsp; '+(obj.fileSize/1024).toFixed(2)+'KB</li>';
				}
				else
				{
					str += '<li><span><a href="#" onclick="$$.filesUploadLayer.opener.<?php echo $callback; ?>({id:'+obj.fileID+',image:false,http:false,name:\''+obj.fileNewName+'\',title:\''+obj.fileName+'\',dir:\''+obj.fileDir+'\',url:\''+obj.fileUrl+'\',pipe:\''+obj.filePipe+'\',size:'+obj.fileSize+'});return false;">使用</a></span><img src="'+obj.fileIcon+'" />'+obj.fileName+'</li>';
				}
			}
			else 
			{
				str = '<li><img src="<?php echo URL_SKIN; ?>icon_stop.png" />'+obj.error+' - '+obj.fileName+'</li>';	
			}
			$('.files_list').append(str);
		},
		//上传出错后
		onError: function (type, info)
		{
			_continue.html(info);
		},
		//选择文件后
		onSelectOnce: function(event, data)
		{
			_continue.html('文件上传中...');
			$('.files_upload_swf_box').animate({height:'30px'}, 300);
		},
		//上传完成后
		onAllComplete: function(event, data)
		{
			_continue.html('上传完成：成功'+data.filesUploaded +'个，失败'+data.errors+'个。&nbsp;&nbsp;点击继续上传');
		}
	});  
	
	_continue.click(function()
	{
		$(this).html('');
		$('.files_upload_swf_box').animate({height:'200px'}, 300);
		$(this).blur();
		return false;
	});
});
</script>  
</head>
<body style="padding:0;">
<div class="tabs">
	<div></div>
	<a href="?mode=files.upload&callback=<?php echo $callback; ?>&type=<?php echo $fileType; ?>&dir=<?php echo $fileDir; ?>" class="C">本地</a>
	<a href="?mode=files.server&callback=<?php echo $callback; ?>&type=<?php echo $fileType; ?>&dir=<?php echo $fileDir; ?>">已上传</a>
	<a href="?mode=files.url&callback=<?php echo $callback; ?>&type=<?php echo $fileType; ?>&dir=<?php echo $fileDir; ?>">网络</a>
</div>
<div class="files_upload_main">
    <h1>本地计算机上的文件</h1>
    <div class="files_upload_swf_box">
    	<div class="files_upload_swf">
        	<p>
            	最多可选择20个文件同时上传<br />
            	单个文件大小限制：<?php echo $A->system['uploadSize']; ?>MB。<br />允许文件类型：<?php echo str_replace(',', ', ', $suffix); ?>
            </p>
    		<input type="file" name="fileInput" id="fileInput" />
        </div>
        <a href="#" id="continue"></a>
    </div>
    <div id="fileQueue"></div>
    <ol class="files_list"></ol>
</div>
</body>
</html>
