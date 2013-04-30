<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>分组编辑 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>jquery.ui.min.js"></script>
<script type="text/javascript">
function group(mode, args)
{
	switch (mode)
	{
		case 'group.update':
			$$.post('control/?mode=' + mode + '&args=' + args, $('#form_detail').serialize(), function()
			{
				$$.redirect('?mode=group.select');
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

<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return group('group.update', <?php echo ($args = $A->strGet('args')); ?>);">
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
		$sql = 'select * from T[group] where gr_id = '.$args;
		$res = $D->query($sql);
		if ($rst = $D->fetch($res))
		{
	?>
	<tr>
		<th>分组名称</th>
		<td><input class="text" type="text" name="gr_name" id="gr_name" value="<?php echo $rst['gr_name']; ?>" /> <span><cite>*</cite> 必填</span></td>
	</tr>
	<tr>
		<th>分组描述</th>
		<td><textarea name="gr_desc" id="gr_desc" cols="45" rows="5"><?php echo $rst['gr_desc']; ?></textarea></td>
	</tr>
	<tr>
		<th>分组权限</th>
		<td>
			<?php
			$modules = $A->loadConfig('system.modules');
			foreach ($modules as $name => $module)
			{
			?>
			<table id="rights_<?php echo $name; ?>" class="rights" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th colspan="3"><span><a href="javascript:void(0);" onclick="var items = $('#rights_<?php echo $name; ?>').find(':checkbox'); items.prop('checked', !items.prop('checked'));" onmouseover="$('#rights_<?php echo $name; ?>').addClass('H');" onmouseout="$('#rights_<?php echo $name; ?>').removeClass('H');">全选</a></span><?php echo $module['name']; ?></th>
				</tr>
				<?php
				$grouprights = explode(',', $rst['gr_rights']);
				while ($rights = each($module['rights']))
				{
				?>
				<tr>
					<td width="120"><input class="checkbox" type="checkbox" name="gr_rights[]" id="<?php echo $rights['key']; ?>" value="<?php echo ($r = $rights['key']); ?>"<?php if (in_array($r, $grouprights)) echo ' checked="checked"'; ?> /><label for="<?php echo $rights['key']; ?>"><?php echo $rights['value']; ?></label></td>
					<?php
					if ($rights = each($module['rights']))
					{
					?>
					<td width="120"><input class="checkbox" type="checkbox" name="gr_rights[]" id="<?php echo $rights['key']; ?>" value="<?php echo ($r = $rights['key']); ?>"<?php if (in_array($r, $grouprights)) echo ' checked="checked"'; ?> /><label for="<?php echo $rights['key']; ?>"><?php echo $rights['value']; ?></label></td>
					<?php
					}
					else
					{
						echo '<td width="120">&nbsp;</td>';
					}
					if ($rights = each($module['rights']))
					{
					?>
					<td><input class="checkbox" type="checkbox" name="gr_rights[]" id="<?php echo $rights['key']; ?>" value="<?php echo ($r = $rights['key']); ?>"<?php if (in_array($r, $grouprights)) echo ' checked="checked"'; ?> /><label for="<?php echo $rights['key']; ?>"><?php echo $rights['value']; ?></label></td>
					<?php
					}
					else
					{
						echo '<td>&nbsp;</td>';
					}
					?>
				</tr>
				<?php
				}//while
				?>
			</table>
			<?php
			}//foreach
			?>
		</td>
	</tr>
	<tr class="action"><th>&nbsp;</th><td><input type="submit" class="button" value="确认修改" /><input type="button" class="button cancle" value="放弃修改" onclick="$$.redirect('?mode=group.select');" /></td></tr>
	<?php
		}
	}
	?>
</table>
</form>


</body>
</html>
