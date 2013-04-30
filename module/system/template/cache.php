<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>缓存管理 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>jquery.ui.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function cache(mode, args)
{
	switch (mode)
	{
		case 'cache.clean':
			args = args || $$.selectval('#cache :checked:enabled[name=select]');
			if (args == '')
			{
				$$.alert({text:'请选择目录。'});
				return false;
			}
			$$.confirm({text:'确定清理所选目录？', ok:function()
			{
				$$.get('control/?mode='+mode+'&args=' + args, function(){$$.redirect();});
			}});
			break;
		case 'cache.time':
			$$.get('control/?mode=' + mode + '&args=' + args);
			break;
		default:
			$$.alert({text:'请选择操作。'});
	}
	
	return false;
}
</script>
</head>
<body>
    
    <ul class="cache" id="cache">
		<?php
		$handle = opendir(PATH_CACHE);
		$i = 0;
		while (($name = readdir($handle)) !== false)
		{
			$dir = PATH_CACHE.$name;
			if ($name == '.' || $name == '..') continue;
		?>
    	<li>
            <span>
            	<input type="checkbox" name="select" id="<?php echo $name; ?>" value="<?php echo $name; ?>" class="checkbox" /> <label for="<?php echo $name; ?>">选择</label> &nbsp; &nbsp;
            	<a href="#" onclick="cache('cache.clean', '<?php echo $name; ?>'); return false;" class="clean">清理</a>
            </span>
			<b class="<?php echo is_dir($dir) ? 'folder' : 'file'; ?>"><?php echo $name; ?></b>
        </li>
		<?php
			$i++;
		}
		closedir($handle);
		if ($i == 0) echo '<li>没有找到缓存。</li>';
		?> 
		<li>
			<span>
				<input type="checkbox" name="check" id="selectall" class="checkbox" onchange="$('#cache :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /> <label for="selectall">全选</label> &nbsp; &nbsp;
				<input type="button" value="清理所选" class="button" onclick="cache('cache.clean');" />
			</span>
			设置前端页面缓存更新周期 <input type="text" id="site_cachetime" style="width:4em;" value="<?php echo $A->loadConfig('system.site', 'site_cachetime'); ?>" class="text" /> 秒 &nbsp; <input type="button" value="保存" class="button" onclick="cache('cache.time', $('#site_cachetime').val());" />
		</li>       
    </ul>
	
</body>
</html>
