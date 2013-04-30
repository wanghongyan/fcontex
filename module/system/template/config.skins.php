<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>控制台皮肤 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function skinActive(dir)
{
	$$.get('control/?mode=config.skin&args='+dir, function()
	{
		window.top.skinSwitch('<?php echo DIR_SITE.'module/system/skins/'; ?>'+dir+'/');
	});
}
</script>
</head>
<body style="padding:0;">
	<div class="tabs">
		<div></div>
		<a href="?mode=config.themes">网站主题</a>
		<a href="?mode=config.skins" class="C">控制台皮肤</a>
	</div>
	<div class="skins" id="skins">
		<?php
		$handle = opendir(PATH_MODULE.'system/skins');
		$path = ''; $skin = array();
		$sel  = str_replace('/', '', str_replace(DIR_SITE.DIR_MODULE.'/system/skins/', '', URL_SKIN));
		while (($name = readdir($handle)) !== false)
		{
			$path = 'skins/'.$name.'/skin.php';
			if (!file_exists($path)) continue;
			$skin = include $path;
		?>
		<a href="#" onclick="$('#skins a').removeClass('C'); this.className='C'; skinActive('<?php echo $name; ?>');return false;"<?php if ($name == $sel) echo ' class="C"'; ?> title="点击应用皮肤">
			<cite></cite>
			<dl>
				<dt>
					<img src="skins/<?php echo $name.'/thumb.jpg'; ?>" width="160" height="100" />
				</dt>
				<dd class="T"><?php echo $skin['name']; ?>&nbsp; &nbsp;<?php echo $skin['version']; ?></dd>
				<dd><span>作者：</span><?php echo $skin['author']; ?>&nbsp; &nbsp; <span>更新时间：</span><?php echo $skin['date']; ?></dd>
				<dd><span>邮件地址：</span><?php echo $skin['mail']; ?></dd>
				<dd><span>官方支持：</span><?php echo $skin['weburl']; ?></dd>
			</dl>
		</a>
		<?php
		}
		?>
	</div>
</body>
</html>
