<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>附件管理</title>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN ?>style.css" />
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>datepicker/WdatePicker.js"></script>
<script type="text/javascript">
function system(mode, args)
{
	switch (mode)
	{
		case 'file.delete':
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
$type	   = $A->strGet('type');
$starttime = $A->strGet('starttime');
$stoptime  = $A->strGet('stoptime');
$where = '1=1';
if ($type) $where .= ' and at_isimage = 1';
if ($starttime) $where .= ' and at_time >= '.strtotime($starttime);
if ($stoptime) $where .= ' and at_time <= '.(strtotime($stoptime) + 60*60*24);
$pager = FCApplication::sharedPageTurnner();
$res = $pager->parse('at_id', '*', 'T[attached]', $where, 'at_id desc');
?>
<form method="get" action="?">
<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
		<th>
        	<input type="hidden" name="mode" value="file" />
            <select id="bnt"><option value="">批量操作</option>
            	<option value="file.delete">删除选择</option>
            </select>
            <input type="button" value="应用" class="button" onclick="system($(this).parent().find('select').val());" />
			<select name="type">
            	<option>全部类型</option>
                <option value="1"<?php if ($type)echo ' selected="selected"'; ?>>图像</option>
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
		<th width="3%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="5%">#</th>
		<th width="14%">&nbsp;</th>
		<th width="26%" style="text-align:left;">文件</th>
		<th width="20%">下载</th>
		<th width="16%">大小</th>
		<th width="16%">日期</th>
	</tr>
	<?php
	while ($rst = $D->fetch($res))
	{
	?>
	<tr>
		<td><input type="checkbox" class="checkbox" name="select" id="select_<?php echo $rst['at_id']; ?>" value="<?php echo $rst['at_id']; ?>" onchange="var _this = $(this); _this.prop('checked') ? _this.parent().parent().addClass('S') : _this.parent().parent().removeClass('S');" /></td>
		<td><?php echo $rst['at_id']; ?></td>
		<td><?php if ($rst['at_isimage']){ ?><img src="<?php echo $A->getThumb($rst['at_dir'].$rst['at_filenewname'], 200, 200); ?>" width="60" /><?php } else { ?><img src="<?php echo $A->fileIcon($rst['at_suffix']); ?>" /><?php } ?></td>
		<td>
			<a href="<?php echo $A->getThumb($rst['at_dir'].$rst['at_filenewname']); ?>" target="_blank"><b><?php echo $rst['at_filename']; ?></b></a><br /><?php echo strtoupper($rst['at_suffix']); ?><br />
			<div class="operate"><a href="<?php echo $A->getThumb($rst['at_dir'].$rst['at_filenewname']); ?>" target="_blank">查看</a><span>|</span><a href="#" class="delete" onclick="system('file.delete', <?php echo $rst['at_id']; ?>); return false;">删除</a></div>
		</td>
		<td><?php echo $rst['at_hits']; ?></td>
		<td><?php echo $A->transSize($rst['at_size']); ?></td>
		<td><?php echo $A->transDate($rst['at_time']); ?></td>
	</tr>
	<?php
	}
	if ($D->count($res) < 1)
	{
	?>
    <tr>
		<td></td>
		<td></td>
		<td></td>
		<td>没有找到数据</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
    <?php
	}
	?>
    <tr id="list_caption">
		<th width="3%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="5%">#</th>
		<th width="14%">&nbsp;</th>
		<th width="26%" style="text-align:left;">文件</th>
		<th width="20%">用户</th>
		<th width="16%">大小</th>
		<th width="16%">日期</th>
	</tr>
</table>

<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
		<th>
            <select><option value="">批量操作</option>
            	<option value="file.delete">删除选择</option>
            </select> <input type="button" value="应用" class="button" onclick="system($(this).parent().find('select').val());" />
		</th>
		<td align="right"><?php echo $pager->turnner; ?></td>
	</tr>
</table>

</body>
</html>
