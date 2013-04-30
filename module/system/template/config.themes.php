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
function themeActive(dir)
{
	$$.get('control/?mode=config.theme&args='+dir);
}
</script>
</head>
<body style="padding:0;">
	<div class="tabs">
		<div></div>
		<a href="?mode=config.themes" class="C">网站主题</a>
		<a href="?mode=config.skins">控制台皮肤</a>
	</div>
	<div class="skins" id="skins">
		<?php
		$handle = opendir(PATH_THEMES);
		$path = ''; $theme = array();
		while (($name = readdir($handle)) !== false)
		{
			$path = PATH_THEMES.$name.'/theme.php';
			if (!file_exists($path)) continue;
			$theme = include $path;
		?>
		<a href="#" onclick="$('#skins a').removeClass('C'); this.className='C'; themeActive('<?php echo $name; ?>');return false;"<?php if ($name == $A->site['site_theme']) echo ' class="C"'; ?> title="点击应用皮肤">
			<cite></cite>
			<dl>
				<dt>
					<img src="<?php echo URL_SITE.DIR_THEMES.'/'.$name.'/images/thumb.jpg'; ?>" width="160" height="100" />
				</dt>
				<dd class="T"><?php echo $theme['name']; ?>&nbsp; &nbsp;<?php echo $theme['version']; ?></dd>
				<dd><span>作者：</span><?php echo $theme['author']; ?>&nbsp; &nbsp; <span>更新时间：</span><?php echo $theme['date']; ?></dd>
				<dd><span>邮件地址：</span><?php echo $theme['mail']; ?></dd>
				<dd><span>官方支持：</span><?php echo $theme['weburl']; ?></dd>
			</dl>
		</a>
		<?php
		}
		?>
	</div>
</body>
</html>
