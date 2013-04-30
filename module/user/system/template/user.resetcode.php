<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户重置密码 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>jquery.ui.min.js"></script>
<script type="text/javascript">
function resetcode(mode, args)
{
	$$.post('control/?mode=user.resetcode', $('#form_detail').serialize(), function()
	{
		location.href = '<?php echo URL_SITE; ?>module/system/';
	});
	
	return false;
}
</script>
<style type="text/css">
html, body{height:100%;}
</style>
</head>
<body>

<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return resetcode();">
<input type="hidden" name="code" value="<?php echo $A->strGet('code'); ?>" />
<table class="table_form" width="520" border="0" cellspacing="0" cellpadding="0" style="position:absolute; top:50%; margin-top:-160px; left:50%; margin-left:-260px;">
	<tr>
		<th>用户名</th>
		<td><input class="text" type="text" name="username" id="username" style="width:300px;" /><span> <cite>*</cite> <br />为了验证用户名和邮箱一致性，请填写用户名</span></td>
	</tr>
	<tr>
		<th>新密码</th>
		<td><input class="text" type="password" name="password_1" id="password_1" style="width:300px;" /> <span><cite>*</cite></span></td>
	</tr>
	<tr>
		<th>确认新密码</th>
		<td>
			<input class="text" type="password" name="password_2" id="password_2" style="width:300px;" /> <span><cite>*</cite></span>
		</td>
	</tr>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="确认" /><a href="<?php echo URL_SITE; ?>module/system/" target="_blank">系统主页</a></td></tr>
</table>
</form>

</body>
</html>
