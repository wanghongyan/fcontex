<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>全局配置 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function settings(mode, args)
{
	switch (mode)
	{
		case 'config.site':
			$$.post('control/?mode=' + mode, $('#form_detail').serialize());
			break;
		default:
			$$.alert({text : '无效参数 [ '+mode+' ]。'});
			break;
	}
	
	return false;
}

</script>
</head>
<body>
<?php
$site = $A->loadConfig('system.site');
?>
<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return settings('config.site');">
<table class="table_form" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<th>网站名称</th>
		<td><input class="text" type="text" name="site_name" id="site_name" value="<?php echo isset($site['site_name']) ? $site['site_name'] : ''; ?>" /></td>
	</tr>
	<tr>
		<th>网站域名</th>
		<td><input class="text" type="text" name="site_domain" id="site_domain" value="<?php echo isset($site['site_domain']) ? $site['site_domain'] : ''; ?>" /></td>
	</tr>
	<tr>
		<th>域名锁定</th>
		<td>
        	<input type="checkbox" class="checkbox" value="1" name="site_domainlock" id="site_domainlock"<?php if(isset($site['site_domainlock']) && $site['site_domainlock'])echo ' checked="checked"'; ?> /> <label for="site_domainlock">其他域名访问时重定向到此域名</label>
		</td>
	</tr>
	<tr>
		<th>页面标题</th>
		<td>
        	<input type="text" value="<?php echo isset($site['site_title']) ? $site['site_title'] : ''; ?>" class="text" name="site_title" />
		</td>
	</tr>
    <tr>
		<th>页面关键字</th>
		<td>
        	<textarea name="site_keywords" id="site_keywords"><?php echo isset($site['site_keywords']) ? $site['site_keywords'] : ''; ?></textarea>
		</td>
	</tr>
    <tr>
		<th>页面描述</th>
		<td>
        	<textarea name="site_description" id="site_description"><?php echo isset($site['site_description']) ? $site['site_description'] : ''; ?></textarea>
		</td>
	</tr>
    <tr>
		<th>统计代码</th>
		<td>
        	<textarea name="site_counter" id="site_counter"><?php echo isset($site['site_counter']) ? $site['site_counter'] : ''; ?></textarea>
		</td>
	</tr>
	<tr>
		<th>版权信息</th>
		<td>
        	<textarea name="site_copyright" id="site_copyright"><?php echo isset($site['site_copyright']) ? $site['site_copyright'] : ''; ?></textarea>
		</td>
	</tr>
	<tr>
		<th>路径重写</th>
		<td>
        	<input type="checkbox" class="checkbox" value="1" name="site_rewrite" id="site_rewrite"<?php if(isset($site['site_rewrite']) && $site['site_rewrite'])echo ' checked="checked"'; ?> /> <label for="site_rewrite">使用URL路径重写</label>
		</td>
	</tr>
	<tr>
		<th>页面大小</th>
		<td>
        	<input type="text" class="text" name="site_pagesize" value="<?php echo isset($site['site_pagesize']) ? $site['site_pagesize'] : ''; ?>" /><br />
        	<span>前端列表页每页显示的信息数量</span>
		</td>
	</tr>
	<tr>
		<th>评论控制</th>
		<td>
        	<input type="checkbox" class="checkbox" value="1" name="site_commentlock" id="site_commentlock"<?php if(isset($site['site_commentlock']) && $site['site_commentlock'])echo ' checked="checked"'; ?> /> <label for="site_commentlock">关闭全站评论功能</label>
		</td>
	</tr>
	<tr>
		<th>留言控制</th>
		<td>
        	<input type="checkbox" class="checkbox" value="1" name="site_gbooklock" id="site_gbooklock"<?php if(isset($site['site_gbooklock']) && $site['site_gbooklock'])echo ' checked="checked"'; ?> /> <label for="site_gbooklock">关闭留言发表功能</label>
		</td>
	</tr>
    <tr>
		<th>发件箱地址</th>
		<td>
        	<input type="text" value="<?php echo isset($site['site_email']) ? $site['site_email'] : ''; ?>" class="text" name="site_email" /><br /><span>用于发送系统邮件的发件箱地址</span>
		</td>
	</tr>
    <tr>
		<th>邮件服务器</th>
		<td>
        	<input type="text" value="" class="text" name="site_emailserver" id="site_emailserver" /><br /><span>发件箱使用的SMTP服务器地址</span>
			<script type="text/javascript">$(function(){setTimeout(function(){$('#site_emailserver').val('<?php echo isset($site['site_emailserver']) ? $site['site_emailserver'] : ''; ?>');},200)});</script>
		</td>
	</tr>
    <tr>
		<th>邮件登录密码</th>
		<td>
        	<input type="password" value="" class="text" name="site_emailpassword" id="site_emailpassword" /><br /><span>发件箱登录服务器的验证密码</span>
			<script type="text/javascript">$(function(){setTimeout(function(){$('#site_emailpassword').val('<?php echo isset($site['site_emailpassword']) ? $site['site_emailpassword'] : ''; ?>');},200)});</script>
		</td>
	</tr>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="保存更改" /><input type="button" class="button cancle" value="放弃更改" onclick="$$.confirm({text:'放弃添加并关闭窗口？', ok:function(){top.naviShut('system/mode=site');}, icon:'WAR'});" /></td></tr>
</table>
</form>
</body>
</html>
