<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>编辑评论 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function comment(mode, args)
{
	switch (mode)
	{
		case 'comment.update':
			$$.post('control/?mode=' + mode + '&args=' + args, $('#form_detail').serialize(), function()
			{
				$$.redirect('?mode=comment.select');
			});
			break;
		default:
			$$.alert({text:'无效参数 [ '+mode+' ]。'});
			break;
	}
	
	return false;
}
</script>
</head>
<body>


<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return comment('comment.update', <?php echo ($args = $A->strGet('args')); ?>);">
<table class="table_form" width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	if (!is_numeric($args))
	{
	?>
	<tr>
		<th>&nbsp;</th>
		<td>无效参数[ <?php echo $args; ?> ]。</td>
	</tr>
	<?php
	}
	else
	{
		$sql = 'select * from T[comment] where cm_id = '.$args;
		$res = $D->query($sql);
		if ($rst = $D->fetch($res))
		{
	?>
	<tr>
		<th>称呼</th>
		<td><input class="text" type="text" disabled="disabled" value="<?php echo $rst['cm_name']; ?>" /></td>
	</tr>
    <tr>
		<th>邮箱</th>
		<td>
        <input class="text" type="text" disabled="disabled" value="<?php echo $rst['cm_email']; ?>" />	 
		</td>
	</tr>
    <tr>
		<th>网址</th>
		<td>
			<input class="text" type="text" disabled="disabled" value="<?php echo $rst['cm_url']; ?>" />
		</td>
	</tr>
	<tr>
		<th>IP地址</th>
		<td>
			<input class="text" type="text" disabled="disabled" value="<?php echo $rst['cm_ip']; ?>" /> <span><?php echo $rst['cm_iparea']; ?></span>
		</td>
	</tr>
    <tr>
		<th>时间</th>
		<td>
			<input class="text" type="text" disabled="disabled" value="<?php echo date('Y-m-d H:i:s', $rst['cm_time']); ?>" />
		</td>
	</tr>
	<tr>
		<th>内容</th>
		<td><textarea style="width:620px; height:140px;" name="cm_content"><?php echo $rst['cm_content']; ?></textarea></td>
	</tr>
	<tr>
		<th>审核</th>
		<td>
			<input type="checkbox" class="checkbox" name="cm_check" id="cm_check"<?php if ($rst['cm_check']) echo ' checked="checked"'; ?> /> <label for="cm_check">通过审核</label>
		</td>
	</tr>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="确认修改" /><input type="button" class="button cancle" value="放弃修改" onclick="history.back();" /></td></tr>
	<?php
		}
	}
	?>
</table>
</form>


</body>
</html>
