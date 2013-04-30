<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>栏目编辑 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>jquery.ui.min.js"></script>
<script type="text/javascript">
function category(mode, args)
{
	switch (mode)
	{
		case 'category.update':
			$$.post('control/?mode=' + mode + '&args=' + args, $('#form_detail').serialize(), function()
			{
				$$.redirect('?mode=category.select');
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

<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return category('category.update', <?php echo ($args = $A->strGet('args')); ?>);">
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
		$sql = 'select * from T[category] where cg_id = '.$args;
		$res = $D->query($sql);
		if ($rst = $D->fetch($res))
		{
	?>
	<tr>
		<tr>
		<th>栏目名称</th>
		<td><input class="text" type="text" name="cg_title" id="cg_title" value="<?php echo $rst['cg_title']; ?>" /> <span><cite>*</cite> 必填</span></td>
	</tr>
	<tr>
		<th>栏目类型</th>
		<td>
			<input type="radio" class="checkbox" name="cg_type" id="cg_type_0" value="0" checked="checked" onclick="$('#cg_target_ui,#cg_url_ui').hide();" /> <label for="cg_type_0">普通栏目</label> &nbsp; &nbsp; &nbsp; &nbsp;
			<input type="radio" class="checkbox" name="cg_type" id="cg_type_1" value="1"<?php if ($rst['cg_type']=='1') echo ' checked="checked"'; ?> onclick="$('#cg_target_ui,#cg_url_ui').show();$('#cg_url').focus();" /> <label for="cg_type_1">链接地址</label>
		</td>
	</tr>
	<tr id="cg_url_ui"<?php if ($rst['cg_type']=='0') echo ' style="display:none;"'; ?>>
		<th>链接地址</th>
		<td><input class="text" type="text" name="cg_url" id="cg_url" value="<?php echo $rst['cg_url']; ?>" /></td>
	</tr>
	<tr id="cg_target_ui"<?php if ($rst['cg_type']=='0') echo ' style="display:none;"'; ?>>
		<th>打开方式</th>
		<td>
			<input type="radio" class="checkbox" name="cg_target" id="cg_target_0" value="_self" checked="checked" /> <label for="cg_target_0">默认窗口打开</label> &nbsp;
			<input type="radio" class="checkbox" name="cg_target" id="cg_target_1" value="_blank"<?php if ($rst['cg_target']=='_blank') echo ' checked="checked"'; ?> /> <label for="cg_target_1">新窗口打开</label>
		</td>
	</tr>
    <tr>
		<th>上级栏目</th>
		<td>
		<?php
		$sql = 'select cg_id from T[category] where cg_pid = '.$args;
		if ($D->fetch($D->query($sql)))
		{
		?>
		<span>有子栏目的栏目不允许修改此项。</span><input name="cg_pid" type="hidden" value="<?php echo $rst['cg_pid']; ?>" />
		<?php
		}
		else
		{
		?>
        <select name="cg_pid">
            <option value="0">=无上级栏目=</option>
            <?php
			$sql = 'select cg_id, cg_title from T[category] where cg_pid = 0 and cg_id <> '.$args.' order by cg_id asc';
			$resG = $D->query($sql);
			while ($rstG = $D->fetch($resG))
			{
			?>
            <option value="<?php echo $rstG['cg_id']; ?>"<?php if($rstG['cg_id']==$rst['cg_pid'])echo ' selected="selected"'; ?>><?php echo $rstG['cg_title']; ?></option>
            <?php
            }
            ?>
        </select>
		<?php
		}
		?>
		</td>
	</tr>
	<tr>
		<th>栏目描述</th>
		<td><textarea name="cg_desc" id="cg_desc" cols="45" rows="5"><?php echo $rst['cg_desc']; ?></textarea></td>
	</tr>
	</tr>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="确认修改" /><input type="button" class="button cancle" value="放弃修改" onclick="$$.redirect('?mode=category.select');" /></td></tr>
	<?php
		}
	}
	?>
</table>
</form>


</body>
</html>
