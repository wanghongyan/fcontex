<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户查看 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function user(mode, args)
{
	switch (mode)
	{
		case 'user.delete':
			args = args || $$.selectval('#table_list :checked:enabled[name=select]');
			if (args == '')
			{
				$$.alert({text:'请选择记录。'});
				return false;
			}
			$$.confirm({text:'确定删除[ #'+args+' ]？', ok:function()
			{
				$$.get('control/?mode=' + mode + '&args=' + args, function(){$$.redirect();});
			}});
			break;
		default:
			$$.alert({text:'请选择操作。'});
	}
	
	return false;
}

$(function()
{
	$('.table_list tr').hover(function()
	{
		$(this).find('.operate').show();	
	},
	function()
	{
		$(this).find('.operate').hide();
	});
});
</script>
</head>
<body>

<?php
	$mode = $A->strGet('mode');
	$by   = trim($A->strGet('by'));
	$key  = trim($A->strGet('key'));
	
	$pager = FCApplication::sharedPageTurnner();
	$where = $key != '' ? $by.' like "%'.$key.'%"' : '1=1';
	$res = $pager->parse('us_id', '*', 'T[user]', $where, 'us_id asc');
	$n = 0;
?>
<form method="get" action="?">
<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<th>
            <select class="bnt"><option value="">批量操作</option>
           	  <option value="user.delete">删除选择</option>
            </select> <input type="button" value="应用" class="button" onclick="user($(this).parent().find('.bnt').val());" />
            
            <select name="by">
                <option value="us_username"<?php if ($by == 'us_username')echo ' selected="selected"'; ?>>用户名</option>
                <option value="us_name"<?php if ($by == 'us_name')echo ' selected="selected"'; ?>>姓名</option>
            </select>
            <input type="text" class="text" name="key" id="search_key" size="20" value="<?php echo $key; ?>" />
            <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
            <input type="submit" value="筛选" class="button" />
        </th>
		<td align="right"><?php echo $pager->turnner; ?></td>
	</tr>
</table>
</form>
<table id="table_list" class="table_list" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr id="list_caption">
		<th width="4%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="6%">#</th>
		<th width="16%">用户名</th>
		<th width="16%">用户组</th>
		<th width="20%">姓名</th>
		<th width="21%">邮箱</th>
		<th width="17%">电话</th>
	</tr>
	<?php
	while ($rst = $D->fetch($res))
	{
	$resg = $D->query('select gr_name from T[group] where gr_id = '.$rst['us_group']);
	$rstg = $D->fetch($resg);
	?>
	<tr>
		<td><input<?php if ($U->isSystemUser($rst['us_id']) && !$U->isSystemUser()) echo ' disabled="disabled"'; ?> type="checkbox" class="checkbox" name="select" id="select_<?php echo $rst['us_id']; ?>" value="<?php echo $rst['us_id']; ?>" onchange="var _this = $(this); _this.prop('checked') ? _this.parent().parent().addClass('S') : _this.parent().parent().removeClass('S');" /></td>
		<td><?php echo $rst['us_id']; ?></td>
		<td>
			<a href="<?php if ($U->isSystemUser($rst['us_id']) && !$U->isSystemUser()){ ?>#<?php }else { ?>?mode=user.update&args=<?php echo $rst['us_id']; } ?>"><?php echo $rst['us_username']; ?></a><br />
			<div class="operate">
				<a class="update" href="<?php if ($U->isSystemUser($rst['us_id']) && !$U->isSystemUser()){ ?>#<?php } else { ?>?mode=user.update&args=<?php echo $rst['us_id']; } ?>">修改</a><span>|</span>
				<?php
				if ($U->isSystemUser($rst['us_id']) && !$U->isSystemUser())
				{
				?>
				<a class="delete" href="javascript:void(0);" style="opacity:0.3; filter:alpha(opacity=30);">删除</a>
				<?php
				}
				else
				{
				?>
				<a class="delete" href="javascript:void(0);" onclick="user('user.delete', <?php echo $rst['us_id']; ?>);">删除</a>
				<?php
				}
				?>
			</div>
        </td>
		<td><?php echo $rstg['gr_name']; ?></td>
		<td><?php echo $rst['us_name']; ?></td>
		<td><?php echo $rst['us_email']; ?></td>
		<td><?php echo $rst['us_phone']; ?>
        </td>
	</tr>
	<?php
		$n++;
	}
	if ($n == 0)
	{
	?>
	<tr>
		<td colspan="7" align="center">暂无记录。</td>
	</tr>
	<?php
	}
	?>
    <tr id="list_caption">
		<th width="4%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="6%">#</th>
		<th width="16%">用户名</th>
		<th width="16%">用户组</th>
		<th width="20%">姓名</th>
		<th width="21%">邮箱</th>
		<th width="17%">电话</th>
	</tr>
</table>

<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<th>
            <select class="bnt"><option value="">批量操作</option>
           	  <option value="user.delete">删除选择</option>
            </select> <input type="button" value="应用" class="button" onclick="user($(this).parent().find('select').val());" />
        </th>
		<td align="right"><?php echo $pager->turnner; ?></td>
	</tr>
</table>


</body>
</html>
