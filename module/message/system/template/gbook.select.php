<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查看留言 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>datepicker/WdatePicker.js"></script>
<script type="text/javascript">
function gbook(mode, args)
{
	switch (mode)
	{
		case 'gbook.delete':
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
		case 'gbook.check_1':
		case 'gbook.check_0':
			var check = mode == 'gbook.check_1' ? 1 : 0;
			args = args || $$.selectval('#table_list :checked:enabled[name=select]');
			if (args == '')
			{
				$$.alert({text:'请选择记录。'});
				return false;
			}
			$$.get('control/?mode=' + mode + '&args=' + args+'&check='+check, function(){$$.redirect();});
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
$keyw = trim($A->strGet('keyw'));
$starttime = $A->strGet('starttime');
$stoptime  = $A->strGet('stoptime');

$where = 'gb_toid = 0';
if ($keyw) $where .= ' and gb_content like "%'.$keyw.'%"';
if ($starttime) $where .= ' and gb_time >= '.strtotime($starttime);
if ($stoptime) $where .= ' and gb_time <= '.(strtotime($stoptime) + 60*60*24);
$pager = FCApplication::sharedPageTurnner();
$resP = $pager->parse('gb_id', '*', 'T[gbook]', $where, 'gb_update desc');
?>
<form method="get" action="?">
<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th>
			<!--<select class="bnt"><option value="">批量操作</option>
            	<option value="gbook.delete">删除选择</option>
                <option value="gbook.check_1">通过审核</option>
                <option value="gbook.check_0">取消审核</option>
            </select>
			<input type="button" value="应用" class="button" onclick="gbook($(this).parent().find('.bnt').val());" />-->
            <input type="text" class="text" name="keyw" id="search_key" size="20" value="<?php echo $keyw; ?>" />
            <input name="starttime" type="text" class="text date" style="width:80px;" onClick="WdatePicker()" value="<?php echo $starttime; ?>" />-<input name="stoptime" type="text" class="text date" style="width:80px;" onClick="WdatePicker()" value="<?php echo $stoptime; ?>" />
            <input type="submit" value="筛选" class="button" />
            <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
		</th>
	</tr>
</table>
</form>
<?php
$n = 0;
while ($rstP = $D->fetch($resP))
{
	$sql = 'select * from T[gbook] where gb_topid = '.$rstP['gb_id'].' order by gb_id asc';
	$res = $D->query($sql);
	$rst = $D->fetch($res);
?>
<dl class="comment" id="gbook_<?php echo $rstP['gb_id']; ?>">
	<dt ondblclick="$(this).find('.B a').click();">
		<span class="A"># <?php echo $rstP['gb_id']; ?></span>
		<span class="B"><?php echo $A->transDate($rstP['gb_time']); ?></span>
		<span class="C"><?php echo $rstP['gb_name']; ?></span>
		<span class="D"><?php echo $A->strLeft($A->trim($rstP['gb_content'],TRUE), 68, '...'); ?></span>
		<span class="E status">
		<?php if (!$rstP['gb_check']) echo ' <cite class="status_f">屏蔽</cite>'; ?>
		<a href="javascript:void(0);" onclick="$('#gbook_<?php echo $rstP['gb_id']; ?> dd').toggle();$(this).toggleClass('d');" class="ud">&nbsp;</a> &nbsp; 
		<a class="update" href="?mode=gbook.update&args=<?php echo $rstP['gb_id']; ?>">查看</a> &nbsp; 
		<?php
		if ($rst)
		{
		?>
		<a class="delete" href="javascript:void(0);" style="opacity:0.5; filter:alpha(opacity=50); color:#ccc;">删除</a>
		<?php
		}
		else
		{
		?>
		<a class="delete" href="javascript:void(0);" onclick="gbook('gbook.delete', <?php echo $rstP['gb_id']; ?>);">删除</a>
		<?php
		}
		?>
		</span>
	</dt>
	<?php
	if ($rst) do
	{
	?>
	<dd id="gbook_<?php echo $rst['gb_id']; ?>">
		<span class="A"># <?php echo $rst['gb_id']; ?></span>
		<span class="B"><?php echo $A->transDate($rst['gb_time']); ?></span>
		<span class="C"><?php echo $rst['gb_name']; ?></span>
		<span class="D"><?php echo $A->strLeft(strip_tags($rst['gb_content']), 68, '...'); ?></span>
		<span class="E status">
			<?php if (!$rst['gb_check']) echo ' <cite class="status_f">屏蔽</cite>'; ?>
			<a class="update" href="?mode=gbook.update&args=<?php echo $rst['gb_id']; ?>">查看</a> &nbsp; 
			<a class="delete" href="javascript:void(0);" onclick="gbook('gbook.delete', <?php echo $rst['gb_id']; ?>);">删除</a>
		</span>
	</dd>
	<?php
	}
	while ($rst = $D->fetch($res));
	?>
</dl>
<?php
	$n++;
}
if ($n == 0)
{
?>
	<dl class="comment">
		<dt><span>暂无记录。</span></dt>
	</dl>
<?php
}
?>
<form method="get" action="?">
<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th>
           <!-- <select class="bnt"><option value="">批量操作</option>
            	<option value="gbook.delete">删除选择</option>
                <option value="gbook.check_1">通过审核</option>
                <option value="gbook.check_0">取消审核</option>
            </select>
            <input type="button" value="应用" class="button" onclick="gbook($(this).parent().find('.bnt').val());" />-->
		</th>
		<td align="right"><?php echo $pager->turnner; ?></td>
	</tr>
</table>
</form>

</body>
</html>
