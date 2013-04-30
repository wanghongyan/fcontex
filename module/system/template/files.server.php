<?php
//脚本回调函数
$callback = $A->strGet('callback');
//上传目录
$fileDir = $A->strGet('dir');
//获取上传类型，确定上传后辍
$fileType = $A->strGet('type');
$show = $A->strGet('show');
$keyw = $A->strGet('keyw');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>已上传到服务器的文件 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
</head>
<body style="padding:0;">
<div class="tabs">
	<div></div>
	<a href="?mode=files.upload&callback=<?php echo $callback; ?>&type=<?php echo $fileType; ?>&dir=<?php echo $fileDir; ?>">本地</a>
	<a href="?mode=files.server&callback=<?php echo $callback; ?>&type=<?php echo $fileType; ?>&dir=<?php echo $fileDir; ?>" class="C">已上传</a>
	<a href="?mode=files.url&callback=<?php echo $callback; ?>&type=<?php echo $fileType; ?>&dir=<?php echo $fileDir; ?>">网络</a>
</div>
<div class="files_upload_main">
	<div class="files_search"><input name="keyw" id="keyw" type="text" value="<?php echo $keyw; ?>" class="text" /> <input type="button" class="button" value="搜索" onclick="location.href='?mode=files.server&type=<?php echo $fileType; ?>&show=<?php echo $show; ?>&keyw='+$('#keyw').val();" /></div>
    <h1>已上传到服务器的文件</h1>
    <div class="files_server_main" style="clear:both;">
    	<?php
		$pager = FCApplication::sharedPageTurnner();
		$pager->style = 'Simple';
		$where = ($show == 'image' ? ' at_isimage = 1' : '1=1');
		if (trim($keyw) != '') $where .= ' and (at_filename like "%'.$keyw.'%" or at_filenewname like "%'.$keyw.'%")';
		$query = $pager->parse('at_id', 'at_id, at_dir, at_filename, at_filenewname, at_isimage, at_size', 'T[attached]', $where, 'at_id desc');
		
        $rescount = $D->query('select count(at_id) as num from T[attached] where at_isimage = 1');
		$rcount   = $D->fetch($rescount);
		?>
        <div class="file_page"><?php echo $pager->turnner; ?></div>
    	<h2 class="files_server_title">
        	<a href="?mode=files.server&type=<?php echo $fileType; ?>&show=all"<?php if ($show == 'all' || trim($show) == '') echo ' class="current"'; ?>>所有文件</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="?mode=files.server&type=<?php echo $fileType; ?>&show=image"<?php if ($show == 'image') echo ' class="current"'; ?>>图像 (<?php echo $rcount['num']; ?>)</a>
        </h2>
        <div style="clear:both;"></div>
        <ul>
        <?php
		while ($r = $D->fetch($query))
		{
		?>
        	<li>
            	<span><a href="#" onclick="$$.filesUploadLayer.opener.<?php echo $callback; ?>({id:<?php echo $r['at_id']; ?>,image:<?php echo $r['at_isimage'] ? 'true' : 'false'; ?>,http:false,name:'<?php echo $r['at_filenewname']; ?>',title:'<?php echo $r['at_filename']; ?>',dir:'<?php echo $r['at_dir']; ?>',url:'<?php echo DIR_SITE.DIR_STORE.'/'.$A->system['uploadDir'].'/'.$r['at_dir'].$r['at_filenewname']; ?>',pipe:'<?php echo $R->getUrl('system/file/'.$A->strEnCode($r['at_id']),'',DIR_SITE); ?>',size:<?php echo $r['at_size']; ?>});return false;">使用</a></span>
                <a target="_blank" href="<?php echo $A->getThumb($r['at_dir'].$r['at_filenewname']); ?>"><img src="<?php echo $r['at_isimage'] ? $A->getThumb($r['at_dir'].$r['at_filenewname'], 200, 200) : $A->fileIcon($A->fileSuffix($r['at_filenewname'])); ?>" height="40" alt="<?php echo $r['at_filename']; ?>" title="<?php echo $r['at_filename']; ?>" /></a> 
                <strong><?php echo $r['at_filename']; ?></strong>&nbsp; |&nbsp; <?php echo round($r['at_size']/1024, 2).'KB'; ?>
            </li>
        <?php
		}
		?>
        </ul>
	</div>
</div>
</body>
</html>
