<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>日志管理</title>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN ?>style.css" />
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>datepicker/WdatePicker.js"></script>
<script type="text/javascript">
function system(mode, args)
{
	switch (mode)
	{
		case 'log.delete':
			args = args || $$.selectval('#table_list :checked:enabled[name=select]');
			if (args == '')
			{
				$$.alert({text:'请选择记录。'});
				return false;
			}
			$$.confirm({text:'确定删除[ #'+args+' ]？', ok:function()
			{
				$$.get('control/?mode='+mode+'&args=' + args, function(){$$.redirect();});
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
$user = $A->strGet('user');
$starttime = $A->strGet('starttime');
$stoptime  = $A->strGet('stoptime');
$where = '1=1';
if ($starttime) $where .= ' and lg_time >= '.strtotime($starttime);
if ($stoptime) $where .= ' and lg_time <= '.(strtotime($stoptime) + 60*60*24);
if ($user) $where .= ' and lg_uid >= '.intval($user);
$pager = FCApplication::sharedPageTurnner();
$res = $pager->parse('lg_id', '*', 'T[logs]', $where, 'lg_id desc');
?>
<form method="get" action="?">
<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
		<th>
        	<input type="hidden" name="mode" value="log" />
            <select><option value="">批量操作</option>
            	<option value="log.delete">删除选择</option>
            </select>
            <input type="button" value="应用" class="button" onclick="system($(this).parent().find('select').val());" />
            <select name="user"><option value="">用户</option>
            	<?php
                $resu = $D->query('select us_id, us_username from T[user]');
				while ($rstu = $D->fetch($resu))
				{
				?>
                <option value="<?php echo $rstu['us_id']; ?>"<?php if ($user == $rstu['us_id'])echo ' selected="selected"'; ?>><?php echo $rstu['us_username']; ?></option>
                <?php
				}
				?>
            </select>
            <input name="starttime" type="text" class="text date" style="width:80px;" onClick="WdatePicker()" value="<?php echo $starttime; ?>" />-<input name="stoptime" type="text" class="text date" style="width:80px;" onClick="WdatePicker()" value="<?php echo $stoptime; ?>" />
            <input type="submit" value="筛选" class="button" />
		</th>
		<td align="right"><?php echo $pager->turnner; ?></td>
	</tr>
</table>
</form>
<table id="table_list" class="table_list" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr id="list_caption">
		<th width="4%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="5%">#</th>
		<th width="26%" style="text-align:left;">事件</th>
		<th width="18%">用户</th>
		<th width="14%">IP</th>
		<th width="16%">地点</th>
		<th width="17%">时间</th>
	</tr>
	<?php
	while ($rst = $D->fetch($res))
	{
	$resu = $D->query('select us_name, us_username from T[user] where us_id = '.intval($rst['lg_uid']));
	$rstu = $D->fetch($resu);
	?>
	<tr>
		<td><input type="checkbox" class="checkbox" name="select" id="select_<?php echo $rst['lg_id']; ?>" value="<?php echo $rst['lg_id']; ?>" onchange="var _this = $(this); _this.prop('checked') ? _this.parent().parent().addClass('S') : _this.parent().parent().removeClass('S');" /></td>
		<td><?php echo $rst['lg_id']; ?></td>
		<td>
			<?php echo $rst['lg_event']; ?><br />
			<div class="operate"><a href="#" class="delete" onclick="system('log.delete', <?php echo $rst['lg_id']; ?>); return false;">删除</a></div>
        </td>
		<td><?php echo $rstu['us_name'] != '' ? $rstu['us_name'] : $rstu['us_username']; ?></td>
		<td><?php echo $rst['lg_ip']; ?></td>
		<td><?php echo $rst['lg_iparea'] ? $rst['lg_iparea'] : '保留地址'; ?></td>
		<td><?php echo $A->transDate($rst['lg_time']); ?></td>
	</tr>
	<?php
	}
	if ($D->count($res) < 1)
	{
	?>
    <tr>
		<td></td>
		<td></td>
		<td>暂无记录。</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
    <?php
	}
	?>
    <tr id="list_caption">
		<th width="4%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="5%">#</th>
		<th width="26%" style="text-align:left;">事件</th>
		<th width="18%">用户</th>
		<th width="14%">IP</th>
		<th width="16%">地点</th>
		<th width="17%">时间</th>
	</tr>
</table>

<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
		<th>
            <select><option value="">批量操作</option>
            	<option value="log.delete">删除选择</option>
            </select>
			<input type="button" value="应用" class="button" onclick="system($(this).parent().find('select').val());" />
			<input type="button" value="全部清理" class="button" onclick="system('log.delete', '*');" />
		</th>
		<td align="right"><?php echo $pager->turnner; ?></td>
	</tr>
</table>

</body>
</html>
