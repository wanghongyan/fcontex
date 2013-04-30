<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>内容查看 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript">
function content(mode, args)
{
	switch (mode)
	{
		case 'content.delete':
		case 'content.check_1':
		case 'content.check_0':
		case 'content.fixed_1':
		case 'content.fixed_0':
			args = args || $$.selectval('#table_list :checked:enabled[name=select]');
			if (args == '')
			{
				$$.alert({text:'请选择记录。'});
				return false;
			}
			if (mode == 'content.delete')
			{
				$$.confirm({text:'确定删除[ #'+args+' ]？', ok:function()
				{
					$$.get('control/?mode=' + mode + '&args=' + args, function(){$$.redirect();});
				}});	
			}
			else 
			{
				$$.get('control/?mode=content.check&save='+ mode +'&args=' + args, function(){$$.redirect();});
			}
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

$cate = isset($_GET['cate']) ? intval($A->strGet('cate')) : (isset($_COOKIE['cate'])?$_COOKIE['cate']:'');
$keyw = isset($_GET['keyw']) ? trim($A->strGet('keyw')  ) : (isset($_COOKIE['keyw'])?$_COOKIE['keyw']:'');
$stat = isset($_GET['stat']) ? trim($A->strGet('stat')  ) : (isset($_COOKIE['stat'])?$_COOKIE['stat']:'');
$page = isset($_GET['page']) ? trim($A->strGet('page')  ) : (isset($_COOKIE['page'])?$_COOKIE['page']:'');

setcookie('cate', $cate);
setcookie('keyw', $keyw);
setcookie('stat', $stat);
setcookie('page', $page);

$pager = FCApplication::sharedPageTurnner();
$pager->page = $page;
$where = 'ct_type = 0';
if ($keyw != '') $where .= ' and ct_title like "%'.$keyw.'%"';
if ($cate) $where .= ' and (ct_cid = '.$cate.' or ct_cid in (select cg_id from T[category] where cg_pid='.$cate.'))';
if ($stat == 1) $where .= ' and ct_check = 0';
if ($stat == 2) $where .= ' and ct_fixed = 1';
$res = $pager->parse('ct_id', 'ct_title, ct_id, ct_cid, ct_check, ct_updatetime, ct_fixed', 'T[content]', $where, 'ct_inserttime desc');
?>
<form method="get" action="?">
<table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th>
		<select class="bnt"><option value="">批量操作</option>
            <option value="content.delete">删除</option>
            <option value="content.check_1">草稿</option>
            <option value="content.check_0">发布</option>
            <option value="content.fixed_1">置顶</option>
            <option value="content.fixed_0">取消置顶</option>
        </select>
        <input type="button" value="应用" class="button" onclick="content($(this).parent().find('.bnt').val());" />
		<select name="cate">
        	<option>全部分类</option>
			<?php
			$sql = 'select cg_id, cg_title from T[category] where cg_pid=0 order by cg_order asc, cg_id asc';
			$resP = $D->query($sql);
			while ($rstP = $D->fetch($resP))
			{
            ?>
            <option value="<?php echo $rstP['cg_id']; ?>"<?php if ($cate == $rstP['cg_id'])echo ' selected="selected"'; ?>><?php echo $rstP['cg_title']; ?></option>
			<?php
				$sql = 'select cg_id, cg_title from T[category] where cg_pid='.$rstP['cg_id'].' order by cg_order asc, cg_id asc';
				$resC = $D->query($sql);
				while ($rstC = $D->fetch($resC))
				{
			?>
				<option value="<?php echo $rstC['cg_id']; ?>"<?php if ($cate == $rstC['cg_id'])echo ' selected="selected"'; ?>>&nbsp; <?php echo $rstC['cg_title']; ?></option>
			<?php
				}
			}
            ?>
		</select>
        <select name="stat">
        	<option value="0">全部状态</option>
        	<option value="1"<?php if ($stat == 1)echo ' selected="selected"'; ?>>草稿</option>
            <option value="2"<?php if ($stat == 2)echo ' selected="selected"'; ?>>置顶</option>
        </select>
        <input type="text" class="text" name="keyw" id="search_key" size="20" value="<?php echo $keyw; ?>" />
        <input type="submit" value="筛选" class="button" />
		<input type="button" value="重置" class="button" onclick="location.href='?mode=<?php echo $mode; ?>&cate=&keyw=&stat=&page=1';" />
        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
		</th>
	</tr>
</table>
</form>

<table id="table_list" class="table_list" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr id="list_caption">
		<th width="4%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="6%">#</th>
		<th>标题</th>
		<th width="21%">所属分类</th>
		<th width="21%">状态</th>
		<th width="17%">最后更新</th>
	</tr>
	<?php
	$n = 0;
	while ($rst = $D->fetch($res))
	{
		$resT = $D->query('select cg_title from T[category] where cg_id = '.$rst['ct_cid']);
		$rstT = $D->fetch($resT);
	?>
	<tr>
		<td><input type="checkbox" class="checkbox" name="select" id="select_<?php echo $rst['ct_id']; ?>" value="<?php echo $rst['ct_id']; ?>" onchange="var _this = $(this); _this.prop('checked') ? _this.parent().parent().addClass('S') : _this.parent().parent().removeClass('S');" /></td>
		<td><?php echo $rst['ct_id']; ?></td>
		<td>
			<a class="update" href="?mode=content.update&args=<?php echo $rst['ct_id'];echo $A->strGet('page') ? '&page='.$A->strGet('page') : ''; ?>"><?php echo $rst['ct_title']; ?></a><br />
			<div class="operate"><a class="update" href="?mode=content.update&args=<?php echo $rst['ct_id']; ?>">修改</a><span>|</span><a class="delete" href="javascript:void(0);" onclick="content('content.delete', <?php echo $rst['ct_id']; ?>);">删除</a></div>
        </td>
		<td><?php echo $rstT['cg_title'] ? $rstT['cg_title'] : '暂无记录。'; ?></td>
		<td class="status">
			<?php echo $rst['ct_check'] ? '<span class="status_y">已发布</span>' : '<span class="status_f">草稿</span>'; if ($rst['ct_fixed']) echo '<span class="status_f">置顶</span>'; ?>
		</td>
		<td><?php echo $A->transDate($rst['ct_updatetime']); ?>
		</td>
	</tr>
	<?php
		$n++;
	}
	if ($n == 0)
	{
	?>
	<tr>
		<td colspan="6" align="center">暂无记录。</td>
	</tr>
	<?php
	}
	?>
    <tr id="list_caption">
		<th width="4%"><input type="checkbox" class="checkbox" id="selectall" onchange="$('#table_list :checkbox:enabled[name=select]').prop('checked', !$(this).prop('checked')).click();" /></th>
		<th width="6%">#</th>
		<th>标题</th>
		<th width="21%">所属分类</th>
		<th width="21%">作者</th>
		<th width="17%">最后更新</th>
	</tr>
</table>
<form method="get" action="?">
    <table class="table_tools" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>
                <select class="bnt"><option value="">批量操作</option>
                	<option value="content.delete">删除</option>
                    <option value="content.check_1">草稿</option>
                    <option value="content.check_0">发布</option>
                    <option value="content.fixed_1">置顶</option>
                    <option value="content.fixed_0">取消置顶</option>
                </select>
                <input type="button" value="应用" class="button" onclick="content($(this).parent().find('.bnt').val());" />
            </th>
			<td align="right"><?php echo $pager->turnner; ?></td>
        </tr>
    </table>
</form>


</body>
</html>
