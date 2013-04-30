<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>内容编辑 - <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></title>
<meta name="robots" content="nofollow">
<link rel="stylesheet" type="text/css" href="<?php echo URL_TOOLS; ?>kindeditor/themes/default/default.css" />
<?php if (0) { ?><link rel="stylesheet" type="text/css" href="../../../system/skins/default/style.css" /><?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_SKIN; ?>style.css" /><?php } ?>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_SCRIPTS; ?>lib.system.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>kindeditor/kindeditor.min.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo URL_TOOLS; ?>datepicker/WdatePicker.js"></script>
<script type="text/javascript">
function content(mode, args)
{
	switch (mode)
	{
		case 'content.update':
			window.__editor_content.sync();
			$$.post('control/?mode=' + mode + '&args=' + args, $('#form_detail').serialize(), function()
			{
				$$.redirect('?mode=page.select');
			});
			break;
		default:
			$$.alert({text:'无效参数 [ '+mode+' ]。'});
			break;
	}
	
	return false;
}

$(function()
{
	window.__editor_content  = $$.editor({target:'#ct_content',css:['<?php echo URL_THEME.'images/style.css'; ?>','<?php echo URL_TOOLS.'kindeditor/plugins/code//prettify.css'; ?>']});
});
</script>
</head>
<body>


<form id="form_detail" name="form_detail" method="post" action="###" onsubmit="return content('content.update', <?php echo ($args = $A->strGet('args')); ?>);">
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
		$sql = 'select * from T[content] where ct_id = '.$args;
		$res = $D->query($sql);
		if ($rst = $D->fetch($res))
		{
	?>
	<tr>
		<th>标题</th>
		<td><input class="text" type="text" name="ct_title" id="ct_title" value="<?php echo $rst['ct_title']; ?>" /> <span><cite>*</cite> 必填</span></td>
	</tr>
    <tr>
		<th>栏目</th>
		<td>
		<input type="hidden" name="ct_category" id="ct_category" value="<?php echo $rst['ct_category']; ?>" />
		<select name="ct_cid" id="ct_cid" onchange="$('#ct_category').val($.trim($(this).find('option:selected[value!=0]').text()));">
        	<option value="0">=选择栏目=</option>
			<?php
			$sql = 'select cg_id, cg_title from T[category] where cg_pid=0 order by cg_order asc, cg_id asc';
			$resP = $D->query($sql);
			while ($rstP = $D->fetch($resP))
			{
            ?>
            <option value="<?php echo $rstP['cg_id']; ?>"<?php if ($rst['ct_cid'] == $rstP['cg_id'])echo ' selected="selected"'; ?>><?php echo $rstP['cg_title']; ?></option>
			<?php
				$sql = 'select cg_id, cg_title from T[category] where cg_pid='.$rstP['cg_id'].' order by cg_order asc, cg_id asc';
				$resC = $D->query($sql);
				while ($rstC = $D->fetch($resC))
				{
			?>
				<option value="<?php echo $rstC['cg_id']; ?>"<?php if ($rst['ct_cid'] == $rstC['cg_id'])echo ' selected="selected"'; ?>>&nbsp; <?php echo $rstC['cg_title']; ?></option>
			<?php
				}
			}
            ?>
		</select>
		<script type="text/javascript">$('#ct_cid').change();</script>
		</td>
	</tr>
	<tr>
		<th>内容</th>
		<td><textarea name="ct_content" id="ct_content" style="width:720px; height:340px;"><?php echo $rst['ct_content']; ?></textarea> <span><cite>*</cite> 必填</span></td>
	</tr>
	<tr>
    	<th>发布时间</th>
		<td><input class="text" type="text" name="ct_inserttime" id="ct_inserttime" value="<?php echo date('Y-m-d H:i:s', $rst['ct_inserttime']); ?>" readonly="readonly" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" /> <span><cite>*</cite> 必填 发布时按此时间倒排序</span> </td>
	</tr>
	<tr>
		<th>附加属性</th>
		<td><input type="checkbox" class="checkbox" value="1" id="ct_quiet" name="ct_quiet"<?php if ($rst['ct_quiet'] == 0)echo ' checked="checked"'; ?> /> <label for="ct_quiet">允许评论</label></td>
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
